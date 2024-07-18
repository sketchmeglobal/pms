<?php #echo '<pre>', print_r($customer_order_details), '</pre>'; die; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Customer Invoice | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="edit Customer Order">

    <!--Data Table-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>

    <!--Select2-->
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">

    <!--iCheck-->
    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">

    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        text-align: right;
        }

        /* Firefox */
        input[type=number] {
            text-align: right;
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="sticky-header">

<section>
    <!-- sidebar left start (Menu)-->
    <?php $this->load->view('components/left_sidebar'); //left side menu ?>
    <!-- sidebar left end (Menu)-->

    <!-- body content start-->
    <div class="body-content" style="min-height: 1500px;">

        <!-- header section start-->
        <?php $this->load->view('components/top_menu'); ?>
        <!-- header section end-->

        <!-- page head start-->
        <div class="page-head">
            <h3 class="m-b-less">Edit Customer Invoice</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Edit Customer Invoice </li>
                </ol>
            </div>
        </div>
        <!-- page head end-->

        <!--body wrapper start-->
        <div class="wrapper">

            <!--Edit Article Costing-->
            <div class="row">
                <div class="col-md-10">
                    <section class="panel">
                        <header class="panel-heading">
                            Edit <?= $customer_invoice_details[0]->cus_inv_number ?>
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">

                            <form id="form_edit_customer_invoice" method="post" action="<?=base_url('admin/form_edit_customer_invoice')?>" enctype="multipart/form-data" class="cmxform form-horizontal tasi-form">

                                <div id="customer_order_id"><?php $customer_invoice_details[0]->co_id ?></div>

                                 <div class="form-group ">
                                
                                	<div class="col-lg-3">
                                        <label for="co_id" class="control-label text-danger">Customer Order Number*</label>
                                        <select name="co_id" id="co_id" class="form-control select2">
                                        <option value="">Select Customer Order</option>
                                        <option value="<?= $customer_invoice_details[0]->co_id ?>" co-no="<?= $customer_invoice_details[0]->co_no ?>" am-id="<?= $customer_invoice_details[0]->am_id ?>" acc-master-name="<?= $customer_invoice_details[0]->name ?>" selected="selected"><?= $customer_invoice_details[0]->co_no ?></option>
                                                <?php
                                                foreach($customer_order as $co){
                                                ?> 
                                                    <option value="<?= $co->co_id ?>" co-no="<?= $co->co_no ?>" am-id="<?= $co->am_id ?>" acc-master-name="<?= $co->name ?>"><?= $co->co_no . ' ['.$co->name.']' ?></option>
                                                <?php
                                                }
                                                ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <label for="cus_inv_number" class="control-label text-danger">Invoice Number *</label>
                                        <input id="cus_inv_number" name="cus_inv_number" type="text" placeholder="Invoice Number" class="form-control round-input" value="<?= $customer_invoice_details[0]->cus_inv_number ?>" />
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <label for="cus_inv_e_way_bill_no" class="control-label">E-WAY Bill no.</label>
                                        <input id="cus_inv_e_way_bill_no" name="cus_inv_e_way_bill_no" value="<?= $customer_invoice_details[0]->cus_inv_e_way_bill_no ?>" type="text" placeholder="E-WAY Bill no." class="form-control round-input" />
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="party_name" class="control-label text-danger">Party Name/Buyer/Customer *</label>
                                        <input id="party_name" name="party_name" value="<?= $customer_invoice_details[0]->name ?>" type="text" placeholder="Party Name/Buyer/Customer" class="form-control round-input" readonly />
                                        <input id="am_id" name="am_id" value="<?= $customer_invoice_details[0]->am_id ?>" type="hidden" class="form-control round-input" />
                                    </div>
                                    
                                 </div>   
                                 <div class="form-group "> 
                                 	
                                    <div class="col-lg-3">
                                        <label for="transporter_id" class="control-label text-danger">Transporter*</label>
                                        <select name="transporter_id" id="transporter_id" class="form-control select2">
                                            <option value="">Select Transporter</option>
                                                <?php
                                                foreach($transporter as $tr){
                                                ?> 
                                                    <option value="<?= $tr->transporter_id ?>" <?php if($customer_invoice_details[0]->transporter_id == $tr->transporter_id){?> selected="selected" <?php } ?>><?= $tr->transporter_name ?></option>
                                                    <?php
                                                }
                                                ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="transporter_cn_number" class="control-label">Transporter CN Number</label>
                                        <input id="transporter_cn_number" name="transporter_cn_number" type="text" placeholder="Transporter CN Number" class="form-control round-input" value="<?= $customer_invoice_details[0]->transporter_cn_number ?>"/>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <label for="cus_inv_number_of_cartons" class="control-label">Total number of Cartons</label>
                                        <input id="cus_inv_number_of_cartons" name="cus_inv_number_of_cartons" type="text" placeholder="Total number of Cartons" value="<?= $customer_invoice_details[0]->cus_inv_number_of_cartons ?>" class="form-control round-input" />
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="cus_inv_total_weight" class="control-label">Total weight</label>
                                        <input id="cus_inv_total_weight" name="cus_inv_total_weight" type="text" placeholder="Total weight" value="<?= $customer_invoice_details[0]->cus_inv_total_weight ?>" class="form-control round-input" />
                                    </div>
                                 </div>
                                 <div class="form-group "> 
                                 	
                                 	<div class="col-lg-3">
                                        <label for="invoice_create_date" class="control-label text-danger">Invoice Create Date *</label>
                                        <input id="invoice_create_date" name="invoice_create_date" type="date"  class="form-control round-input" value="<?= date('Y-m-d', strtotime($customer_invoice_details[0]->invoice_create_date)) ?>" />
                                    </div>  
                                    <div class="col-lg-3">
                                        <label for="packing_rate" class="control-label">Packaging Rate</label>
                                        <input id="packing_rate" name="packing_rate" type="number" placeholder="Packaging Rate" class="form-control round-input" value="<?=$customer_invoice_details[0]->packing_rate?>" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="packing_tax" class="control-label">Packaging Tax (%)</label>
                                        <input id="packing_tax" name="packing_tax" type="number" placeholder="Packaging Tax" class="form-control round-input" value="<?=$customer_invoice_details[0]->packing_tax?>" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="remarks" class="control-label">Remarks</label>
                                        <textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"><?= $customer_invoice_details[0]->remarks ?></textarea>
                                    </div>  
                                 </div>
                                 <div class="form-group "> 
                                 	<div class="col-lg-3">
                                        <label for="pay_type" class="control-label text-danger">Transporter Payment Type*</label>
                                        <select name="pay_type" id="pay_type" class="select2 form-control">
                                            <option <?= ($customer_invoice_details[0]->transport_payment_type == 'client') ? 'selected' : '' ?> value="client">By Client (To Pay)</option>
                                            <option <?= ($customer_invoice_details[0]->transport_payment_type == 'company') ? 'selected' : '' ?> value="company">By Company (Paid)</option>
                                        </select>
                                    </div>     
                                    <div class="col-lg-3">
                                        <label for="pay_type" class="control-label">Transporter Payment Amount</label>
                                        <input placeholder="Payment Amount" value="<?=$customer_invoice_details[0]->transport_payment_amount?>" type="number" step="0.50" id="cn_pay_amount" name="cn_pay_amount" class="form-control round-input">
                                    </div>  
                                    <div class="col-lg-3">
                                    	<label for="terms" class="control-label">Terms & Condition</label>
                                    	<textarea id="terms" name="terms" placeholder="Terms & Condition" class="form-control round-input"><?= $customer_invoice_details[0]->terms ?></textarea>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <label class="control-label text-danger">Status *</label><br />
                                        <input type="radio" name="status" id="enable" value="1" <?php if($customer_invoice_details[0]->status == '1'){ ?> checked <?php } ?> required class="iCheck-square-green">
                                        <label for="enable" class="control-label">Enable</label>

                                        <input type="radio" name="status" id="disable" value="0" <?php if($customer_invoice_details[0]->status == '0'){ ?> checked <?php } ?> required class="iCheck-square-red">
                                        <label for="disable" class="control-label">Disable</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-3">
                                <button class="btn btn-success" type="submit"><i class="fa fa-refresh"> Update Customer Invoice</i></button>
                                </div>
                                </div>

                                <input type="hidden" id="cus_inv_id" name="cus_inv_id" class="hidden" value="<?= $customer_invoice_details[0]->cus_inv_id ?>" />
                            </form>
                        </div>
                    </section>
                </div>
                <div class="col-md-2">
                    <section class="panel">
                        <header class="panel-heading">
                            Buyer:
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <!-- < ?= print_r($customer_order); ?> -->
                        <div class="panel-body">
                            <p class='text-center'> <a target="_blank" class="badge bg-primary" href="<?= base_url('admin_panel/Master/account_master/edit') ?>/<?= $customer_invoice_details[0]->am_id ?>"><?= $customer_invoice_details[0]->name .'' ?></a></p>
                            <hr />
                        </div>
                    </section>
                </div>
                <div class="col-md-2">
                    <section class="panel">
                        <header class="panel-heading">
                            Buyer Documents:
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                         <?php if($customer_invoice_details[0]->img != ''){ ?> 
                        <div class="panel-body">
                            <a href="<?= base_url('assets/admin_panel/img/customer_order') .'/' . $customer_invoice_details[0]->img ?>" class="" download>Download Document</a>   
                            <hr />
                        </div>
                        <?php } ?>
                    </section>
                </div>
            </div>



            <!--Article Costing Charges-->
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Add customer order details for <?= $customer_invoice_details[0]->cus_inv_number ?>
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <!--Tabs-->
                            <ul id="customer_order_tabs" class="nav nav-tabs nav-justified">
                                <li class="active"><a href="#co_list" data-toggle="tab">List</a></li>
                                <li><a href="#co_add" data-toggle="tab">Add</a></li>
                                <li id="co_details_edit_tab" class="disabled"><a href="#co_details_edit" data-toggle="">Edit</a></li>
                            </ul>
                            <!--Tab Content-->
                            <div class="tab-content">
                            <img style="display:none; position: absolute;margin: auto;left: 0;right: 0;" src="<?=base_url('assets/img/ellipsis.gif')?>" id="loading_div"><span class="sr-only">Processing...</span>
                            
                                <div id="co_list" class="tab-pane fade in active">
                                    <table id="co_details_table" class="table data-table dataTable">
                                        <thead>
                                        <tr>
                                            <th>Size (GSM & BF)</th>
                                            <th>Colour</th>
                                            <th>Paper Bag Quantity</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div id="co_add" class="tab-pane fade">
                                    <br/>
                                    <div class="form">
                                        <form id="form_add_customer_invoice_details" method="post" action="<?=base_url('admin/form_add_customer_invoice_details')?>" class="cmxform form-horizontal tasi-form">
                                         
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <td>Size (GSM & BF)</td>
                                                    <td>Colour</td>
                                                    <td>Ordered Quantity</td>
                                                    <td>Delivered Quantity</td>
                                                    <td>Due Quantity</td>
                                                    <td>Rate/Unit</td>
                                                    <td>Total</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
												if($invoice_pending_status == 0){
													foreach($customer_order_details as $cod){
												$total_amount = $cod->cus_order_quantity * $cod->rate_per_unit;
												?>
                                                <tr>
                                                    <td><?=$cod->size.' [GSM: '.$cod->paper_gsm.'] [BF: '.$cod->paper_bf.']'?></td>
                                                    <td>
													<?=$cod->color?>
                  <input type="hidden" name="co_id[]" value="<?=$cod->co_id?>">
                  <input type="hidden" name="cod_id[]" value="<?=$cod->cod_id?>">
                  <input type="hidden" name="sz_id[]" value="<?=$cod->sz_id?>">
                  <input type="hidden" name="paper_gsm[]" value="<?=$cod->paper_gsm?>">
                  <input type="hidden" name="paper_bf[]" value="<?=$cod->paper_bf?>">
                  <input type="hidden" name="c_id[]" value="<?=$cod->c_id?>">
                  									</td>
                                                    <td><?=$cod->cus_order_quantity?>
                                                    <input type="hidden" name="cus_order_quantity_hidden[]" id="cus_order_quantity_<?=$cod->cod_id?>" value="<?=$cod->cus_order_quantity?>">
                                                    </td>
                                                    <td>
                                                    <input type="number" class="form-control" name="delivered_quantity[]" id="delivered_quantity_<?=$cod->cod_id?>" value="<?=$cod->cus_order_quantity?>" max="<?=$cod->cus_order_quantity?>" required onBlur="updateTotalAmount(<?=$cod->cod_id?>)">
                                                    <input type="hidden" name="delivered_quantity_hidden[]" id="delivered_quantity_hidden_<?=$cod->cod_id?>" value="<?=$cod->cus_order_quantity?>">
                                                    </td>
                                                    <td><input type="number" class="form-control" name="due_quantity[]" id="due_quantity_<?=$cod->cod_id?>" value="0" min="0" ></td>
                                                    <td><input type="number" class="form-control" name="rate_per_unit[]" id="rate_per_unit_<?=$cod->cod_id?>" required min="0" value="<?=$cod->rate_per_unit?>" onBlur="updateTotalAmount(<?=$cod->cod_id?>)"></td>
                                                    <td><input type="number" class="form-control" name="total_amount[]" id="total_amount_<?=$cod->cod_id?>" required min="0" value="<?=$total_amount?>" readonly></td>
                                                </tr>
                                                <?php 
													} //end foreach
													}else{
														//If the Invoice already generated and a few order quantity is pending
													foreach($customer_order_details as $cod){
												$total_amount = $cod->cus_order_quantity * $cod->rate_per_unit;
												?>
                                                <tr>
                                                    <td><?=$cod->size.' [GSM: '.$cod->paper_gsm.'] [BF: '.$cod->paper_bf.']'?></td>
                                                    <td>
													<?=$cod->color?>
                  <input type="hidden" name="co_id[]" value="<?=$cod->co_id?>">
                  <input type="hidden" name="cod_id[]" value="<?=$cod->cod_id?>">
                  <input type="hidden" name="sz_id[]" value="<?=$cod->sz_id?>">
                  <input type="hidden" name="paper_gsm[]" value="<?=$cod->paper_gsm?>">
                  <input type="hidden" name="paper_bf[]" value="<?=$cod->paper_bf?>">
                  <input type="hidden" name="c_id[]" value="<?=$cod->c_id?>">
                  									</td>
                                                    <td><?=$cod->cus_order_quantity?>
                                                    <input type="hidden" name="cus_order_quantity_hidden[]" id="cus_order_quantity_<?=$cod->cod_id?>" value="<?=$cod->cus_order_quantity?>">
                                                    </td>
                                                    <td>
                                                    <input type="number" class="form-control" name="delivered_quantity[]" id="delivered_quantity_<?=$cod->cod_id?>" value="<?=$cod->cus_order_quantity?>" max="<?=$cod->cus_order_quantity?>" required onBlur="updateTotalAmount(<?=$cod->cod_id?>)">
                                                    <input type="hidden" name="delivered_quantity_hidden[]" id="delivered_quantity_hidden_<?=$cod->cod_id?>" value="<?=$cod->cus_order_quantity?>">
                                                    </td>
                                                    <td><input type="number" class="form-control" name="due_quantity[]" id="due_quantity_<?=$cod->cod_id?>" value="0" min="0" ></td>
                                                    <td><input type="number" class="form-control" name="rate_per_unit[]" id="rate_per_unit_<?=$cod->cod_id?>" required min="0" value="<?=$cod->rate_per_unit?>"></td>
                                                    <td><input type="number" class="form-control" name="total_amount[]" id="total_amount_<?=$cod->cod_id?>" required min="0" value="<?=$total_amount?>" readonly></td>
                                                </tr>
                                                <?php 
												} //end foreach
												}//end if
											?>
                                            </tbody>
                                        </table>

                                          
                                            <div class="form-group ">                      
                                                <div class="col-lg-2">
                                                <label for="article_remarks" class="control-label">&nbsp;</label><br />
                                                <button class="btn btn-success" type="submit" id="detail_add"><i class="fa fa-plus"></i> Update details</button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="cus_inv_id_add" class="hidden" value="<?= $customer_invoice_details[0]->cus_inv_id ?>" />
                                        </form>
                                    </div>
                                </div>

                                <div id="co_details_edit" class="tab-pane">
                                    <br/>
                                    <div class="form">
                                        <form id="form_edit_customer_invoice_details" method="post" action="<?=base_url('admin/form_edit_customer_invoice_details')?>" class="cmxform form-horizontal tasi-form">
                                           <div class="form-group ">                                            
                                            <div class="col-lg-4">
                                                <label for="sz_id_edit" class="control-label text-danger">Size*</label>
                                                <select id="sz_id_edit" name="sz_id_edit" required class="select2 form-control round-input">
                                                    <option value="">Select Size</option>
                                                   <?php
                                                   foreach($sizes as $sz){
                                                    ?>
                                                        <option value="<?=$sz->sz_id?>" paper-gsm="<?=$sz->paper_gsm?>" paper-bf="<?=$sz->paper_bf?>"><?=$sz->size?> [GSM: <?=$sz->paper_gsm?>] [BF: <?=$sz->paper_bf?>]</option>
                                                    <?php   
                                                   }
                                                   ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-2">
                                                    <label for="paper_gsm_edit" class="control-label">Paper GSM</label>
                                                    <input type="text" id="paper_gsm_edit" name="paper_gsm_edit" class="form-control" readonly />
                                                </div>

                                             <div class="col-lg-2">
                                                    <label for="paper_bf_edit" class="control-label">Paper BF</label>
                                                    <input type="text" id="paper_bf_edit" name="paper_bf_edit" class="form-control" readonly />
                                                </div>
                                                
                                             <div class="col-lg-2">
                                              <label for="colour" class="control-label text-danger">Colour*</label>
                                              <select id="c_id_edit" name="c_id_edit" required class="select2 form-control round-input">
                                                    <option value="">Select Colour</option>
                                                   <?php
                                                   foreach($colours as $colour){
                                                    ?>
                                                        <option value="<?=$colour->c_id?>"><?=$colour->color?> [ <?=$colour->c_code?> ]</option>
                                                    <?php   
                                                   }
                                                   ?>
                                                </select>
                                            </div>   
                                                
                                            <div class="col-lg-2">
                                                  <label for="cus_order_quantity_edit" class="control-label text-danger">Paper Bag Quantity *</label>
                                                  <input type="number" step="0.01" id="cus_order_quantity_edit" name="cus_order_quantity_edit" required class="form-control" />
                                            </div>
                                                
                                            
                                          </div>
                                          <div class="form-group ">
                                             
                                                <div class="col-lg-2">
                                                <label for="article_remarks" class="control-label">&nbsp;</label><br />
                                                <button class="btn btn-success" type="submit"><i class="fa fa-plus"></i> Update details</button>
                                                </div>
                                          </div>
                                          
                                            <input type="hidden" id="cod_id" name="order_details_id" class="hidden" value="" />
                                            <input type="hidden" name="cus_inv_id_edit" class="hidden" value="<?= $customer_invoice_details[0]->cus_inv_id ?>" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <?php $this->load->view('components/footer'); ?>
        <!--footer section end-->

    </div>
    <!-- body content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="<?=base_url()?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>
<!-- common js -->
<?php $this->load->view('components/_common_js'); //left side menu ?>
<!--Data Table-->
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/JSZip-2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/js/responsive.bootstrap.min.js"></script>
<!--data table init-->
<script src="<?=base_url()?>assets/admin_panel/js/data-table-init.js"></script>
<!--Select2-->
<script src="<?=base_url();?>assets/admin_panel/js/select2.js" type="text/javascript"></script>
<script>
    $('.select2').select2();
</script>
<!--Icheck-->
<script src="<?=base_url();?>assets/admin_panel/js/icheck/skins/icheck.min.js"></script>
<script src="<?=base_url();?>assets/admin_panel/js/icheck-init.js"></script>
<!--form validation-->
<script src="<?=base_url();?>assets/admin_panel/js/jquery.validate.min.js"></script>
<!--ajax form submit-->
<script src="<?=base_url();?>assets/admin_panel/js/jquery.form.min.js"></script>

<script>
    
    $(document).ready(function() {

       $("#co_id").change(function(){
			$co_id = $(this).val();
			
			$co_no = $('#co_id option:selected').attr('co-no');
			$co_no1 = $co_no.split("-");		
			$co_no2 = 'INV-'+$co_no1[1];
			$("#cus_inv_number").val($co_no2);
			
			$acc_master_name = $('#co_id option:selected').attr('acc-master-name');
			$("#party_name").val($acc_master_name);
			
			$am_id = $('#co_id option:selected').attr('am-id');
			$("#am_id").val($am_id);

		});
		
		$("#transporter_id").change(function(){
			$transporter_id = $(this).val();
			
			/*$transporter_cn_number = $('#transporter_id option:selected').attr('transporter-cn-number');
			$("#transporter_cn_number").val($transporter_cn_number);*/

		});


// ADD - multiply for article_amount
        $("#article_quantity, #article_rate").on('change', function () {
            $article_quantity = $("#article_quantity").val();
            $article_rate = $("#article_rate").val();
            $("#article_amount").val(($article_quantity * $article_rate).toFixed(2));
        });
// EDIT - multiply for article_amount
        $("#article_quantity_edit, #article_rate_edit").on('change', function () {
            $article_quantity = $("#article_quantity_edit").val();
            $article_rate = $("#article_rate_edit").val();
            $("#article_amount_edit").val(($article_quantity * $article_rate).toFixed(2));
        });

        $('#co_details_table').DataTable( {
            "processing": true,
            "language": {
                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',
            },
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url('ajax_customer_order_details_table_data')?>",
                "type": "POST",
                "dataType": "json",
                data: {
                    customer_order_id: function () {
                        return $("#customer_order_id").val();
                    },
                },
            },
            //will get these values from JSON 'data' variable
            "columns": [
                { "data": "size" },
                { "data": "colour" },
                { "data": "roll_quantity" },
                { "data": "action" },
            ],
            //column initialisation properties
            "columnDefs": [{
                "targets": [3],
                "orderable": false,
            }]
        } );

       
    } );

    
	function updateTotalAmount(cod_id){
		console.log('cod_id:'+cod_id);
		
		$delivered_quantity_hidden = $('#delivered_quantity_hidden_'+cod_id).val();
		$delivered_quantity = $('#delivered_quantity_'+cod_id).val();
		$due_quantity = $('#due_quantity_'+cod_id).val();
		$rate_per_unit = $('#rate_per_unit_'+cod_id).val();
		
		$due_quantity_new = parseFloat($delivered_quantity_hidden) - parseFloat($delivered_quantity);
		$('#due_quantity_'+cod_id).val($due_quantity_new);
		
		$total_amount = parseFloat($delivered_quantity) * parseFloat($rate_per_unit);
		$('#total_amount_'+cod_id).val($total_amount);
		
		console.log('due_quantity_new:'+$due_quantity_new);
		
		}//end function
	
    $("#form_edit_customer_invoice").validate({
        rules: {
            co_id: {
                required: true
            },
            // cus_inv_e_way_bill_no: {
            //     required: true
            // },
            transporter_id: {
                required: true
            },
            invoice_create_date: {
                required: true
            }
        },
        messages: {

        }
    });
    $('#form_edit_customer_invoice').ajaxForm({
        beforeSubmit: function () {
            return $("#form_edit_customer_invoice").valid(); // TRUE when form is valid, FALSE will cancel submit
        },
        success: function (returnData) {
            obj = JSON.parse(returnData);
            notification(obj);
        }
    });

    //add-customer order details-form validation and submit
    $("#form_add_customer_invoice_details").validate({
        rules: {
            sz_id_add: {
                required: true,
            },
            pod_quantity_add: {
                required: true,
            },
            c_id_add: {
                required: true,
            }
        },
        messages: {

        }
    });
    $('#form_add_customer_invoice_details').ajaxForm({
        beforeSubmit: function () {
			$('#loading_div').show();
			$('#detail_add').prop( "disabled", true );
            return $("#form_add_customer_invoice_details").valid(); // TRUE when form is valid, FALSE will cancel submit
        },
        success: function (returnData) {
            // console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);
			$('#loading_div').hide();
			$('#detail_add').prop( "disabled", false );

            /*$customer_order_total_amount = parseFloat(obj.total_amount).toFixed(2);
            $customer_order_total_quantity = parseFloat(obj.total_qnty).toFixed(2);
            $("#customer_order_total_amount").text($customer_order_total_amount);
            $("#customer_order_total_quantity").text($customer_order_total_quantity);*/

             /*$('#form_add_customer_order_details')[0].reset(); //reset form
             $("#form_add_customer_order_details select").select2("val", ""); //reset all select2 fields
             $('#form_add_customer_order_details :radio').iCheck('update'); //reset all iCheck fields
             $("#form_add_customer_order_details").validate().resetForm();*/ //reset validation
            notification(obj);
            //$("#lc_id").select2('open');
            //refresh table
            //$('#co_details_table').DataTable().ajax.reload();
            
        }
    });

    //edit-customer order details-form validation and submit
    $("#form_edit_customer_invoice_details").validate({
        rules: {
            sz_id_edit: {
                required: true,
            },
            cus_order_quantity_edit: {
                required: true,
            },
            c_id_edit: {
                required: true,
            },
            paper_gsm_edit: {
                required: true,
            },
            paper_bf_edit: {
                required: true,
            }
        },
        messages: {
            
        }
    });

    $('#form_edit_customer_invoice_details').ajaxForm({
        beforeSubmit: function () {
            return $("#form_edit_customer_invoice_details").valid(); // TRUE when form is valid, FALSE will cancel submit
        },
        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);

            $customer_order_total_amount = parseFloat(obj.total_amount).toFixed(2);
            $customer_order_total_quantity = parseFloat(obj.total_qnty).toFixed(2);
            $("#customer_order_total_amount").text($customer_order_total_amount);
            $("#customer_order_total_quantity").text($customer_order_total_quantity);

            $('#form_add_customer_order_details')[0].reset(); //reset form
            // $("#form_add_customer_order_details select").select2("val", ""); //reset all select2 fields
            // $('#form_add_customer_order_details :radio').iCheck('update'); //reset all iCheck fields
            $("#form_add_customer_order_details").validate().resetForm(); //reset validation
            notification(obj);
            
            //refresh table
            $('#co_details_table').DataTable().ajax.reload();
            
        }
    });

    //article-costing-measurement edit button
    $("#co_details_table").on('click', '.customer_details_edit_btn', function() {
        $cod_id = $(this).attr('cod_id');

        $.ajax({
            url: "<?= base_url('admin/ajax_fetch_customer_order_details_on_pk') ?>",
            method: "post",
            dataType: 'json',
            data: {'cod_id': $cod_id,},
            success: function(data){
                console.log(data);
                data = data[0];
				
				$("#sz_id_edit").select2('destroy'); 
    			$("#sz_id_edit").val(data.sz_id);
    			$("#sz_id_edit").select2();
				
				$("#cus_order_quantity_edit").val(data.cus_order_quantity);
				
				$("#c_id_edit").select2('destroy'); 
    			$("#c_id_edit").val(data.c_id);
    			$("#c_id_edit").select2();
				
				$("#paper_gsm_edit").val(data.paper_gsm);
				
				$("#paper_bf_edit").val(data.paper_bf);

                $("#cod_id").val($cod_id);
                $('#co_details_edit_tab').removeClass('disabled');
                $('#co_details_edit_tab').children("a").attr("data-toggle", 'tab');
                // $('#co_details_edit_tab li:eq(2) a').tab('show');
                $('a[href="#co_details_edit"]').tab('show');
            },
        });
    });


    //toastr notification
    function notification(obj) {
        toastr[obj.type](obj.msg, obj.title, {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "7000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        })
    }

// delete area 
    
    $(document).on('click', '.delete', function(){
        $this = $(this);
        if(confirm("Are You Sure? This Process Can\'t be Undone.")){
            $pk = $(this).attr('data-pk');
            $tab = $(this).attr('data-tab');
            $header_id = $("#customer_order_id").val();
			$proforma_status = $(this).attr('proforma_status');

            $.ajax({
                url: "<?= base_url('admin/del-row-on-table-pk-customer-order/') ?>",
                dataType: 'json',
                type: 'POST',
                data: {pk: $pk, tab : $tab, co_id: $header_id, proforma_status: $proforma_status},
                success: function (returnData) {
                    console.log(returnData);
                    $this.closest('tr').remove();
                    
                    // obj = JSON.parse(returnData);
                    notification(returnData);

                    $customer_order_total_amount = parseFloat(returnData.total_amount).toFixed(2);
                    $customer_order_total_quantity = parseFloat(returnData.total_qnty).toFixed(2);
                    $("#customer_order_total_amount").text($customer_order_total_amount);
                    $("#customer_order_total_quantity").text($customer_order_total_quantity);
                    
                    //refresh table
                    $("#co_details_table").DataTable().ajax.reload();

                },
                error: function (returnData) {
                    obj = JSON.parse(returnData);
                    notification(obj);
                }
            });
        }
        
    });
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });    

    function fetch_color($am_id, $lc_id){
        $.ajax({
                url: "<?= base_url('ajax-fetch-article-colours') ?>",
                method: "post",
                dataType: 'json',
                data: {'am_id': $am_id,'lc_id': $lc_id},
                success: function(data){
                        // console.log(data);
                        if($lc_id == 'false'){
                            $("#lc_id").html("<option value=''>Select Leather Colour</option>");
                        }
                        $("#fc_id").html("<option value=''>Select Fitting Colour</option>");
                        $.each(data, function (index, itemData) {
                            if($lc_id == 'false'){
                                $str1 = '<option value="'+itemData.leather_id +'">'+ itemData.leather_color +' ['+ itemData.leather_code +']' +'</option>';
                                $("#lc_id").append($str1);
                                // $str2 = '<option value="'+itemData.fitting_id +'">'+ itemData.fitting_color +' ['+ itemData.fitting_code +']' +'</option>';
                                // $("#fc_id").append($str2);
                            }else{
                                $str2 = '<option selected value="'+itemData.fitting_id +'">'+ itemData.fitting_color +' ['+ itemData.fitting_code +']' +'</option>';
                                $("#fc_id").append($str2);
                                $('#fc_id').select2().trigger('change');
                            }
                        });
                        if($lc_id == 'false'){
                            $("#lc_id").select2('open');
                        }
                },
            });
    }

    function fetch_rate($am_id, $ptype){
        $.ajax({
                url: "<?= base_url('ajax-fetch-article-rate-on-type') ?>",
                method: "post",
                dataType: 'json',
                data: {'am_id': $am_id,'ptype': $ptype},
                success: function(data){
                    // console.log(data);
                    if(data == 0){
                        alert('No rate found for this article. Using 0.00 as reference.');
                        $("#article_rate").val('0.00');   
                    }else{
                        $("#article_rate").val(data);   
                    }
                },
                error: function(e){
                    console.log(e);
                }
            });
    }

</script>

</body>
</html>
