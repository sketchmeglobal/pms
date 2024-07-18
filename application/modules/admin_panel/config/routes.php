<?php

	$route['admin/dashboard'] = 'admin_panel/Dashboard/dashboard';
	$route['404'] = 'admin_panel/Dashboard/error_404';
	$route['js_disabled'] = 'admin_panel/Dashboard/js_disabled';
	
	$route['admin/profile'] = 'admin_panel/Profile/profile';
	$route['admin/form_basic_info'] = 'admin_panel/Profile/form_basic_info';
	$route['admin/form_change_pass'] = 'admin_panel/Profile/form_change_pass';
	$route['admin/form_change_email'] = 'admin_panel/Profile/form_change_email';
	$route['admin/change_email/(:any)'] = 'admin_panel/Profile/change_email/$1';
	$route['admin/ajax_username_check'] = 'admin_panel/Profile/ajax_username_check';
	$route['admin/form_change_username'] = 'admin_panel/Profile/form_change_username';
	
	
	// MASTER AREA STARS 
	$route['admin/units'] = 'admin_panel/Master/units';
	$route['admin/product-categories'] = 'admin_panel/Master/product_category';
	$route['admin/sizes'] = 'admin_panel/Master/sizes';
	$route['admin/account_master'] = 'admin_panel/Master/account_master';
	$route['admin/departments'] = 'admin_panel/Master/departments';
	$route['admin/mill'] = 'admin_panel/Master/mill';
	$route['admin/transporter'] = 'admin_panel/Master/transporter';
	$route['admin/employees'] = 'admin_panel/Master/employees';
	$route['admin/colors'] = 'admin_panel/Master/colors';
	$route['admin/menusetting'] = 'admin_panel/Master/menusetting';
// COMMON ROUTES ENDS 

// PURCHASE ORDER AREA STARTS 
	// list
	$route['admin/purchase-order'] = 'admin_panel/Purchase_order/purchase_order';
	$route['admin/ajax_purchase_order_table_data'] = 'admin_panel/Purchase_order/ajax_purchase_order_table_data';
	// add 
	$route['admin/add-purchase-order'] = 'admin_panel/Purchase_order/add_purchase_order';
	$route['admin/ajax_unique_purchase_order_number'] = 'admin_panel/Purchase_order/ajax_unique_purchase_order_no';
	$route['admin/form_add_purchase_order'] = 'admin_panel/Purchase_order/form_add_purchase_order';
	$route['admin/form_add_purchase_order_details'] = 'admin_panel/Purchase_order/form_add_purchase_order_details';
	// edit 
	$route['admin/edit-purchase-order/(:num)'] = 'admin_panel/Purchase_order/edit_purchase_order/$1';
	$route['admin/ajax_fetch_purchase_order_details_on_pk'] = 'admin_panel/Purchase_order/ajax_fetch_purchase_order_details_on_pk';
	
	$route['admin/form_edit_purchase_order'] = 'admin_panel/Purchase_order/form_edit_purchase_order';
	$route['admin/ajax_purchase_order_details_table_data'] = 'admin_panel/Purchase_order/ajax_purchase_order_details_table_data';
	$route['admin/form_edit_purchase_order_details'] = 'admin_panel/Purchase_order/form_edit_purchase_order_details';
	//Delete
	$route['admin/ajax_del_row_on_table_and_pk_purchase_order'] = 'admin_panel/Purchase_order/ajax_del_row_on_table_and_pk_purchase_order';
	$route['admin/del-row-on-table-pk-purchase-order-details'] = 'admin_panel/Purchase_order/del_row_on_table_pk_purchase_order_details';
	
	// print 
	$route['admin/purchase-order-print-with-code/(:num)'] = 'admin_panel/Purchase_order/purchase_order_print_with_code/$1';
	$route['admin/purchase-order-print-without-code/(:num)'] = 'admin_panel/Purchase_order/purchase_order_print_without_code/$1';
// PURCHASE ORDER AREA ENDS

//PRODUCTION START
	// list
	$route['admin/production/(:num)'] = 'admin_panel/Production/production/$1';
	$route['admin/ajax_production_table_data'] = 'admin_panel/Production/ajax_production_table_data';
	// add 
	$route['admin/add-production/(:num)'] = 'admin_panel/Production/add_production/$1';
	$route['admin/ajax_unique_prod_number'] = 'admin_panel/Production/ajax_unique_prod_number';
	$route['admin/form_add_production'] = 'admin_panel/Production/form_add_production';
	$route['admin/form_add_production_details'] = 'admin_panel/Production/form_add_production_details';
	
	// edit 
	$route['admin/edit-production/(:num)/(:num)'] = 'admin_panel/Production/edit_production/$1/$2';
	$route['admin/form_edit_production'] = 'admin_panel/Production/form_edit_production';
	
	$route['admin/ajax_production_details_table_data'] = 'admin_panel/Production/ajax_production_details_table_data';
	$route['admin/ajax_fetch_production_details_on_pk'] = 'admin_panel/Production/ajax_fetch_production_details_on_pk';
	$route['admin/form_edit_production_details'] = 'admin_panel/Production/form_edit_production_details';
	$route['admin/ajax_fetch_last_miter_reading'] = 'admin_panel/Production/ajax_fetch_last_miter_reading';
	
	//Delete
	$route['admin/ajax_delete_production_on_pk'] = 'admin_panel/Production/ajax_delete_production_on_pk';
	$route['admin/ajax_delete_production_detail_on_pk'] = 'admin_panel/Production/ajax_delete_production_detail_on_pk';
	
//PRODUCTION END

//DISTRIBUTE AREA STARTS 
	// list
	$route['admin/distribute'] = 'admin_panel/Distribute/distribute';
	$route['admin/ajax_distribute_table_data'] = 'admin_panel/Distribute/ajax_distribute_table_data';
	// add 
	$route['admin/add-distribute'] = 'admin_panel/Distribute/add_distribute';
	$route['admin/ajax_unique_distribute_number'] = 'admin_panel/Distribute/ajax_unique_distribute_number';
	$route['admin/form_add_distribute'] = 'admin_panel/Distribute/form_add_distribute';
	$route['admin/all-details-in-purchase-order'] = 'admin_panel/Distribute/all_details_in_purchase_order';
	$route['admin/form_add_distribute_details'] = 'admin_panel/Distribute/form_add_distribute_details';
	
	// edit 
	$route['admin/edit-distribute/(:num)'] = 'admin_panel/Distribute/edit_distribute/$1';
	$route['admin/ajax_distribute_details_table_data'] = 'admin_panel/Distribute/ajax_distribute_details_table_data';
	
	$route['admin/form_edit_distribute'] = 'admin_panel/Distribute/form_edit_distribute';
	$route['admin/all-items-on-item-group'] = 'admin_panel/Purchase_order/ajax_all_items_on_item_group';
	$route['admin/all-colors-on-item-master'] = 'admin_panel/Purchase_order/ajax_all_colors_on_item_master';
	$route['admin/form_edit_purchase_order_details'] = 'admin_panel/Purchase_order/form_edit_purchase_order_details';
	
	//Delete
	$route['admin/ajax_distribute_delete_on_pk'] = 'admin_panel/Distribute/ajax_distribute_delete_on_pk';
	$route['admin/del-row-on-table-pk-distribute-details'] = 'admin_panel/Distribute/del_row_on_table_pk_distribute_details';
//DISTRIBUTE ORDER AREA ENDS

//CHECK IN AREA STARTS 
	// list
	$route['admin/checkin/(:num)'] = 'admin_panel/Checkin/checkin/$1';
	$route['admin/ajax_checkin_table_data'] = 'admin_panel/Checkin/ajax_checkin_table_data';
	// add 
	$route['admin/add-checkin'] = 'admin_panel/Checkin/add_checkin';
	$route['admin/ajax_unique_checkin_number'] = 'admin_panel/Checkin/ajax_unique_checkin_number';
	$route['admin/form_add_checkin'] = 'admin_panel/Checkin/form_add_checkin';
	$route['admin/all-details-in-purchase-order-checkin'] = 'admin_panel/Checkin/all_details_in_purchase_order';
	$route['admin/form_add_checkin_details'] = 'admin_panel/Checkin/form_add_checkin_details';
	
	// edit 
	$route['admin/edit-checkin/(:num)'] = 'admin_panel/Checkin/edit_checkin/$1';
	$route['admin/all-get-details-from-distribute-detail-table'] = 'admin_panel/Checkin/all_get_details_from_distribute_detail_table';
	$route['admin/ajax_checkin_details_table_data'] = 'admin_panel/Checkin/ajax_checkin_details_table_data';
	$route['admin/form_edit_checkin'] = 'admin_panel/Checkin/form_edit_checkin';
	$route['admin/ajax_fetch_checkin_details_on_pk'] = 'admin_panel/Checkin/ajax_fetch_checkin_details_on_pk';
	$route['admin/form_edit_checkin_details'] = 'admin_panel/Checkin/form_edit_checkin_details';
	
	//Delete
	$route['admin/ajax_distribute_delete_on_pk'] = 'admin_panel/Checkin/ajax_distribute_delete_on_pk';
	$route['admin/del-row-on-table-pk-checkin-details'] = 'admin_panel/Checkin/del_row_on_table_pk_checkin_details';
//CHECK IN AREA ENDS

// CUSTOMER ORDER AREA STARTS
	$route['admin/customer-order'] = 'admin_panel/Customer_order/customer_order';
	$route['admin/add-customer-order'] = 'admin_panel/Customer_order/add_customer_order';
	$route['ajax_customer_order_table_data'] = 'admin_panel/Customer_order/ajax_customer_order_table_data';
	$route['ajax-fetch-article-colours'] = 'admin_panel/Customer_order/ajax_fetch_article_colours';
	$route['ajax_unique_customer_order_number'] = 'admin_panel/Customer_order/ajax_unique_customer_order_number';
	$route['admin/form_add_customer_order'] = 'admin_panel/Customer_order/form_add_customer_order';


	// edit area 
	$route['admin/edit-customer-order/(:num)'] = 'admin_panel/Customer_order/edit_customer_order/$1';
	$route['admin/form_edit_customer_order'] = 'admin_panel/Customer_order/form_edit_customer_order';
	$route['admin/ajax_unique_customer_order_no'] = 'admin_panel/Customer_order/ajax_unique_customer_order_no';
	$route['ajax_customer_order_details_table_data'] = 'admin_panel/Customer_order/ajax_customer_order_details_table_data';
	$route['admin/form_add_customer_order_details'] = 'admin_panel/Customer_order/form_add_customer_order_details';
	$route['admin/ajax_fetch_customer_order_details_on_pk'] = 'admin_panel/Customer_order/ajax_fetch_customer_order_details_on_pk';
	$route['admin/form_edit_customer_order_details'] = 'admin_panel/Customer_order/form_edit_customer_order_details';
	$route['admin/ajax_unique_co_no_and_art_no_and_lth_color'] = 'admin_panel/Customer_order/ajax_unique_co_no_and_art_no_and_lth_color';
	
	$route['admin/del-row-on-table-pk-customer-order'] = 'admin_panel/Customer_order/ajax_del_row_on_table_and_pk_customer_order';
	
	// print area
	$route['admin/print-customer-order-consumption/(:num)'] = 'admin_panel/Customer_order/print_customer_order_consumption/$1';
	$route['admin/full-order-history/(:num)'] = 'admin_panel/Customer_order/full_order_history/$1';
	
	$route['admin/print-invoice/proforma/(:num)'] = 'admin_panel/Customer_invoice/customer_invoice_print/$1';
	$route['admin/print-invoice/tax/(:num)'] = 'admin_panel/Customer_invoice/customer_invoice_print/$1';

	$route['admin/print-receipt/(:num)'] = 'admin_panel/Customer_invoice/customer_receipt_print/$1';

	$route['ajax-fetch-article-rate-on-type'] = 'admin_panel/Customer_order/ajax_fetch_article_rate_on_type';

	$route['admin/ajax-get-consume-list-purchase-order-receive-detail'] = 'admin_panel/Customer_order/ajax_get_consume_list_purchase_order_receive_detail';
	
	// DELETE AREA
	$route['admin/ajax-delete-customer-order'] = 'admin_panel/Customer_order/ajax_delete_customer_order';
	
// CUSTOMER ORDER AREA ENDS 

// CUSTOMER INVOICE AREA STARTS
	$route['admin/customer-invoice'] = 'admin_panel/Customer_invoice/customer_invoice';
	$route['admin/add-customer-invoice'] = 'admin_panel/Customer_invoice/add_customer_invoice';
	
	$route['ajax_customer_invoice_table_data'] = 'admin_panel/Customer_invoice/ajax_customer_invoice_table_data';
	$route['ajax_unique_customer_invoice_number'] = 'admin_panel/Customer_invoice/ajax_unique_customer_invoice_number';
	$route['admin/form_add_customer_invoice'] = 'admin_panel/Customer_invoice/form_add_customer_invoice';
    $route['admin/form_add_invoice_payment'] = 'admin_panel/Customer_invoice/form_add_invoice_payment';
    $route['admin/ajax-fetch-invoice-payment'] = 'admin_panel/Customer_invoice/ajax_fetch_invoice_payment';
    $route['admin/del-payment-received'] = 'admin_panel/Customer_invoice/del_payment_received';

    $route['admin/ajax-form-update-transport-payment'] = 'admin_panel/Customer_invoice/form_update_transport_payment';

	// edit area 
	$route['admin/edit-customer-invoice/(:num)'] = 'admin_panel/Customer_invoice/edit_customer_invoice/$1';
	$route['admin/form_edit_customer_invoice'] = 'admin_panel/Customer_invoice/form_edit_customer_invoice';
	$route['admin/ajax_unique_customer_order_no'] = 'admin_panel/Customer_invoice/ajax_unique_customer_order_no';
// 	$route['ajax_customer_order_details_table_data'] = 'admin_panel/Customer_invoice/ajax_customer_order_details_table_data';
	
	
	$route['admin/form_add_customer_invoice_details'] = 'admin_panel/Customer_invoice/form_add_customer_invoice_details';
	
	
	$route['admin/ajax_fetch_customer_order_details_on_pk'] = 'admin_panel/Customer_invoice/ajax_fetch_customer_order_details_on_pk';
	$route['admin/ajax_unique_co_no_and_art_no_and_lth_color'] = 'admin_panel/Customer_invoice/ajax_unique_co_no_and_art_no_and_lth_color';
	
	$route['admin/del-row-on-table-pk-customer-invoice'] = 'admin_panel/Customer_invoice/ajax_del_row_on_table_and_pk_customer_order';
	$route['admin/del-row-on-table-pk-invoice'] = 'admin_panel/Customer_invoice/del_row_on_table_pk_invoice';


	// print area
	$route['admin/print-customer-order-consumption/(:num)'] = 'admin_panel/Customer_invoice/print_customer_order_consumption/$1';
	$route['admin/full-order-history/(:num)'] = 'admin_panel/Customer_order/full_order_history/$1';
	
	$route['ajax-fetch-article-rate-on-type'] = 'admin_panel/Customer_invoice/ajax_fetch_article_rate_on_type';
// CUSTOMER INVOICE AREA ENDS 


//REPORT SECTION
// Priority
$route['admin/employee-priority'] = 'admin_panel/Employee_priority/employee_priority';

// Checkin
$route['admin/report-employee'] = 'admin_panel/Report_employee/report_employee';
$route['admin/check-in-out-report'] = 'admin_panel/Report_employee/check_in_out_report';

// Distribute
$route['admin/report-employee-distribute'] = 'admin_panel/Report_employee_distribute/report_employee_distribute';

// PO Ledger
$route['admin/report-po-ledger'] = 'admin_panel/Report_po_ledger/report_po_ledger';
$route['admin/all-cn-list'] = 'admin_panel/Report_po_ledger/all_cn_list';
$route['admin/report-po-ledger-init'] = 'admin_panel/Report_po_ledger/generate_po_ledger_init';

//Wastage Report
$route['admin/wastage-report'] = 'admin_panel/Report_employee/wastage_report';

//Stock Report
$route['admin/stock-report'] = 'admin_panel/Report_stock/stock_report';

//Order Details Report
$route['admin/order-details-report'] = 'admin_panel/Report_employee/order_details_report';
$route['admin/product-sizes-on-category'] = 'admin_panel/Report_employee/product_sizes_on_category';
// Invoice area
$route['admin/invoice-details-report'] = 'admin_panel/Report_po_ledger/invoice_details_report';

// Payment area 
$route['admin/employee-payment'] = 'admin_panel/Employee_priority/employee_payment';
$route['admin/update-payment-status'] = 'admin_panel/Employee_priority/update_payment_status';

// ATTENDANCE

$route['admin/employee-attendance'] = 'admin_panel/Employee_priority/employee_attendance';


//Settings Area

$route['admin/meter-reading'] = 'admin_panel/Settings/meter_reading';
$route['admin/meter-reading/(:any)'] = 'admin_panel/Settings/meter_reading';
$route['admin/meter-reading/(:any)/(:any)'] = 'admin_panel/Settings/meter_reading';

$route['admin/database-backup'] = 'admin_panel/Settings/database_backup_m';