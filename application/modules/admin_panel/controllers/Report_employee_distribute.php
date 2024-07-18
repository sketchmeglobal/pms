<?php

class Report_employee_distribute extends My_Controller {

    private $user_type = null;

    public function __construct() {
        parent::__construct();

        $this->load->library('grocery_CRUD');

        if($this->session->has_userdata('user_id')) { //if logged-in
            $this->user_type = $this->session->usertype;
        }
    }

    public function index() {
        redirect(base_url('admin/report-employee-distribute'));
    }

    public function check_permission($auth_usertype = array()) {
        //if not logged-in
        if($this->user_type == null) {
            $this->session->set_flashdata('title', 'Log-in!');
            $this->session->set_flashdata('msg', 'Kindly log-in to access that page.');
            redirect(base_url('admin'));
        }

        //if no special permission required (should be logged-in only)
        if(count($auth_usertype) == 0) {
            return true;
        }

        if(in_array($this->user_type, $auth_usertype)) {
            return true;
        } else {
            $this->session->set_flashdata('title', 'Prohibited!');
            $this->session->set_flashdata('msg', 'You do not have permission to access that page, kindly contact Administrator.');
            redirect(base_url('admin/dashboard'));
        }
    }
	
	public function report_employee_distribute() {
        if($this->check_permission(array(1)) == true) {
			$this->load->model('Report_employee_distribute_m');
			if($this->input->post()){
            	$data['employee_report'] = $this->Report_employee_distribute_m->generate_employee_report();				
			}else{
				$data['employee_report'] = $this->Report_employee_distribute_m->generate_employee_init();
			}
            $this->load->view('reports/report_employee_distribute_v', $data);            
        }
    }	
	
	
}//end ctrl

?>