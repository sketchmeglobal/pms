<?php
class Report_po_ledger_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db->query("SET sql_mode = ''");
    }

    public function generate_po_ledger_init()
    {
        $data = array();
        $data["start_date"] = date('Y-m-d');
        $data["end_date"] = date('Y-m-d');

        $this
            ->db
            ->select('po_id, po_number');
        $rs_po_num = $this
            ->db
            ->get_where('purchase_order', array(
            'purchase_order.status' => 1
        ))
            ->result();
        $data["purchase_order"] = $rs_po_num;
        $rs_po_num1 = $this
            ->db
            ->get_where('purchase_order_details', array(
            'purchase_order_details.status' => 1
        ))
            ->result();
        $data["purchase_order_detail"] = $rs_po_num1;
        $data["po_id"] = 0;
        $data["show_or_not"] = 0;

        return $data;
    }

    public function generate_po_ledger()
    {
        if (empty($this
            ->input
            ->post('pod_id')))
        {
            die('No details found');
        }
        $start_date = $this
            ->input
            ->post('start_date');
        $end_date = $this
            ->input
            ->post('end_date');
        $po_id = $this
            ->input
            ->post('po_id');
        $pod_id = $this
            ->input
            ->post('pod_id');

        $data = array();
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;
        $data["po_id"] = $po_id;
        $data["pod_id"] = $pod_id;

        if ($start_date != '' && $end_date != '')
        {

            $start_date1 = $start_date . ' 00:00:01';
            $end_date1 = $end_date . ' 23:59:59';

        }
        else
        {

            $start_date1 = '';
            $end_date1 = '';

        }
        $table_datas = array();
        $result_data = array();

        foreach ($pod_id as $p_i)
        {

            $data['result'][] = $this->_custom_generate_po_ledger($p_i, $start_date1, $end_date1);

        }

        $data['segment'] = 'po_ledger_report';

        /********************************* Main Part ******************************************/

        //new section 21-07-2021//
        // echo '<pre>', print_r($data), '</pre>'; die();
        return $data;

    }

    public function _custom_generate_po_ledger($p_i, $start_date1, $end_date1){

    	if(empty($p_i)){
            die('No details found');
        } else {

		if($start_date1 != '' && $end_date1 != '') {

		$this->db->select('sizes.size,purchase_order_details.pod_id, purchase_order_details.size_in_text, purchase_order_details.consignement_number, distribute_detail.create_date as dis_create_date, SUM(distribute_detail.distribute_pod_quantity) as distribute_pod_quantity, employees.e_id, employees.name, product_category.category_name');

		return $result = $this->db->join('purchase_order','purchase_order.po_id = purchase_order_details.po_id', 'left')
    		->join('distribute_detail','distribute_detail.pod_id = purchase_order_details.pod_id', 'left')
    		->join('employees','employees.e_id = distribute_detail.e_id', 'left')
    		->join('sizes','sizes.sz_id = distribute_detail.sz_id', 'left')
            ->join('production_detail', 'production_detail.prod_detail_id = distribute_detail.prod_detail_id', 'left')
            ->join('product_category', 'product_category.pc_id = production_detail.pc_id', 'left')
    		->group_by('distribute_detail.pod_id, distribute_detail.e_id')
    		->get_where('purchase_order_details', array('purchase_order_details.pod_id' => $p_i, 'purchase_order.create_date >=' => $start_date1, 'purchase_order.create_date <=' => $end_date1))->result();
	} else {
		$this->db->select('sizes.size,purchase_order_details.pod_id, purchase_order_details.size_in_text, purchase_order_details.consignement_number, distribute_detail.create_date as dis_create_date, SUM(distribute_detail.distribute_pod_quantity) as distribute_pod_quantity, employees.e_id, employees.name, product_category.category_name');

		return $result = $this->db->join('purchase_order','purchase_order.po_id = purchase_order_details.po_id', 'left')
    		->join('distribute_detail','distribute_detail.pod_id = purchase_order_details.pod_id', 'left')
    		->join('employees','employees.e_id = distribute_detail.e_id', 'left')
    		->join('sizes','sizes.sz_id = distribute_detail.sz_id', 'left')
            ->join('production_detail', 'production_detail.prod_detail_id = distribute_detail.prod_detail_id', 'left')
            ->join('product_category', 'product_category.pc_id = production_detail.pc_id', 'left')
    		->group_by('distribute_detail.pod_id, distribute_detail.e_id')
    		->get_where('purchase_order_details', array('purchase_order_details.pod_id' => $p_i))->result();
	}


}
	//new section 21-7-2021//
    }

    public function all_cn_list()
    {
        $po_id = $this
            ->input
            ->post('po_id');
        return $this
            ->db
            ->get_where('purchase_order_details', array(
            'purchase_order_details.po_id' => $po_id
        ))->result_array();
    }

    public function invoice_details_report(){

    	$query = "
    	SELECT
		    customer_invoice.cus_inv_number,
		    customer_invoice.cus_inv_e_way_bill_no,
		    customer_invoice.transporter_cn_number,
		    customer_invoice.cus_inv_number_of_cartons,
		    customer_invoice.cus_inv_total_weight,
		    customer_invoice.transport_payment_amount,
		    IF(customer_invoice.transport_payment_type='client', 'Client (To pay)', 'Company (Paid)') AS transport_payment_type,
		    customer_invoice.packing_rate,
		    customer_invoice.packing_tax,
		    IF(customer_invoice.transport_payment_status=0, 'Pending', 'Paid') AS transport_payment_status,
		    transporter.transporter_name,
		    acc_master.name AS customer_name,
		    acc_master.vat_no,
		    SUM(customer_invoice_detail.total_amount) as invoice_total_amount
		  
		FROM
		    `customer_invoice`
		LEFT JOIN transporter ON transporter.transporter_id = customer_invoice.transporter_id
		LEFT JOIN acc_master ON acc_master.am_id = customer_invoice.am_id
		LEFT JOIN customer_invoice_detail ON customer_invoice_detail.cus_inv_id = customer_invoice.cus_inv_id
		GROUP BY customer_invoice_detail.cus_inv_id
    	";
        $res = $this->db->query($query)->result();
        return $res;

    }

} //end ctrl

