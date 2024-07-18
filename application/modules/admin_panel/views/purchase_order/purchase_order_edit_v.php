<!DOCTYPE html>

<html lang="en">
    <head>
    <title>Edit Purchase Order |
    <?=WEBSITE_NAME;?>
    </title>
    <meta name="description" content="edit Purchase Order">

    <!--Data Table-->

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>

    <!--Select2-->

    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">

    <!--iCheck-->

    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <!-- common head -->

    <?php $this->load->view('components/_common_head'); ?>

    <!-- /common head -->

    <style>
/* Chrome, Safari, Edge, Opera */


input::-webkit-outer-spin-button,  input::-webkit-inner-spin-button {
 -webkit-appearance: none;
 margin: 0;
 text-align: right;
}
/* Firefox */

input[type=number] {
	text-align: right;
	-moz-appearance: textfield;
}
.border-black-bottom {
	border-bottom: 1px dotted #000
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
          <h3 class="m-b-less">Edit Purchase Order</h3>
          <div class="state-information">
        <ol class="breadcrumb m-b-less bg-less">
              <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
              <li class="active"> Edit Purchase Order </li>
            </ol>
      </div>
        </div>
    
    <!-- page head end--> 
    
    <!--body wrapper start-->
    
    <div class="wrapper"> 
          
          <!--Edit Article Costing-->
          
          <div class="row">
        <div class="col-md-12">
              <section class="panel">
            <header class="panel-heading"> Edit
                  <?= $purchase_order_details[0]->po_number ?>
                  <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            <div class="panel-body">
                  <!-- < ?php #print_r($purchase_order_details); die;?> -->
                  <form id="form_edit_purchase_order" method="post" action="<?=base_url('admin/form_edit_purchase_order')?>" class="cmxform form-horizontal tasi-form">
                <div class="form-group ">
                      <div class="col-lg-4">
                    <label for="po_number" class="control-label text-danger">Purchase Order Number*</label>
                    <br />
                    <input id="po_number" name="po_number" type="text" required="" placeholder="Purchase Order Number" class="form-control round-input" value="<?=$purchase_order_details[0]->po_number ?>" />
                    
                    <!-- <label><b>< ?= $purchase_order_details[0]->po_number ?></b></label> -->
                  </div>
                    <div class="col-lg-4">
                    <label for="acc_master_id" class="control-label">Supplier</label>
                    <br />
                    <label><b>
                    <?= $purchase_order_details[0]->name . '['. $purchase_order_details[0]->short_name .']' ?>
                    </b></label>
                    </div>
                    <div class="col-lg-4">
                    <label for="acc_master_id" class="control-label">Supplier Invoice Number</label>
                    <br />
                    <input id="po_invoice_number" name="po_invoice_number" type="text" placeholder="Supplier Invoice Number" class="form-control round-input" value="<?= $purchase_order_details[0]->po_invoice_number?>" />
                    </div>
                    </div>
                <div class="form-group ">
                    <div class="col-lg-4">
                    <label for="mill_id" class="control-label">Select Mill</label>
                    <select name="mill_id" class="form-control select2">
                    <option value="">Select Mill</option>
						<?php
                        
                        foreach($mill_details as $md){
                        $mn = ($md->mill_short_code == '' ? '-' : $md->mill_short_code);
                        
                        ?>
            <option <?= ($md->mill_id == $purchase_order_details[0]->mill_id) ? 'selected' : '' ?> value="<?= $md->mill_id ?>"><?= $md->mill_name . ' ['. $mn .']' ?></option>
                        <?php
                        }
                        ?>
                    </select>
                  </div>
                      <div class="col-lg-4">
                    <label for="po_date" class="control-label text-danger">Purchase Order Date *</label>
                    <input value="<?= date('Y-m-d', strtotime($purchase_order_details[0]->po_date)) ?>" id="po_date" name="po_date" type="date" placeholder="Purchase Order Date" class="form-control round-input" />
                  </div>
                      <div class="col-lg-4">
                    <label for="delivery_date" class="control-label text-danger">Delivery Date *</label>
                    <input value="<?= date('Y-m-d', strtotime($purchase_order_details[0]->po_delivery_date)) ?>" id="delivery_date" name="delivery_date" type="date" placeholder="Delivery Date" class="form-control round-input" />
                  </div>
                    </div>
                <div class="form-group ">
                      <div class="col-lg-4">
                    <label for="remarks" class="control-label">Remarks</label>
                    <textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"><?= $purchase_order_details[0]->remarks ?>
</textarea>
                  </div>
                      <div class="col-lg-4">
                    <label for="terms" class="control-label">Terms & Conditions</label>
                    <textarea id="terms" name="terms" placeholder="Terms & Conditions" class="form-control round-input"><?= $purchase_order_details[0]->terms?>
</textarea>
                  </div>
                 <div class="col-lg-2">
                    <label class="control-label text-danger">Status *</label>
                    <br />
                    <input type="radio" name="status" id="enable" value="1" <?= ($purchase_order_details[0]->status == 1) ? 'checked' : '' ?> required class="iCheck-square-green">
                    <label for="enable" class="control-label">Enable</label>
                    <input type="radio" name="status" id="disable" value="0" <?= ($purchase_order_details[0]->status == 0) ? 'checked' : '' ?> required class="iCheck-square-red">
                    <label for="disable" class="control-label">Disable</label>
                  </div>
                  
                  <div class="col-lg-2">
                     <?php if($purchase_order_details[0]->img != ''){ ?>
                    <label for="terms" class="control-label">Download Attachment</label>
                        <div class="panel-body">
                            <a href="<?= base_url('assets/admin_panel/img/supplier_po') .'/' . $purchase_order_details[0]->img ?>" class="" download><strong>Download Document</strong></a>   
                            <hr />
                        </div>
                        <?php } ?>
                  </div>
                  
                    </div>
                <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-3">
                    <button class="btn btn-success" type="submit"><i class="fa fa-refresh"> Update Purchase Order</i></button>
                  </div>
                      <!--<div class="col-sm-3">
                    <button id="print_all" type="button" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                  </div>-->
                    </div>
                <input type="hidden" id="purchase_order_id" name="purchase_order_id" class="hidden" value="<?= $purchase_order_details[0]->po_id ?>" />
              </form>
                </div>
          </section>
            </div>
            
        <!--<div class="col-md-2 hidden-xs">
              <section class="panel">
            <header class="panel-heading"> Buyer: <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            
            <div class="panel-body">
                  <p class='text-center'> <a target="_blank" class="badge bg-primary" href="<?= base_url('admin_panel/Master/account_master/edit') ?>/<?= $purchase_order_details[0]->am_id ?>">
                    <?= $purchase_order_details[0]->name . '['. $purchase_order_details[0]->short_name .']' ?>
                    </a></p>
                  <hr />
                </div>
          </section>
            </div>-->
            
      </div>
          <div class="row">
        <div class="col-md-12">
              <section class="panel">
            <header class="panel-heading"> Add purchase order details for
                  <?= $purchase_order_details[0]->po_number ?>
                  <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            <div class="panel-body"> 
                  
                  <!--Tabs-->
                  
                  <ul id="purchase_order_tabs" class="nav nav-tabs nav-justified">
                <li class="active"><a href="#po_list" data-toggle="tab">List</a></li>
                <li><a href="#po_add" data-toggle="tab">Add</a></li>
                <li id="po_details_edit_tab" class="disabled"><a href="#po_details_edit" data-toggle="">Edit</a></li>
              </ul>
                  
                  <!--Tab Content-->
                  
                  <div class="tab-content"> <img id="pod_edit_loader" class="hidden" style="display:block; margin: auto" src="<?= base_url('assets/img/ellipsis.gif') ?>" alt="" />
                <div id="po_list" class="tab-pane fade in active">
                      <table id="po_details_table" class="table data-table dataTable">
                    <thead>
                      <tr>
                        <th>Sl. No.</th>
                        <th>Consignment No.</th>
                        <th>Item Description</th>
                        <th>Size(Cm.)</th>
                        <th>GSM</th>
                        <th>BF</th>
                        <th>Action</th>
                      </tr>
                        </thead>
                    <tbody>
                        </tbody>
                  </table>
                    </div>
                <div id="po_add" class="tab-pane fade"> <br/>
                      <div class="form">
                    <form id="form_add_purchase_order_details" method="post" action="<?=base_url('admin/form_add_purchase_order_details')?>" class="cmxform form-horizontal tasi-form">
                          <div class="form-group ">
                        	<div class="col-lg-3">
                              <label for="roll_handel" class="control-label text-danger">Roll/Handel*</label>
                              <select id="roll_handel" name="roll_handel" required class="select2 form-control round-input">
                                <option value="">Select Roll/Handel</option>
                                <option value="0">Roll</option>
                                <option value="1">Handel</option>
                              </select>
                            </div>
                            
                            
                            <div class="col-lg-3">
                              <label for="colour" class="control-label text-danger">Colour*</label>
                              <select id="c_id" name="c_id" required class="select2 form-control round-input">
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
                            
                            <div class="col-lg-3">
                                  <label for="size_in_text" class="control-label text-danger">Size *</label>
                                  <input type="number" step="0.001" id="size_in_text" name="size_in_text" required class="form-control" />
                            </div>

                            <div class="col-lg-3">
                            <label for="u_id" class="control-label text-danger">Unit*</label>
                            <input type="hidden" step="0.001" id="pod_quantity" name="pod_quantity" value="1" class="form-control" />
                            <select id="u_id" name="u_id" required class="select2 form-control round-input">
                                <option value="">Select Unit</option>
                               <?php
                               foreach($units as $unit){
                                ?>
                                    <option value="<?=$unit->u_id?>"><?=$unit->unit?></option>
                                <?php   
                               }
                               ?>
                            </select>
                        </div>

                      </div>
                          <div class="form-group ">
                        
                        <div class="col-lg-3">
                              <label for="roll_weight" class="control-label text-danger">Roll Weight(MT)*</label>
                              <input type="number" step="0.001" id="roll_weight" name="roll_weight" required class="form-control" />
                            </div>
                        
                        <div class="col-lg-3">
                              <label for="rate_per_unit" class="control-label text-danger">Rate/unit *</label>
                              <input type="number" step="0.001" id="rate_per_unit" name="rate_per_unit" required class="form-control" />
                            </div>
                        <div class="col-lg-3 border-black-bottom">
                              <label for="pod_total" class="control-label">Total</label>
                              <br />
                              <label id="pod_total"></label>
                            </div>
                        <div class="col-lg-3">
                              <label for="pod_remarks" class="control-label">Remarks</label>
                              <input type="text" id="pod_remarks" name="pod_remarks" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group ">    
                            <div class="col-lg-3">
                              <label for="consignement_number" class="control-label text-danger">Consignement Number*</label>
                              <input type="text" id="consignement_number" name="consignement_number" value="<?=$consignement_number?>" required class="form-control" readonly />
                            </div> 
                            
                            <div class="col-lg-3">
                            <label for="paper_gsm_add" class="control-label">Paper GSM</label>
                            <input type="text" id="paper_gsm_add" name="paper_gsm_add" class="form-control" />
                            </div>
                            
                            <div class="col-lg-3">
                            <label for="paper_bf_add" class="control-label">Paper BF</label>
                            <input type="text" id="paper_bf_add" name="paper_bf_add" class="form-control" />
                            </div>                          
                            
                            <div class="col-lg-3">
                              <label for="number_of_copy" class="control-label">Number of Copy</label>
                              <input type="text" id="number_of_copy" name="number_of_copy" value="" class="form-control" />
                            </div>
                            
                      </div>
                      
                      
                      
                      <div class="form-group">
                        
                      </div>
                      
                          <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Add details</button>
                            </div>
                      </div>
                          <input type="hidden" name="purchase_order_id" class="hidden" value="<?= $purchase_order_details[0]->po_id ?>" />
                        </form>
                  </div>
                    </div>
                <div id="po_details_edit" class="tab-pane"> <br/>
                      <div class="form">
                    <form id="form_edit_purchase_order_details" method="post" action="<?=base_url('admin/form_edit_purchase_order_details')?>" class="cmxform form-horizontal tasi-form">
                          <div class="form-group ">
                          <div class="col-lg-3">
                              <label for="roll_handel" class="control-label text-danger">Roll/Handel*</label>
                              <select id="roll_handel_edit" name="roll_handel" required class="select2 form-control round-input">
                                <option value="">Select Roll/Handel</option>
                                <option value="0">Roll</option>
                                <option value="1">Handel</option>
                              </select>
                            </div>
                            
                            <div class="col-lg-3">
                              <label for="c_id_edit" class="control-label text-danger">Colour*</label>
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
                            
                            
                            <div class="col-lg-3">
                                  <label for="size_in_text_edit" class="control-label text-danger">Size *</label>
                                  <input type="number" step="0.001" id="size_in_text_edit" name="size_in_text_edit" required class="form-control" />
                            </div>

                            <div class="col-lg-3">
                                <label for="u_id_edit" class="control-label text-danger">Unit*</label>
                                <input type="hidden" step="0.001" id="pod_quantity_edit" name="pod_quantity" value="1" class="form-control" />
                                <select id="u_id_edit" name="u_id_edit" required class="select2 form-control round-input">
                                    <option value="">Select Unit</option>
                                   <?php
                                   foreach($units as $unit){
                                    ?>
                                        <option value="<?=$unit->u_id?>"><?=$unit->unit?></option>
                                    <?php   
                                   }
                                   ?>
                                </select>
                            </div>

                      		</div>
                          	<div class="form-group ">
                            <div class="col-lg-3">
                              <label for="roll_weight_edit" class="control-label text-danger">Roll Weight(MT)*</label>
                              <input type="number" step="0.001" id="roll_weight_edit" name="roll_weight" required class="form-control" readonly/>
                            </div>
                            
                            <div class="col-lg-3">
                              <label for="rate_per_unit_edit" class="control-label text-danger">Rate/Unit *</label>
                              <input type="number" step="0.001" id="rate_per_unit_edit" name="rate_per_unit" required class="form-control" readonly/>
                            </div>
                            <div class="col-lg-3 border-black-bottom">
                              <label for="pod_total_edit" class="control-label">Total</label>
                              <br />
                              <label id="pod_total_edit"></label>
                            </div>
                            
                            <div class="col-lg-3">
                              <label for="pod_remarks_edit" class="control-label">Remarks</label>
                              <input type="text" id="pod_remarks_edit" name="pod_remarks_edit" class="form-control" />
                            </div>
                      		</div>
                          	<div class="form-group ">
                            
                           <div class="col-lg-3">
                              <label for="consignement_number_edit" class="control-label text-danger">Consignement Number*</label>
                              <input type="text" id="consignement_number_edit" name="consignement_number" value="" required class="form-control" readonly />
                            </div>
                            
                            <div class="col-lg-3">
                            <label for="paper_gsm_edit" class="control-label">Paper GSM</label>
                            <input type="text" id="paper_gsm_edit" name="paper_gsm_edit" class="form-control" />
                            </div>
                            
                            <div class="col-lg-3">
                            <label for="paper_bf_edit" class="control-label">Paper BF</label>
                            <input type="text" id="paper_bf_edit" name="paper_bf_edit" class="form-control" />
                            </div>                              
                      </div>
                      
                          <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Update details</button>
                            </div>
                      </div>
                          <input type="hidden" name="purchase_order_id" class="hidden" value="<?= $purchase_order_details[0]->po_id ?>" />
                          <input type="hidden" name="pod_id" id="pod_id" class="hidden" value="" />
                        </form>
                  </div>
                    </div>
              </div>
                </div>
          </section>
            </div>
        <!--<div class="col-md-2">
              <section class="panel">
            <header class="panel-heading"> Total: <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            <div class="panel-body other-charges">
                  <label><strong>Total Amount</strong></label>
                  <br />
                  <div id="purchase_order_total_amount" class="bg-dark text-center" style="padding: 2%">
                <?= $purchase_order_details[0]->po_total ?>
              </div>
                </div>
          </section>
            </div>-->
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

        $('#po_details_table').DataTable( {

            "processing": true,

            "language": {

                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',

            },

            "serverSide": true,

            "ajax": {

                "url": "<?=base_url('admin/ajax_purchase_order_details_table_data')?>",

                "type": "POST",

                "dataType": "json",

                data: {

                    purchase_order_id: function () {

                        return $("#purchase_order_id").val();

                    },

                },

            },

            //will get these values from JSON 'data' variable

            "columns": [
                
				{ "data": "sl_no" },
        { "data": "consignement_number" },
				{ "data": "item_description" },
                { "data": "size" },
                { "data": "gsm" },
                { "data": "bf" },
                { "data": "action" },
            ],

            //column initialisation properties

            "columnDefs": [{

                "targets": [6],

                "orderable": false,

            }]

        } );  

    });
	
    $("#ig_id").change(function(){

        $ig_id = $(this).val();

        $.ajax({

            url: "<?= base_url('admin/all-items-on-item-group') ?>",

            method: "post",

            dataType: 'json',

            data: {'item_group': $ig_id,},

            success: function(all_items){

                console.log(all_items);

                $("#pod_unit").html("<b>" +all_items[0].unit+ '</b>');

                $("#id_id").html("");

                $.each(all_items, function(index, item) {

                    $str = '<option data-im-id = '+ item.im_id +' value=' + item.id_id + ' item_group_val=' + item.value + ' unit=' + item.unit + '> '+ item.item_name + '</option>';

                    $("#id_id").append($str);

                });

                // open the item tray 

                $('#id_id').select2('open');

            },

            error: function(e){console.log(e);}

        });

    });



    $(document).on('change', '#id_id', function(){

        $im_id = $(this).find(':selected').data('im-id');
        
        // alert($im_id);

        $.ajax({

            url: "<?= base_url('admin/all-colors-on-item-master') ?>",

            method: "post",
            dataType: 'json',
            data: {'item_id': $im_id,},
            success: function(all_colors){
                // console.log(all_items);
                $("#color").html("");

                $.each(all_colors, function(index, item) {

                    $str = '<option value=' + item.item_dtl_id + '> '+ item.color + '</option>';

                    $("#color").append($str);

                });

                // open the item tray 

                $('#color').select2('open');

            },

            error: function(e){console.log(e);}

        });

    });





    // ADD - multiply for item_amount

    $("#roll_weight, #rate_per_unit").on('change', function () {

        $roll_weight = $("#roll_weight").val();

        $pod_rate = $("#rate_per_unit").val();

        $("#pod_total").html("<b>" +($roll_weight * $pod_rate).toFixed(3) + "</b>");

    });

// EDIT - multiply for item_amount

    $("#roll_weight_edit, #rate_per_unit_edit").on('change', function () {

        $roll_weight_edit = $("#roll_weight_edit").val();

        $pod_rate_edit = $("#rate_per_unit_edit").val();

        $("#pod_total_edit").html("<b>" +($roll_weight_edit * $pod_rate_edit).toFixed(3)+ "</b>");

    });



    

    $("#form_edit_purchase_order").validate({

        rules: {

            po_number:{
              required: true
            },

            po_date: {

                required: true

            },

            delivery_date: {

                required: true

            },

        },

        messages: {



        }

    });

    $('#form_edit_purchase_order').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_purchase_order").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {

            obj = JSON.parse(returnData);

            notification(obj);

        }

    });



    //add-purchase order details-form validation and submit

    $("#form_add_purchase_order_details").validate({

        rules: {

            roll_handel: {

                required: true,

            },

            roll_weight: {

                required: true,

            },

            /*color: {    
                required: true,
                 remote: {
                     url: "<?=base_url('admin/ajax_unique_po_number_and_art_no_and_lth_color')?>",
                     type: "post",
                     data: {
                         purchase_order_id: function () {
                             return $("#purchase_order_id").val();
                         },
                         id_id: function () {
                             return $("#id_id").val();
                         },
                         color: function () {
                             return $("#color").val();
                         },
                     },
                 },
            },*/
            c_id: {

                required: true,

            },

            size_in_text: {

                required: true,

            },

            pod_quantity: {

                required: true,

            },

            u_id: {

                required: true,

            },

            rate_per_unit: {

                required: true,

            },

            consignement_number: {

                required: true,

            }

        },

        messages: {



        }

    });

    $('#form_add_purchase_order_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_add_purchase_order_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);

            $purchase_order_total_amount = parseFloat(obj.pod_total).toFixed(2);
            $("#purchase_order_total_amount").text($purchase_order_total_amount);
			
			
			$consignement_number = obj.consignement_number;
			console.log('consignement_number: '+$consignement_number);
            

            $("#pod_total").html("");
            $('#color').select2('open');
            $('#form_add_purchase_order_details')[0].reset(); //reset form
            $("#form_add_purchase_order_details select").select2("val", ""); //reset all select2 fields
            // $('#form_add_purchase_order_details :radio').iCheck('update'); //reset all iCheck fields
            $("#form_add_purchase_order_details").validate().resetForm(); //reset validation
            notification(obj);
            //refresh table

            $('#po_details_table').DataTable().ajax.reload();
			$("#consignement_number").val($consignement_number);
        }

    });



    //edit-purchase order details-form validation and submit

    $("#form_edit_purchase_order_details").validate({

        rules: {

            roll_handel_edit: {

                required: true,

            },

            roll_weight_edit: {

                required: true,

            },

            c_id_edit: {

                required: true,

            },

            size_in_text_edit: {

                required: true,

            },

            u_id_edit: {

                required: true,

            },

            rate_per_unit_edit: {

                required: true,

            },

            consignement_number_edit: {

                required: true,

            }

        },

        messages: {

            

        }

    });



    $('#form_edit_purchase_order_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_purchase_order_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {

            console.log('RD => ' + returnData);

            obj = JSON.parse(returnData);



            $purchase_order_total_amount = parseFloat(obj.total_amount).toFixed(2);

            $purchase_order_total_quantity = parseFloat(obj.total_qnty).toFixed(2);

            //$("#purchase_order_total_amount").text($purchase_order_total_amount);

            //$("#purchase_order_total_quantity").text($purchase_order_total_quantity);

            $('#form_add_purchase_order_details')[0].reset(); //reset form

            // $("#form_add_purchase_order_details select").select2("val", ""); //reset all select2 fields

            // $('#form_add_purchase_order_details :radio').iCheck('update'); //reset all iCheck fields

            $("#form_add_purchase_order_details").validate().resetForm(); //reset validation

            notification(obj);

            $po_total = parseFloat(obj.pod_total).toFixed(2);

            //$("#purchase_order_total_amount").text($po_total);

            //refresh table

            $('#po_details_table').DataTable().ajax.reload();

            

        }

    });

    //article-costing-measurement edit button

    $("#po_details_table").on('click', '.purchase_details_edit_btn', function() {

        $("#pod_edit_loader").removeClass('hidden');



        $pod_id = $(this).attr('pod_id');



        $.ajax({

            url: "<?= base_url('admin/ajax_fetch_purchase_order_details_on_pk') ?>",

            method: "post",

            dataType: 'json',

            data: {'pod_id': $pod_id,},

            success: function(pod_data){

                console.log(pod_data);

                data = pod_data[0];

                if(data.roll_handel == '0'){
					var roll_txt = 'Roll';	
				}else{
					var roll_txt = 'Handel';	
				}//end
				
				$('#roll_handel_edit').select2('destroy');				
                $("#roll_handel_edit").val(data.roll_handel);
				$('#roll_handel_edit').select2();
				
				$("#roll_weight_edit").val(data.roll_weight);
				
				$('#c_id_edit').select2('destroy');				
                $("#c_id_edit").val(data.c_id);
				$('#c_id_edit').select2();
				
				$("#size_in_text_edit").val(data.size_in_text);
				/*$('#sz_id_edit').select2('destroy');				
                $("#sz_id_edit").val(data.sz_id);
				$('#sz_id_edit').select2();*/
				
				$('#u_id_edit').select2('destroy');				
                $("#u_id_edit").val(data.u_id);
				$('#u_id_edit').select2();

                //$("#pod_quantity_edit").val(data.pod_quantity);

                $("#rate_per_unit_edit").val(data.rate_per_unit);

                $("#pod_total_edit").html('<b>'+(Number(data.pod_total)).toFixed(3)+'</b>');

                $("#pod_remarks_edit").val(data.pod_remarks);
				$("#consignement_number_edit").val(data.consignement_number);
				$("#paper_gsm_edit").val(data.paper_gsm);
				$("#paper_bf_edit").val(data.paper_bf);

                $("#pod_id").val(data.pod_id);



                $('#po_details_edit_tab').removeClass('disabled');

                $('#po_details_edit_tab').children("a").attr("data-toggle", 'tab');

                // $('#po_details_edit_tab li:eq(2) a').tab('show');

                $('a[href="#po_details_edit"]').tab('show');

                $("#pod_edit_loader").addClass('hidden');

                

            }

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
			
			$id_id = $(this).attr('id-id');
			
            $header_id = $("#purchase_order_id").val();



            $.ajax({

                url: "<?= base_url('admin/del-row-on-table-pk-purchase-order-details') ?>",

                dataType: 'json',

                type: 'POST',

                data: {pk: $pk, tab : $tab, po_id: $header_id, id_id: $id_id},

                success: function (returnData) {

                    console.log(returnData);

                    $this.closest('tr').remove();

                    

                    // obj = JSON.parse(returnData);

                    notification(returnData);



                    $pod_total = parseFloat(returnData.pod_total).toFixed(2);

                    //$purchase_order_total_quantity = parseFloat(returnData.total_qnty).toFixed(2);

                    $("#purchase_order_total_amount").text($pod_total);

                    //$("#purchase_order_total_quantity").text($purchase_order_total_quantity);

                    

                    //refresh table

                    $("#po_details_table").DataTable().ajax.reload();



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



</script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script> 
<script>

    $("#print_all").click(function(){

        $poi = $("#purchase_order_id").val();

        $.confirm({

            title: 'Choose!',

            content: 'Choose printing methods from the below options',

            buttons: {

                printwithcode: {

                    text: 'With code',

                    btnClass: 'btn-blue',

                    keys: ['enter', 'shift'],

                    action: function(){

                        window.open("<?= base_url() ?>admin/purchase-order-print-with-code/"+ $poi, "_blank");

                    }

                },

                printwithoutcode: {

                    text: 'Without code',

                    btnClass: 'btn-blue',

                    keys: ['enter', 'shift'],

                    action: function(){

                        window.open("<?= base_url() ?>admin/purchase-order-print-without-code/"+ $poi, "_blank");

                    }

                },

                cancel: function () {}

            }

        });

    });

    

</script>
</body>
</html>
