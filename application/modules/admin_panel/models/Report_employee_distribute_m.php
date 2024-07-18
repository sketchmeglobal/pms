<?php

class Report_employee_distribute_m extends CI_Model {

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
		
		$this->db->select('distribute_detail.dis_detail_id, distribute_detail.dis_id, distribute_detail.e_id, distribute_detail.pod_id, distribute_detail.roll_handel, distribute_detail.sz_id, distribute_detail.u_id, distribute_detail.distribute_pod_quantity, distribute_detail.remarks, distribute_detail.terms, distribute_detail.status, distribute_detail.user_id, distribute_detail.create_date, distribute_detail.modify_date, employees.e_code, employees.name, employees.emp_adhar_card_number, employees.rate_per_bag, sizes.size, sizes.rate_per_unit, purchase_order_details.consignement_number, units.unit');
		
		$this->db->join('employees', 'employees.e_id = distribute_detail.e_id', 'left');
		$this->db->join('sizes', 'sizes.sz_id = distribute_detail.sz_id', 'left');
		$this->db->join('purchase_order_details', 'purchase_order_details.pod_id = distribute_detail.pod_id', 'left');
		$this->db->join('units', 'units.u_id = distribute_detail.u_id', 'left');

		$this->db->order_by('distribute_detail.sz_id');
		$rs = $this->db->get_where('distribute_detail', array('distribute_detail.e_id' => $e_id, 'distribute_detail.create_date >=' => $start_date1, 'distribute_detail.create_date <=' => $end_date1))->result();
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
	
	

}//end ctrl