<?php

class Report_stock extends My_Controller {

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
	
	public function get_size(){
	    echo "ok";
	}
	public function stock_report() {
        if($this->check_permission(array(1)) == true) {
            
            $data['filter_data'] = array();
            $po_list = $gsm_list = $size_list = '';
			$this->load->model('Report_stock_m');
            $data['po_report'] = $this->Report_stock_m->stock_report();		
            // echo '<pre>', print_r($data['po_report']), '</pre>'; die;
            
            if($this->input->post('print')){
                
                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');
                
                if(count($this->input->post('po_list')) > 0){
                    $po_list = implode(",",$this->input->post('po_list'));    
                }
                
                if(count($this->input->post('gsm_list')) > 0){
                    $gsm_list = implode(",",$this->input->post('gsm_list'));    
                }
                
                if(count($this->input->post('size_list')) > 0){
                    $size_list = implode(",",$this->input->post('size_list'));    
                }
               
                    
                // $this->db->select('distribute_detail.distribute_pod_quantity, purchase_order.po_id, purchase_order.po_number, purchase_order.po_date,sizes.paper_gsm');
                // $this->db->join('purchase_order_details','distribute_detail.pod_id=purchase_order_details.pod_id','left');
                // $this->db->join('purchase_order','purchase_order.po_id=purchase_order_details.po_id','left');
                // $this->db->join('sizes','distribute_detail.sz_id=sizes.sz_id','left');
                
                
                
                // $data['filter_data'] = $this->db->get_where('distribute_detail', array('distribute_detail.status' => 1))->result();
                
                $this->db->query("SET sql_mode=''");
                // $query = "SELECT der.* FROM (
                //             SELECT  SUM(`distribute_detail`.`distribute_pod_quantity`) AS distribute_pod_quantity, `purchase_order`.`po_id`, `purchase_order`.`po_number`, `purchase_order`.`po_date`, `sizes`.`paper_gsm`, `distribute_detail`.`status` 
                //             FROM `distribute_detail` 
                //             LEFT JOIN `purchase_order_details` ON `distribute_detail`.`pod_id`=`purchase_order_details`.`pod_id` 
                //             LEFT JOIN `purchase_order` ON `purchase_order`.`po_id`=`purchase_order_details`.`po_id` 
                //             LEFT JOIN `sizes` ON `distribute_detail`.`sz_id`=`sizes`.`sz_id` 
                //              LEFT JOIN `checkin_detail` ON `checkin_detail`.`pod_id` = `distribute_detail`.`pod_id`
                //             GROUP BY `purchase_order`.`po_id`
                //         ) as der
                //         WHERE der.status = 1
                // ";
                
                $query = "SELECT
                            SUM(
                                `checkin_detail`.`received_quantity`
                            ) AS received_quantity,
                            `purchase_order`.`po_id`,
                            `purchase_order`.`po_number`,
                            `purchase_order`.`po_date`,
                            `sizes`.`paper_gsm`,
                            `distribute_detail`.`status`
                        FROM
                            `purchase_order_details`
                        LEFT JOIN `purchase_order` ON `purchase_order`.`po_id` = `purchase_order_details`.`po_id`
                         LEFT JOIN `distribute_detail` ON `distribute_detail`.`pod_id` = `purchase_order_details`.`pod_id`
                        LEFT JOIN `sizes` ON `distribute_detail`.`sz_id` = `sizes`.`sz_id`
                        LEFT JOIN `checkin_detail` ON `checkin_detail`.`pod_id` = `distribute_detail`.`pod_id`
                        WHERE
                            distribute_detail.status = 1 ";
                            
                if($po_list != ''){
                    $where1 = " AND purchase_order_details.`pod_id` IN($po_list)";
                    $query .= $where1;
                }
                 if($size_list != ''){
                    $where1 = " AND distribute_detail.`sz_id` IN($size_list)";
                    $query .= $where1;
                }
                
                if($gsm_list != ''){
                    $where2 = "AND der.paper_gsm IN($gsm_list)";
                    $query .= $where2;
                }
                    $query .="    GROUP BY
                            `purchase_order`.`po_id`,
                            `purchase_order`.`po_number`,
                            `purchase_order`.`po_date`,
                            `sizes`.`paper_gsm`,
                            `distribute_detail`.`status`";
                // echo $query;
                // die;
                // if($po_list != ''){
                //     $where1 = " AND der.`po_id` IN($po_list)";
                //     $query .= $where1;
                // }
                
                // if($gsm_list != ''){
                //     $where2 = "AND der.paper_gsm IN($gsm_list)";
                //     $query .= $where2;
                // }
                
                $data['filter_data'] = $this->db->query($query)->result();
                // echo $this->db->last_query(); die;
                
            }
            
            $this->load->view('reports/stock/stock_report_filter', $data); 
            
        }
    }

    
	
}//end ctrl

?>