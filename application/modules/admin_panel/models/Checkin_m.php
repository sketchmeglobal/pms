<?php

class Checkin_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function checkin($po_id) {
        $data['po_id'] = $po_id;
        return array('page'=>'checkin/checkin_list_v', 'data'=>$data);
    }

    public function ajax_checkin_table_data() {

        $po_id = $this->input->post('purchase_order_id');
        
        //actual db table column names
        $column_orderable = array(
            0 => 'check_in.checkin_date',
            1 => 'check_in.checkin_number',
            2 => 'check_in.status'
        );
		
        // Set searchable column fields
        $column_search = array('check_in.checkin_number','check_in.checkin_date','check_in.status');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->_checkin_list_common_query($po_id);

        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);

            $rs = $this->_checkin_list_common_query($po_id);
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();
			
			$rs = $this->_checkin_list_common_query($po_id);
			
			$totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
            $rs = $this->_checkin_list_common_query($po_id);

            $this->db->flush_cache();
        }

        $data = array();

        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['checkin_number'] = $val->checkin_number;
            $nestedData['po_number'] = $val->po_number;
            $nestedData['consignement_number'] = $val->consignement_number;
            $nestedData['checkin_date'] = $val->checkin_date;
            $nestedData['status'] = $status;
            $nestedData['action'] = '<a href="'. base_url('admin/edit-checkin/'.$val->checkin_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a href="javascript:void(0)" pk-name="checkin_id" pk-value="'.$val->checkin_id.'" tab="check_in" child="1" ref-table="checkin_detail" ref-pk-name="checkin_id" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }    

    private function _checkin_list_common_query($po_id){

        $query = "
        SELECT
            checkin.*,
            `purchase_order`.`po_id`,
            GROUP_CONCAT(
                DISTINCT(`purchase_order`.`po_number`) SEPARATOR '<br>'
            ) AS `po_number`,
            CONCAT(
                `purchase_order_details`.`consignement_number`,
                ' /<b>',
                `purchase_order_details`.`size_in_text`,
                '</b>'
            ) AS consignement_number
        FROM
            (
            SELECT
                check_in.checkin_id,
                check_in.checkin_number,
                DATE_FORMAT(
                    check_in.checkin_date,
                    '%d-%m-%Y'
                ) AS checkin_date,
                check_in.remarks,
                check_in.terms,
                check_in.status
            FROM
                `check_in`
        ) AS checkin
        LEFT JOIN checkin_detail ON checkin.checkin_id = checkin_detail.checkin_id
        LEFT JOIN purchase_order_details ON checkin_detail.pod_id = purchase_order_details.pod_id
        LEFT JOIN purchase_order ON purchase_order.po_id = purchase_order_details.po_id
        WHERE purchase_order.po_id = $po_id
        GROUP BY checkin_id
        ";
        $rs = $this->db->query($query)->result();

        return $rs;
    }

// ADD purchase ORDER 


    public function add_checkin() {
		$data['checkin_number'] = 'CHK-IN/'.date('dmY').'/'.date('his');
		
        $data['supplier_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', 
		array('acc_master.status' => 1))->result();
		
		$today = date('Y-m-d');
		$data['checkin_date'] = $today;

        return array('page'=>'checkin/checkin_add_v', 'data'=>$data);
    }

    public function ajax_unique_checkin_number(){
        $checkin_number = $this->input->post('checkin_number');

        $rs = $this->db->get_where('check_in', array('checkin_number' => $checkin_number))->num_rows();
        if($rs != '0') {
            $data = 'Check-in no. already exists.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function form_add_checkin(){
       $insertArray = array(
			'checkin_number' => $this->input->post('checkin_number'),
			'checkin_date' => $this->input->post('checkin_date'),
			'remarks' => $this->input->post('remarks'),
			'terms' => $this->input->post('terms'),
			'status' => $this->input->post('status'),
			'user_id' => $this->session->user_id
		);
		
		$this->db->insert('check_in', $insertArray);
		if($this->db->insert_id() > 0){
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'Checked in successfully.';
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Data Insert Error';
		}
		return $data; 
    }

    public function edit_checkin($checkin_id) {
		
        $this->db->select('distribute_detail.e_id, employees.name, employees.e_code, employees.rate_per_bag, employees.due_amount');
		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
		$this->db->group_by('distribute_detail.e_id');
		$rs_emp = $this->db->get_where('distribute_detail', array('distribute_detail.status' => 1))->result();
		
		$rs_emp_new = array();
		
		for($i = 0; $i < sizeof($rs_emp); $i++){
			$e_id = $rs_emp[$i]->e_id;
			
			$total_due_amount = $rs_emp[$i]->due_amount;
			$rs_emp[$i]->total_due_amount = $total_due_amount;
			
			$distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('e_id' => $e_id))->result()[0]->distribute_pod_quantity;
            
			$received_quantity = $this->db->select('SUM(received_quantity) as received_quantity')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->received_quantity;
			
			/*$total_due_amount = $this->db->select('due_amount')->limit(0,1)->order_by('checkin_detail_id', 'DESC')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->due_amount;
			$rs_emp[$i]->total_due_amount = $total_due_amount;*/
            
			if($received_quantity == '' || $received_quantity == 0){
				array_push($rs_emp_new, $rs_emp[$i]);
			}
			
			if($received_quantity > 0 & $received_quantity != $distribute_pod_quantity){
                array_push($rs_emp_new, $rs_emp[$i]);
            }	
		}//end for
		
		$data["employees"] = $rs_emp_new;
		        
		$data['sizes'] = $this->db->select('sz_id, size')->get_where('sizes', array('sizes.status' => 1))->result();
		
		$data['units'] = $this->db->select('u_id, unit')->get_where('units', array('units.status' => 1))->result();
		
		//$data['consignement_number'] = $this->db->select('pod_id, consignement_number')->get_where('purchase_order_details', array('purchase_order_details.status' => 1, 'purchase_order_details.production_status' => 1, 'purchase_order_details.distribute_status' => 0))->result();
		
        $this->db->select('check_in.checkin_id, check_in.checkin_number, DATE_FORMAT(check_in.checkin_date, "%d-%m-%Y") as checkin_date, check_in.remarks, check_in.terms, check_in.status');
		$rs = $this->db->get_where('check_in', array('check_in.checkin_id' => $checkin_id))->result();
		$data['check_in_details'] = $rs;	
		
		//echo json_encode($data);die;
		
        return array('page'=>'checkin/checkin_edit_v', 'data'=>$data);
    }

    public function form_edit_checkin(){
		$checkin_id = $this->input->post('checkin_id');
		
        $updateArray = array(
            'checkin_number' => $this->input->post('checkin_number'),
            'checkin_date' => $this->input->post('checkin_date'),
            'remarks' => $this->input->post('remarks'),
            'terms' => $this->input->post('terms'),
            'status' => $this->input->post('status'),
            'user_id' => $this->session->user_id
        );
        $this->db->update('check_in', $updateArray, array('checkin_id' => $checkin_id));

        $data['type'] = 'success';
        $data['msg'] = 'Check-in updated successfully.';
        return $data;

    }

    public function ajax_checkin_details_table_data() {
        $checkin_id = $this->input->post('checkin_id');
        
        //actual db table column names
        $column_orderable = array(
            0 => 'purchase_order_details.consignement_number',
        );
        
        // Set searchable column fields
        $column_search = array('employees.name','purchase_order_details.consignement_number','purchase_order_details.roll_handel','sizes.size','units.unit','checkin_detail.received_quantity');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        // $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.status, employees.e_code, purchase_order_details.size_in_text, employees.name, employees.due_amount, purchase_order_details.consignement_number, sizes.size, units.unit, purchase_order.po_number');
        $this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
        $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = checkin_detail.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order_details.po_id = purchase_order.po_id', 'left');
        $this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
        $this->db->join('units', 'units.u_id = checkin_detail.u_id', 'left');
        
        $rs = $this->db->order_by('checkin_detail.checkin_detail_id')->get_where('checkin_detail', array('checkin_detail.checkin_id' => $checkin_id))->result();
            
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            // $this->db->order_by($order, $dir);
            
        $this->db->select('checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.status, employees.e_code, purchase_order_details.size_in_text, employees.name, employees.due_amount, purchase_order_details.consignement_number, sizes.size, units.unit, purchase_order.po_number');
        $this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
        $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = checkin_detail.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order_details.po_id = purchase_order.po_id', 'left');
        $this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
        $this->db->join('units', 'units.u_id = checkin_detail.u_id', 'left');
        
        $rs = $this->db->order_by('checkin_detail.checkin_detail_id')->get_where('checkin_detail', array('checkin_detail.checkin_id' => $checkin_id))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();
            
            $this->db->select('checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.status, employees.e_code, purchase_order_details.size_in_text, employees.name, employees.due_amount, purchase_order_details.consignement_number, sizes.size, units.unit, purchase_order.po_number');
        $this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
        $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = checkin_detail.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order_details.po_id = purchase_order.po_id', 'left');
        $this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
        $this->db->join('units', 'units.u_id = checkin_detail.u_id', 'left');
        
        $rs = $this->db->order_by('checkin_detail.checkin_detail_id')->get_where('checkin_detail', array('checkin_detail.checkin_id' => $checkin_id))->result();                  

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            // $this->db->order_by($order, $dir);
            
        $this->db->select('checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.status, employees.e_code, purchase_order_details.size_in_text, employees.name, employees.due_amount, purchase_order_details.consignement_number, sizes.size, units.unit, purchase_order.po_number');
        $this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
        $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = checkin_detail.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order_details.po_id = purchase_order.po_id', 'left');
        $this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
        $this->db->join('units', 'units.u_id = checkin_detail.u_id', 'left');
        
        $rs = $this->db->order_by('checkin_detail.checkin_detail_id')->get_where('checkin_detail', array('checkin_detail.checkin_id' => $checkin_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        

        foreach ($rs as $val) {
            if($val->roll_handel == 0){
                $roll_handel_txt = 'Roll';
            }else{
                $roll_handel_txt = 'Handel';
            }
            
            $nestedData['employee_name'] = $val->name . ' ['. $val->e_code .']';    
            $nestedData['po_number'] = $val->po_number;
            $nestedData['consignment_number'] = $val->consignement_number. '<b> /' .$val->size_in_text .'</b>';
            $nestedData['size'] = $val->size;
            $nestedData['unit'] = $val->unit;
            $nestedData['quantity'] = $val->received_quantity;
            
            $nestedData['action'] = '<a href="javascript:void(0)" checkin_detail_id="'.$val->checkin_detail_id.'" class="checkin_detail_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a data-tab="checkin_detail" data-pk="'.$val->checkin_detail_id.'" pk-name="checkin_detail_id" pod-id="'.$val->pod_id.'" due-amount="'.$val->due_amount.'" e-id="'.$val->e_id.'" href="javascript:void(0)" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            
            $data[] = $nestedData;

            // echo '<pre>', print_r($rs), '</pre>'; 
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }
	
	public function all_get_details_from_distribute_detail_table(){
        $e_id = $this->input->post('e_id');
		
		$this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, distribute_detail.distribute_pod_quantity, purchase_order_details.consignement_number, purchase_order_details.size_in_text');
		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
		$this->db->group_by('distribute_detail.pod_id');
		$distribute_detail = $this->db->get_where('distribute_detail', array('distribute_detail.e_id' => $e_id, 'purchase_order_details.check_in_status' => 0))->result();
		
		$distribute_detail_new = array();
		for($i = 0; $i < sizeof($distribute_detail); $i++){
			$pod_id = $distribute_detail[$i]->pod_id;
			$e_id = $distribute_detail[$i]->e_id;
			
			$distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('e_id' => $e_id, 'pod_id' => $pod_id))->result()[0]->distribute_pod_quantity;
            
			$received_quantity = $this->db->select('SUM(received_quantity) as received_quantity')->get_where('checkin_detail', array('e_id' => $e_id, 'pod_id' => $pod_id))->result()[0]->received_quantity;
            
			if($received_quantity <= $distribute_pod_quantity){
                array_push($distribute_detail_new, $distribute_detail[$i]);
            }	
		}//end for
		
		$rs["distribute_detail"] = $distribute_detail_new;	
		
		return $rs;
    }

    public function all_details_in_purchase_order(){
        $pod_id = $this->input->post('pod_id');
		$e_id = $this->input->post('e_id');
		
		$this->db->select('distribute_detail.dis_detail_id, distribute_detail.roll_handel, distribute_detail.sz_id,distribute_detail.u_id, sizes.size, units.unit');
		$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
		$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
		$this->db->group_by('distribute_detail.sz_id');			
		$distribute_detail = $this->db->get_where('distribute_detail', array('distribute_detail.pod_id' => $pod_id, 'distribute_detail.e_id' => $e_id))->result();
		
		$all_distribute_detail = array();
		for($i = 0; $i < sizeof($distribute_detail); $i++){
			$sz_id = $distribute_detail[$i]->sz_id;
			
			$received_quantity = $this->db->select_sum('received_quantity')->get_where('checkin_detail', array('pod_id' => $pod_id, 'sz_id' => $sz_id, 'e_id' => $e_id))->result()[0]->received_quantity;			
			$distribute_detail[$i]->received_quantity = $received_quantity;
			
			$distribute_pod_quantity = $this->db->select_sum('distribute_pod_quantity')->get_where('distribute_detail', array('pod_id' => $pod_id, 'sz_id' => $sz_id, 'e_id' => $e_id))->result()[0]->distribute_pod_quantity;
			$distribute_detail[$i]->distribute_pod_quantity = $distribute_pod_quantity;
			
			$max_allowed_to_received = ($distribute_pod_quantity - $received_quantity);
			$distribute_detail[$i]->max_allowed_to_received = $max_allowed_to_received;
			
			if($max_allowed_to_received > 0){
				array_push($all_distribute_detail, $distribute_detail[$i]);
			}
		}//end for
		
		
		$rs["all_distribute_detail"] = $all_distribute_detail;	
		
		return $rs;
    }
    
    public function ajax_all_colors_on_item_master(){
        $item_id = $this->input->post('item_id');
        $this->db->select('item_dtl.id_id as item_dtl_id, colors.*');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        return $this->db->get_where('item_dtl', array('item_dtl.status'=>'1', 'item_dtl.im_id' => $item_id, 'color <>' => null))->result_array();
    }

    public function form_add_checkin_details(){
		$checkin_id_add = $this->input->post('checkin_id_add');
		$distribute_pod_quantity = $this->input->post('distribute_pod_quantity');
		$pod_id = $this->input->post('pod_id_add');
		$sz_id = $this->input->post('sz_id_add');
		
		$net_payable = $this->input->post('net_payable');
		$net_payable_hidden = $this->input->post('net_payable_hidden');
		$due_amount = $this->input->post('due_amount');
		$e_id1 = $this->input->post('e_id_add');
		
		if($net_payable_hidden == $net_payable){
			$due_amount = 0;	
		}
		
		$insertArray = array(
			'checkin_id' => $checkin_id_add,
			'e_id' => $this->input->post('e_id_add'),
			'pod_id' => $this->input->post('pod_id_add'),
			'roll_handel' => $this->input->post('roll_handel_add'),
			'sz_id' => $sz_id,
			'u_id' => $this->input->post('u_id_add'),
			'received_quantity' => $this->input->post('received_quantity_add'),
			'remarks' => $this->input->post('remarks_add'),
			'terms' => $this->input->post('terms_add'),
			'rate_per_bag' => $this->input->post('rate_per_bag'),
			'todays_payable' => $this->input->post('todays_payable'),
			'due_amount' => $due_amount,
			'net_payable' => $net_payable,
			'user_id' => $this->session->user_id
		);

		$this->db->insert('checkin_detail', $insertArray);		
		
        //echo $this->db->last_query();die;
		if($this->db->insert_id() > 0){
			//Update due amount
			$update_array_due = array(
				'due_amount' => $due_amount
			);
			$this->db->where(array('e_id' => $e_id1))->update('employees', $update_array_due);
			
			//Update the Purchase_Order Details Table Distribution status
            $distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('pod_id' => $pod_id))->result()[0]->distribute_pod_quantity;
            
			$received_quantity = $this->db->select('SUM(received_quantity) as received_quantity')->get_where('checkin_detail', array('pod_id' => $pod_id))->result()[0]->received_quantity;
            
			if($received_quantity == $distribute_pod_quantity){
                $update_array = array(
                    'check_in_status' => 1
                );
                $this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
            }
			
		$this->db->select('distribute_detail.e_id, employees.name, employees.e_code, employees.rate_per_bag, employees.due_amount');
		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
		$this->db->group_by('distribute_detail.e_id');
		$rs_emp = $this->db->get_where('distribute_detail', array('distribute_detail.status' => 1))->result();
		
		$rs_emp_new = array();
		
		for($i = 0; $i < sizeof($rs_emp); $i++){
			$e_id = $rs_emp[$i]->e_id;
			
			$total_due_amount = $rs_emp[$i]->due_amount;
			$rs_emp[$i]->total_due_amount = $total_due_amount;
			
			$distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('e_id' => $e_id))->result()[0]->distribute_pod_quantity;
            
			$received_quantity = $this->db->select('SUM(received_quantity) as received_quantity')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->received_quantity;
			
			/*$total_due_amount = $this->db->select('due_amount')->limit(0,1)->order_by('checkin_detail_id', 'DESC')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->due_amount;
			*/
            
			if($received_quantity == '' || $received_quantity == 0){
				array_push($rs_emp_new, $rs_emp[$i]);
			}
			
			if($received_quantity > 0 & $received_quantity != $distribute_pod_quantity){
                array_push($rs_emp_new, $rs_emp[$i]);
            }	
		}//end for
		
		$data["employees"] = $rs_emp_new;
						
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'Check-in details added successfully.';
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Data Insert Problem';
		}

        return $data;
    }

    public function form_edit_checkin_details(){
        $checkin_id_add = $this->input->post('checkin_id_edit');
        $checkin_detail_id_add = $this->input->post('checkin_detail_id_rdit');
        $distribute_pod_quantity = $this->input->post('received_quantity_edit');
        $pod_id = $this->input->post('pod_id_edit');
        
        $net_payable = $this->input->post('net_payable_edit');
        $net_payable_hidden = $this->input->post('net_payable_hidden_edit');
        $due_amount = $this->input->post('due_amount_edit');
        $e_id1 = $this->input->post('e_id_edit');
        
        if($net_payable_hidden == $net_payable){
            $due_amount = 0;    
        }
        
        $insertArray = array(
            'checkin_id' => $checkin_id_add,
            'e_id' => $this->input->post('e_id_edit'),
            'received_quantity' => $this->input->post('received_quantity_edit'),
            'rate_per_bag' => $this->input->post('rate_per_bag_edit'),
            'todays_payable' => $this->input->post('todays_payable_edit'),
            'due_amount' => $due_amount,
            'net_payable' => $net_payable,
            'user_id' => $this->session->user_id
        );

        $rs = $this->db->update('checkin_detail', $insertArray, array('checkin_detail_id' => $checkin_detail_id_add));      
        
        //echo $this->db->last_query();die;
        if($rs = 1){
            //Update due amount
            $update_array_due = array(
                'due_amount' => $due_amount
            );
            $this->db->where(array('e_id' => $e_id1))->update('employees', $update_array_due);
            
            //Update the Purchase_Order Details Table Distribution status
            $distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('pod_id' => $pod_id))->result()[0]->distribute_pod_quantity;
            
            $received_quantity = $this->db->select('SUM(received_quantity) as received_quantity')->get_where('checkin_detail', array('pod_id' => $pod_id))->result()[0]->received_quantity;
            
            if($received_quantity == $distribute_pod_quantity){
                $update_array = array(
                    'check_in_status' => 1
                );
                $this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
            }
            
        $this->db->select('distribute_detail.e_id, employees.name, employees.e_code, employees.rate_per_bag, employees.due_amount');
        $this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
        $this->db->group_by('distribute_detail.e_id');
        $rs_emp = $this->db->get_where('distribute_detail', array('distribute_detail.status' => 1))->result();
        
        $rs_emp_new = array();
        
        for($i = 0; $i < sizeof($rs_emp); $i++){
            $e_id = $rs_emp[$i]->e_id;
            
            $total_due_amount = $rs_emp[$i]->due_amount;
            $rs_emp[$i]->total_due_amount = $total_due_amount;
            
            $distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('e_id' => $e_id))->result()[0]->distribute_pod_quantity;
            
            $received_quantity = $this->db->select('SUM(received_quantity) as received_quantity')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->received_quantity;
            
            /*$total_due_amount = $this->db->select('due_amount')->limit(0,1)->order_by('checkin_detail_id', 'DESC')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->due_amount;
            */
            
            if($received_quantity == '' || $received_quantity == 0){
                array_push($rs_emp_new, $rs_emp[$i]);
            }
            
            if($received_quantity > 0 & $received_quantity != $distribute_pod_quantity){
                array_push($rs_emp_new, $rs_emp[$i]);
            }   
        }//end for
        
        $data["employees"] = $rs_emp_new;
                        
            $data['insert_id'] = $this->db->insert_id();
            $data['type'] = 'success';
            $data['msg'] = 'Check-in details updated successfully.';
        }else{
            $data['type'] = 'error';
            $data['msg'] = 'Data Insert Problem';
        }

        return $data;
    }

    public function ajax_fetch_purchase_order_details_on_pk(){
        $pod_id = $this->input->post('pod_id');
		$this->db->select('purchase_order_details.pod_id, purchase_order_details.po_id, purchase_order_details.roll_handel, purchase_order_details.roll_weight, purchase_order_details.c_id, purchase_order_details.sz_id, purchase_order_details.pod_quantity, purchase_order_details.u_id, purchase_order_details.rate_per_unit, purchase_order_details.pod_total, purchase_order_details.pod_remarks, purchase_order_details.consignement_number, colors.color, colors.c_code, sizes.size, units.unit');
		$this->db->join('colors', 'colors.c_id = purchase_order_details.c_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = purchase_order_details.sz_id', 'left');
		$this->db->join('units', 'units.u_id = purchase_order_details.u_id', 'left');
		
		return $rs = $this->db->get_where('purchase_order_details', array('purchase_order_details.pod_id' => $pod_id))->result();
    }

    public function ajax_fetch_checkin_details_on_pk(){

        $checkin_id = $this->input->post('checkin_id');

        $this->db->select('checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.status, checkin_detail.todays_payable, checkin_detail.net_payable, checkin_detail.rate_per_bag, employees.e_id, employees.e_code, employees.name, employees.due_amount, purchase_order_details.consignement_number, purchase_order_details.size_in_text, sizes.size, units.unit');
        $this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
        $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = checkin_detail.pod_id', 'left');
        $this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
        $this->db->join('units', 'units.u_id = checkin_detail.u_id', 'left');
        
        return $rs = $this->db->order_by('checkin_detail.checkin_detail_id')->get_where('checkin_detail', array('checkin_detail.checkin_detail_id' => $checkin_id))->result();

        // echo '<pre>', print_r($rs), '</pre>'; die();

    }
	
	public function ajax_unique_po_number_and_art_no_and_lth_color(){
        $purchase_order_id = $this->input->post('purchase_order_id');
		$id_id = $this->input->post('id_id');
		$color = $this->input->post('color');

        $rs = $this->db->get_where('purchase_order_details', array('id_id' => $color, 'po_id' => $purchase_order_id))->num_rows();
        if($rs != '0') {
            $data = 'Same Item and Colour is already exists.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }
	
    public function purchase_order_print_with_code($po_id){
        
        $data['purchase_order_details'] = $this->db
                ->select('purchase_order.*, purchase_order_details.*, acc_master.name, acc_master.address,countries.country,item_master.item,colors.color, colors.c_code, units.unit,item_groups.ig_id as item_group, thick')
                ->join('purchase_order_details', 'purchase_order_details.po_id = purchase_order.po_id', 'left') // 
                ->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left')
                ->join('countries', 'countries.c_id = acc_master.c_id', 'left')
                ->join('item_dtl', 'purchase_order_details.id_id = item_dtl.id_id', 'left')
                ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
                ->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left')
                ->join('units', 'units.u_id = item_groups.u_id', 'left')
                ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
                ->get_where('purchase_order', array('purchase_order.po_id' => $po_id))
                ->result();
        return array('page'=>'purchase_order/purchase_order_print_with_code_v', 'data'=>$data);
    }

    public function purchase_order_print_without_code($po_id){
        
        $data['purchase_order_details'] = $this->db
                ->select('purchase_order.*, purchase_order_details.*, acc_master.name, acc_master.address,countries.country,item_master.item,colors.color, colors.c_code, units.unit,item_groups.ig_id as item_group, thick')
                ->join('purchase_order_details', 'purchase_order_details.po_id = purchase_order.po_id', 'left') // 
                ->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left')
                ->join('countries', 'countries.c_id = acc_master.c_id', 'left')
                ->join('item_dtl', 'purchase_order_details.id_id = item_dtl.id_id', 'left')
                ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
                ->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left')
                ->join('units', 'units.u_id = item_groups.u_id', 'left')
                ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
                ->get_where('purchase_order', array('purchase_order.po_id' => $po_id))
                ->result();
        return array('page'=>'purchase_order/purchase_order_print_without_code_v', 'data'=>$data);
    }


   //  public function form_edit_checkin_details(){
   //      $pod_total = ($this->input->post('pod_quantity') * $this->input->post('rate_per_unit'));
		
   //      $updateArray = array(
   //          'roll_handel' => $this->input->post('roll_handel'),
			// 'roll_weight' => $this->input->post('roll_weight'),
			// //'c_id' => $this->input->post('c_id'),
			// //'sz_id' => $this->input->post('sz_id'),
			// 'pod_quantity' => $this->input->post('pod_quantity'),
			// //'u_id' => $this->input->post('u_id'),
			// 'rate_per_unit' => $this->input->post('rate_per_unit'),
			// 'pod_total' => $pod_total,
   //          'pod_remarks' => $this->input->post('pod_remarks_edit'),
   //          'consignement_number' => $this->input->post('consignement_number'),
   //          'user_id' => $this->session->user_id
   //      );
   //      $pod_id = $this->input->post('pod_id');
   //      $this->db->update('purchase_order_details', $updateArray, array('pod_id' => $pod_id));

   //      /*$data['pod_total'] = $this->db->select_sum('pod_total')->get_where('purchase_order_details', array('po_id' => $this->input->post('purchase_order_id')))->result()[0]->pod_total;
   //      // update purchase order table 
   //      $updateArray1= array(
   //          'po_total' => $data['pod_total']
   //      );
   //      $this->db->update('purchase_order', $updateArray1, array('po_id' => $this->input->post('purchase_order_id')))*/;

   //      $data['type'] = 'success';
   //      $data['msg'] = 'purchase order details updated successfully.';
   //      return $data;
   //  }
    // ---------------------------------------working-----------------------------------------

    

    public function ajax_unique_purchase_order_number() {
        $order_no = $this->input->post('order_no');
        $rs = $this->db->get_where('purchase_order', array('co_no' => $order_no))->num_rows();
        // echo $this->db->last_query();die;
        
        if($rs != '0') {
            $data = 'Order no. already exists.';
        }else{
            $data='true';
        }

        return $data;
    }

    public function ajax_distribute_delete_on_pk(){
		$pk_name = $this->input->post('pk_name');
		$pk_value = $this->input->post('pk_value');
		$ref_table = $this->input->post('ref_table');		
		$tab = $this->input->post('tab');
		
		$this->db->select('distribute_detail.pod_id');
		$this->db->group_by('distribute_detail.pod_id');
		$rs = $this->db->get_where('distribute_detail', array('distribute_detail.dis_id' => $pk_value))->result();
		$data['rs'] = $rs;
		
		for($i = 0; $i < sizeof($rs); $i++){
			$pod_id = $rs[$i]->pod_id;
			$update_array = array(
				'distribute_status' => 0
			);
			$this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
		}//end for
		
		$this->db->where($pk_name, $pk_value)->delete($ref_table);
		$this->db->where($pk_name, $pk_value)->delete($tab);
		
		$data['title'] = 'Deleted!';
		$data['type'] = 'success';
		$data['msg'] = 'Distribute Data Successfully Deleted';
		
        return $data;
    }
	
	public function del_row_on_table_pk_checkin_details(){
		$pk = $this->input->post('pk');
		$tab = $this->input->post('tab');
		$pk_name = $this->input->post('pk_name');
		$pod_id = $this->input->post('pod_id');
		
		//check & update due amount start
		$due_amount = $this->input->post('due_amount');
		$e_id1 = $this->input->post('e_id');
		
		$due_amount_old = $this->db->select('due_amount')->get_where('employees', array('e_id' => $e_id1))->result()[0]->due_amount;
		if($due_amount_old > 0){
			$due_amount_new = $due_amount_old - $due_amount;
			$update_array1 = array(
				'due_amount' => $due_amount_new
			);
			$this->db->where(array('e_id' => $e_id1))->update('employees', $update_array1);
		}
		//check & update due amount end
		
		$update_array = array(
			'check_in_status' => 0
		);
		$this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
			
		$this->db->where($pk_name, $pk)->delete($tab);
		
		//Update the Purchase_Order Details Table Distribution status
		$this->db->select('distribute_detail.e_id, employees.name, employees.e_code, employees.rate_per_bag, employees.due_amount');
		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
		$this->db->group_by('distribute_detail.e_id');
		$rs_emp = $this->db->get_where('distribute_detail', array('distribute_detail.status' => 1))->result();
		
		$rs_emp_new = array();
		
		for($i = 0; $i < sizeof($rs_emp); $i++){
			$e_id = $rs_emp[$i]->e_id;		
			
			$total_due_amount = $rs_emp[$i]->due_amount;
			$rs_emp[$i]->total_due_amount = $total_due_amount;
			
			$distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('e_id' => $e_id))->result()[0]->distribute_pod_quantity;
            
			$received_quantity = $this->db->select('SUM(received_quantity) as received_quantity')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->received_quantity;
			
			/*$total_due_amount = $this->db->select('due_amount')->limit(0,1)->order_by('checkin_detail_id', 'DESC')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->due_amount;
			$rs_emp[$i]->total_due_amount = $total_due_amount;*/
            
			if($received_quantity == '' || $received_quantity == 0){
				array_push($rs_emp_new, $rs_emp[$i]);
			}
			
			if($received_quantity > 0 & $received_quantity != $distribute_pod_quantity){
                array_push($rs_emp_new, $rs_emp[$i]);
            }	
		}//end for
		
		$data["employees"] = $rs_emp_new;
		
		$data['title'] = 'Deleted!';
		$data['type'] = 'success';
		$data['msg'] = 'Checkin Detail Successfully Deleted';
		
        return $data;
    }

    // purchase ORDER ENDS 

}