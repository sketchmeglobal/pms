<?php

class Dashboard_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function dashboard() {
		$data = array();
        /*$data['lastest_costings'] = $this->db
                ->select('article_master.am_id, article_master.modify_date, article_master.art_no, article_master.status,article_costing.am_id, article_costing.ac_id, article_costing.user_id, article_costing.status, users.username')
                ->join('article_master', 'article_master.am_id = article_costing.am_id', 'left')
                ->join('users', 'article_costing.user_id = users.user_id', 'left')
                ->order_by('article_master.modify_date', 'desc')
                ->limit(5)
                ->get_where('article_costing', array('article_costing.status' => 1, 'article_master.status' => 1))
                ->result();
                
        $data['lastest_orders'] = $this->db
                // ->select('customer_order.co_id, customer_order.modify_date, customer_order.co_no, customer_order.status,customer_order_dtl.cut_id, customer_order_dtl.co_id as cod_id, customer_order_dtl.user_id, customer_order_dtl.status, users.username')
                ->join('customer_order_dtl', 'customer_order_dtl.co_id = customer_order.co_id', 'left')
                ->join('users', 'customer_order_dtl.user_id = users.user_id', 'left')
                ->group_by('co_no')
                ->order_by('customer_order_dtl.modify_date', 'desc')
                ->limit(5)
                ->get_where('customer_order', array('customer_order.status' => 1, 'customer_order_dtl.status' => 1))
                ->result();        
                
        $data['lastest_cutting_issues'] = $this->db
                ->select('customer_order.co_id, customer_order.co_no, customer_order.status,cutting_issue_challan_details.cut_id, cutting_issue_challan_details.co_id, cutting_issue_challan_details.user_id, cutting_issue_challan_details.status, cutting_issue_challan_details.modify_date, users.username')
                ->join('customer_order', 'cutting_issue_challan_details.co_id = customer_order.co_id', 'left')
                ->join('users', 'cutting_issue_challan_details.user_id = users.user_id', 'left')
                ->group_by('cutting_issue_challan_details.co_id')
                ->order_by('cutting_issue_challan_details.modify_date', 'desc')
                ->limit(5)
                ->get_where('cutting_issue_challan_details', array('customer_order.status' => 1, 'cutting_issue_challan_details.status' => 1))
                ->result();    
                
        $data['lastest_cutting_receive'] = $this->db
                ->select('customer_order.co_id, customer_order.co_no, customer_order.status,cutting_received_challan_detail.cut_rcv_id, cutting_received_challan_detail.co_id, cutting_received_challan_detail.user_id, cutting_received_challan_detail.status, cutting_received_challan_detail.modify_date, users.username')
                ->join('customer_order', 'cutting_received_challan_detail.co_id = customer_order.co_id', 'left')
                ->join('users', 'cutting_received_challan_detail.user_id = users.user_id', 'left')
                ->group_by('cutting_received_challan_detail.co_id')
                ->order_by('cutting_received_challan_detail.modify_date', 'desc')
                ->limit(5)
                ->get_where('cutting_received_challan_detail', array('customer_order.status' => 1, 'cutting_received_challan_detail.status' => 1))
                ->result(); 
                
        $data['lastest_skiving_receive'] = $this->db
                // ->select('skiving_receive_challan_details.skiving_receive_challan_number, skiving_receive_challan_details.co_id, skiving_receive_challan_details.user_id, skiving_receive_challan_details.status, skiving_receive_challan_details.modified_date, users.username')
                ->join('skiving_receive_challan', 'skiving_receive_challan_details.skiving_receive_id = skiving_receive_challan.skiving_receive_id', 'left')
                ->join('users', 'skiving_receive_challan_details.user_id = users.user_id', 'left')
                ->group_by('skiving_receive_challan_details.skiving_receive_id')
                ->order_by('skiving_receive_challan_details.modified_date', 'desc')
                ->limit(5)
                ->get_where('skiving_receive_challan_details', array('skiving_receive_challan.status' => 1, 'skiving_receive_challan_details.status' => 1))
                ->result();  */ 
                
        // $data['lastest_jobber_issues'] = $this->db
        //         ->select('customer_order.co_id, customer_order.co_no, customer_order.status,cutting_issue_challan_details.cut_id, cutting_issue_challan_details.co_id, cutting_issue_challan_details.user_id, cutting_issue_challan_details.status, cutting_issue_challan_details.modify_date, users.username')
        //         ->join('customer_order', 'cutting_issue_challan_details.co_id = customer_order.co_id', 'left')
        //         ->join('users', 'cutting_issue_challan_details.user_id = users.user_id', 'left')
        //         ->group_by('cutting_issue_challan_details.co_id')
        //         ->order_by('cutting_issue_challan_details.modify_date', 'desc')
        //         ->limit(5)
        //         ->get_where('cutting_issue_challan_details', array('customer_order.status' => 1, 'cutting_issue_challan_details.status' => 1))
        //         ->result();        
        return array('page' => 'dashboard_v', 'data' => $data);
    }


} // /.Dashboard_m model