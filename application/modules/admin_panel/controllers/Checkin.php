<?php

class Checkin extends My_Controller {



    private $user_type = null;



    public function __construct() {

        parent::__construct();



        $this->load->library('grocery_CRUD');



        if($this->session->has_userdata('user_id')) { //if logged-in

            $this->user_type = $this->session->usertype;

        }

    }



    public function index() {

        redirect(base_url('admin/checkin'));

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



    // ---------------------------------- LIST ------------------------------------



    public function checkin($po_id) {

        if($this->check_permission(array(1)) == true) {

            $this->load->model('Checkin_m');
            $data = $this->Checkin_m->checkin($po_id);
            $this->load->view($data['page'], $data['data']);

        }

    }



    // -------------------------  ADD STARTS -----------------------------



    public function ajax_checkin_table_data(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->ajax_checkin_table_data();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function add_checkin() {

        if($this->check_permission(array(1)) == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->add_checkin();

            $this->load->view($data['page'], $data['data']);

        }

    }



    public function ajax_unique_checkin_number(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->ajax_unique_checkin_number();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function form_add_checkin(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->form_add_checkin();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function form_add_checkin_details(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->form_add_checkin_details();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }

    // ------------------------------- ADD ENDS ---------------------------

    // ------------------------ EDIT STARTS ----------------------------------



    public function edit_checkin($checkin_id) {

        if($this->check_permission(array(1)) == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->edit_checkin($checkin_id);

            $this->load->view($data['page'], $data['data']);

        }

    }



    public function form_edit_checkin(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->form_edit_checkin();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function ajax_checkin_details_table_data(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->ajax_checkin_details_table_data();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function all_details_in_purchase_order(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->all_details_in_purchase_order();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }
	
	public function all_get_details_from_distribute_detail_table(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->all_get_details_from_distribute_detail_table();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function ajax_all_colors_on_item_master(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->ajax_all_colors_on_item_master();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function ajax_fetch_purchase_order_details_on_pk(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->ajax_fetch_purchase_order_details_on_pk();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }
	
	public function ajax_unique_po_number_and_art_no_and_lth_color(){
        if($this->check_permission() == true) {
            $this->load->model('Checkin_m');
            $data = $this->Checkin_m->ajax_unique_po_number_and_art_no_and_lth_color();
            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
            exit();
        }
    }

	

	public function ajax_distribute_delete_on_pk(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->ajax_distribute_delete_on_pk();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }

    public function ajax_fetch_checkin_details_on_pk(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->ajax_fetch_checkin_details_on_pk();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }
	
	public function del_row_on_table_pk_checkin_details(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->del_row_on_table_pk_checkin_details();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }



    public function form_edit_checkin_details(){

        if($this->check_permission() == true) {

            $this->load->model('Checkin_m');

            $data = $this->Checkin_m->form_edit_checkin_details();

            echo json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);

            exit();

        }

    }

    

    // --------------------------------------------------------EDIT ENDS--------------------------------------------------------

    

// --------------------------------------------------------PRINT STARTS--------------------------------------------------------

public function purchase_order_print_with_code($po_id) {

    if($this->check_permission(array(1)) == true) {

        $this->load->model('Checkin_m');

        $data = $this->Checkin_m->purchase_order_print_with_code($po_id);

        $this->load->view($data['page'], $data['data']);

    }

}



public function purchase_order_print_without_code($po_id) {

    if($this->check_permission(array(1)) == true) {

        $this->load->model('Checkin_m');

        $data = $this->Checkin_m->purchase_order_print_without_code($po_id);

        $this->load->view($data['page'], $data['data']);

    }

}

// --------------------------------------------------------PRINT ENDS--------------------------------------------------------

    

}