<?php

class Report_stock_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function stock_report() {
		$data = array();
		$data["start_date"] = date('Y-m-d');
		$data["end_date"] = date('Y-m-d');
		
		$data["po_list"] = $this->db->get_where('purchase_order', array('status' => 1))->result();
		$data["gsm_list"] = $this->db->distinct('paper_gsm')->select('paper_gsm')->order_by('paper_gsm')->get_where('purchase_order_details', array('status' => 1))->result();
		
// 		print_r($data["gsm_list"]); die;

        return $data;
    }
    
    public function order_qnty_on_po_and_gsm($po_id,$paper_gsm){
        return $this->db->select('SUM(cus_order_quantity) as order_quantity')->group_by('po_id,paper_gsm')->get_where('customer_order_dtl',array('po_id' => $po_id, 'paper_gsm' => $paper_gsm))->result();
    }
	

}//end ctrl