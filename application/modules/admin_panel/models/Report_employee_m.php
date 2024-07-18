<?php

class Report_employee_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function generate_employee_init() {
		$data = array();
		$data["start_date"] = date('Y-m-d');
		$data["end_date"] = date('Y-m-d');
		
		$this->db->select('e_id, e_code, name, emp_adhar_card_number, due_amount');
		$rs_emp = $this->db->get_where('employees', array('employees.status' => 1))->result();
		$data["employees"] = $rs_emp;
		$data["e_id"] = 0;

        return $data;
    }
	
    public function generate_employee_report() {
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$e_id = $this->input->post('e_id');
		
		$data = array();
		$data["start_date"] = $start_date;
		$data["end_date"] = $end_date;
		$data["e_id"] = $e_id;
		
		$start_date1 = $start_date.' 00:00:01';
		$end_date1 = $end_date.' 23:59:59';
		
		$this->db->select('checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.terms, checkin_detail.remarks, checkin_detail.rate_per_bag, checkin_detail.todays_payable, checkin_detail.due_amount, checkin_detail.net_payable, checkin_detail.status, checkin_detail.user_id, checkin_detail.create_date, checkin_detail.modify_date, employees.e_code, employees.name, employees.emp_adhar_card_number, employees.rate_per_bag, sizes.size, sizes.rate_per_unit');
		$this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
		//$this->db->order_by('checkin_detail.e_id');
		$this->db->order_by('checkin_detail.sz_id');
		$rs = $this->db->get_where('checkin_detail', array('checkin_detail.e_id' => $e_id, 'checkin_detail.create_date >=' => $start_date1, 'checkin_detail.create_date <=' => $end_date1))->result();
		//$rs = $this->db->get_where('checkin_detail', array('checkin_detail.status' => 1))->result();
		
		//echo $this->db->last_query();
		$data["emp_data"] = $rs;
		
		$due_amount = $this->db->select('e_id, e_code, name, emp_adhar_card_number, due_amount')->get_where('employees', array('employees.e_id' => $e_id))->result();
		$data["due_amount"] = $due_amount;
		
		$this->db->select('e_id, e_code, name, emp_adhar_card_number, due_amount');
		$rs_emp = $this->db->get_where('employees', array('employees.status' => 1))->result();
		$data["employees"] = $rs_emp;
		
        return $data;
    }
    
    public function check_in_out_report() {
        $data = '';

        if($this->input->post()) {

        $start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$e_id = $this->input->post('e_id');
		
		$data = array();
		$data["start_date"] = $start_date;
		$data["end_date"] = $end_date;
		$data["e_id"] = $e_id;
		
		if($start_date != '' && $end_date != '') {
		
		$start_date1 = $start_date;
		$end_date1 = $end_date;

        } else {
        
        $start_date1 = '';
        $end_date1 = '';
        
        }

		foreach($e_id as $e_i) {

		$data['result'][] = $this->_fetch_all_check_in_out_details($e_i, $start_date1, $end_date1);

	    }

	    // echo '<pre>', print_r($data['result']), '</pre>'; die();

	    $data['segment'] = 'check_in_out_report';

	    return array('page'=>'reports/common_print_v', 'data'=>$data);

	}
        
            $data['fetch_all_employee'] = $this->db
            ->order_by('employees.name')
            ->get_where('employees', array('employees.status' => 1))->result();

        return array('page'=>'reports/check_in_out_report_v', 'data'=>$data);
    }

    public function _fetch_all_check_in_out_details($e_i, $start_date1, $end_date1) {
    	if(empty($e_i)) {
    		die('No Details Found');
    	}

    	$new_array = array();
    	$payment_stat='';
    	
    	if($start_date1 != '' && $end_date1 != '') {
		
    		$this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, SUM(distribute_detail.distribute_pod_quantity) as distribute_pod_quantity, distribute_detail.remarks, distribute_detail.terms, distribute_detail.status, distribute_detail.user_id, distribute_detail.create_date, distribute_detail.modify_date, employees.e_code, employees.name, employees.emp_adhar_card_number, employees.rate_per_bag, sizes.size, sizes.rate_per_unit, purchase_order_details.consignement_number, production_detail.prod_detail_date, units.unit, product_category.category_name, purchase_order.po_number');
    		
    		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
    		$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
    		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
    		$this->db->join('purchase_order', 'purchase_order_details.po_id = purchase_order.po_id', 'left');
    		$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
    		$this->db->join('production_detail', 'production_detail.prod_detail_id = distribute_detail.prod_detail_id', 'left');
    		$this->db->join('product_category', 'product_category.pc_id = production_detail.pc_id', 'left');
            $this->db->group_by('distribute_detail.pod_id, distribute_detail.sz_id');
    		$this->db->order_by('distribute_detail.sz_id');
    		$rs = $this->db->get_where('distribute_detail', array('distribute_detail.e_id' => $e_i, 'production_detail.prod_detail_date >=' => $start_date1, 'production_detail.prod_detail_date <=' => $end_date1))->result();
		

    	} else {
    	    
            $this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, SUM(distribute_detail.distribute_pod_quantity) as distribute_pod_quantity, distribute_detail.remarks, distribute_detail.terms, distribute_detail.status, distribute_detail.user_id, distribute_detail.create_date, distribute_detail.modify_date, employees.e_code, employees.name, employees.emp_adhar_card_number, employees.rate_per_bag, sizes.size, sizes.rate_per_unit, purchase_order_details.consignement_number, production_detail.prod_detail_date, units.unit, product_category.category_name, purchase_order.po_number');
    		
    		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
    		$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
    		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
    		$this->db->join('purchase_order', 'purchase_order_details.po_id = purchase_order.po_id', 'left');
    		$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
    		$this->db->join('production_detail', 'production_detail.prod_detail_id = distribute_detail.prod_detail_id', 'left');
    		$this->db->join('product_category', 'product_category.pc_id = production_detail.pc_id', 'left');
            $this->db->group_by('distribute_detail.pod_id, distribute_detail.sz_id');
    		$this->db->order_by('distribute_detail.sz_id');
    		$rs = $this->db->get_where('distribute_detail', array('distribute_detail.e_id' => $e_i))->result(); 
    	    
    	}

        foreach($rs as $r) {

// 			echo '<pre>', print_r($r), '</pre>'; die;        	

            $this->db->select('payment_status, payment_date, checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.terms, checkin_detail.remarks, checkin_detail.rate_per_bag, checkin_detail.todays_payable, checkin_detail.due_amount, checkin_detail.net_payable, checkin_detail.status, checkin_detail.user_id, DATE_FORMAT(checkin_detail.create_date, "%d-%M-%Y") as receive_date, checkin_detail.modify_date, employees.e_code, employees.name, employees.emp_adhar_card_number, employees.rate_per_bag, sizes.size, sizes.rate_per_unit, check_in.checkin_number, check_in.checkin_date');
    
    		$this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
    		$this->db->join('check_in', 'check_in.checkin_id = checkin_detail.checkin_id', 'left');
    		$this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
    		//$this->db->order_by('checkin_detail.e_id');
    		$this->db->order_by('checkin_detail.sz_id');
	        $received_rs = $this->db->get_where('checkin_detail', array('checkin_detail.e_id' => $r->e_id, 'checkin_detail.pod_id' => $r->pod_id, 'checkin_detail.sz_id' => $r->sz_id))->result();

    		if($r->roll_handel = 0) {
    			$roll_handel = 'Roll';
    		} else {
    			$roll_handel = 'Handel';
    		}

            $nw_rcvd_qnty = 0;
		    $received_date = array();
		    $received_qnty = array();
		    $received_no = array();
		    $nw_rcvd_qnty_paid = 0;
		    $payment_stat = '';
		    $nw_rcvd_qnty_unpaid = 0;
		    $payment_statuss = array();

    		if(count($received_rs) > 0) {
    		    
    		    foreach($received_rs as $r_r) {
    		       // echo '<pre>',print_r($r_r),'</pre>';die;
    		      if($r_r->payment_status == 1){
    	            $received = "<span style='color: green;'>". $r_r->checkin_number." (". date('d-M-Y', strtotime($r_r->checkin_date)) .")</span>"; # OBSERVATION-> SINGLE ENTRY FOR SINGLE EMPLOYEE AND CHECKIN ID    
    	          }else{
    	            $received = '<span style="color: red;">'.$r_r->checkin_number." (". date('d-M-Y', strtotime($r_r->checkin_date)) .')</span>';  
    	          }
    	          array_push($received_no, $received);
    	          
    	          if($r_r->payment_status == 1){
    	            $received_da = "<span style='color: green;'>". $r_r->receive_date ."</span>"; # OBSERVATION-> SINGLE ENTRY FOR SINGLE EMPLOYEE AND CHECKIN ID    
    	          }else{
    	            $received_da = '<span style="color: red;">'.$r_r->receive_date .'</span>';  
    	          }
    	          
    	          array_push($received_date, $received_da);
    	          
    	          if($r_r->payment_status == 1){
    	            $received_qn = "<span style='color: green;'>". $r_r->received_quantity ."</span>"; # OBSERVATION-> SINGLE ENTRY FOR SINGLE EMPLOYEE AND CHECKIN ID    
    	          }else{
    	            $received_qn = '<span style="color: red;">'. $r_r->received_quantity .'</span>';  
    	          }
    	          
    	          array_push($received_qnty, $received_qn);
    	          
    	          $nw_rcvd_qnty += $r_r->received_quantity;
    	          
    	          if($r_r->payment_status == 1){
    	            $payment_stat = "<span style='color: green;'>Paid on (". date('d-M-Y', strtotime($r_r->payment_date)) .")</span>"; # OBSERVATION-> SINGLE ENTRY FOR SINGLE EMPLOYEE AND CHECKIN ID    
    	          }else{
    	            $payment_stat = '<span style="color: red;">Not Paid</span>';  
    	          }
    	          
    	          array_push($payment_statuss, $payment_stat);
    	          
    	          if($r_r->payment_status == 1) {
    	            $nw_rcvd_qnty_paid += $r_r->received_quantity;    
    	          }else{
    	            $nw_rcvd_qnty_unpaid += $r_r->received_quantity;  
    	          }
    	          
    	        }
    			if($nw_rcvd_qnty > $r->distribute_pod_quantity) {
    				$extra_due = ($nw_rcvd_qnty - $r->distribute_pod_quantity)." (Extra)";
    			} else {
    				$extra_due = ($r->distribute_pod_quantity - $nw_rcvd_qnty)." (Due)";
    			}
    			
    		} else {
    			$extra_due = '';
    			$nw_rcvd_qnty += 0;
    			$nw_rcvd_qnty_paid += 0;
    			$nw_rcvd_qnty_unpaid += 0;
    		}

    		$arr = array(
                 'employee_name' => $r->name,
                 'category' => $r->category_name,
                 'consignment_no' => $r->consignement_number,
                 'roll_handel' => $roll_handel,
                 'size' => $r->size,
                 'unit' => $r->unit,
                 'distributed_date' => $r->prod_detail_date,
                 'distributed_qnty' => $r->distribute_pod_quantity,
                 'checkin_no' => $received_no,
                 'po_number' => $r->po_number,
                 'received_date' => $received_date,
                 'received_qnty' => $received_qnty,
                 'new_received_qnty' => $nw_rcvd_qnty,
                 'extra_due' => $extra_due,
                 'nw_rcvd_qnty_paid' => $nw_rcvd_qnty_paid,
                 'nw_rcvd_qnty_unpaid' => $nw_rcvd_qnty_unpaid,
                 'employee_id' => $r->e_id,
                 'payment' => $payment_statuss
    		);

		    array_push($new_array, $arr);

        }
        
        return $new_array;

    }
    
    public function wastage_report() {
    	$data = array();
		$data["start_date"] = date('Y-m-d');
		$data["end_date"] = date('Y-m-d');
		
		$this->db->select('po_id, po_number');
		$rs_po_num = $this->db->get_where('purchase_order', array('purchase_order.status' => 1))->result();
		$data["purchase_order"] = $rs_po_num;
		$rs_po_num1 = $this->db->get_where('purchase_order_details', array('purchase_order_details.status' => 1))->result();
		$data["purchase_order_detail"] = $rs_po_num1;
		$data["po_id"] = 0;
		$data["show_or_not"] = 0;

		if($this->input->post()) {

        $start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$po_id = $this->input->post('po_id');
		$pod_id = $this->input->post('pod_id');
		
		$data = array();
		$data["start_date"] = $start_date;
		$data["end_date"] = $end_date;
		$data["po_id"] = $po_id;
		$data["pod_id"] = $pod_id;
		
		if($start_date != '' && $end_date != '') {
		
		$start_date1 = $start_date.' 00:00:01';
		$end_date1 = $end_date.' 23:59:59';

	} else {

   $start_date1 = '';
   $end_date1 = '';

	}
		$table_datas = array();
		$result_data = array();
        
		foreach($pod_id as $p_i) {

		$data['result'][] = $this->_custom_generate_wastage_report($p_i, $start_date1, $end_date1);

	    }

		$data['segment'] = 'wastage_report';
		
		/********************************* Main Part ******************************************/
		
		//new section 21-07-2021//

	    				// echo '<pre>', print_r($data), '</pre>'; die();		

		return array('page'=>'reports/common_print_v', 'data'=>$data);
         
	    }

        return array('page'=>'reports/wastage_report_v', 'data'=>$data);  

        }

        public function _custom_generate_wastage_report($p_i, $start_date1, $end_date1){

    	if(empty($p_i)){
            die('No details found');
        } else {

        	$new_array = array();

		if($start_date1 != '' && $end_date1 != '') {

		 $r = $this->db->join('purchase_order_details','purchase_order_details.pod_id = production.pod_id', 'left')
		->join('purchase_order','purchase_order.po_id = purchase_order_details.po_id', 'left')
		->get_where('production', array('production.pod_id' => $p_i, 'production.create_date >=' => $start_date1, 'production.create_date <=' => $end_date1))->row();
	} else {

		 $r = $this->db->join('purchase_order_details','purchase_order_details.pod_id = production.pod_id', 'left')
		->join('purchase_order','purchase_order.po_id = purchase_order_details.po_id', 'left')
		->get_where('production', array('production.pod_id' => $p_i))->row();
	}

	if(count($r) > 0) {

	if($r->roll_handel = 0) {
			$roll_handel = 'Roll';
		} else {
			$roll_handel = 'Handel';
		}

		$arr = array(
             'consignment_no' => $r->consignement_number,
             'roll_handel' => $roll_handel,
             'size' => $r->size_in_text,
             'unit' => $r->rate_per_unit,
             'production_no' => $r->prod_number,
             'production_date' => $r->prod_date,
             'total_roll_weight' => $r->total_roll_weight,
             'expected_production' => $r->expected_production,
             'bag_produced' => $r->prod_bag_produced,
             'wastage_pcs' => $r->prod_wastage_pcs,
             'wastage_gm' => $r->prod_wastage_kg   
		);

		array_push($new_array, $arr);

	}

	return $new_array;

}

}

public function order_details_report() {
    	$data = array();
		$data["start_date"] = date('Y-m-d');
		$data["end_date"] = date('Y-m-d');

		if($this->input->post()) {
 
	        if($this->input->post('pod_id') != '') {
				$size_id = implode(",",$this->input->post('pod_id'));
			} else {
	            $size_id ='';
			}

	        $start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');

			if($start_date != '' && $end_date != '') {
			
			$start_date1 = $start_date.' 00:00:01';
			$end_date1 = $end_date.' 23:59:59';

			} else {

			   $start_date1 = '';
			   $end_date1 = '';

			}

			$data = array();
			$data["start_date1"] = $start_date1;
			$data["end_date1"] = $end_date1;
			
			
			$table_datas = array();
			$result_data = array();
        
			$data['result'] = $this->_custom_generate_order_details_report($size_id, $start_date1, $end_date1);

			$data['segment'] = 'order_details';

			// echo '<pre>', print_r($data['result']), '</pre>'; die();		

			return array('page'=>'reports/common_print_v', 'data'=>$data);
         
	    }

	    $data["product_category"] = $this->db->get_where('product_category', array('status' => 1))->result();

	    $data['size_details'] = $this->db->get('sizes')->result();

        return array('page'=>'reports/order_details_v', 'data'=>$data);  

        }

        public function _custom_generate_order_details_report($size_id, $start_date1, $end_date1) {

        	$array_final = array();
        	$po_id_store_array = array();
        	$po_number = '';
        	$po_date = '';
        	$order_detail_date = '';
        	$order_detail_number_cn = '';
        	$order_detail_quantity_cn = '';
        	$order_detail_quantity_total_cn = 0;
        	$order_detail_received_quantity = '';
        	$order_detail_received_quantity_total = 0;
        	$order_detail_finished_stock = 0;
        	$order_details_invoice_date = '';
        	$order_details_accn_name = '';
        	$order_details_invoice_number = '';
        	$order_details_invoice_quantities = '';
        	$order_details_invoice_quantities_total = 0;
        	$order_details_invoice_quantities_balance = 0;
        	$order_details_invoice_quantities_total1 = 0;

		     if($size_id == '') {
		         $res = $this->db->get_where('purchase_order_details', array('purchase_order_details.create_date >=' => $start_date1, 'purchase_order_details.create_date <=' => $end_date1))->result();
		     } else {
		     	$query = "SELECT
		            purchase_order_details.*, `distribute_detail`.sz_id   
		        FROM
		            `purchase_order_details`
				LEFT JOIN distribute_detail ON 
					`purchase_order_details`.pod_id = `distribute_detail`.pod_id
		        WHERE
		             `distribute_detail`.sz_id IN ($size_id)";
		           $res = $this->db->query($query)->result();
		     }

		    // echo '<pre>', print_r($res),'</pre>';die;

		if(!empty($res)){

			foreach($res as $r) {
           $po_id_store_array[] = $r->pod_id;
         }
         $all_id = implode(",", $po_id_store_array);

         // print_r($all_id);die;

         if($size_id == '') {
		         $query = "SELECT
            production_detail.*,
            purchase_order.po_number,
            purchase_order.po_date,
            sizes.size   
        FROM
            `purchase_order_details`
        LEFT JOIN purchase_order ON purchase_order_details.po_id = purchase_order.po_id
        LEFT JOIN production_detail ON purchase_order_details.pod_id = production_detail.pod_id
        LEFT JOIN sizes ON production_detail.sz_id = sizes.sz_id
        WHERE
             `purchase_order_details`.`pod_id` IN($all_id)
        GROUP BY
              `production_detail`.`sz_id`";
		     } else {
		     	$query = "SELECT
            production_detail.*,
            purchase_order.po_number,
            purchase_order.po_date,
            sizes.size   
        FROM
            `purchase_order_details`
        LEFT JOIN purchase_order ON purchase_order_details.po_id = purchase_order.po_id
        LEFT JOIN production_detail ON purchase_order_details.pod_id = production_detail.pod_id
        LEFT JOIN sizes ON production_detail.sz_id = sizes.sz_id
        WHERE
             `purchase_order_details`.`pod_id` IN($all_id) AND `production_detail`.sz_id IN ($size_id)
        GROUP BY
              `production_detail`.`sz_id`";
		     }

           $res_get_size = $this->db->query($query)->result();

           // echo '<pre>', print_r($res_get_size), '</pre>'; die();
           // echo $this->db->last_query(); die();

        foreach($res_get_size as $r_g_s) {

        	if($r_g_s->sz_id == '') {
        		continue;
        	}

            $po_number = '';
        	$po_date = '';
        	$order_detail_date = '';
        	$order_detail_number_cn = '';
        	$order_detail_quantity_cn = '';
        	$order_detail_quantity_total_cn = 0;
        	$order_detail_received_quantity = '';
        	$order_detail_received_quantity_total = 0;
        	$order_detail_finished_stock = 0;
        	$order_date = '';
        	$order_name = '';
        	$order_quantity = '';
        	$order_quantity_total = '';
        	$order_details_invoice_date = '';
        	$order_details_accn_name = '';
        	$order_details_invoice_number = '';
        	$order_details_invoice_quantities = '';
        	$order_details_invoice_quantities_total = 0;
        	$order_details_invoice_quantities_balance = 0;

        	$query = "SELECT *,
            DATE_FORMAT( purchase_order_details.create_date, '%d-%m-%Y' ) as purchase_order_details_date,
           DATE_FORMAT(purchase_order.po_date, '%d-%m-%Y' ) as po_date,
            sizes.size,
            SUM(production_detail.prod_detail_quantity) as prod_detail_quantity   
	        FROM
	            `purchase_order_details`
	        LEFT JOIN purchase_order ON purchase_order_details.po_id = purchase_order.po_id
	        LEFT JOIN production_detail ON purchase_order_details.pod_id = production_detail.pod_id
	        LEFT JOIN sizes ON production_detail.sz_id = sizes.sz_id
	        WHERE
	             `production_detail`.`pod_id` IN($all_id) AND `production_detail`.`sz_id` = $r_g_s->sz_id
             GROUP BY purchase_order_details.pod_id";

           $res_get_size_po_id = $this->db->query($query)->result();

           $order_detail_received_quantity = '';
           $order_detail_received_quantity_total = 0;

         foreach($res_get_size_po_id as $r_g_s_p_i) {
         	$po_number .= $r_g_s_p_i->po_number."<br/>";
         	$po_date .= $r_g_s_p_i->po_date."<br/>";
         	$order_detail_date .= $r_g_s_p_i->purchase_order_details_date."<br/>";
         	$order_detail_number_cn .= $r_g_s_p_i->consignement_number.'/'.$r_g_s_p_i->size_in_text."<br/>";
         	$order_detail_quantity_cn .= $r_g_s_p_i->prod_detail_quantity."<br/>";
         	$order_detail_quantity_total_cn += $r_g_s_p_i->prod_detail_quantity;

            $rcvd_row_details = $this->db
            	->select('checkin_detail.*, SUM(checkin_detail.received_quantity) as received_quantity')
				->get_where('checkin_detail', array('sz_id' => $r_g_s->sz_id, 'pod_id' => $r_g_s_p_i->pod_id))
				->row();

				if(count($rcvd_row_details) > 0) {

					$order_detail_received_quantity .= $rcvd_row_details->received_quantity."<br/>";
					$order_detail_received_quantity_total += $rcvd_row_details->received_quantity;

				} else {
					$order_detail_received_quantity .= ' '."<br/>";
					$order_detail_received_quantity_total += 0;
				}

         }

		$order_detail_finished_stock = ($order_detail_quantity_total_cn - $order_detail_received_quantity_total);

		// $this->db->query("SET sql_mode=''");

		$customer_order_row_details = $this->db
			->select('customer_order.co_no, SUM(customer_order_dtl.cus_order_quantity) AS delivered_quantity, DATE_FORMAT(customer_order.co_date, "%d-%m-%Y" ) as co_row_create_date')
			->join('customer_order','customer_order.co_id = customer_order_dtl.co_id', 'left')
			->group_by('customer_order_dtl.co_id')
			->get_where('customer_order_dtl', array('customer_order_dtl.sz_id' => $r_g_s->sz_id, 'customer_order.create_date >=' => $start_date1, 'customer_order.create_date <=' => $end_date1))
			->result();

		// echo $this->db->last_query(); die;			

		// echo '<pre>',print_r($customer_order_row_details), '<pre>';	

		foreach($customer_order_row_details as $co_r_d) {         	
         	$order_date .= $co_r_d->co_row_create_date."<br/>";
         	$order_name .= $co_r_d->co_no."<br/>";
         	$order_quantity .= $co_r_d->delivered_quantity."<br/>";
         	$order_quantity_total += $co_r_d->delivered_quantity;
        }	

		$invc_row_details = $this->db
			->select('*, SUM(customer_invoice_detail.delivered_quantity) AS delivered_quantity, DATE_FORMAT(customer_invoice.create_date, "%d-%m-%Y" ) as invoice_row_create_date')
			->join('customer_invoice','customer_invoice.cus_inv_id = customer_invoice_detail.cus_inv_id', 'left')
			->join('acc_master','acc_master.am_id = customer_invoice.am_id', 'left')
			->group_by('customer_invoice_detail.cus_inv_id')
			->get_where('customer_invoice_detail', array('customer_invoice_detail.sz_id' => $r_g_s->sz_id, 'customer_invoice.create_date >=' => $start_date1, 'customer_invoice.create_date <=' => $end_date1))
			->result();

         foreach($invc_row_details as $i_r_d) {         	
         	$order_details_invoice_date .= $i_r_d->invoice_row_create_date."<br/>";
         	$order_details_accn_name .= $i_r_d->name."<br/>";
         	$order_details_invoice_number .= $i_r_d->cus_inv_number."<br/>";
         	$order_details_invoice_quantities .= $i_r_d->delivered_quantity."<br/>";
         	$order_details_invoice_quantities_total += $i_r_d->delivered_quantity;
         	$order_details_invoice_quantities_total1 += $i_r_d->delivered_quantity;
         }

    	$order_details_invoice_quantities_balance=($order_detail_received_quantity_total - $order_details_invoice_quantities_total);

         $arr = array(
           'size' => $r_g_s->size,
           'po_date' => $po_date,
           'po_number' => $po_number,
           'order_detail_date' => $order_detail_date,
           'order_detail_number_cn' => $order_detail_number_cn,
           'order_detail_quantity_cn' => $order_detail_quantity_cn,
           'order_detail_quantity_total_cn' => $order_detail_quantity_total_cn,
           'order_detail_received_quantity' => $order_detail_received_quantity,
           'order_detail_received_quantity_total' => $order_detail_received_quantity_total,
           'order_detail_finished_stock' => $order_detail_finished_stock,
           'order_date' => $order_date,
           'order_name' => $order_name,
           'order_quantity' => $order_quantity,
           'order_quantity_total' => $order_quantity_total,
           'order_details_invoice_date' => $order_details_invoice_date,
           'order_details_accn_name' => $order_details_accn_name,
           'order_details_invoice_number' => $order_details_invoice_number,
           'order_details_invoice_quantities' => $order_details_invoice_quantities,
           'order_details_invoice_quantities_total' => $order_details_invoice_quantities_total1,
           'order_details_invoice_quantities_balance' => $order_details_invoice_quantities_balance,
         );

         array_push($array_final, $arr);

         }

		}     
         // echo '<pre>', print_r($array_final), '</pre>'; die();

          return $array_final;

        }
	

    public function product_sizes_on_category(){
    	$pc_id = $this->input->post('pc_id');    	
    	return $this->db->get_where('sizes', array('pc_id' => $pc_id))->result();
    }   

}//end ctrl