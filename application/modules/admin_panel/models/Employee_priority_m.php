<?php

class Employee_priority_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function employee_priority_report() {
		$data = array();
		
		$this->db->select('employees.e_id, employees.e_code, employees.name, employees.emp_adhar_card_number, employees.due_amount');
		//$this->db->join('distribute_detail', 'distribute_detail.e_id = employees.e_id', 'left');
		//$this->db->join('checkin_detail', 'checkin_detail.e_id = employees.e_id', 'left');
		$rs_emp = $this->db->get_where('employees', array('employees.status' => 1))->result();
		
		
		for($i = 0; $i < sizeof($rs_emp); $i++){
			$e_id = $rs_emp[$i]->e_id;
			
			$total_distribute_pod_quantity = $this->db->select('SUM(distribute_pod_quantity) as total_distribute_pod_quantity')->get_where('distribute_detail', array('e_id' => $e_id))->result()[0]->total_distribute_pod_quantity;
			if($total_distribute_pod_quantity > 0){
				$rs_emp[$i]->total_distribute_pod_quantity = $total_distribute_pod_quantity;
			}else{
				$rs_emp[$i]->total_distribute_pod_quantity = 0;
			}
			
			$total_received_quantity = $this->db->select('SUM(received_quantity) as total_received_quantity')->get_where('checkin_detail', array('e_id' => $e_id))->result()[0]->total_received_quantity;
			if($total_received_quantity > 0){
				$rs_emp[$i]->total_received_quantity = $total_received_quantity;
			}else{
				$rs_emp[$i]->total_received_quantity = 0;
			}
			
			$remain_to_receive = $total_distribute_pod_quantity - $total_received_quantity;
			if($remain_to_receive > 0){
				$rs_emp[$i]->remain_to_receive = $remain_to_receive;
			}else{
				$rs_emp[$i]->remain_to_receive = 0;
			}
			}
		//echo $this->db->last_query();die;
		$data["employee_priority_list"] = $rs_emp;

/*, SUM(distribute_detail.distribute_pod_quantity) as total_distribute_pod_quantity, SUM(checkin_detail.received_quantity) as total_received_quantity*/
        return $data;
    }
	

	public function employee_list()	{		
		return $this->db->get_where('employees', array('employees.status' => 1))->result();
	}

	public function employee_payment($e_id)	{		
		
// 		$rs['payment_details'] = $this->db
// 			->select('checkin_detail.checkin_detail_id, employees.name, employees.e_code,sizes.size, sizes.product_name, sizes.rate_per_unit, 
            // purchase_order.po_number, checkin_detail.received_quantity, checkin_detail.rate_per_bag, checkin_detail.todays_payable, checkin_detail.create_date, 
            // payment_status, payment_date, employee_payment_rate_per_unit')
// 			->join('employees','employees.e_id = checkin_detail.e_id','left')
// 			->join('sizes','sizes.sz_id = checkin_detail.sz_id','left')
// 			->join('purchase_order_details','purchase_order_details.pod_id = checkin_detail.pod_id','left')
// 			->join('purchase_order','purchase_order.po_id = purchase_order_details.po_id','left')
// 			->order_by('checkin_detail.sz_id, checkin_detail.checkin_detail_id')
// 			->get_where('checkin_detail', array('checkin_detail.e_id' => $e_id))->result();

// 		$rs['total_amount'] = $this->db->select_sum('todays_payable')
// 			->group_by('e_id')
// 			->get_where('checkin_detail', array('e_id' => $e_id))->result();

// 		$rs['total_paid'] = $this->db->select_sum('todays_payable')
// 			->group_by('e_id')
// 			->get_where('checkin_detail', array('e_id' => $e_id, 'payment_status' => 1))->result();	

        $new_array[] ='';
        $this->db->select('*');
            
        $this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
    	$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
    	$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
    	$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');
    	$this->db->join('production_detail', 'production_detail.prod_detail_id = distribute_detail.prod_detail_id', 'left');
    	$this->db->join('product_category', 'product_category.pc_id = production_detail.pc_id', 'left');
        $this->db->group_by('distribute_detail.pod_id, distribute_detail.sz_id');
    	$this->db->order_by('distribute_detail.sz_id');
        $rs = $this->db->get_where('distribute_detail', array('distribute_detail.e_id' => $e_id))->result(); 

        foreach($rs as $r) {           

            $this->db->select('checkin_detail.checkin_detail_id, checkin_detail.checkin_id, checkin_detail.e_id, checkin_detail.pod_id, checkin_detail.roll_handel, 
            checkin_detail.sz_id, checkin_detail.u_id, checkin_detail.received_quantity, checkin_detail.terms, checkin_detail.remarks, checkin_detail.rate_per_bag, 
            checkin_detail.todays_payable, checkin_detail.due_amount, checkin_detail.net_payable, checkin_detail.status, checkin_detail.user_id, 
            DATE_FORMAT(checkin_detail.create_date, "%d-%M-%Y") as receive_date, checkin_detail.modify_date, employees.e_code, employees.name, 
            employees.emp_adhar_card_number, employees.rate_per_bag, sizes.size, sizes.product_name, sizes.rate_per_unit, check_in.checkin_number, 
            check_in.checkin_date,payment_status, payment_date, employee_payment_rate_per_unit');
    
            $this->db->join('employees', 'employees.e_id = checkin_detail.e_id', 'left');
            $this->db->join('check_in', 'check_in.checkin_id = checkin_detail.checkin_id', 'left');
            $this->db->join('sizes', 'sizes.sz_id = checkin_detail.sz_id', 'left');
            //$this->db->order_by('checkin_detail.e_id');
            $this->db->order_by('checkin_detail.sz_id');
            
            if($this->input->post('start_date') != ''){
                $start_date = $this->input->post('start_date');
                $this->db->where('payment_date >=', $start_date);
            }
            
            if($this->input->post('end_date') != ''){
                $end_date = $this->input->post('end_date');
                $this->db->where('payment_date <=', $end_date);
            }
            
            if($this->input->post('unpaid') != ''){
                $this->db->where('payment_status', '0');
            }
            
            $received_rs = $this->db->get_where('checkin_detail', array('checkin_detail.e_id' => $r->e_id, 'checkin_detail.pod_id' => $r->pod_id, 'checkin_detail.sz_id' => $r->sz_id))->result();

            // echo $this->db->last_query(); die;
            // echo '<pre>', print_r($received_rs), '</pre>'; die; 

            if($r->roll_handel = 0) {
                $roll_handel = 'Roll';
            } else {
                $roll_handel = 'Handel';
            }

            $nw_rcvd_qnty = 0;
            $received_date = '';
            $received_qnty = '';
            $received_no = '';

            if(count($received_rs) > 0) {
                // echo '<pre>', print_r($received_rs), '</pre>'; die('dead');
                foreach($received_rs as $r_r) {
                   // echo '<pre>',print_r($r_r),'</pre>';die;
                   
                   $pod_id = $r_r->pod_id;
                   $query = "SELECT `purchase_order`.po_number FROM `purchase_order_details` LEFT JOIN `purchase_order` ON `purchase_order_details`.`po_id` = `purchase_order`.`po_id` WHERE pod_id =" . $pod_id;
                   $po_name = $this->db->query($query)->result();
                   $po_number = $po_name[0]->po_number;
                   
                  $received_no .= $r_r->checkin_number." (". date('d-M-Y', strtotime($r_r->checkin_date)) .")<br/>";
                  $received_date .= $r_r->receive_date."<br/>";
                  $received_qnty .= $r_r->received_quantity."<br/>";
                  $nw_rcvd_qnty += $r_r->received_quantity;
                }
                if($nw_rcvd_qnty > $r->distribute_pod_quantity) {
                    $extra_due = ($nw_rcvd_qnty - $r->distribute_pod_quantity)." (Extra)";
                } else {
                    $extra_due = ($r->distribute_pod_quantity - $nw_rcvd_qnty)." (Due)";
                }
                
                $arr = array(
                
                 'checkin_detail_id' => $r_r->checkin_detail_id,
                 'name' => $r->name,
                 'po_number' => $po_number,
                 'category' => $r->category_name,
                 'consignment_no' => $r->consignement_number,
                 'roll_handel' => $roll_handel,
                 'size' => $r->size,
                 'unit' => $r->unit,
                 'distributed_date' => $r->create_date,
                 'distributed_qnty' => $r->distribute_pod_quantity,
                 'checkin_no' => $received_no,
                 'received_date' => $received_date,
                 'received_quantity' => $received_qnty,
                 'new_received_qnty' => $nw_rcvd_qnty,
                 'extra_due' => $extra_due,
                 'employee_id' => $r->e_id, 
                 'payment_status' => $r_r->payment_status, 
                 'payment_date' => $r_r->payment_date,
                 'employee_payment_rate_per_unit' => $r_r->employee_payment_rate_per_unit
            );

            array_push($new_array, $arr);
                
            }

        } 
    
        // $new_array = array_filter($new_array, 'strlen');
        return $new_array;

	}

	public function update_payment_status(){
		
		$cdi = $this->input->post('checkin_detail_id');
		$pay = $this->input->post('pay');
		$type = $this->input->post('type');

        // print_r($cdi); die;

		if($pay == 'pay'){
			$updateArray = array(			
				'payment_status' => 1
			);
		}else{
			$updateArray = array(			
				'payment_status' => 0
			);
		}
        
        if($type == 'single'){
            
		    $this->db->update('checkin_detail', $updateArray, array('checkin_detail_id' => $cdi));
		    
        }else{
            
            $query = "UPDATE checkin_detail SET `payment_status` = 1 WHERE checkin_detail_id IN (".$cdi.")";
            $this->db->query($query);
        
        }
		$data['status'] = 'success';
		$data['msg'] = "Payment Status Successfully Updated";
		
		return $data;
	}

}//end ctrl