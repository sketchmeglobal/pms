<?php

class Customer_invoice_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function customer_invoice() {
        $data = '';
        return array('page'=>'customer_invoice/customer_invoice_list_v', 'data'=>$data);
    }

    public function ajax_customer_invoice_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'customer_invoice.cus_inv_number',
            1 => 'customer_invoice.invoice_create_date',
            2 => 'customer_invoice.cus_inv_e_way_bill_no',
            4 => 'acc_master.name',
			5 => 'transporter.transporter_name',
        );
		
        // Set searchable column fields
        $column_search = array('customer_invoice.cus_inv_number','customer_invoice.invoice_create_date','customer_invoice.cus_inv_e_way_bill_no','acc_master.name','transporter.transporter_name');
		
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('customer_invoice.cus_inv_id, customer_invoice.co_id, customer_invoice.cus_inv_number, customer_invoice.cus_inv_e_way_bill_no, customer_invoice.am_id, customer_invoice.transporter_id, customer_invoice.cus_inv_number_of_cartons, customer_invoice.cus_inv_total_weight, DATE_FORMAT(customer_invoice.invoice_create_date, "%d-%m-%Y") as invoice_create_date, customer_invoice.remarks, customer_invoice.terms, customer_invoice.status, acc_master.name, transporter.transporter_name');
		$this->db->join('acc_master', 'acc_master.am_id = customer_invoice.am_id', 'left');
		$this->db->join('transporter', 'transporter.transporter_id = customer_invoice.transporter_id', 'left');
		$rs = $this->db->get_where('customer_invoice', array('customer_invoice.status => 1'))->result();
		
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('customer_invoice.cus_inv_id, customer_invoice.co_id, customer_invoice.cus_inv_number, customer_invoice.cus_inv_e_way_bill_no, customer_invoice.am_id, customer_invoice.transporter_id, customer_invoice.cus_inv_number_of_cartons, customer_invoice.cus_inv_total_weight, DATE_FORMAT(customer_invoice.invoice_create_date, "%d-%m-%Y") as invoice_create_date, customer_invoice.remarks, customer_invoice.terms, customer_invoice.status, acc_master.name, transporter.transporter_name');
            $this->db->join('acc_master', 'acc_master.am_id = customer_invoice.am_id', 'left');
			$this->db->join('transporter', 'transporter.transporter_id = customer_invoice.transporter_id', 'left');
            $rs = $this->db->get_where('customer_invoice', array('customer_invoice.status => 1'))->result();
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

            $this->db->select('customer_invoice.cus_inv_id, customer_invoice.co_id, customer_invoice.cus_inv_number, customer_invoice.cus_inv_e_way_bill_no, customer_invoice.am_id, customer_invoice.transporter_id, customer_invoice.cus_inv_number_of_cartons, customer_invoice.cus_inv_total_weight, DATE_FORMAT(customer_invoice.invoice_create_date, "%d-%m-%Y") as invoice_create_date, customer_invoice.remarks, customer_invoice.terms, customer_invoice.status, acc_master.name, transporter.transporter_name');
            $this->db->join('acc_master', 'acc_master.am_id = customer_invoice.am_id', 'left');
			$this->db->join('transporter', 'transporter.transporter_id = customer_invoice.transporter_id', 'left');
            $rs = $this->db->get_where('customer_invoice', array('customer_invoice.status => 1'))->result();
			
            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('customer_invoice.cus_inv_id, customer_invoice.co_id, customer_invoice.cus_inv_number, customer_invoice.cus_inv_e_way_bill_no, customer_invoice.am_id, customer_invoice.transporter_id, customer_invoice.cus_inv_number_of_cartons, customer_invoice.cus_inv_total_weight, DATE_FORMAT(customer_invoice.invoice_create_date, "%d-%m-%Y") as invoice_create_date, customer_invoice.remarks, customer_invoice.terms, customer_invoice.status, acc_master.name, transporter.transporter_name');
            $this->db->join('acc_master', 'acc_master.am_id = customer_invoice.am_id', 'left');
			$this->db->join('transporter', 'transporter.transporter_id = customer_invoice.transporter_id', 'left');
            $rs = $this->db->get_where('customer_invoice', array('customer_invoice.status => 1'))->result();
			
            $this->db->flush_cache();
        }

        $data = array();

        // echo '<pre>', print_r($rs), '</pre>'; die;
        // echo $this->db->last_query();die;

        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['cus_inv_number'] = $val->cus_inv_number;
            $nestedData['invoice_create_date'] = $val->invoice_create_date;
            $nestedData['cus_inv_e_way_bill_no'] = $val->cus_inv_e_way_bill_no;
            $nestedData['am_id'] = $val->name;
            $nestedData['transporter_name'] = $val->transporter_name;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
            <a href="'. base_url('admin/edit-customer-invoice/'.$val->cus_inv_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a inv-num="'.$val->cus_inv_number.'" co-id="'.$val->cus_inv_id.'" class="btn btn-success payment" data-toggle="modal" href="#myModal"><i class="fa fa-inr"></i> Payment</a>
            <button co-id="'.$val->cus_inv_id.'" type="button" class="btn btn-primary print_all_invoices"><i class="fa fa-print"></i> Print</button>
			<a href="javascript:void(0)" pk-name="cus_inv_id" pk-value="'.$val->cus_inv_id.'" tab="customer_invoice" child="1" ref-table="customer_invoice_detail" ref-pk-name="cus_inv_id" co-id="'.$val->co_id.'" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            // 
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


    public function add_customer_invoice() {
        
		$this->db->select('customer_order.co_id, customer_order.co_no, acc_master.am_id, acc_master.name, acc_master.short_name');
		$this->db->join('acc_master', 'acc_master.am_id = customer_order.acc_master_id', 'left');
		$cus_od_detail = $this->db->get_where('customer_order', array('customer_order.invoice_status' => 0))->result(); // Sundry Debtors
		
		$data['customer_order'] = $cus_od_detail;
		
		$data['transporter'] = $this->db->select('transporter_id, transporter_name, transporter_cn_number')->get_where('transporter', array('transporter.status' => 1))->result(); // Sundry Debtors
		
		
		$data['invoice_create_date'] = date('Y-m-d');

        return array('page'=>'customer_invoice/customer_invoice_add_v', 'data'=>$data);
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

    public function ajax_unique_customer_invoice_number() {
        $cus_inv_number = $this->input->post('cus_inv_number');
        $rs = $this->db->get_where('customer_invoice', array('cus_inv_number' => $cus_inv_number))->num_rows();
        // echo $this->db->last_query();die;
        
        if($rs != '0') {
            $data = 'Invoice no. already exists.';
        }else{
            $data='true';
        }

        return $data;
    }

    public function form_add_customer_invoice(){
		$co_id = $this->input->post('co_id');
		
       $insertArray = array(
			'co_id' => $this->input->post('co_id'),
			'cus_inv_number' => $this->input->post('cus_inv_number'),
			'cus_inv_e_way_bill_no' => $this->input->post('cus_inv_e_way_bill_no'),
			'am_id' => $this->input->post('am_id'),
			'transporter_id' => $this->input->post('transporter_id'),
			'transporter_cn_number' => $this->input->post('transporter_cn_number'),
			'cus_inv_number_of_cartons' => $this->input->post('cus_inv_number_of_cartons'),
			'cus_inv_total_weight' => $this->input->post('cus_inv_total_weight'),
			'packing_rate' => $this->input->post('packing_rate'),
            'packing_tax' => $this->input->post('packing_tax'),
			'invoice_create_date' => $this->input->post('invoice_create_date'),
			'remarks' => $this->input->post('remarks'),
			'terms' => $this->input->post('terms'),
            'transport_payment_type' => $this->input->post('pay_type'),
            'transport_payment_amount' => $this->input->post('cn_pay_amount'),
			'status' => $this->input->post('status'),
			'user_id' => $this->session->user_id
		);

        $this->db->insert('customer_invoice', $insertArray);
		$insert_id = $this->db->insert_id();
		
		if($insert_id > 0){
			$updateArray = array(
				'invoice_status' => 1
			);
			$this->db->update('customer_order', $updateArray, array('co_id' => $co_id));
			
			$data['insert_id'] = $insert_id;
			$data['type'] = 'success';
			$data['msg'] = 'Customer Invoice added successfully.';
		}else{
        	$data['type'] = 'error';
        	$data['msg'] = 'Data Insert Error';
		}
        return $data;
    }
    
    public function form_add_invoice_payment(){
                
       $insertArray = array(
            'invoice_id' => $this->input->post('invoice-id-submit'),
            'payment_date' => $this->input->post('payment_date'),
            'amount' => $this->input->post('payment_amount'),
            'remarks' => $this->input->post('payment_remarks'),
            'user_id' => $this->session->user_id
        );
        // print_r($insertArray); die;
        $this->db->insert('customer_payment', $insertArray);
        // echo $this->db->last_query();
        $insert_id = $this->db->insert_id();
        
        if($insert_id > 0){
            $data['insert_id'] = $insert_id;
            $data['type'] = 'success';
            $data['msg'] = 'Invoice payment added successfully. Refresh to update.';
        }else{
            $data['type'] = 'error';
            $data['msg'] = 'Data Insert Error';
        }
        return $data;
    }

    public function ajax_fetch_invoice_payment($in_id){
        $rs["payment"] = $this->db->select('*, DATE_FORMAT(payment_date, "%d-%m-%Y") as payment_date')->get_where('customer_payment', array('invoice_id' => $in_id))->result();
        $query = "SELECT
                    customer_invoice.cus_inv_id,
                    SUM(total_amount + (total_amount*(12/100))) as total_amount,
                    packing_rate,
                    packing_tax,
                    transport_payment_type,
                    transport_payment_amount,
                    transport_payment_status
                FROM
                    `customer_invoice`
                LEFT JOIN customer_invoice_detail ON customer_invoice.cus_inv_id = customer_invoice_detail.cus_inv_id
                WHERE
                    customer_invoice_detail.cus_inv_id = $in_id";
        $rs["invoice"] = $this->db->query($query)->result();
        return $rs;
    }

    public function edit_customer_invoice($cus_inv_id) {
        $this->db->select('customer_order.co_id, customer_order.co_no, acc_master.am_id, acc_master.name, acc_master.short_name');
		$this->db->join('acc_master', 'acc_master.am_id = customer_order.acc_master_id', 'left');
		$cus_od_detail = $this->db->get_where('customer_order', array('customer_order.invoice_status' => 0))->result(); // Sundry Debtors
		
		$data['customer_order'] = $cus_od_detail;
		
		$data['transporter'] = $this->db->select('transporter_id, transporter_name, transporter_cn_number')->get_where('transporter', array('transporter.status' => 1))->result();
		
		$this->db->select('customer_invoice.cus_inv_id, customer_invoice.co_id, customer_invoice.cus_inv_number, customer_invoice.cus_inv_e_way_bill_no, customer_invoice.am_id, customer_invoice.transporter_id, customer_invoice.cus_inv_number_of_cartons, customer_invoice.cus_inv_total_weight, DATE_FORMAT(customer_invoice.invoice_create_date, "%d-%m-%Y") as invoice_create_date, customer_invoice.packing_rate,customer_invoice.packing_tax,customer_invoice.remarks, customer_invoice.terms, customer_invoice.transport_payment_type, customer_invoice.transport_payment_amount, customer_invoice.status, acc_master.name, transporter.transporter_name, customer_invoice.transporter_cn_number, customer_order.img, customer_order.co_no');
		$this->db->join('acc_master', 'acc_master.am_id = customer_invoice.am_id', 'left');
		$this->db->join('transporter', 'transporter.transporter_id = customer_invoice.transporter_id', 'left');
		$this->db->join('customer_order', 'customer_order.co_id = customer_invoice.co_id', 'left');
		$data['customer_invoice_details'] = $this->db->get_where('customer_invoice', array('customer_invoice.cus_inv_id' => $cus_inv_id))->result();	
		
		$co_id = $this->db->select('co_id')->get_where('customer_invoice', array('cus_inv_id' => $cus_inv_id))->result()[0]->co_id;
		
		$invoice_pending_status = $this->db->select('invoice_pending_status')->get_where('customer_order', array('co_id' => $co_id))->result()[0]->invoice_pending_status;
		$data['invoice_pending_status'] = $invoice_pending_status;
		
		if($invoice_pending_status == 0){
			$this->db->select('customer_order_dtl.cod_id, customer_order_dtl.co_id,customer_order_dtl.sz_id,customer_order_dtl.cus_order_quantity,customer_order_dtl.c_id,customer_order_dtl.paper_gsm,customer_order_dtl.paper_bf, sizes.size, sizes.rate_per_unit, colors.color, colors.c_code');
			$this->db->join('sizes', 'sizes.sz_id = customer_order_dtl.sz_id', 'left');
			$this->db->join('colors', 'colors.c_id = customer_order_dtl.c_id', 'left');
			$customer_order_details = $this->db->get_where('customer_order_dtl', array('co_id' => $co_id))->result();		
		}else{
			$this->db->select('customer_invoice_detail.cus_inv_detail_id, customer_invoice_detail.cus_inv_id, customer_invoice_detail.co_id, customer_invoice_detail.cod_id, customer_invoice_detail.sz_id, customer_invoice_detail.paper_gsm, customer_invoice_detail.paper_bf, customer_invoice_detail.c_id, customer_invoice_detail.cus_order_quantity, customer_invoice_detail.rate_per_unit, customer_invoice_detail.delivered_quantity, customer_invoice_detail.due_quantity, customer_invoice_detail.total_amount, sizes.size, sizes.rate_per_unit, colors.color, colors.c_code');
			$this->db->join('sizes', 'sizes.sz_id = customer_invoice_detail.sz_id', 'left');
			$this->db->join('colors', 'colors.c_id = customer_invoice_detail.c_id', 'left');
			$customer_order_details = $this->db->get_where('customer_invoice_detail', array('cus_inv_id' =>$cus_inv_id, 'co_id' => $co_id))->result();
		}
		
		$data['customer_order_details'] = $customer_order_details;
				
        return array('page'=>'customer_invoice/customer_invoice_edit_v', 'data'=>$data);
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


    public function form_add_customer_invoice_details(){
		$cus_inv_id = $this->input->post('cus_inv_id_add');
		$co_id = $this->input->post('co_id');
		$cod_id = $this->input->post('cod_id');
		$sz_id = $this->input->post('sz_id');
		$paper_gsm = $this->input->post('paper_gsm');
		$paper_bf = $this->input->post('paper_bf');
		$c_id = $this->input->post('c_id');
		$cus_order_quantity = $this->input->post('cus_order_quantity_hidden');
		$rate_per_unit = $this->input->post('rate_per_unit');
		$delivered_quantity = $this->input->post('delivered_quantity');
		$due_quantity = $this->input->post('due_quantity');
		$total_amount = $this->input->post('total_amount');
        		
		$co_id1 = $co_id[0];
		$insert_id = 0;
        $due_quantity_temp = 0;
		for($i = 0; $i < sizeof($cod_id); $i++){
			$insertArray = array(
				'cus_inv_id' => $cus_inv_id,
				'co_id' => $co_id[$i],
				'cod_id' => $cod_id[$i],
				'sz_id' => $sz_id[$i],
				'paper_gsm' => $paper_gsm[$i],
				'paper_bf' => $paper_bf[$i],
				'c_id' => $c_id[$i],
				'cus_order_quantity' => $cus_order_quantity[$i],
				'rate_per_unit' => $rate_per_unit[$i],
				'delivered_quantity' => $delivered_quantity[$i],
				'due_quantity' => $due_quantity[$i],
				'total_amount' => $total_amount[$i],
				'user_id' => $this->session->user_id
			);
			$due_quantity_temp = $due_quantity_temp + $due_quantity[$i];
			
			$this->db->insert('customer_invoice_detail', $insertArray);
			$insert_id = $this->db->insert_id();
		}//end for

        
        if($insert_id > 0){
			if($due_quantity_temp > 0){
				$update_array = array(
					'invoice_status' => 1,
                    'invoice_pending_status' => 1,
					'invoice_final_status' => 0
                );
                $this->db->where(array('co_id' => $co_id1))->update('customer_order', $update_array);
			}else{
				$update_array = array(
					'invoice_status' => 1,
                    'invoice_pending_status' => 0,
					'invoice_final_status' => 1
                );
                $this->db->where(array('co_id' => $co_id1))->update('customer_order', $update_array);
			}//end if
			
			$data['insert_id'] = $insert_id;
			$data['type'] = 'success';
			$data['msg'] = 'Customer Invoice details updated successfully.';
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Sorry! Data Insert Problem';
		}
		 
        return $data;
    }
	
    public function ajax_fetch_customer_order_details_on_pk(){
        $cod_id = $this->input->post('cod_id');
        
		$this->db->select('customer_order_dtl.cod_id, customer_order_dtl.co_id,customer_order_dtl.sz_id,customer_order_dtl.cus_order_quantity,customer_order_dtl.c_id,customer_order_dtl.paper_gsm,customer_order_dtl.paper_bf, sizes.size, colors.color, colors.c_code');
		$this->db->join('sizes', 'sizes.sz_id = customer_order_dtl.sz_id', 'left');
		$this->db->join('colors', 'colors.c_id = customer_order_dtl.c_id', 'left');
		return $rs = $this->db->get_where('customer_order_dtl', array('customer_order_dtl.cod_id' => $cod_id))->result();
		
    }

    public function form_edit_customer_invoice_details(){
        
        $insertArray = array(
            'sz_id' => $this->input->post('sz_id_edit'),
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

    public function form_edit_customer_invoice(){
       $cus_inv_id = $this->input->post('cus_inv_id');
		
       $updateArray = array(
			'co_id' => $this->input->post('co_id'),
			'cus_inv_number' => $this->input->post('cus_inv_number'),
			'cus_inv_e_way_bill_no' => $this->input->post('cus_inv_e_way_bill_no'),
			'am_id' => $this->input->post('am_id'),
			'transporter_id' => $this->input->post('transporter_id'),
			'transporter_cn_number' => $this->input->post('transporter_cn_number'),
			'cus_inv_number_of_cartons' => $this->input->post('cus_inv_number_of_cartons'),
			'cus_inv_total_weight' => $this->input->post('cus_inv_total_weight'),
			'packing_rate' => $this->input->post('packing_rate'),
            'packing_tax' => $this->input->post('packing_tax'),
			'invoice_create_date' => $this->input->post('invoice_create_date'),
			'remarks' => $this->input->post('remarks'),
			'terms' => $this->input->post('terms'),
			'status' => $this->input->post('status'),
			'user_id' => $this->session->user_id
		);
        
        $this->db->update('customer_invoice', $updateArray, array('cus_inv_id' => $cus_inv_id));

        $data['type'] = 'success';
        $data['msg'] = 'Customer Invoice updated successfully.';
        return $data;

    }
	
    public function del_payment_received($pk){
        $this->db->where('cp_id', $pk)->delete('customer_payment');
        $data['title'] = 'Deleted!';
        $data['type'] = 'success';
        $data['msg'] = 'Invoice Payment Successfully Deleted. Refresh to update.';
        return $data;
    }

    public function del_row_on_table_pk_invoice(){
        $pk_name = $this->input->post('pk_name');
		$pk_val = $this->input->post('pk_val');
        $tab = $this->input->post('tab');
		$ref_table = $this->input->post('ref_table');
		$ref_pk_name = $this->input->post('ref_pk_name');
		$co_id = $this->input->post('co_id');
		
		$updateArray = array(
			'invoice_status' => 0,
			'invoice_final_status' => 0
		);
		$this->db->update('customer_order', $updateArray, array('co_id' => $co_id));
		
		
        $this->db->where($pk_name, $pk_val)->delete($tab);
		$this->db->where($ref_pk_name, $pk_val)->delete($ref_table);
		
		// delete invoice table
        $this->db->where('invoice_id', $pk_val)->delete('customer_payment');  

        $data['title'] = 'Deleted!';
        $data['type'] = 'success';
        $data['msg'] = 'Invoice Successfully Deleted';
        return $data;
    }

    public function form_update_transport_payment(){
        $tps = $this->input->post('tps');
        $inv_id = $this->input->post('inv');

        $updateArray = array(
            'transport_payment_status' => $tps
        );

        $this->db->update('customer_invoice', $updateArray, array('cus_inv_id' => $inv_id));

        $data['type'] = 'success';
        $data['msg'] = 'Transport Payment Status Updated Successfully.';
        return $data;
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

// CUTOMER ORDER PRINTING

    public function invoice_print($co_id){

        $this->db->select('customer_invoice_detail.*,customer_invoice.*, sizes.hsn_code, sizes.size,sizes.product_name, colors.color, colors.c_code, customer_order.co_no,acc_master.*');
        
        $this->db->join('customer_invoice', 'customer_invoice.cus_inv_id = customer_invoice_detail.cus_inv_id', 'left');
        $this->db->join('sizes', 'sizes.sz_id = customer_invoice_detail.sz_id', 'left');
        $this->db->join('colors', 'colors.c_id = customer_invoice_detail.c_id', 'left');
        $this->db->join('customer_order', 'customer_order.co_id = customer_invoice_detail.co_id', 'left');
        $this->db->join('acc_master', 'acc_master.am_id = customer_invoice.am_id', 'left');
    
        $rd = $this->db->get_where('customer_invoice_detail', array('customer_invoice.cus_inv_id' => $co_id))->result();

        // echo $this->db->last_query();

        return $rd;

    }

   public function customer_receipt_print($co_id){

        $this->db->select('*');
        $this->db->join('customer_payment', 'customer_payment.invoice_id = customer_invoice.cus_inv_id', 'left');
        $rd = $this->db->get_where('customer_invoice', array('customer_invoice.cus_inv_id' => $co_id))->result();
        // echo $this->db->last_query();
        return $rd;
    }
    
// CUSTOMER ORDER ENDS 

}