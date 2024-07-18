<?php

class Purchase_order_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function purchase_order() {
        $data = '';
        return array('page'=>'purchase_order/purchase_order_list_v', 'data'=>$data);
    }

    public function ajax_purchase_order_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'po_number',
            1 => 'po_date',
            2 => 'name',
            4 => 'status',
        );
        // Set searchable column fields
        $column_search = array('po_number','name');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get('purchase_order')->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            // $this->db->order_by($order, $dir);
            $this->db->order_by('purchase_order.create_date');
            $this->db->select('po_id, po_number, DATE_FORMAT(po_date, "%d-%m-%Y") as po_date, DATE_FORMAT(po_delivery_date, "%d-%m-%Y") as po_delivery_date, remarks, terms, po_total, purchase_order.status, name');
            $this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
            $rs = $this->db->get_where('purchase_order', array('purchase_order.status => 1'))->result();
            // echo $this->db->last_query();
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

            $this->db->select('po_id, po_number, DATE_FORMAT(po_date, "%d-%m-%Y") as po_date, DATE_FORMAT(po_delivery_date, "%d-%m-%Y") as po_delivery_date, remarks, terms, po_total, purchase_order.status, name');
            $this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
            $rs = $this->db->get_where('purchase_order', array('purchase_order.status => 1'))->result();
            // echo $this->db->get_compiled_select('purchase_order');
            // exit();
        

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('po_id, po_number, DATE_FORMAT(po_date, "%d-%m-%Y") as po_date, DATE_FORMAT(po_delivery_date, "%d-%m-%Y") as po_delivery_date, remarks, terms, po_total, purchase_order.status, name');
            $this->db->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left');
            $rs = $this->db->get_where('purchase_order', array('purchase_order.status => 1'))->result();

            $this->db->flush_cache();
        }

        $data = array();

        // echo '<pre>', print_r($rs), '</pre>'; die;
        // echo $this->db->last_query();die;

        foreach ($rs as $val) {

            if($val->status == '1'){$status='Enable';} else{$status='Disable';}
        
        $query = "SELECT
                      purchase_order_details.size_in_text
                    FROM
                        `purchase_order_details`
                    WHERE
                        purchase_order_details.po_id = $val->po_id
                    GROUP BY
                        size_in_text";

            $purchase_order_details_id = $this->db->query($query)->result();
            
        $purchase_order_total = 0;

        if(count($purchase_order_details_id) > 0) {
        foreach($purchase_order_details_id as $p_o_i_d) {
         $total_amount = $this->db->get_where('purchase_order_details', array('po_id' => $val->po_id, 'size_in_text' => $p_o_i_d->size_in_text))->row()->pod_total;

        $purchase_order_total += $total_amount;

        }
        }

            $production_link = base_url('admin/production').'/'.$val->po_id;
            $rcv_link = base_url('admin/checkin').'/'.$val->po_id;

            $nestedData['po_number'] = $val->po_number;
            $nestedData['po_date'] = $val->po_date;
            $nestedData['name'] = $val->name;
            $nestedData['po_total'] = $purchase_order_total;
            $nestedData['status'] = $status;
            $nestedData['action'] = '<a href="'. base_url('admin/edit-purchase-order/'.$val->po_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit PO</a>
            <a href="'.$production_link.'" po-id="'.$val->po_id.'" class="btn bg-green"><i class="fa fa-mail-forward"></i> Distribute</a>
            <a href="'.$rcv_link.'" po-id="'.$val->po_id.'" class="btn bg-beige"><i class="fa fa-mail-reply"></i> Receive</a>
            <button po-id="'.$val->po_id .'" type="button" class="btn btn-primary print_all"><i class="fa fa-print"></i> Print</button>
            <a href="javascript:void(0)" pk-name="po_id" pk-value="'.$val->po_id.'" tab="purchase_order" child="1" ref-table="purchase_order_details" ref-pk-name="item-master#multiple-check" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
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

// ADD purchase ORDER 


    public function add_purchase_order() {
		$data['auto_po_number'] = 'PO/'.date('dmY/his');
		
        $data['supplier_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', 
		array('acc_master.status' => 1, 'acc_master.supplier_buyer' => 0))->result();
		
		$today = date('Y-m-d');
		$data['po_date'] = $today;
		
		$time = strtotime($today);
		$final = date("Y-m-d", strtotime("+1 month", $time));
		$data['delivery_date'] = $final;
		
		$data['consignement_number'] = date('dmhis');
		
		$data['mill_details'] = $this->db->get_where('mill', array('mill.status' => 1))->result();

        return array('page'=>'purchase_order/purchase_order_add_v', 'data'=>$data);
    }

    public function ajax_unique_purchase_order_no(){
        $po_number = $this->input->post('po_number');

        $rs = $this->db->get_where('purchase_order', array('po_number' => $po_number))->num_rows();
        if($rs != '0') {
            $data = 'Purchase order no. already exists.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function form_add_purchase_order(){
		$consignement_number = $this->input->post('consignement_number');
		$number_of_copy = $this->input->post('number_of_copy');
		//print_r($_FILES); die;
		if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/supplier_po/';
            $config['allowed_types'] = 'docx|doc|xlx|pdf|gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 3072;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = "Image Upload problem Max Size limit 3072kb";//$this->upload->display_errors();
				
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
				$insertArray = array(
					'po_number' => $this->input->post('po_number'),
					'am_id' => $this->input->post('acc_master_id'),
					'mill_id' => $this->input->post('mill_id'), 
					'po_invoice_number' => $this->input->post('po_invoice_number'),
					'po_date' => $this->input->post('po_date'),
					'po_delivery_date' => $this->input->post('delivery_date'),
					'img' => $uploaded_data['file_name'],
					'remarks' => $this->input->post('remarks'),
					'terms' => $this->input->post('terms'),
					'user_id' => $this->session->user_id
				); 
				//echo 'block 2'; die;
				$this->db->insert('purchase_order', $insertArray);
				$purchase_order_id = $this->db->insert_id();
			
				$data['insert_id'] = $purchase_order_id;
				$data['type'] = 'success';
				$data['msg'] = 'Purchase order added successfully.';
				//return $data;               
            }
        }else{
			//echo 'block 3'; die;
			$insertArray = array(
				'po_number' => $this->input->post('po_number'),
				'am_id' => $this->input->post('acc_master_id'),
				'mill_id' => $this->input->post('mill_id'),
				'po_invoice_number' => $this->input->post('po_invoice_number'),
				'po_date' => $this->input->post('po_date'),
				'po_delivery_date' => $this->input->post('delivery_date'),
				'remarks' => $this->input->post('remarks'),
				'terms' => $this->input->post('terms'),
				'user_id' => $this->session->user_id
			);
			
			$this->db->insert('purchase_order', $insertArray);
			$purchase_order_id = $this->db->insert_id();
			
			$data['insert_id'] = $purchase_order_id;
			$data['type'] = 'success';
			$data['msg'] = 'Purchase order added successfully.';
			//return $data;
		}//end if (!empty($_FILES))
		
		if($number_of_copy > 0){
			$consignement_number = $this->input->post('consignement_number');
			for($i = 0; $i < $number_of_copy; $i++){
				$consignement_number1 = $consignement_number + $i;
				$insertArray = array(
					'po_id' => $purchase_order_id,					
					'consignement_number' => $consignement_number1,
					'user_id' => $this->session->user_id
				);
		
				$this->db->insert('purchase_order_details', $insertArray);
			}//end for
		}//end if($number_of_copy > 0)
		
		$data['insert_id'] = $purchase_order_id;
		$data['type'] = 'success';
		$data['msg'] = 'Purchase order added successfully.';
		return $data; 
    }

    public function edit_purchase_order($po_id) {
        $data['sizes'] = $this->db->select('sz_id, size')->get_where('sizes', array('sizes.status' => 1))->result();
        
		$data['colours'] = $this->db->select('c_id, color, c_code')->get_where('colors', array('colors.status' => 1))->result();
		
		$data['units'] = $this->db->select('u_id, unit')->get_where('units', array('units.status' => 1))->result();
		
// 		$data['consignement_number'] = date('dmhis');
		
		$cn = ($this->db->query("SELECT max(consignement_number) as cn FROM purchase_order_details")->result()[0]->cn + 1); 
		
		$data['consignement_number'] = $cn;
		
        $data['purchase_order_details'] = $this->db
                ->select('purchase_order.*, acc_master.am_id, acc_master.name, acc_master.short_name')
                ->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left')
                ->get_where('purchase_order', array('purchase_order.po_id' => $po_id))
                ->result();
                
		$data['mill_details'] = $this->db->get_where('mill', array('mill.status' => 1))->result();
				
        return array('page'=>'purchase_order/purchase_order_edit_v', 'data'=>$data);
    }

    public function form_edit_purchase_order(){
        $updateArray = array(
            'po_number' => $this->input->post('po_number'),
            'po_date' => $this->input->post('po_date'),
            'mill_id' => $this->input->post('mill_id'),
            'po_delivery_date' => $this->input->post('delivery_date'),
            'po_invoice_number' => $this->input->post('po_invoice_number'),
            'remarks' => $this->input->post('remarks'),
            'terms' => $this->input->post('terms'),
            'status' => $this->input->post('status'),
            'user_id' => $this->session->user_id
        );
        $this->db->update('purchase_order', $updateArray, array('po_id' => $this->input->post('purchase_order_id')));

        $data['type'] = 'success';
        $data['msg'] = 'Purchase order updated successfully.';
        return $data;

    }

    public function ajax_purchase_order_details_table_data() {
        $purchase_order_id = $this->input->post('purchase_order_id');
		
        //actual db table column names
        $column_orderable = array(
            0 => 'purchase_order_details.pod_id',
			1 => 'purchase_order_details.roll_handel',
			2 => 'purchase_order_details.size_in_text',
            3 => 'purchase_order_details.paper_gsm',
			4 => 'purchase_order_details.paper_bf',
			5 => 'purchase_order_details.roll_weight',
			6 => 'purchase_order_details.rate_per_unit',
			7 => 'purchase_order_details.pod_total'
        );
		
        // Set searchable column fields
        $column_search = array('purchase_order_details.pod_id', 'purchase_order_details.roll_handel', 'purchase_order_details.paper_gsm', 'purchase_order_details.size_in_text', 'purchase_order_details.paper_bf', 'purchase_order_details.roll_weight', 'purchase_order_details.rate_per_unit','purchase_order_details.pod_total');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('purchase_order_details.pod_id, purchase_order_details.po_id, purchase_order_details.roll_handel, purchase_order_details.roll_weight, purchase_order_details.c_id, purchase_order_details.sz_id, purchase_order_details.size_in_text, purchase_order_details.pod_quantity, purchase_order_details.u_id, purchase_order_details.rate_per_unit, purchase_order_details.pod_total, purchase_order_details.pod_remarks, purchase_order_details.consignement_number, purchase_order_details.paper_gsm, purchase_order_details.paper_bf, purchase_order_details.total_no_of_reel, colors.color, colors.c_code, sizes.size, units.unit');
		$this->db->join('colors', 'colors.c_id = purchase_order_details.c_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = purchase_order_details.sz_id', 'left');
		$this->db->join('units', 'units.u_id = purchase_order_details.u_id', 'left');
		
		$rs = $this->db->get_where('purchase_order_details', array('purchase_order_details.po_id' => $purchase_order_id))->result();
			
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('purchase_order_details.pod_id, purchase_order_details.po_id, purchase_order_details.roll_handel, purchase_order_details.roll_weight, purchase_order_details.c_id, purchase_order_details.sz_id, purchase_order_details.size_in_text, purchase_order_details.pod_quantity, purchase_order_details.u_id, purchase_order_details.rate_per_unit, purchase_order_details.pod_total, purchase_order_details.pod_remarks, purchase_order_details.consignement_number, purchase_order_details.paper_gsm, purchase_order_details.paper_bf, purchase_order_details.total_no_of_reel, colors.color, colors.c_code, sizes.size, units.unit');
            $this->db->join('colors', 'colors.c_id = purchase_order_details.c_id', 'left');
            $this->db->join('sizes', 'sizes.sz_id = purchase_order_details.sz_id', 'left');
            $this->db->join('units', 'units.u_id = purchase_order_details.u_id', 'left');
            
			$rs = $this->db->get_where('purchase_order_details', array('purchase_order_details.po_id' => $purchase_order_id))->result();
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

            $this->db->select('purchase_order_details.pod_id, purchase_order_details.po_id, purchase_order_details.roll_handel, purchase_order_details.roll_weight, purchase_order_details.c_id, purchase_order_details.sz_id, purchase_order_details.size_in_text, purchase_order_details.pod_quantity, purchase_order_details.u_id, purchase_order_details.rate_per_unit, purchase_order_details.pod_total, purchase_order_details.pod_remarks, purchase_order_details.consignement_number, purchase_order_details.paper_gsm, purchase_order_details.paper_bf, purchase_order_details.total_no_of_reel, colors.color, colors.c_code, sizes.size, units.unit');
            $this->db->join('colors', 'colors.c_id = purchase_order_details.c_id', 'left');
            $this->db->join('sizes', 'sizes.sz_id = purchase_order_details.sz_id', 'left');
            $this->db->join('units', 'units.u_id = purchase_order_details.u_id', 'left');
            
			$rs = $this->db->get_where('purchase_order_details', array('purchase_order_details.po_id' => $purchase_order_id))->result();
        

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('purchase_order_details.pod_id, purchase_order_details.po_id, purchase_order_details.roll_handel, purchase_order_details.roll_weight, purchase_order_details.c_id, purchase_order_details.sz_id, purchase_order_details.size_in_text, purchase_order_details.pod_quantity, purchase_order_details.u_id, purchase_order_details.rate_per_unit, purchase_order_details.pod_total, purchase_order_details.pod_remarks, purchase_order_details.consignement_number, purchase_order_details.paper_gsm, purchase_order_details.paper_bf, purchase_order_details.total_no_of_reel, colors.color, colors.c_code, sizes.size, units.unit');
            $this->db->join('colors', 'colors.c_id = purchase_order_details.c_id', 'left');
            $this->db->join('sizes', 'sizes.sz_id = purchase_order_details.sz_id', 'left');
            $this->db->join('units', 'units.u_id = purchase_order_details.u_id', 'left');
            
			$rs = $this->db->get_where('purchase_order_details', array('purchase_order_details.po_id' => $purchase_order_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
         
		$i = 0;
        foreach ($rs as $val) {
			$i++;			
			
			$roll_handel_txt = '';
			if($val->roll_handel == '0'){
				$roll_handel_txt = 'Roll';
			}
			if($val->roll_handel == '1'){
				$roll_handel_txt = 'Handel';
			}
			
			if($val->c_id > 0){
				$color = $val->color . ' ['. $val->c_code .']';
			}else{
				$color = '';
			}
			
			$nestedData['sl_no'] = $i;
            $nestedData['item_description'] = $roll_handel_txt;
			$nestedData['gsm'] = $val->paper_gsm;
            $nestedData['size'] = $val->size_in_text;
            $nestedData['total_no_of_reel'] = $val->total_no_of_reel;
            $nestedData['bf'] = $val->paper_bf;
            $nestedData['consignement_number'] = $val->consignement_number;
            
            $nestedData['action'] = '<a href="javascript:void(0)" pod_id="'.$val->pod_id.'" class="purchase_details_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a data-tab="purchase_order_details" data-pk="'.$val->pod_id.'" href="javascript:void(0)" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            
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

    public function ajax_all_items_on_item_group(){
        $item_group = $this->input->post('item_group');
        $this->db->select('item_dtl.*, item_master.item as item_name, item_groups.group_name, item_groups.value, units.unit');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left');
        $this->db->join('units', 'units.u_id = item_groups.u_id', 'left');
        $this->db->group_by('item_master.item');
        return $this->db->get_where('item_dtl', array('item_dtl.status'=>'1', 'item_master.ig_id' => $item_group))->result_array();
    }
    
    public function ajax_all_colors_on_item_master(){
        $item_id = $this->input->post('item_id');
        $this->db->select('item_dtl.id_id as item_dtl_id, colors.*');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        return $this->db->get_where('item_dtl', array('item_dtl.status'=>'1', 'item_dtl.im_id' => $item_id, 'color <>' => null))->result_array();
    }

    public function form_add_purchase_order_details(){
        $pod_total = ($this->input->post('roll_weight') * $this->input->post('rate_per_unit'));
		$number_of_copy = $this->input->post('number_of_copy');
		
		//if($number_of_copy > 2){$number_of_copy = $number_of_copy - 1;}
		
		if($number_of_copy > 0){
			$consignement_number = $this->input->post('consignement_number');
			for($i = 0; $i < $number_of_copy; $i++){
				$consignement_number1 = $consignement_number + $i;
				$insertArray = array(
					'po_id' => $this->input->post('purchase_order_id'),
					'roll_handel' => $this->input->post('roll_handel'),
					'roll_weight' => $this->input->post('roll_weight'),
					'c_id' => $this->input->post('c_id'),
					'size_in_text' => $this->input->post('size_in_text'),
					'pod_quantity' => $this->input->post('pod_quantity'),
					'u_id' => $this->input->post('u_id'),
					'rate_per_unit' => $this->input->post('rate_per_unit'),
					'pod_total' => $pod_total,
					'pod_remarks' => $this->input->post('pod_remarks'),
					'consignement_number' => $consignement_number1,
					'paper_gsm' => $this->input->post('paper_gsm_add'),
					'paper_bf' => $this->input->post('paper_bf_add'),
					'total_no_of_reel' => $number_of_copy,
					'user_id' => $this->session->user_id
				);
		
				$this->db->insert('purchase_order_details', $insertArray);
			}//end for

		}else{
			$insertArray = array(
				'po_id' => $this->input->post('purchase_order_id'),
				'roll_handel' => $this->input->post('roll_handel'),
				'roll_weight' => $this->input->post('roll_weight'),
				'c_id' => $this->input->post('c_id'),
				'size_in_text' => $this->input->post('size_in_text'),
				'pod_quantity' => $this->input->post('pod_quantity'),
				'u_id' => $this->input->post('u_id'),
				'rate_per_unit' => $this->input->post('rate_per_unit'),
				'pod_total' => $pod_total,
				'pod_remarks' => $this->input->post('pod_remarks'),
				'consignement_number' => $this->input->post('consignement_number'),
				'paper_gsm' => $this->input->post('paper_gsm_add'),
				'paper_bf' => $this->input->post('paper_bf_add'),
				'user_id' => $this->session->user_id
			);
	
			$this->db->insert('purchase_order_details', $insertArray);
		}//end 
		
        //echo $this->db->last_query();die;
		if($this->db->insert_id() > 0){
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'purchase order details added successfully.';
			$data['consignement_number'] = date('dmhis');
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Data Insert Problem';
		}

        return $data;
    }

    public function ajax_fetch_purchase_order_details_on_pk(){
        $pod_id = $this->input->post('pod_id');
		$this->db->select('purchase_order_details.pod_id, purchase_order_details.po_id, purchase_order_details.roll_handel, purchase_order_details.roll_weight, purchase_order_details.c_id, purchase_order_details.sz_id, purchase_order_details.size_in_text, purchase_order_details.size_in_text, purchase_order_details.pod_quantity, purchase_order_details.u_id, purchase_order_details.rate_per_unit, purchase_order_details.pod_total, purchase_order_details.pod_remarks, purchase_order_details.consignement_number, purchase_order_details.paper_gsm, purchase_order_details.paper_bf, colors.color, colors.c_code, sizes.size, units.unit');
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
        
        /*$data['purchase_order_details'] = $this->db
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
                ->result();*/
				
		$data['purchase_order_details'] = $this->db
		->select('purchase_order.*, purchase_order_details.*')
		->join('purchase_order', 'purchase_order.po_id = purchase_order_details.po_id', 'left')
        ->order_by('purchase_order_details.pod_id')
		->get_where('purchase_order_details', array('purchase_order_details.po_id' => $po_id))
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


    public function form_edit_purchase_order_details(){
        $pod_total = ($this->input->post('pod_quantity') * $this->input->post('rate_per_unit'));
		
        $updateArray = array(
            'roll_handel' => $this->input->post('roll_handel'),
			'roll_weight' => $this->input->post('roll_weight'),
			'c_id' => $this->input->post('c_id_edit'),
			'size_in_text' => $this->input->post('size_in_text_edit'),
			'pod_quantity' => $this->input->post('pod_quantity'),
			'u_id' => $this->input->post('u_id_edit'),
			'rate_per_unit' => $this->input->post('rate_per_unit'),
			'pod_total' => $pod_total,
            'pod_remarks' => $this->input->post('pod_remarks_edit'),
            'consignement_number' => $this->input->post('consignement_number'),
            'paper_gsm' => $this->input->post('paper_gsm_edit'),
            'paper_bf' => $this->input->post('paper_bf_edit'),
            'user_id' => $this->session->user_id
        );
        $pod_id = $this->input->post('pod_id');
        $this->db->update('purchase_order_details', $updateArray, array('pod_id' => $pod_id));

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

    public function ajax_del_row_on_table_and_pk_purchase_order(){
		$pk_name = $this->input->post('pk_name');
		$pk_value = $this->input->post('pk_value');
		
		$ref_table = $this->input->post('ref_table');
		
		$this->db->where($pk_name, $pk_value)->delete($ref_table);
		
		$tab = $this->input->post('tab');
		if($tab == 'purchase_order'){
			$this->db->where($pk_name, $pk_value)->delete($tab);
		}
		
		$data['title'] = 'Deleted!';
		$data['type'] = 'success';
		$data['msg'] = 'Purchase Order Successfully Deleted';
		
        return $data;
    }
	
	public function del_row_on_table_pk_purchase_order_details(){
		$pk = $this->input->post('pk');
		$tab = $this->input->post('tab');
		
		$this->db->where('pod_id', $pk)->delete($tab);
		$data['title'] = 'Deleted!';
		$data['type'] = 'success';
		$data['msg'] = 'Item of Purchase Order Successfully Deleted';
		
        return $data;
    }

    // purchase ORDER ENDS 

}