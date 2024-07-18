<?php

class Production_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function production($po_id) {
        $data['po_id'] = $po_id;
        return array('page'=>'production/production_list_v', 'data'=>$data);
    }

    public function ajax_production_table_data() {

    	$po_id = $this->input->post('purchase_order_id');
    	// fetch all pod id
    	$query = "
	    	SELECT
			    GROUP_CONCAT(pod_id) AS pod_ids
			FROM
			    `purchase_order_details`
			WHERE
			    po_id = $po_id";

    	$prod_id =$this->db->query($query)->result();
    	$production_id = $prod_id[0]->pod_ids;

		// echo '<pre>', print_r($production_id), '</pre>';die;

        //actual db table column names
       	$column_orderable = array(
            0 => 'production.prod_date',
            1 => 'purchase_order_details.consignement_number'
        );
        // Set searchable column fields
        $column_search = array('purchase_order_details.consignement_number', 'production.prod_bag_produced', 'production.prod_avg_weight', 'production.prod_wastage_pcs');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->_production_common_query($production_id);
		
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
        	
        	$rs = $this->_production_common_query($production_id);    
			
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

            $rs = $this->_production_common_query($production_id);  

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$rs = $this->_production_common_query($production_id);

            $this->db->flush_cache();
        }

        $data = array();

        foreach ($rs as $val) {

            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['po_number'] = $val->po_number;
            $nestedData['consignement_number'] = $val->consignement_number . ' /<b>'.$val->size_in_text.'</b>';
            $nestedData['prod_date'] = $val->prod_date;
            $nestedData['prod_bag_produced'] = $val->prod_bag_produced;
            $nestedData['prod_avg_weight'] = $val->prod_avg_weight;
            $nestedData['prod_wastage'] = $val->prod_wastage_pcs;
            $nestedData['status'] = $status;
            $nestedData['action'] = '<a href="'. base_url('admin/edit-production/'.$val->prod_id . '/'. $val->po_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a href="javascript:void(0)" pk-name="prod_id" pk-value="'.$val->prod_id.'" tab="production" child="1" ref-table="" ref-pk-name="" pod_id="'.$val->pod_id.'" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            $data[] = $nestedData;

            // echo '<pre>', print_r($rs), '</pre>'; 
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );


        // echo $this->db->last_query();die;

        return $json_data;
    }    

   	private function _production_common_query($production_id){
   		
   		$production_id = explode(",",$production_id);

   		$rs = $this->db->select('production.prod_id, production.prod_number, production.pod_id, DATE_FORMAT(production.prod_date, "%d-%m-%Y") as prod_date, production.prod_bag_produced, production.prod_avg_weight, production.prod_wastage_pcs, production.prod_wastage_kg, production.remarks, production.terms, production.status, purchase_order.po_id, purchase_order.po_number, purchase_order_details.consignement_number,size_in_text');
            $this->db->join('purchase_order_details', 'purchase_order_details.pod_id = production.pod_id', 'left');
            $this->db->join('purchase_order', 'purchase_order_details.po_id = purchase_order.po_id', 'left');
            $rs = $this->db->order_by('prod_date')->where_in('production.pod_id', $production_id)->get_where('production', array('production.status' => 1))->result();
        return $rs;    
   	}

    public function add_production($po_id) {
		$data['auto_prod_number'] = 'PROD/'.date('d-m-Y/h-i-s');
		$data['prod_date'] = date('Y-m-d');
		
		$data['cn_list'] = $this->db->select('pod_id, consignement_number')->get_where('purchase_order_details', array('status' => 1, 'production_status' => 0, 'po_id' => $po_id))->result();

        return array('page'=>'production/production_add_v', 'data'=>$data);
    }

    public function ajax_unique_prod_number(){
        $prod_number = $this->input->post('prod_number');

        $rs = $this->db->get_where('production', array('prod_number' => $prod_number))->num_rows();
        if($rs != '0') {
            $data = 'Production no. already exists.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function form_add_production(){
		$pod_id = $this->input->post('pod_id');
       $insertArray = array(
			'prod_number' => $this->input->post('prod_number'),
			'pod_id' => $pod_id,
			'prod_date' => $this->input->post('prod_date'),
			'total_roll_weight' => $this->input->post('total_roll_weight'),
			'prod_avg_weight' => $this->input->post('prod_avg_weight'),
			'expected_production' => $this->input->post('expected_production'),
			'remarks' => $this->input->post('remarks'),
			'terms' => $this->input->post('terms'),
			'user_id' => $this->session->user_id
		);
		
		$this->db->insert('production', $insertArray);
		
		if($this->db->insert_id() > 0){
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'Production added successfully.';
			
			$updateArray = array(
				'production_status' => 1,
			);
			$this->db->update('purchase_order_details', $updateArray, array('pod_id' => $pod_id));

		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Data Insert Problem';
		}
		
		return $data; 
    }
	
	public function form_add_production_details(){
		$prod_id = $this->input->post('prod_id_add');
		$pod_id = $this->input->post('pod_id_detail_add');		
		$prod_detail_quantity_add = $this->input->post('prod_detail_quantity_add');
		
		$insertArray = array(
			'prod_id' => $prod_id,
			'pod_id' => $pod_id,
			'prod_detail_date' => $this->input->post('prod_detail_date_add'),
			'pc_id' => $this->input->post('product_category_id_add'),
			'sz_id' => $this->input->post('sz_id_add'),
			'prod_detail_quantity' => $prod_detail_quantity_add,
			'miter_start_reading' => $this->input->post('miter_start_reading_add'),
			'miter_end_reading' => $this->input->post('miter_end_reading_add'),
			'user_id' => $this->session->user_id
		);

		$this->db->insert('production_detail', $insertArray); 
		$db_insert_id = $this->db->insert_id();
		$prod_detail_id = $this->db->insert_id();
		
		$prod_bag_produced = $this->db->select('prod_bag_produced')->get_where('production', array('prod_id' => $prod_id))->result()[0]->prod_bag_produced;
		
		$prod_bag_produced_new = $prod_bag_produced + $prod_detail_quantity_add;
		
		$expected_production = $this->db->select('expected_production')->get_where('production', array('prod_id' => $prod_id))->result()[0]->expected_production;
		
		$prod_wastage_pcs = $expected_production - $prod_bag_produced_new;
		
		$prod_avg_weight = $this->db->select('prod_avg_weight')->get_where('production', array('prod_id' => $prod_id))->result()[0]->prod_avg_weight;
		
		$waight_of_one_unit = $prod_avg_weight / 10;
		// $prod_wastage_kg = ($prod_wastage_pcs * $waight_of_one_unit ) * 1000;
		$prod_wastage_gm = (($waight_of_one_unit/10000) * $prod_wastage_pcs);

		$update_array = array(
			'prod_bag_produced' => $prod_bag_produced_new,
			'prod_wastage_pcs' => $prod_wastage_pcs,
			'prod_wastage_kg' => $prod_wastage_gm
		);
		$this->db->where(array('prod_id' => $prod_id))->update('production', $update_array);
		
		$update_array = array(
			'production_status' => 1,
			'check_in_status' => 0
		);
		$this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
		
        //echo $this->db->last_query();die;
		if($db_insert_id > 0){
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'Production details added successfully.';
			$data['prod_bag_produced_new'] = $prod_bag_produced_new;
			$data['prod_wastage_pcs'] = $prod_wastage_pcs;
			$data['prod_wastage_kg'] = $prod_wastage_gm;
			
			/*** Distribution or Checkout code start here ***/
			
			$dis_id_add = 1;
			$prod_detail_quantity = 0;
			$sz_id = $this->input->post('sz_id_add');
			
			$insertArray = array(
				'dis_id' => $dis_id_add,
				'e_id' => $this->input->post('e_id_add'),
				'pod_id' => $pod_id,
				'prod_detail_id' => $prod_detail_id,				
				'roll_handel' => 0,
				'sz_id' => $sz_id,
				'u_id' => $this->input->post('u_id_add'),
				'distribute_pod_quantity' => $this->input->post('prod_detail_quantity_add'),
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
					//$this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
				}
				
				//Fetch CN No.
				/*$data['cn_list'] = $this->db->select('pod_id, consignement_number')->get_where('purchase_order_details', array('purchase_order_details.status' => 1, 'purchase_order_details.production_status' => 1, 'purchase_order_details.distribute_status' => 0))->result();
							
				$data['insert_id'] = $this->db->insert_id();
				$data['type'] = 'success';
				$data['msg'] = 'Distribute details added successfully.';*/
			}else{
				$data['type'] = 'error';
				$data['msg'] = 'Data Insert Problem';
			}
			/*** Distribution or Checkout code end here ***/
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Data Insert Problem';
		}

        return $data;
    }

    public function edit_production($prod_id, $po_id) {
		$data['employees'] = $this->db->select('e_id, name, e_code')->get_where('employees', array('employees.status' => 1))->result();
		
		$data['product_categories'] = $this->db->select('pc_id, category_name')->get_where('product_category', array('status' => 1))->result();

		$data['sizes'] = $this->db->select('sz_id, size, paper_gsm')->get_where('sizes', array('sizes.status' => 1))->result();
        
		$data['cn_list'] = $this->db->select('pod_id, consignement_number,size_in_text')->get_where('purchase_order_details', array('status' => 1, 'production_status' => 0, 'po_id' => $po_id))->result();
		
		$data['units'] = $this->db->select('u_id, unit')->get_where('units', array('units.status' => 1))->result();

		$data['product_categories'] = $this->db->select('pc_id, category_name')->get_where('product_category', array('status' => 1))->result();
		
        $this->db->select('production.prod_id, production.prod_number, production.pod_id, DATE_FORMAT(production.prod_date, "%d-%m-%Y") as prod_date, production.prod_bag_produced, production.total_roll_weight, production.prod_avg_weight, production.expected_production, production.prod_wastage_pcs, production.prod_wastage_kg, production.remarks, production.terms, production.status, purchase_order_details.consignement_number, size_in_text');
		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = production.pod_id', 'left');
		$rs = $this->db->get_where('production', array('production.prod_id' => $prod_id))->result();		
        
		$data['production_details'] = $rs;
				
        return array('page'=>'production/production_edit_v', 'data'=>$data);
    }

    public function form_edit_production(){
		$pod_id_old = $this->input->post('pod_id_old');
		$pod_id_new = $this->input->post('pod_id');
		$prod_id = $this->input->post('prod_id');
		
        $updateArray = array(
            'prod_number' => $this->input->post('prod_number'),
            'pod_id' => $this->input->post('pod_id'),
            'prod_date' => $this->input->post('prod_date'),
            'total_roll_weight' => $this->input->post('total_roll_weight'),
            'prod_avg_weight' => $this->input->post('prod_avg_weight'),
            'expected_production' => $this->input->post('expected_production'),
            'prod_bag_produced' => $this->input->post('prod_bag_produced'),
            'prod_wastage_pcs' => $this->input->post('prod_wastage_pcs'),
            'prod_wastage_kg' => $this->input->post('prod_wastage_kg'),
            'remarks' => $this->input->post('remarks'),
            'terms' => $this->input->post('terms'),
            'status' => $this->input->post('status'),
            'user_id' => $this->session->user_id
        );
        $this->db->update('production', $updateArray, array('prod_id' => $prod_id));

        $data['type'] = 'success';
        $data['msg'] = 'Purchase order updated successfully.';
        return $data;

    }

    public function ajax_production_details_table_data() {
        $prod_id = $this->input->post('prod_id');
		
        //actual db table column names
        $column_orderable = array(
            0 => 'purchase_order_details.consignement_number',
			1 => 'production_detail.prod_detail_date',
			2 => 'sizes.size',
            3 => 'production_detail.prod_detail_quantity',
            4 => 'production_detail.miter_start_reading',
            5 => 'production_detail.miter_end_reading'
        );
		
        // Set searchable column fields
        $column_search = array('purchase_order_details.consignement_number','production_detail.prod_detail_date','sizes.size','production_detail.prod_detail_quantity','production_detail.miter_start_reading','production_detail.miter_end_reading');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('production_detail.prod_detail_id, production_detail.prod_id, DATE_FORMAT(production_detail.prod_detail_date, "%d-%m-%Y") as prod_detail_date, production_detail.sz_id, production_detail.prod_detail_quantity, production_detail.miter_start_reading,production_detail.miter_end_reading, sizes.size, purchase_order_details.pod_id, purchase_order_details.consignement_number, purchase_order_details.size_in_text,employees.name,employees.e_code');

        $this->db->join('distribute_detail', 'distribute_detail.prod_detail_id = production_detail.prod_detail_id', 'left');
		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
		$this->db->join('production', 'production.prod_id = production_detail.prod_id', 'left');
		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = production.pod_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = production_detail.sz_id', 'left');
		
		$rs = $this->db->order_by('distribute_detail.dis_detail_id')->get_where('production_detail', array('production_detail.prod_id' => $prod_id))->result();
			
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('production_detail.prod_detail_id, production_detail.prod_id, DATE_FORMAT(production_detail.prod_detail_date, "%d-%m-%Y") as prod_detail_date, production_detail.sz_id, production_detail.prod_detail_quantity, production_detail.miter_start_reading,production_detail.miter_end_reading, sizes.size, purchase_order_details.pod_id, purchase_order_details.consignement_number, purchase_order_details.size_in_text,employees.name,employees.e_code');

	        $this->db->join('distribute_detail', 'distribute_detail.prod_detail_id = production_detail.prod_detail_id', 'left');
			$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
			$this->db->join('production', 'production.prod_id = production_detail.prod_id', 'left');
			$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = production.pod_id', 'left');
			$this->db->join('sizes', 'sizes.sz_id = production_detail.sz_id', 'left');
			
			$rs = $this->db->order_by('distribute_detail.dis_detail_id')->get_where('production_detail', array('production_detail.prod_id' => $prod_id))->result();
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

			$this->db->select('production_detail.prod_detail_id, production_detail.prod_id, DATE_FORMAT(production_detail.prod_detail_date, "%d-%m-%Y") as prod_detail_date, production_detail.sz_id, production_detail.prod_detail_quantity, production_detail.miter_start_reading,production_detail.miter_end_reading, sizes.size, purchase_order_details.pod_id, purchase_order_details.consignement_number, purchase_order_details.size_in_text,employees.name,employees.e_code');

	        $this->db->join('distribute_detail', 'distribute_detail.prod_detail_id = production_detail.prod_detail_id', 'left');
			$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
			$this->db->join('production', 'production.prod_id = production_detail.prod_id', 'left');
			$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = production.pod_id', 'left');
			$this->db->join('sizes', 'sizes.sz_id = production_detail.sz_id', 'left');
			
			$rs = $this->db->order_by('distribute_detail.dis_detail_id')->get_where('production_detail', array('production_detail.prod_id' => $prod_id))->result();
        
            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            
			$this->db->select('production_detail.prod_detail_id, production_detail.prod_id, DATE_FORMAT(production_detail.prod_detail_date, "%d-%m-%Y") as prod_detail_date, production_detail.sz_id, production_detail.prod_detail_quantity, production_detail.miter_start_reading,production_detail.miter_end_reading, sizes.size, purchase_order_details.pod_id, purchase_order_details.consignement_number, purchase_order_details.size_in_text,employees.name,employees.e_code');

	        $this->db->join('distribute_detail', 'distribute_detail.prod_detail_id = production_detail.prod_detail_id', 'left');
			$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
			$this->db->join('production', 'production.prod_id = production_detail.prod_id', 'left');
			$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = production.pod_id', 'left');
			$this->db->join('sizes', 'sizes.sz_id = production_detail.sz_id', 'left');
			
			$rs = $this->db->order_by('distribute_detail.dis_detail_id')->get_where('production_detail', array('production_detail.prod_id' => $prod_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        

        foreach ($rs as $val) {
			
            $nestedData['cn_no'] = $val->consignement_number. ' <b>/' . $val->size_in_text .'</b>';
            $nestedData['employee'] = $val->name . ' ['. $val->e_code .']';
            $nestedData['production_detail_date'] = $val->prod_detail_date;
            $nestedData['prod_paper_size'] = $val->size;
            $nestedData['prod_detail_quantity'] = $val->prod_detail_quantity;
			$nestedData['miter_start_reading'] = $val->miter_start_reading;
			$nestedData['miter_end_reading'] = $val->miter_end_reading;
            
            $nestedData['action'] = '<a href="javascript:void(0)" prod_detail_id="'.$val->prod_detail_id.'" class="production_details_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a data-tab="production_detail" data-ref-tab="production" pk-name="prod_detail_id" data-pk="'.$val->prod_detail_id.'" data-quantity="'.$val->prod_detail_quantity.'" pod-id="'.$val->pod_id.'" href="javascript:void(0)" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            
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

    

    public function form_add_purchase_order_details(){
        $pod_total = ($this->input->post('pod_quantity') * $this->input->post('rate_per_unit'));
		
		$number_of_copy = $this->input->post('number_of_copy');
		
		if($number_of_copy > 0){
			$consignement_number = $this->input->post('consignement_number');
			for($i = 0; $i < $number_of_copy; $i++){
				$consignement_number1 = $consignement_number.'_'.$i;
				$insertArray = array(
					'po_id' => $this->input->post('purchase_order_id'),
					'roll_handel' => $this->input->post('roll_handel'),
					'roll_weight' => $this->input->post('roll_weight'),
					'c_id' => $this->input->post('c_id'),
					'sz_id' => $this->input->post('sz_id'),
					'pod_quantity' => $this->input->post('pod_quantity'),
					'u_id' => $this->input->post('u_id'),
					'rate_per_unit' => $this->input->post('rate_per_unit'),
					'pod_total' => $pod_total,
					'pod_remarks' => $this->input->post('pod_remarks'),
					'consignement_number' => $consignement_number1,
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
				'sz_id' => $this->input->post('sz_id'),
				'pod_quantity' => $this->input->post('pod_quantity'),
				'u_id' => $this->input->post('u_id'),
				'rate_per_unit' => $this->input->post('rate_per_unit'),
				'pod_total' => $pod_total,
				'pod_remarks' => $this->input->post('pod_remarks'),
				'consignement_number' => $this->input->post('consignement_number'),
				'user_id' => $this->session->user_id
			);
	
			$this->db->insert('purchase_order_details', $insertArray);
		}//end 
		
        //echo $this->db->last_query();die;
		if($this->db->insert_id() > 0){
			$data['insert_id'] = $this->db->insert_id();
			$data['type'] = 'success';
			$data['msg'] = 'purchase order details added successfully.';
			$data['consignement_number'] = 'CN-'.date('dmoYhis');
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Data Insert Problem';
		}

        return $data;
    }

    public function ajax_fetch_production_details_on_pk(){
        $prod_detail_id = $this->input->post('prod_detail_id');
		
		$this->db->select('production_detail.prod_detail_id, production_detail.prod_id, production_detail.pod_id, DATE_FORMAT(production_detail.prod_detail_date, "%Y-%m-%d") as prod_detail_date, production_detail.sz_id, production_detail.prod_detail_quantity, production_detail.pc_id, sizes.size, purchase_order_details.pod_id, purchase_order_details.consignement_number,employees.name,employees.e_code, employees.e_id');
		//$this->db->join('production', 'production.prod_id = production_detail.prod_id', 'left');
		 $this->db->join('distribute_detail', 'distribute_detail.prod_detail_id = production_detail.prod_detail_id', 'left');
			$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = production_detail.pod_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = production_detail.sz_id', 'left');
		
		return $rs = $this->db->get_where('production_detail', array('production_detail.prod_detail_id' => $prod_detail_id))->result();
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
	
	public function form_edit_production_details(){
        $prod_detail_id = $this->input->post('prod_detail_id');
		$pod_id_edit = $this->input->post('pod_id_edit');
		$prod_id_edit = $this->input->post('prod_id_edit');
		$prod_detail_hidden_quantity_edit = $this->input->post('prod_detail_hidden_quantity_edit');
		$prod_detail_quantity = $this->input->post('prod_detail_quantity_edit');
		
        $updateArray = array(
            'prod_detail_date' => $this->input->post('prod_detail_date_edit'),
            'pc_id' => $this->input->post('product_category_id_edit'),
            'sz_id' => $this->input->post('sz_id_edit'),
            'prod_detail_quantity' => $prod_detail_quantity,
            'user_id' => $this->session->user_id
        );
        $this->db->update('production_detail', $updateArray, array('prod_detail_id' => $prod_detail_id));

        // Different update for employee
        $updateEmpArray = array(
            'e_id' => $this->input->post('e_id_edit'),
            'sz_id' => $this->input->post('sz_id_edit'),
            'distribute_pod_quantity' => $prod_detail_quantity,
            'user_id' => $this->session->user_id
        );
        $this->db->update('distribute_detail', $updateEmpArray, array('prod_detail_id' => $prod_detail_id));
        
        $prod_bag_produced = $this->db->select_sum('prod_bag_produced')->get_where('production', array('prod_id' => $prod_id_edit))->result()[0]->prod_bag_produced;
		
		$prod_bag_produced_new = ($prod_bag_produced - $prod_detail_hidden_quantity_edit) + $prod_detail_quantity;
        
		$expected_production = $this->db->select('expected_production')->get_where('production', array('prod_id' => $prod_id_edit))->result()[0]->expected_production;
		
		$prod_wastage_pcs = $expected_production - $prod_bag_produced_new;
		
		$prod_avg_weight = $this->db->select('prod_avg_weight')->get_where('production', array('prod_id' => $prod_id_edit))->result()[0]->prod_avg_weight;
		
		$waight_of_one_unit = $prod_avg_weight / 10;
		// $prod_wastage_kg = (($prod_wastage_pcs * $waight_of_one_unit ) * 1000;
		$prod_wastage_gm = (($waight_of_one_unit/10000) * $prod_wastage_pcs);
		
		// update production table 
        $updateArray1= array(
            'prod_bag_produced' => $prod_bag_produced_new,
			'prod_wastage_pcs' => $prod_wastage_pcs,
			'prod_wastage_kg' => $prod_wastage_gm
        );
        $this->db->update('production', $updateArray1, array('prod_id' => $prod_id_edit));
        
        $updateArray = array(
            'distribute_pod_quantity' => $prod_detail_quantity,
            'user_id' => $this->session->user_id
        );

        $this->db->update('distribute_detail', $updateArray, array('prod_detail_id' => $prod_detail_id));

        $data['type'] = 'success';
        $data['msg'] = 'Production details updated successfully.';
		$data['prod_bag_produced_new'] = $prod_bag_produced_new;
		$data['prod_detail_hidden_quantity_edit'] = $prod_detail_quantity;
		$data['prod_wastage_pcs'] = $prod_wastage_pcs;
		$data['prod_wastage_kg'] = $prod_wastage_gm;
		
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


    public function form_edit_purchase_order_details(){
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

    public function ajax_delete_production_on_pk(){
		$pk_name = $this->input->post('pk_name');
		$pk_value = $this->input->post('pk_value');		
		$tab = $this->input->post('tab');		
		$pod_id = $this->input->post('pod_id');
		
		if($tab == 'production'){
			$this->db->where($pk_name, $pk_value)->delete($tab);
		}
		
		$this->db->where($pk_name, $pk_value)->delete('production_detail');
		
		$updateArray = array(
			'production_status' => 0,
		);
		$this->db->update('purchase_order_details', $updateArray, array('pod_id' => $pod_id));
		
		$data['title'] = 'Deleted!';
		$data['type'] = 'success';
		$data['msg'] = 'Production Successfully Deleted';
		
        return $data;
    }
	
	public function ajax_delete_production_detail_on_pk(){
		$header_tab = $this->input->post('header_tab');
		$tab = $this->input->post('tab');		
		$pk_name = $this->input->post('pk_name');		
		$pk_val = $this->input->post('pk_val'); 
		$data_quantity = $this->input->post('data_quantity');		
		$prod_id = $this->input->post('prod_id');		
		$pod_id = $this->input->post('pod_id');
		
		$this->db->where($pk_name, $pk_val)->delete($tab);
		
		$tab1 = 'distribute_detail';
		$this->db->where($pk_name, $pk_val)->delete($tab1);
		
		$prod_bag_produced = $this->db->select('prod_bag_produced')->get_where('production', array('prod_id' => $prod_id))->result()[0]->prod_bag_produced;
		
		$prod_bag_produced_new = $prod_bag_produced - $data_quantity;
		
		$expected_production = $this->db->select('expected_production')->get_where('production', array('prod_id' => $prod_id))->result()[0]->expected_production;
		
		$prod_wastage_pcs = $expected_production - $prod_bag_produced_new;
		
		$prod_avg_weight = $this->db->select('prod_avg_weight')->get_where('production', array('prod_id' => $prod_id))->result()[0]->prod_avg_weight;
		
		$waight_of_one_unit = $prod_avg_weight / 10;
		// $prod_wastage_kg = ($prod_wastage_pcs * $waight_of_one_unit ) * 1000;
		$prod_wastage_gm = (($waight_of_one_unit/10000) * $prod_wastage_pcs);
		
		$update_array = array(
			'prod_bag_produced' => $prod_bag_produced_new,
			'prod_wastage_pcs' => $prod_wastage_pcs,
			'prod_wastage_kg' => $prod_wastage_gm
		);
		$this->db->where(array('prod_id' => $prod_id))->update('production', $update_array);
		
		if($prod_bag_produced_new == 0){
			$update_array = array(
				'production_status' => 0,
				'distribute_status' => 0
			);
			$this->db->where(array('pod_id' => $pod_id))->update('purchase_order_details', $update_array);
		}
		
		$data['title'] = 'Deleted!';
		$data['type'] = 'success';
		$data['msg'] = 'Production Detail Successfully Deleted';
		$data['prod_bag_produced_new'] = $prod_bag_produced_new;
		$data['prod_wastage_pcs'] = $prod_wastage_pcs;
		$data['prod_wastage_kg'] = $prod_wastage_gm;
        return $data;
    }

    public function ajax_fetch_last_miter_reading(){

    	$query = "SELECT MAX(miter_end_reading) as mer FROM `production_detail`";
    	$data['max_reading'] = $this->db->query($query)->result()[0]->mer;

    	$query1 = "SELECT miter_end_reading as last FROM `production_detail` ORDER BY `prod_detail_id` DESC LIMIT 1";
    	$data['last_reading'] = $this->db->query($query1)->result()[0]->last;

    	$data['reading_option'] = $this->db->get('meter_reading')->result()[0]->meter_reading_option;
    	// echo '<pre>',print_r($data),'</pre>';

    	return $data;

    }

    // production ORDER ENDS 

}