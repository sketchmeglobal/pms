<?php

class Customer_order_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function customer_order() {
        $data = '';
        return array('page'=>'customer_order/customer_order_list_v', 'data'=>$data);
    }

    public function ajax_customer_order_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'co_id'
        );
        // Set searchable column fields
        $column_search = array('co_no','buyer_reference_no','name');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get('customer_order')->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('customer_order.co_id, co_no, buyer_reference_no, name as customer_name, DATE_FORMAT(co_delivery_date, "%d-%m-%Y") as co_delivery_date, DATE_FORMAT(co_date, "%d-%m-%Y") as co_date, co_total_amount, co_total_quantity, customer_order.status, customer_invoice.cus_inv_id');
            $this->db->join('acc_master', 'acc_master.am_id = customer_order.acc_master_id', 'left');
            $this->db->join('customer_invoice', 'customer_invoice.co_id = customer_order.co_id', 'left');
            $rs = $this->db->get_where('customer_order', array('customer_order.status => 1'))->result();
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

            $this->db->select('customer_order.co_id, co_no, buyer_reference_no, name as customer_name, DATE_FORMAT(co_delivery_date, "%d-%m-%Y") as co_delivery_date, DATE_FORMAT(co_date, "%d-%m-%Y") as co_date, co_total_amount, co_total_quantity, customer_order.status, customer_invoice.cus_inv_id');
            $this->db->join('acc_master', 'acc_master.am_id = customer_order.acc_master_id', 'left');
            $this->db->join('customer_invoice', 'customer_invoice.co_id = customer_order.co_id', 'left');
            $rs = $this->db->get_where('customer_order', array('customer_order.status => 1'))->result();
            // echo $this->db->get_compiled_select('customer_order');
            // exit();
        

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('customer_order.co_id, co_no, buyer_reference_no, name as customer_name, DATE_FORMAT(co_delivery_date, "%d-%m-%Y") as co_delivery_date, DATE_FORMAT(co_date, "%d-%m-%Y") as co_date, co_total_amount, co_total_quantity, customer_order.status, customer_invoice.cus_inv_id');
            $this->db->join('acc_master', 'acc_master.am_id = customer_order.acc_master_id', 'left');
            $this->db->join('customer_invoice', 'customer_invoice.co_id = customer_order.co_id', 'left');
            $rs = $this->db->get_where('customer_order', array('customer_order.status => 1'))->result();

            $this->db->flush_cache();
        }

        $data = array();

        // echo '<pre>', print_r($rs), '</pre>'; die;
        // echo $this->db->last_query();die;

        foreach ($rs as $val) {

            // if($val->img){$img='<img src="'.base_url('assets/admin_panel/img/article_img/'.$val->img).'" width="50">';} else{$img='';}
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['co_no'] = $val->co_no;
            $nestedData['buyer_reference_no'] = $val->buyer_reference_no;
            $nestedData['customer_name'] = $val->customer_name;
            $nestedData['co_date'] = $val->co_date;
            $nestedData['co_delivery_date'] = $val->co_delivery_date;
            $nestedData['co_total_amount'] = $val->co_total_amount;
            $nestedData['co_total_quantity'] = $val->co_total_quantity;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
            <a href="'. base_url('admin/edit-customer-order/'.$val->co_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a data-co_id="'.$val->co_id.'" data-inv_id="'.$val->cus_inv_id.'" href="javascript:void(0)" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
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
    
// ADD CUSTOMER ORDER 


    public function add_customer_order() {
        $data['buyer_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', array('acc_master.status' => 1, 'acc_master.supplier_buyer' => 1))->result(); // Sundry Debtors
		
		$data['order_no'] = 'CO/'. date('dmY/his');

        return array('page'=>'customer_order/customer_order_add_v', 'data'=>$data);
    }

    public function ajax_fetch_article_colours($am_id, $lc_id){
        
        if($lc_id == 'false'){
            return $this->db
                ->select('c1.color as leather_color, c1.c_code as leather_code, c1.c_id as leather_id, 
                    c2.color as fitting_color, c2.c_code as fitting_code, c2.c_id as fitting_id')
                ->join('colors c1', 'c1.c_id = article_dtl.lth_color_id', 'left')
                ->join('colors c2', 'c2.c_id = article_dtl.fit_color_id', 'left')
                ->get_where('article_dtl', array('article_dtl.am_id' => $am_id))
                ->result();
        }else{
            return $this->db
                ->select('c2.color as fitting_color, c2.c_code as fitting_code, c2.c_id as fitting_id')
                ->join('colors c2', 'c2.c_id = article_dtl.fit_color_id', 'left')
                ->get_where('article_dtl', array('article_dtl.am_id' => $am_id, 'article_dtl.lth_color_id' => $lc_id))
                ->result();
        }
        
    }
    
    public function ajax_fetch_article_rate_on_type($am, $ptype){
        if($ptype == 'Ex-works Price'){
            $type = 'exworks_amt';
        }else if($ptype == 'C&F Price'){
            $type = 'cf_amt';
        }else if($ptype == 'CIF Price'){
            $type = 'cf_amt';
        }else{
            $type = 'fob_amt';
        } 
        $res = $this->db
            ->get_where('article_master', array('am_id' => $am))
            ->result();
            if(isset($res[0])){
                return $res[0]->$type;
            }else{
                return 0;
            }
    }

    public function ajax_unique_customer_order_number() {
        $order_no = $this->input->post('order_no');
        $rs = $this->db->get_where('customer_order', array('co_no' => $order_no))->num_rows();
        // echo $this->db->last_query();die;
        
        if($rs != '0') {
            $data = 'Order no. already exists.';
        }else{
            $data='true';
        }

        return $data;
    }

    public function form_add_customer_order(){

        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/customer_order/';
            $config['allowed_types'] = 'docx|doc|xlx|pdf|gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 3072;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
                $insertArray = array(
                    'co_no' => $this->input->post('order_no'),
                    'acc_master_id' => $this->input->post('acc_master_id'),
                    'buyer_reference_no' => $this->input->post('buyref'),
                    'co_date' => $this->input->post('order_date'),
                    //'co_reference_date' => $this->input->post('ref_date'),
                    'co_delivery_date' => $this->input->post('delv_date'),
                    //'co_price_type' => $this->input->post('rate_type'),
                    'img' => $uploaded_data['file_name'],
                    'co_remarks' => $this->input->post('remarks'),
                    'user_id' => $this->session->user_id
                );
                
            }
        }else{
            $insertArray = array(
                'co_no' => $this->input->post('order_no'),
                'acc_master_id' => $this->input->post('acc_master_id'),
                'buyer_reference_no' => $this->input->post('buyref'),
                'co_date' => $this->input->post('order_date'),
                //'co_reference_date' => $this->input->post('ref_date'),
                'co_delivery_date' => $this->input->post('delv_date'),
                //'co_price_type' => $this->input->post('rate_type'),
                'co_remarks' => $this->input->post('remarks'),
                'user_id' => $this->session->user_id
            );
        }

        // echo '<pre>', print_r($insertArray), '</pre>';die;

        $this->db->insert('customer_order', $insertArray);
		
		if($this->db->insert_id() > 0){
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'Customer order added successfully.';
		}else{
        	$data['type'] = 'error';
        	$data['msg'] = 'Data Insert Error';
		}
        return $data;
    }

    public function edit_customer_order($co_id) {
        $data['sizes'] = $this->db->select('sz_id, size, paper_gsm, paper_bf')->get_where('sizes', array('sizes.status' => 1))->result();
        
		$data['colours'] = $this->db->select('c_id, color, c_code')->get_where('colors', array('colors.status' => 1))->result();
		
		$data['units'] = $this->db->select('u_id, unit')->get_where('units', array('units.status' => 1))->result();
		
        $data['buyer_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', array('acc_master.status' => 1, 'acc_master.supplier_buyer' => 1))->result();
        $data['colors_details'] = $this->db->select('*')->get_where('colors', array('status' => 1))->result_array();
        $data['customer_order_details'] = $this->db
                ->select('customer_order.*, acc_master.name, acc_master.short_name')
                ->join('acc_master', 'acc_master.am_id = customer_order.acc_master_id', 'left')
                ->get_where('customer_order', array('customer_order.co_id' => $co_id))
                ->result();
        return array('page'=>'customer_order/customer_order_edit_v', 'data'=>$data);
    }

    public function ajax_customer_order_details_table_data() {
        $customer_order_id = $this->input->post('customer_order_id');
        //actual db table column names
        $column_orderable = array(
			0 => 'sizes.size',
			1 => 'colors.color',
			2 => 'customer_order_dtl.cus_order_quantity',
        );
        // Set searchable column fields
        $column_search = array('sizes.size','colors.color','customer_order_dtl.cus_order_quantity');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('customer_order_dtl.cod_id, customer_order_dtl.co_id,customer_order_dtl.sz_id,customer_order_dtl.cus_order_quantity,customer_order_dtl.c_id,customer_order_dtl.paper_gsm,customer_order_dtl.paper_bf, sizes.size, colors.color, colors.c_code, purchase_order_details.consignement_number, purchase_order.po_number');
		$this->db->join('sizes', 'sizes.sz_id = customer_order_dtl.sz_id', 'left');
		$this->db->join('colors', 'colors.c_id = customer_order_dtl.c_id', 'left');
        $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = customer_order_dtl.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_details.po_id', 'left');
		$rs = $this->db->get_where('customer_order_dtl', array('co_id' => $customer_order_id))->result();
		
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('customer_order_dtl.cod_id, customer_order_dtl.co_id,customer_order_dtl.sz_id,customer_order_dtl.cus_order_quantity,customer_order_dtl.c_id, customer_order_dtl.paper_gsm,customer_order_dtl.paper_bf,sizes.size, colors.color, colors.c_code, purchase_order_details.consignement_number, purchase_order.po_number');
            $this->db->join('sizes', 'sizes.sz_id = customer_order_dtl.sz_id', 'left');
            $this->db->join('colors', 'colors.c_id = customer_order_dtl.c_id', 'left');
            $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = customer_order_dtl.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_details.po_id', 'left');
            $rs = $this->db->get_where('customer_order_dtl', array('customer_order_dtl.co_id' => $customer_order_id))->result();
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

            $this->db->select('customer_order_dtl.cod_id, customer_order_dtl.co_id,customer_order_dtl.sz_id,customer_order_dtl.cus_order_quantity,customer_order_dtl.c_id,customer_order_dtl.paper_gsm,customer_order_dtl.paper_bf, sizes.size, colors.color, colors.c_code, purchase_order_details.consignement_number, purchase_order.po_number');
            $this->db->join('sizes', 'sizes.sz_id = customer_order_dtl.sz_id', 'left');
            $this->db->join('colors', 'colors.c_id = customer_order_dtl.c_id', 'left');
            $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = customer_order_dtl.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_details.po_id', 'left');
            $rs = $this->db->get_where('customer_order_dtl', array('customer_order_dtl.co_id' => $customer_order_id))->result();        

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('customer_order_dtl.cod_id, customer_order_dtl.co_id,customer_order_dtl.sz_id,customer_order_dtl.cus_order_quantity,customer_order_dtl.c_id,customer_order_dtl.paper_gsm,customer_order_dtl.paper_bf, sizes.size, colors.color, colors.c_code, purchase_order_details.consignement_number, purchase_order.po_number');
            $this->db->join('sizes', 'sizes.sz_id = customer_order_dtl.sz_id', 'left');
            $this->db->join('colors', 'colors.c_id = customer_order_dtl.c_id', 'left');
            $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = customer_order_dtl.pod_id', 'left');
        $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_details.po_id', 'left');
            $rs = $this->db->get_where('customer_order_dtl', array('customer_order_dtl.co_id' => $customer_order_id))->result();

            $this->db->flush_cache();
        }

        $data = array();

        foreach ($rs as $val) {
			$size_gsm_bf = $val->size.' [GSM: '.$val->paper_gsm.'] [BF: '.$val->paper_bf.']';
            $nestedData['size'] = $size_gsm_bf;
            $nestedData['po_number'] = $val->po_number;
            $nestedData['consignement_number'] = $val->consignement_number;
            $nestedData['roll_quantity'] = $val->cus_order_quantity;
            $nestedData['colour'] = $val->color.'['.$val->c_code.']';
            
            $nestedData['action'] = '
             <a href="javascript:void(0)" cod_id="'.$val->cod_id.'" class="customer_details_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a data-tab="customer_order_details" data-pk="'.$val->cod_id.'" href="javascript:void(0)" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            
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


    public function form_add_customer_order_details(){

        $pod_id = $this->input->post('pod_id');

        for ($i = 0; $i < sizeof($pod_id); $i++) {
        
        $insertArray = array(
            'co_id' => $this->input->post('order_id'),
            'sz_id' => $this->input->post('sz_id_add'),
            'po_id' => $this->input->post('po_id')[$i],
            'pod_id' => $this->input->post('pod_id')[$i],
            'cus_order_quantity' => $this->input->post('issue_quantity')[$i],
            'c_id' => $this->input->post('c_id_add'),
            'paper_gsm' => $this->input->post('paper_gsm_add'),
            'paper_bf' => $this->input->post('paper_bf_add'),
            'user_id' => $this->session->user_id
        );

        // echo '<pre>', print_r($insertArray), '</pre>';

        $this->db->insert('customer_order_dtl', $insertArray);
    }
        
        // echo $this->db->last_query();die;
		 
        $data['insert_id'] = $this->db->insert_id();

        if ($data['insert_id'] > 0) {
        $data['type'] = 'success';
        $data['msg'] = 'Customer order details added successfully.';
        } else {
            $data['type'] = 'error';
            $data['msg'] = 'Data Insert Error';
        }
        // echo '<pre>', print_r($data), '</pre>';die;
        return $data;

    }
	
    public function ajax_fetch_customer_order_details_on_pk(){
        $cod_id = $this->input->post('cod_id');
        
		$this->db->select('customer_order_dtl.cod_id, customer_order_dtl.co_id,customer_order_dtl.sz_id,customer_order_dtl.cus_order_quantity,customer_order_dtl.c_id,customer_order_dtl.paper_gsm,customer_order_dtl.paper_bf, sizes.size, colors.color, colors.c_code, sizes.size');
		$this->db->join('sizes', 'sizes.sz_id = customer_order_dtl.sz_id', 'left');
		$this->db->join('colors', 'colors.c_id = customer_order_dtl.c_id', 'left');
		return $rs = $this->db->get_where('customer_order_dtl', array('customer_order_dtl.cod_id' => $cod_id))->result();
		
    }

    public function form_edit_customer_order_details(){
        
        $insertArray = array(
            'cus_order_quantity' => $this->input->post('cus_order_quantity_edit'),
            'c_id' => $this->input->post('c_id_edit'),
            'paper_gsm' => $this->input->post('paper_gsm_edit'),
            'paper_bf' => $this->input->post('paper_bf_edit'),
            'user_id' => $this->session->user_id
        );
        $cod_id = $this->input->post('order_details_id');
        
        // echo '<pre>', print_r($insertArray), '</pre>';die;
        $this->db->update('customer_order_dtl', $insertArray, array('cod_id' => $cod_id));
        // echo $this->db->last_query();die;

        /*$data['total_qnty'] = $this->db->select_sum('co_quantity')->get_where('customer_order_dtl', array('co_id' => $this->input->post('order_id')))->result()[0]->co_quantity;
        $data['total_amount'] = $this->db->select_sum('co_price')->get_where('customer_order_dtl', array('co_id' => $this->input->post('order_id')))->result()[0]->co_price;
        
        // update customer order table 
        $updateArray= array(
            'co_total_amount' => $data['total_amount'],
            'co_total_quantity' => $data['total_qnty']
        );
        $this->db->update('customer_order', $updateArray, array('co_id', $this->input->post('order_id')));*/

        $data['type'] = 'success';
        $data['msg'] = 'Customer order details updated successfully.';
        return $data;
    }

    public function ajax_unique_customer_order_no(){
        $customer_order_id = $this->input->post('customer_order_id');
        $order_no = $this->input->post('order_no');

        $rs = $this->db->get_where('customer_order', array('co_no' => $order_no, 'co_id <>' => $customer_order_id))->num_rows();
        if($rs != '0') {
            $data = 'Order no. already exists.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function form_edit_customer_order(){
        
        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/customer_order/';
            $config['allowed_types'] = 'docx|doc|xlx|pdf|gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 3072;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
            echo 'kkk' . $this->input->post('order_no'); die;
                $uploaded_data = $this->upload->data();
                $updateArray = array(
                    'co_no' => $this->input->post('order_no'),
                    'acc_master_id' => $this->input->post('acc_master_id'),
                    'buyer_reference_no' => $this->input->post('buyref'),
                    'co_date' => date('Y-m-d', strtotime($this->input->post('order_date'))),
                    //'co_reference_date' => date('Y-m-d', strtotime($this->input->post('ref_date'))),
                    'co_delivery_date' => date('Y-m-d', strtotime($this->input->post('delv_date'))),
                    //'co_price_type' => $this->input->post('rate_type'),
                    'co_remarks' => $this->input->post('remarks'),
                    'img' => $uploaded_data['file_name'],
                    'status' => $this->input->post('status'),
                    'user_id' => $this->session->user_id
                );
                
            }
        }else{
            $updateArray = array(
                'co_no' => $this->input->post('order_no'),
                'acc_master_id' => $this->input->post('acc_master_id'),
                'buyer_reference_no' => $this->input->post('buyref'),
                'co_date' => date('Y-m-d', strtotime($this->input->post('order_date'))),
                //'co_reference_date' => date('Y-m-d', strtotime($this->input->post('ref_date'))),
                'co_delivery_date' => date('Y-m-d', strtotime($this->input->post('delv_date'))),
                //'co_price_type' => $this->input->post('rate_type'),
                'co_remarks' => $this->input->post('remarks'),
                'status' => $this->input->post('status'),
                'user_id' => $this->session->user_id
            );
        }
        
        
        
        $this->db->update('customer_order', $updateArray, array('co_id' => $this->input->post('order_id')));

        $data['type'] = 'success';
        $data['msg'] = 'Customer order updated successfully.';
        return $data;

    }

    // DELETE CUSTOMER ORDER

    public function ajax_delete_customer_order(){

        $co_id = $this->input->post('co_id');
        $inv_id = $this->input->post('inv_id');
        
        $this->db->where('invoice_id', $inv_id)->delete('customer_payment');
        $this->db->where('cus_inv_id', $inv_id)->delete('customer_invoice_detail');
        $this->db->where('cus_inv_id', $inv_id)->delete('customer_invoice');
        $this->db->where('co_id', $co_id)->delete('customer_order_dtl');
        $this->db->where('co_id', $co_id)->delete('customer_order');

        $data['title'] = 'Deleted!';
        $data['type'] = 'success';
        $data['msg'] = 'Customer Order, Invoices and Payemnts Successfully Deleted';
        return $data;
    }

    public function ajax_unique_co_no_and_art_no_and_lth_color(){
        $customer_order_id = $this->input->post('customer_order_id');
        $lc_id = $this->input->post('lc_id');
        $am_id = $this->input->post('am_id');
        $customer_order_detail_id = $this->input->post('customer_order_detail_id');

        $rs = $this->db->get_where('customer_order_dtl', array('lc_id' => $lc_id,'am_id' => $am_id, 'co_id' => $customer_order_id, 'cod_id <>' => $customer_order_detail_id))->num_rows();
        if($rs != '0') {
            $data = 'Leather colour exists for this article.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function print_customer_order_consumption($co_id){
        $query = "SELECT
                customer_order.co_no,
                customer_order.co_date,
                customer_order.buyer_reference_no,
                customer_order.co_reference_date,
                acc_master.name,
                acc_master.short_name,
                item_master.item AS item_name,
                item_master.im_code AS item_code,
                item_groups.ig_code,
                item_groups.group_name,
                units.unit,
                colors.color,
                customer_order_dtl.cod_id,
                customer_order_dtl.co_id,
                customer_order_dtl.am_id,
                article_costing.ac_id AS costing_id,
                article_costing_details.id_id AS item_dtl,
                article_costing_details.quantity AS item_dtl_quantity,
                co_quantity,
                (
                    article_costing_details.quantity * co_quantity
                ) AS temp_qnty,
                SUM(
                    article_costing_details.quantity * co_quantity
                ) AS final_qnty
            FROM
                `customer_order`
            LEFT JOIN customer_order_dtl ON customer_order.co_id = customer_order_dtl.co_id
            LEFT JOIN article_costing ON article_costing.am_id = customer_order_dtl.am_id
            LEFT JOIN article_costing_details ON article_costing.ac_id = article_costing_details.ac_id
            LEFT JOIN acc_master ON acc_master.am_id = customer_order.acc_master_id
            LEFT JOIN item_dtl ON item_dtl.id_id = article_costing_details.id_id
            LEFT JOIN colors ON customer_order_dtl.lc_id = colors.c_id
            LEFT JOIN item_master ON item_master.im_id = item_dtl.im_id
            LEFT JOIN item_groups ON item_master.ig_id = item_groups.ig_id
            LEFT JOIN units ON item_groups.u_id = units.u_id
            WHERE
                customer_order.`co_id` = $co_id AND customer_order.status = 1
            GROUP BY
                item_dtl.im_id, customer_order_dtl.lc_id";
        return $this->db->query($query)->result();
    }
    
        public function full_order_history($co_id){
        $query = "SELECT
            customer_order.co_no,
            DATE_FORMAT(customer_order.co_date, '%d-%m-%Y') as co_date,
            customer_order_dtl.co_quantity,
            colors.color,
            article_master.art_no,
            article_master.alt_art_no
            
        FROM
            `customer_order`
        LEFT JOIN customer_order_dtl ON customer_order.co_id = customer_order_dtl.co_id
        LEFT JOIN colors ON customer_order_dtl.lc_id = colors.c_id
        LEFT JOIN article_master ON article_master.am_id = customer_order_dtl.am_id
        
        WHERE
            customer_order.`co_id` = $co_id AND customer_order.status = 1";
        return $this->db->query($query)->result();
    }

    public function ajax_get_consume_list_purchase_order_receive_detail() {
        $data = array();
        $preview_data = array();
        $sz_id_add = $this->input->post('sz_id_add');
        $quantity = $this->input->post('quantity'); //10


        $result1 = $this->db->select('purchase_order_details.pod_id, purchase_order.po_id, purchase_order.po_number, purchase_order_details.consignement_number, production_detail.sz_id, SUM(checkin_detail.received_quantity) as received_quantity')
                        ->join('purchase_order_details', 'purchase_order_details.pod_id = production_detail.pod_id', 'left')
                        ->join('checkin_detail', 'checkin_detail.pod_id = purchase_order_details.pod_id', 'left')
                        ->join('purchase_order', 'purchase_order.po_id = purchase_order_details.po_id', 'left')
                        ->group_by('checkin_detail.pod_id')
                        ->get_where('production_detail', array('production_detail.sz_id' => $sz_id_add))->result();
            
            // echo count($result1); die();
        
         if (count($result1) > 0) {

            $data_array = array();

            $ordered = 0;

            foreach ($result1 as $r) {

                $result1_ordered = $this->db->select('SUM(cus_order_quantity) as cus_order_quantity')
                        ->group_by('customer_order_dtl.sz_id, customer_order_dtl.pod_id')
                        ->get_where('customer_order_dtl', array('customer_order_dtl.sz_id' => $r->sz_id, 'customer_order_dtl.pod_id' => $r->pod_id))->row();

                        if(count($result1_ordered) > 0) {
                            $ordered = $result1_ordered->cus_order_quantity; 
                        } else {
                            $ordered = 0;
                        }

                $arr = array(
                    'pod_id' => $r->pod_id,
                    'po_id' => $r->po_id,
                    'po_number' => $r->po_number,
                    'consignement_number' => $r->consignement_number,
                    'received_quantity' => ($r->received_quantity - $ordered),
                );
                array_push($data_array, $arr);
            }

    // echo '<pre>', print_r($data_array), '</pre>'; die;


            


            $a = $quantity;

            for ($i = 0; $i < count($data_array); $i++) {
                $preview = new stdClass();

                if ($a > 0) {

                    $b = ($data_array[$i]['received_quantity']); //100
                    $total_rate = 0;

                    if ($b <= $a) {
                        $r = $a - $b;
                        $preview->consumed = $b;
                    } else {
                        if ($i == 0) {
                            $preview->consumed = $a;
                        } else {
                            $preview->consumed = $r;
                        }
                        $r = 0;
                    }
                } else {
                    break;
                }

                $preview->pod_id = $data_array[$i]['pod_id'];
                $preview->po_id = $data_array[$i]['po_id'];
                $preview->po_number = $data_array[$i]['po_number'];
                $preview->consignement_number = $data_array[$i]['consignement_number'];

                array_push($preview_data, $preview);

                //echo ' Required = '.$a.' Consumed = '.$b.' Remaining = '.$r;
                //echo "<br/>";
                $a = $r;
            }
        }

        $data["preview_data"] = $preview_data;

        // echo '<pre>', print_r($data["preview_data"]), '</pre>'; die;

        if (sizeof($preview_data) > 0) {
            $data["status"] = true;
        } else {
            $data["status"] = false;
        }

        return $data;
    }

// CUSTOMER ORDER ENDS 

}