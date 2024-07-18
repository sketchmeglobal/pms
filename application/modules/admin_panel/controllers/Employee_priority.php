<?php

class Employee_priority extends My_Controller {

    private $user_type = null;

    public function __construct() {
        parent::__construct();

        $this->load->library('grocery_CRUD');

        if($this->session->has_userdata('user_id')) { //if logged-in
            $this->user_type = $this->session->usertype;
        }
    }

    public function index() {
        redirect(base_url('admin/employee-priority'));
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
	
	public function employee_priority() {
        if($this->check_permission(array(1)) == true) {
			$this->load->model('Employee_priority_m');
			
			$data['employee_priority'] = $this->Employee_priority_m->employee_priority_report();
			
            $this->load->view('reports/employee_priority_v', $data);            
        }
    }

    public function employee_payment() {
        if($this->check_permission(array(1)) == true) {
            
            $this->load->model('Employee_priority_m');  
            $data['post_return'] = false;
            $data['employee_list'] = $this->Employee_priority_m->employee_list();
            $data['employee_payment'] = array();
            
            $e_id = $this->input->post("e_id");

            if($e_id){
                $data['employee_payment'] = $this->Employee_priority_m->employee_payment($e_id);
                $data['post_return'] = true;
            }
            
            $this->load->view('payment/employee_payment', $data);            

        }
    }

    public function update_payment_status(){

        if($this->check_permission() == true) {

            $this->load->model('Employee_priority_m');
            $data = $this->Employee_priority_m->update_payment_status();
            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
            exit();
            
        }     
    }
    
    public function employee_attendance() {
        if($this->check_permission(array(1)) == true) {
            
            try{
                $crud = new grocery_CRUD();
                $crud->set_crud_url_path(base_url('admin_panel/Employee_priority/employee_attendance'));
                $crud->set_theme('datatables');
                $crud->set_subject('Employee Attendace');
                $crud->set_table('employee_attendance');
                $crud->unset_add();
                $crud->unset_clone();
    
                // permission setter 
                $this->table_name = 'employee_attendance';
                // $crud->callback_before_update(array($this,'log_before_update'));
    
                $crud->columns('eid','upload_picture','comment','phase','created_date','status');
                $crud->fields('comment','phase','status');
                
                $crud->required_fields('eid','upload_picture','today','status');
                $crud->unique_fields(array('upload_picture'));
    
                $crud->set_relation('eid','employees','name');
                $crud->set_field_upload('upload_picture', 'assets/attendance', 'jpg|jpeg|png|bpm');
    
                $crud->display_as('eid','Name');
                $crud->display_as('upload_picture','Picture');
                $crud->display_as('created_date','Date & Time');
    
                $output = $crud->render();
                //rending extra value to $output
                $output->tab_title = 'Employee Attendance';
                $output->section_heading = 'Employee Attendance<small>(Add / Edit / Delete)</small>';
                $output->menu_name = 'Virtual Attendance';
                $output->add_button = '';
    
                $this->load->view('common_v', $output); //loading common view page
            } catch(Exception $e) {
                show_error($e->getMessage().'<br>'.$e->getTraceAsString());
            }
            

        }
    }
	
	
}//end ctrl

?>