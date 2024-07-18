
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Customer Order | <?=WEBSITE_NAME;?></title>
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
            <h3 class="m-b-less">Edit Customer Order</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Edit Customer Order </li>
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
                            Edit <?= $customer_order_details[0]->co_no ?>
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">

                            <form id="form_edit_customer_order" method="post" action="<?=base_url('admin/form_edit_customer_order')?>" enctype="multipart/form-data" class="cmxform form-horizontal tasi-form">
                                <div class="form-group ">
                                    <div class="col-lg-4">
                                        <label for="acc_master_id" class="control-label text-danger">Buyer / Customer *</label>
                                        <select name="acc_master_id" id="acc_master_id" class="form-control select2">
                                            <option value="">Select Buyer/Customer</option>
                                            <?php
                                            foreach($buyer_details as $bd){
                                                $sn = ($bd->short_name == '' ? '-' : $bd->short_name);
                                            ?> 
                                            <option data-code="<?= ($sn == '') ? 'CO' : $sn ?>" <?= ($bd->am_id == $customer_order_details[0]->acc_master_id) ? 'selected' : '' ?> value="<?= $bd->am_id ?>"><?= $bd->name . ' ['. $sn .']' ?>
                                            </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="order_no" class="control-label text-danger">Order Number *</label>
                                        <input value="<?= $customer_order_details[0]->co_no ?>" id="order_no" name="order_no" type="text" placeholder="Order Number" class="form-control round-input" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="buyref" class="control-label">Buyer Ref. No.</label>
                                        <input value="<?= $customer_order_details[0]->buyer_reference_no ?>" id="buyref" name="buyref" type="text" placeholder="Buyer Ref. No." class="form-control round-input" />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-lg-4">
                                        <label for="order_date" class="control-label text-danger">Order Date *</label>
                                        <input value="<?= date('Y-m-d', strtotime($customer_order_details[0]->co_date)) ?>" id="order_date" name="order_date" type="date" placeholder="Order Date" class="form-control round-input" />
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="delv_date" class="control-label text-danger">Delivery Date *</label>
                                        <input value="<?= date('Y-m-d', strtotime($customer_order_details[0]->co_delivery_date)) ?>" id="delv_date" name="delv_date" type="date" placeholder="Delivery Date" class="form-control round-input" />
                                    </div>
                                    
                                    <div class="col-lg-4">
                                    	<label for="remarks" class="control-label">Remarks</label>
                                    	<textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"><?= $customer_order_details[0]->co_remarks ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="img" class="control-label">Documents</label>
                                        <input type="file" id="img" name="img" accept=".jpg,.jpeg,.png,.doc,.docx,.xls,.pdf" class="file" >
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label text-danger">Status *</label><br />
                                        <input type="radio" name="status" id="enable" value="1" <?= ($customer_order_details[0]->status == 1) ? 'checked' : '' ?> required class="iCheck-square-green">
                                        <label for="enable" class="control-label">Enable</label>

                                        <input type="radio" name="status" id="disable" value="0" <?= ($customer_order_details[0]->status == 0) ? 'checked' : '' ?> required class="iCheck-square-red">
                                        <label for="disable" class="control-label">Disable</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button class="btn btn-success" type="submit"><i class="fa fa-refresh"> Update Customer Order</i></button>
                                    </div>
                                    <!--<div class="col-sm-3">
                                        <a target="_blank" href="<?= base_url('admin/print-customer-order-consumption') .'/'. $customer_order_details[0]->co_id ?>" class="btn btn-primary"><i class="fa fa-print"></i> Consumption</a>
                                    </div>-->    
                                </div>
                                <input type="hidden" id="customer_order_id" name="order_id" class="hidden" value="<?= $customer_order_details[0]->co_id ?>" />
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
                            <p class='text-center'> <a target="_blank" class="badge bg-primary" href="<?= base_url('admin_panel/Master/account_master/edit') ?>/<?= $customer_order_details[0]->acc_master_id ?>"><?= $customer_order_details[0]->name . ' ['. $sn .']' ?></a></p>
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
                         <?php if($customer_order_details[0]->img != ''){ ?> 
                        <div class="panel-body">
                            <a href="<?= base_url('assets/admin_panel/img/customer_order') .'/' . $customer_order_details[0]->img ?>" class="" download>Download Document</a>   
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
                            Add customer order details for <?= $customer_order_details[0]->co_no ?>
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <!--Tabs-->
                            <ul id="customer_order_tabs" class="nav nav-tabs nav-justified">
                                <li class="active"><a href="#co_list" data-toggle="tab">List</a></li>
                                <li><a href="#co_add" data-toggle="tab">Add (auto)</a></li>
                                <li><a href="#co_add_man" data-toggle="tab">Add (manual)</a></li>
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
                                            <th>PO No.</th>
                                            <th>Consignment No.</th>
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
                                        <form id="form_add_customer_order_details" method="post" action="<?=base_url('admin/form_add_customer_order_details')?>" class="cmxform form-horizontal tasi-form">
                                         <div class="form-group ">                                            
                                            <div class="col-lg-4">
                                                <label for="sz_id_add" class="control-label text-danger">Size*</label>
                                                <select id="sz_id_add" name="sz_id_add" required class="select2 form-control round-input">
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
                                                    <label for="paper_gsm_add" class="control-label">Paper GSM</label>
                                                    <input type="number" id="paper_gsm_add" name="paper_gsm_add" class="form-control" readonly />
                                                </div>

                                             <div class="col-lg-2">
                                                    <label for="paper_bf_add" class="control-label">Paper BF</label>
                                                    <input type="number" id="paper_bf_add" name="paper_bf_add" class="form-control" readonly />
                                                </div>
                                                
                                                <div class="col-lg-2">
                                              <label for="colour" class="control-label text-danger">Colour*</label>
                                              <select id="c_id_add" name="c_id_add" required class="select2 form-control round-input">
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
                                                  <label for="cus_order_quantity_add" class="control-label text-danger">Paper Bag Quantity *</label>
                                                  <input type="number" step="0.01" id="cus_order_quantity_add" name="cus_order_quantity_add" required class="form-control" />
                                            </div>
                                          </div>
                                          
                                          
                                            <div class="form-group ">                      
                                                <!-- <div class="col-lg-2">
                                                <label for="article_remarks" class="control-label">&nbsp;</label><br />
                                                <button class="btn btn-success" type="submit" id="detail_add"><i class="fa fa-plus"></i> Add details</button>
                                                </div> -->

                                                <div class="col-lg-2">
                                                    <label for="" class="control-label">&nbsp;</label><br>
                                                    <button class="btn btn-success" style="margin: auto; display:block;" type="button" id="preview_btn"><i class="fa fa-search"></i> Preview</button>
                                                </div>

                                            </div>

                                            <div class="form-group" id="preview_table">
                                            

                                            </div>

                                            <input type="hidden" name="order_id" class="hidden" value="<?= $customer_order_details[0]->co_id ?>" />
                                        </form>
                                    </div>
                                </div>
                                
                                <div id="co_add_man" class="tab-pane fade">
                                    hi
                                </div>

                                <div id="co_details_edit" class="tab-pane">
                                    <br/>
                                    <div class="form">
                                        <form id="form_edit_customer_order_details" method="post" action="<?=base_url('admin/form_edit_customer_order_details')?>" class="cmxform form-horizontal tasi-form">
                                           <div class="form-group ">                                            
                                            <div class="col-lg-4">
                                                <label for="sz_id_edit" class="control-label text-danger">Size*</label>
                                                <input type="text" id="sz_id_edit" name="sz_id_edit" class="form-control" value="" readonly />
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
                                            <input type="hidden" name="order_id" class="hidden" value="<?= $customer_order_details[0]->co_id ?>" />
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
    $(document).on('click', '#preview_btn', function(){
        
        $cus_order_quantity_add = $('#cus_order_quantity_add').val();
        // alert($issue_quantity_prev);
        // alert($issue_quantity_preview);

        $sz_id_add = $('option:selected', "#sz_id_add").val();
          // alert($cus_order_quantity_add);  
        $.ajax({
            url: "<?= base_url('admin/ajax-get-consume-list-purchase-order-receive-detail') ?>",
            method: "post",
            dataType: 'json',
            data: {'sz_id_add': $sz_id_add, 'quantity' : $cus_order_quantity_add},
            success: function(response){
                
                $table = '';
                console.log(JSON.stringify(response.preview_data));
                $preview_data = response.preview_data;
                
                if(response.status == true){
                  $table += '<table class="table">';
                  $table += '<thead>';
                    $table += '<tr>';
                      $table += '<th scope="col">PO Number</th>';
                      $table += '<th scope="col">Consignment Number</th>';
                      $table += '<th scope="col" style="text-align: right;">Quantity</th>';
                    $table += '</tr>';
                  $table += '</thead>';
                  $table += '<tbody>';

                  var sum_q = 0;
                  var sum_r = 0;
                  var sum_t = 0;
                  
                  for($i = 0; $i < $preview_data.length; $i++){

                    sum_q += parseFloat($preview_data[$i].consumed);

                    $table += '<tr>';
                      $table += '<th scope="row">'+$preview_data[$i].po_number+'</th>';
                      $table += '<td>'+$preview_data[$i].consignement_number+'<input type="hidden" name="po_id[]" id="po_id_'+$preview_data[$i].pod_id+'" value="'+$preview_data[$i].po_id+'"><input type="hidden" name="pod_id[]" id="pod_id_'+$preview_data[$i].pod_id+'" value="'+$preview_data[$i].pod_id+'"></td>';
                      $table += '<td><input type="text" id="issue_quantity_'+$preview_data[$i].pod_id+'" name="issue_quantity[]" required class="form-control class_q" value="'+$preview_data[$i].consumed+'" readonly style="text-align: right;"/></td>';
                    $table += '</tr>';
                  }//end for
                $table += '</tbody>';
                $table += '<tfoot><tr><th colspan="2">Total</th><th id="tot_qn" style="text-align: right;">'+sum_q.toFixed(2)+'</th></tr></tfoot>';
                $table += '</table>';
                $table += '<div class="form-group">';
                    $table += '<div class="col-lg-4 col-lg-offset-4">';
                        $table += '<label for="" class="control-label">&nbsp;</label><br>';
                        $table += '<button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Add details</button>';
                    $table += '</div>';
                $table += '</div>';
                
                }else{
                    $table = 'Sorry! No preview data available';
                }

                $('#preview_table').html($table);

                },
            error: function(e){console.log(e);}
        });
        // alert($pur_order_rcv_detail);
    });//end function
</script>

<script>
    
    $(document).ready(function() {

       $("#sz_id_edit").change(function(){
			$sz_id_edit = $(this).val();
			
			$paper_gsm = $('#sz_id_edit option:selected').attr('paper-gsm');
			$("#paper_gsm_edit").val($paper_gsm);
			
			$paper_bf = $('#sz_id_edit option:selected').attr('paper-bf');
			$("#paper_bf_edit").val($paper_bf);
		});

		$("#sz_id_add").change(function(){
			$sz_id_add = $(this).val();
			
			$paper_gsm = $('#sz_id_add option:selected').attr('paper-gsm');
			$("#paper_gsm_add").val($paper_gsm);
			
			$paper_bf = $('#sz_id_add option:selected').attr('paper-bf');
			$("#paper_bf_add").val($paper_bf);
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
                { "data": "po_number" },
                { "data": "consignement_number" },
                { "data": "colour" },
                { "data": "roll_quantity" },
                { "data": "action" },
            ],
            //column initialisation properties
            "columnDefs": [{
                "targets": [5],
                "orderable": false,
            }]
        } );

       
    } );

    
    $("#form_edit_customer_order").validate({
        rules: {
            order_no: {
                required: true,
                remote: {
                    url: "<?=base_url('admin/ajax_unique_customer_order_no')?>",
                    type: "post",
                    data: {
                        customer_order_id: function () {
                            return $("#customer_order_id").val();
                        },
                        order_no: function () {
                            return $("#order_no").val();
                        },
                    },
                },
            },
        },
        messages: {

        }
    });
    $('#form_edit_customer_order').ajaxForm({
        beforeSubmit: function () {
            return $("#form_edit_customer_order").valid(); // TRUE when form is valid, FALSE will cancel submit
        },
        success: function (returnData) {
            obj = JSON.parse(returnData);
            notification(obj);
        }
    });

    //add-customer order details-form validation and submit
    $("#form_add_customer_order_details").validate({
        rules: {
            sz_id_add: {
                required: true,
            },
            pod_quantity_add: {
                required: true,
            },
            c_id_add: {
                required: true,
            },
            issue_quantity: {
                required: true,
            }
        },
        messages: {

        }
    });
    $('#form_add_customer_order_details').ajaxForm({
        beforeSubmit: function () {
			$('#loading_div').show();
			$('#detail_add').prop( "disabled", true );
            return $("#form_add_customer_order_details").valid(); // TRUE when form is valid, FALSE will cancel submit
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

             $('#form_add_customer_order_details')[0].reset(); //reset form
             $("#form_add_customer_order_details select").select2("val", ""); //reset all select2 fields
             $('#form_add_customer_order_details :radio').iCheck('update'); //reset all iCheck fields
             $("#form_add_customer_order_details").validate().resetForm(); //reset validation
            notification(obj);
            $("#lc_id").select2('open');
            //refresh table
            $('#co_details_table').DataTable().ajax.reload();
            
        }
    });

    //edit-customer order details-form validation and submit
    $("#form_edit_customer_order_details").validate({
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

    $('#form_edit_customer_order_details').ajaxForm({
        beforeSubmit: function () {
            return $("#form_edit_customer_order_details").valid(); // TRUE when form is valid, FALSE will cancel submit
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
				
    			$("#sz_id_edit").val(data.size);
				
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
    
    $("#acc_master_id").on('change', function(){
        $val = ($("#acc_master_id").select2().find(":selected").data("code"));
        console.log($val);
        if($val != 'CO'){
            string = $("#order_no").val();
            $ns = string.replace(/^.{2}/g, $val);
            $("#order_no").val($ns);
        }
        
    })

</script>

</body>
</html>
