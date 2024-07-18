<?php

class Report_po_ledger extends My_Controller {

    private $user_type = null;

    public function __construct() {
        parent::__construct();

        $this->load->library('grocery_CRUD');

        if($this->session->has_userdata('user_id')) { //if logged-in
            $this->user_type = $this->session->usertype;
        }
    }

    public function index() {
        redirect(base_url('admin/report-po-ledger'));
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
	
	public function generate_po_ledger_init() {
        if($this->check_permission(array(1)) == true) {
			$this->load->model('Report_po_ledger_m');
            if($this->input->post('print') == 'Generate Report'){
           $data = $this->Report_po_ledger_m->generate_po_ledger();
            $this->load->view('reports/common_print_v', $data);

        } else {
            $data['po_report'] = $this->Report_po_ledger_m->generate_po_ledger_init();		
            $this->load->view('reports/report_po_ledger_v', $data); 
            }          
        }
    }

    public function generate_po_ledger() {
        if($this->check_permission(array(1)) == true) {
            $this->load->model('Report_po_ledger_m');
                $data['po_report'] = $this->Report_po_ledger_m->generate_po_ledger();
            $this->load->view('reports/common_print_v', $data);            
        }
    }
	
	 public function all_cn_list(){

        if($this->check_permission() == true) {

            $this->load->model('Report_po_ledger_m');

            $data = $this->Report_po_ledger_m->all_cn_list();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }	
	
	public function invoice_details_report() {
        if($this->check_permission(array(1)) == true) {
            $this->load->model('Report_po_ledger_m');
            $data['invoice_details_report'] = $this->Report_po_ledger_m->invoice_details_report();
            $data['segment'] = 'invoice_details_report';
            $this->load->view('reports/common_print_v', $data);            
        }
    }
	
}//end ctrl

?>