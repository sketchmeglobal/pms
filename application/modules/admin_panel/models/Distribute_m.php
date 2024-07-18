<?php

class Distribute_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function distribute() {
        $data = '';
        return array('page'=>'distribute/distribute_list_v', 'data'=>$data);
    }

    public function ajax_distribute_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'distribute.dis_number',
            1 => 'distribute.dis_date',
            2 => 'distribute.dis_return_date',
            4 => 'distribute.status',
        );
		
        // Set searchable column fields
        $column_search = array('distribute.dis_number','distribute.dis_date','distribute.dis_return_date','distribute.status');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

		$this->db->select('distribute.dis_id, distribute.dis_number, DATE_FORMAT(distribute.dis_date, "%d-%m-%Y") as dis_date, DATE_FORMAT(distribute.dis_return_date, "%d-%m-%Y") as dis_return_date, distribute.remarks, distribute.terms, distribute.status');
		//$this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
		$rs = $this->db->get_where('distribute', array('distribute.status => 1'))->result();
		
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('distribute.dis_id, distribute.dis_number, DATE_FORMAT(distribute.dis_date, "%d-%m-%Y") as dis_date, DATE_FORMAT(distribute.dis_return_date, "%d-%m-%Y") as dis_return_date, distribute.remarks, distribute.terms, distribute.status');
            //$this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
            $rs = $this->db->get_where('distribute', array('distribute.status => 1'))->result();
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
			
			$this->db->select('distribute.dis_id, distribute.dis_number, DATE_FORMAT(distribute.dis_date, "%d-%m-%Y") as dis_date, DATE_FORMAT(distribute.dis_return_date, "%d-%m-%Y") as dis_return_date, distribute.remarks, distribute.terms, distribute.status');
            //$this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
            $rs = $this->db->get_where('distribute', array('distribute.status => 1'))->result();
			
			$totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('distribute.dis_id, distribute.dis_number, DATE_FORMAT(distribute.dis_date, "%d-%m-%Y") as dis_date, DATE_FORMAT(distribute.dis_return_date, "%d-%m-%Y") as dis_return_date, distribute.remarks, distribute.terms, distribute.status');
            //$this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
            $rs = $this->db->get_where('distribute', array('distribute.status => 1'))->result();

            $this->db->flush_cache();
        }

        $data = array();

        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['dis_number'] = $val->dis_number;
            $nestedData['dis_date'] = $val->dis_date;
            $nestedData['dis_return_date'] = $val->dis_return_date;
            $nestedData['status'] = $status;
            $nestedData['action'] = '<a href="'. base_url('admin/edit-distribute/'.$val->dis_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a href="javascript:void(0)" pk-name="dis_id" pk-value="'.$val->dis_id.'" tab="distribute" child="1" ref-table="distribute_detail" ref-pk-name="dis_id" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
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

// ADD purchase ORDER 


    public function add_distribute() {
		$data['dis_number'] = 'DIS/'.date('dmY/his');
		
        $data['supplier_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', 
		array('acc_master.status' => 1))->result();
		
		$today = date('Y-m-d');
		$data['dis_date'] = $today;
		
		$time = strtotime($today);
		$final = date("Y-m-d", strtotime("+7 days", $time));
		$data['dis_return_date'] = $final;

        return array('page'=>'distribute/distribute_add_v', 'data'=>$data);
    }

    public function ajax_unique_distribute_number(){
        $dis_number = $this->input->post('dis_number');

        $rs = $this->db->get_where('distribute', array('dis_number' => $dis_number))->num_rows();
        if($rs != '0') {
            $data = 'Distribute no. already exists.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function form_add_distribute(){

       $insertArray = array(
			'dis_number' => $this->input->post('dis_number'),
			'dis_date' => $this->input->post('dis_date'),
			'dis_return_date' => $this->input->post('dis_return_date'),
			'remarks' => $this->input->post('remarks'),
			'terms' => $this->input->post('terms'),
			'user_id' => $this->session->user_id
		);
		
		$this->db->insert('distribute', $insertArray);
		if($this->db->insert_id() > 0){
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'Purchase order added successfully.';
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Data Insert Error';
		}
		return $data; 
    }

    public function edit_distribute($dis_id) {
        $data['employees'] = $this->db->select('e_id, name, e_code')->get_where('employees', array('employees.status' => 1))->result();
        
		$data['sizes'] = $this->db->select('sz_id, size')->get_where('sizes', array('sizes.status' => 1))->result();
		
		$data['units'] = $this->db->select('u_id, unit')->get_where('units', array('units.status' => 1))->result();
		
		$data['consignement_number'] = $this->db->select('pod_id, consignement_number')->get_where('purchase_order_details', array('purchase_order_details.status' => 1, 'purchase_order_details.production_status' => 1, 'purchase_order_details.distribute_status' => 0))->result();
		
        $this->db->select('distribute.dis_id, distribute.dis_number, DATE_FORMAT(distribute.dis_date, "%d-%m-%Y") as dis_date, DATE_FORMAT(distribute.dis_return_date, "%d-%m-%Y") as dis_return_date, distribute.remarks, distribute.terms, distribute.status');
		//$this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
		$rs = $this->db->get_where('distribute', array('distribute.dis_id' => $dis_id))->result();
		$data['distribute_details'] = $rs;	
		
		//echo json_encode($data);
		
        return array('page'=>'distribute/distribute_edit_v', 'data'=>$data);
    }

    public function form_edit_distribute(){
		$dis_id = $this->input->post('dis_id');
		
        $updateArray = array(
            'dis_number' => $this->input->post('dis_number'),
            'dis_date' => $this->input->post('dis_date'),
            'dis_return_date' => $this->input->post('dis_return_date'),
            'remarks' => $this->input->post('remarks'),
            'terms' => $this->input->post('terms'),
            'status' => $this->input->post('status'),
            'user_id' => $this->session->user_id
        );
        $this->db->update('distribute', $updateArray, array('dis_id' => $dis_id));

        $data['type'] = 'success';
        $data['msg'] = 'Distribute updated successfully.';
        return $data;

    }

    public function ajax_distribute_details_table_data() {
        $dis_id = $this->input->post('dis_id');
		
        //actual db table column names
        $column_orderable = array(
            0 => 'employees.name',
			1 => 'purchase_order_details.consignement_number',
			2 => 'purchase_order_details.roll_handel',
            3 => 'sizes.size',
			4 => 'units.unit',
			5 => 'distribute_detail.distribute_pod_quantity'
        );
		
        // Set searchable column fields
        $column_search = array('employees.name','purchase_order_details.consignement_number','purchase_order_details.roll_handel','sizes.size','units.unit');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, distribute_detail.distribute_pod_quantity, distribute_detail.remarks, distribute_detail.terms, distribute_detail.status, employees.e_code, employees.name, purchase_order_details.consignement_number, sizes.size, units.unit');
		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
		$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
		
		$rs = $this->db->get_where('distribute_detail', array('distribute_detail.dis_id' => $dis_id))->result();
			
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, distribute_detail.distribute_pod_quantity, distribute_detail.remarks, distribute_detail.terms, distribute_detail.status, employees.e_code, employees.name, purchase_order_details.consignement_number, sizes.size, units.unit');
			$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
			$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
			$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
			$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
			
			$rs = $this->db->get_where('distribute_detail', array('distribute_detail.dis_id' => $dis_id))->result();
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

            $this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, distribute_detail.distribute_pod_quantity, distribute_detail.remarks, distribute_detail.terms, distribute_detail.status, employees.e_code, employees.name, purchase_order_details.consignement_number, sizes.size, units.unit');
			$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
			$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
			$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
			$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
			
			$rs = $this->db->get_where('distribute_detail', array('distribute_detail.dis_id' => $dis_id))->result();
        

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, distribute_detail.distribute_pod_quantity, distribute_detail.remarks, distribute_detail.terms, distribute_detail.status, employees.e_code, employees.name, purchase_order_details.consignement_number, sizes.size, units.unit');
			$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
			$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
			$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
			$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
			
			$rs = $this->db->get_where('distribute_detail', array('distribute_detail.dis_id' => $dis_id))->result();

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
            $nestedData['consignment_number'] = $val->consignement_number;		
            $nestedData['roll_handel'] = $roll_handel_txt;
            $nestedData['size'] = $val->size;
            $nestedData['unit'] = $val->unit;
            $nestedData['quantity'] = $val->distribute_pod_quantity;
            
            $nestedData['action'] = '<!--<a href="javascript:void(0)" dis_detail_id="'.$val->dis_detail_id.'" class="distribute_details_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>-->
            <a data-tab="distribute_detail" data-pk="'.$val->dis_detail_id.'" pk-name="dis_detail_id" pod-id="'.$val->pod_id.'" href="javascript:void(0)" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            
            $data[] = $nestedData;

            //echo '<pre>', print_r($rs), '</pre>'; 
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function all_details_in_purchase_order(){
        $pod_id = $this->input->post('pod_id');
		
		$this->db->select('purchase_order_details.pod_id, purchase_order_details.po_id, purchase_order_details.roll_handel, purchase_order_details.roll_weight, purchase_order_details.c_id, purchase_order_details.sz_id, purchase_order_details.pod_quantity, purchase_order_details.u_id, purchase_order_details.rate_per_unit, purchase_order_details.pod_total, purchase_order_details.pod_remarks, purchase_order_details.consignement_number, colors.color, colors.c_code, sizes.size, units.unit, production.prod_bag_produced');
		$this->db->join('colors', 'colors.c_id = purchase_order_details.c_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = purchase_order_details.sz_id', 'left');
		$this->db->join('units', 'units.u_id = purchase_order_details.u_id', 'left');
		$this->db->join('production', 'production.pod_id = purchase_order_details.pod_id', 'left');
		
		$purchase_order_details = $this->db->get_where('purchase_order_details', array('purchase_order_details.pod_id' => $pod_id))->result();
		$rs["purchase_order_details"] = $purchase_order_details;
		
		$this->db->select('production_detail.sz_id, sizes.sz_id, sizes.size');
		$this->db->join('sizes', 'sizes.sz_id = production_detail.sz_id', 'left');
		$this->db->group_by('production_detail.sz_id');
		$all_size = $this->db->get_where('production_detail', array('production_detail.pod_id' => $pod_id))->result();
		
		$all_size_new = array();
		for($i = 0; $i < sizeof($all_size); $i++){
			$sz_id = $all_size[$i]->sz_id;
			
			$prod_detail_quantity = $this->db->select_sum('prod_detail_quantity')->get_where('production_detail', array('pod_id' => $pod_id, 'sz_id' => $sz_id))->result()[0]->prod_detail_quantity;			
			$all_size[$i]->prod_detail_quantity = $prod_detail_quantity;
			
			$distribute_pod_quantity = $this->db->select_sum('distribute_pod_quantity')->get_where('distribute_detail', array('pod_id' => $pod_id, 'sz_id' => $sz_id))->result()[0]->distribute_pod_quantity;
			$all_size[$i]->distribute_pod_quantity = $distribute_pod_quantity;
			
			$max_allowed_to_distribute = ($prod_detail_quantity - $distribute_pod_quantity);
			$all_size[$i]->max_allowed_to_distribute = $max_allowed_to_distribute;
			
			if($max_allowed_to_distribute > 0){
				array_push($all_size_new, $all_size[$i]);
			}
		}//end for
		
		
		$rs["all_size"] = $all_size_new;	
		
		return $rs;
    }
    
    public function ajax_all_colors_on_item_master(){
        $item_id = $this->input->post('item_id');
        $this->db->select('item_dtl.id_id as item_dtl_id, colors.*');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        return $this->db->get_where('item_dtl', array('item_dtl.status'=>'1', 'item_dtl.im_id' => $item_id, 'color <>' => null))->result_array();
    }

    public function form_add_distribute_details(){
		$dis_id_add = $this->input->post('dis_id_add');
		$prod_detail_quantity = $this->input->post('prod_detail_quantity');
		$pod_id = $this->input->post('pod_id_add');
		$sz_id = $this->input->post('sz_id_add');
		
		$insertArray = array(
			'dis_id' => $dis_id_add,
			'e_id' => $this->input->post('e_id_add'),
			'pod_id' => $this->input->post('pod_id_add'),
			'roll_handel' => $this->input->post('roll_handel_add_hidden'),
			'sz_id' => $sz_id,
			'u_id' => $this->input->post('u_id_add_hidden'),
			'distribute_pod_quantity' => $this->input->post('distribute_pod_quantity_add'),
			'remarks' => $this->input->post('remarks_add'),
			'terms' => $this->input->post('terms_add'),
			'user_id' => $this->session->user_id
		);

		$this->db->insert('distribute_detail', $insertArray);		
		
        //echo $this->db->last_query();die;
		if($this->db->insert_id() > 0){
			//Update the Purchase_Order Details Table Distribution status
            $distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as distribute_pod_quantity')->get_where('distribute_detail', array('pod_id' => $pod_id))->result()[0]->distribute_pod_quantity;
            
			$prod_detail_quantity = $this->db->select('SUM(prod_detail_quantity) as prod_detail_quantity')->get_where('production_detail', array('pod_id' => $pod_id))->result()[0]->prod_detail_quantity;
            
			if($prod_detail_quantity == $distribute_pod_quantity){
                $update_array = array(
                    'distribute_status' => 1
                );
                $this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
            }
			
			//Fetch CN No.
			$data['cn_list'] = $this->db->select('pod_id, consignement_number')->get_where('purchase_order_details', array('purchase_order_details.status' => 1, 'purchase_order_details.production_status' => 1, 'purchase_order_details.distribute_status' => 0))->result();
						
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'Distribute details added successfully.';
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


    public function form_edit_distribute_details(){
        $pod_total = ($this->input->post('pod_quantity') * $this->input->post('rate_per_unit'));
		
        $updateArray = array(
            'roll_handel' => $this->input->post('roll_handel'),
			'roll_weight' => $this->input->post('roll_weight'),
			//'c_id' => $this->input->post('c_id'),
			//'sz_id' => $this->input->post('sz_id'),
			'pod_quantity' => $this->input->post('pod_quantity'),
			//'u_id' => $this->input->post('u_id'),
			'rate_per_unit' => $this->input->post('rate_per_unit'),
			'pod_total' => $pod_total,
            'pod_remarks' => $this->input->post('pod_remarks_edit'),
            'consignement_number' => $this->input->post('consignement_number'),
            'user_id' => $this->session->user_id
        );
        $pod_id = $this->input->post('pod_id');
        $this->db->update('purchase_order_details', $updateArray, array('pod_id' => $pod_id));

        /*$data['pod_total'] = $this->db->select_sum('pod_total')->get_where('purchase_order_details', array('po_id' => $this->input->post('purchase_order_id')))->result()[0]->pod_total;
        // update purchase order table 
        $updateArray1= array(
            'po_total' => $data['pod_total']
        );
        $this->db->update('purchase_order', $updateArray1, array('po_id' => $this->input->post('purchase_order_id')))*/;

        $data['type'] = 'success';
        $data['msg'] = 'purchase order details updated successfully.';
        return $data;
    }
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
	
	public function del_row_on_table_pk_distribute_details(){
		$pk = $this->input->post('pk');
		$tab = $this->input->post('tab');
		$pk_name = $this->input->post('pk_name');
		$pod_id = $this->input->post('pod_id');
		
		$update_array = array(
			'distribute_status' => 0
		);
		$this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
		
		//Fetch CN No.
		$data['cn_list'] = $this->db->select('pod_id, consignement_number')->get_where('purchase_order_details', array('purchase_order_details.status' => 1, 'purchase_order_details.production_status' => 1, 'purchase_order_details.distribute_status' => 0))->result();
			
		$this->db->where($pk_name, $pk)->delete($tab);
		$data['title'] = 'Deleted!';
		$data['type'] = 'success';
		$data['msg'] = 'Distribute Detail Successfully Deleted';
		
        return $data;
    }

    // purchase ORDER ENDS 

}