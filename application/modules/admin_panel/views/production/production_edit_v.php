<!DOCTYPE html>

<html lang="en">
    <head>
    <title>Edit Production | <?=WEBSITE_NAME;?> </title>
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
    th{text-align:center;border: 1px solid #dcdcdc!important;}
    td{border: 1px solid #dedede;text-align: center;}
    td:last-child{text-align:left; white-space: nowrap;}
    .nowrap{ white-space: nowrap;}
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
          <h3 class="m-b-less">Edit Production</h3>
          <div class="state-information">
        <ol class="breadcrumb m-b-less bg-less">
              <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
              <li class="active"> Edit Production</li>
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
                  <?= $production_details[0]->prod_number ?>
                  <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            <div class="panel-body">
                  <?php #print_r($purchase_order_details); die;?>
                  <form id="form_edit_production" method="post" action="<?=base_url('admin/form_edit_production')?>" class="cmxform form-horizontal tasi-form">
                <div class="form-group ">
                  <div class="col-lg-4">
                    <label for="prod_number" class="control-label text-danger">Production Number *</label>
                    <input id="prod_number" name="prod_number" type="text" placeholder="Purchase Order Number" class="form-control round-input" value="<?= $production_details[0]->prod_number ?>" />
                  </div>
                  
                  <div class="col-lg-4">
                    <label for="pod_id" class="control-label text-danger">Select Consignement Number *</label>
                    <select name="pod_id" id="pod_id" class="form-control select2">
                    <option value="">Select Consignement Number</option>
                    <option value="<?= $production_details[0]->pod_id ?>" selected><?= $production_details[0]->consignement_number .' /'. $production_details[0]->size_in_text ?></option>
                    <?php
					foreach($cn_list as $cn){
					?>
					<option value="<?=$cn->pod_id ?>"><?=$cn->consignement_number.' /'. $cn->size_in_text ?></option>
					<?php
					}
					?>
                    </select>
                    <input type="hidden" name="pod_id_old" id="pod_id_old" value="<?= $production_details[0]->pod_id ?>">
                  </div>
                  
                  <div class="col-lg-4">
                    <label for="prod_date" class="control-label text-danger">Production Date *</label>
                    <input id="prod_date" name="prod_date" type="date" class="form-control round-input" value="<?= date('Y-m-d', strtotime($production_details[0]->prod_date))?>" />
                  </div>
                </div>
                
                <div class="form-group">
                <div class="col-lg-4">
                    <label for="total_roll_weight" class="control-label text-danger">Total Roll Weight*(Kg.)</label>
                    <input id="total_roll_weight" name="total_roll_weight" type="number" step="0.01" placeholder="Total Roll Weight" class="form-control round-input" value="<?=$production_details[0]->total_roll_weight?>"/>
                  </div>
                  
                <div class="col-lg-4">
                    <label for="prod_avg_weight" class="control-label text-danger">Average Weight*(gm/10 Pcs) </label>
                    <input id="prod_avg_weight" name="prod_avg_weight" type="number" step="0.01" placeholder="Average Weight" class="form-control round-input" value="<?=$production_details[0]->prod_avg_weight?>" />
                  </div>
                 <div class="col-lg-4">
                    <label for="expected_production" class="control-label">Expected Production(Pcs.)</label>
                    <input id="expected_production" name="expected_production" type="number" placeholder="Expected Production(Pcs.)" class="form-control round-input" readonly value="<?=$production_details[0]->expected_production?>"/>
                  </div>
                </div> 
                
                <div class="form-group">
                <div class="col-lg-4">
                    <label for="prod_bag_produced" class="control-label text-danger">Bag Produced(Pcs.)*</label>
                    <input id="prod_bag_produced" name="prod_bag_produced" type="number" step="0.01" placeholder="Bag Produced" class="form-control round-input" value="<?= $production_details[0]->prod_bag_produced ?>" readonly />
                  </div>
                  
                
                <div class="col-lg-4">
                    <label for="prod_wastage_pcs" class="control-label text-danger">Wastage(Pcs.)</label>
                    <input id="prod_wastage_pcs" name="prod_wastage_pcs" type="number" step="0.01" placeholder="Wastage(Pcs.)" class="form-control round-input" value="<?= $production_details[0]->prod_wastage_pcs ?>" readonly />
                  </div>
                <div class="col-lg-4">
                    <label for="prod_wastage_kg" class="control-label text-danger">Wastage(gm)</label>
                    <input id="prod_wastage_kg" name="prod_wastage_kg" type="number" step="0.01" placeholder="Wastage(gm)" class="form-control round-input" value="<?= $production_details[0]->prod_wastage_kg ?>" readonly />
                  </div>
                </div>
                
                <div class="form-group">  
                  <div class="col-lg-4">
                    <label for="remarks" class="control-label">Remarks</label>
                    <textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"><?= $production_details[0]->remarks ?></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label for="terms" class="control-label">Terms & Conditions</label>
                    <textarea id="terms" name="terms" placeholder="Terms & Conditions" class="form-control round-input"><?= $production_details[0]->terms ?></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label class="control-label text-danger">Status *</label>
                    <br />
                    <input type="radio" name="status" id="enable" value="1" <?php if($production_details[0]->status == '1'){ ?>checked<?php } ?> required class="iCheck-square-green">
                    <label for="enable" class="control-label">Enable</label>
                    <input type="radio" name="status" id="disable" value="0" <?php if($production_details[0]->status == '0'){ ?>checked<?php } ?> required class="iCheck-square-red">
                    <label for="disable" class="control-label">Disable</label>
                  </div>           
                </div>
                  
                  
                <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-3">
                    <button class="btn btn-success" type="submit"><i class="fa fa-refresh"> Update Production</i></button>
                  </div>
                    </div>
                <input type="hidden" id="prod_id" name="prod_id" class="hidden" value="<?= $production_details[0]->prod_id ?>" />
                <input type="hidden" name="pod_id_add" id="pod_id_add" class="hidden" value="<?= $production_details[0]->pod_id ?>" />
              </form>
                </div>
          </section>
            </div>
            
      </div>
          <div class="row">
        <div class="col-md-12">
              <section class="panel">
            <header class="panel-heading"> Add Production details for
                  <?= $production_details[0]->prod_number ?>
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
                      <table id="production_details_table" class="table data-table dataTable">
                    <thead>
                      <tr>
                        <th>C.N. No.</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Miter Start reading</th>
                        <th>Miter End reading</th>
                        <th>Actions</th>
                      </tr>
                        </thead>
                    <tbody>
                        </tbody>
                  </table>
                    </div>
                <div id="po_add" class="tab-pane fade"> <br/>
                      <div class="form">
                    <form id="form_add_production_details" method="post" action="<?=base_url('admin/form_add_production_details')?>" class="cmxform form-horizontal tasi-form">
                          <div class="form-group ">
                          	<div class="col-lg-3">
                                <label for="prod_detail_date_add" class="control-label text-danger">Production Date *</label>
                                <input id="prod_detail_date_add" name="prod_detail_date_add" type="date" class="form-control round-input" />
                              </div>  
                              
                              <div class="col-lg-3">
                                <label for="u_id_add" class="control-label text-danger">Unit*</label>
                                <select id="u_id_add" name="u_id_add" required class="select2 form-control round-input">
                                    <option value="">Select Unit</option>
                                   <?php
                                   foreach($units as $unit){
                                    ?>
                                        <option value="<?=$unit->u_id?>"><?=$unit->unit?></option>
                                    <?php   
                                   }
                                   ?>
                                </select>
                                <input type="hidden" name="u_id_add_hidden" id="u_id_add_hidden" value="">
                            </div>                      	
                            
                            <div class="col-lg-3">
                              <label for="product_category_id_add" class="control-label text-danger">Product Category*</label>
                                <select id="product_category_id_add" name="product_category_id_add" required class="select2 form-control round-input">
                                    <option value="">Select Product Category</option>
                                   <?php
                                   foreach($product_categories as $pc){
                                    ?>
                                        <option value="<?=$pc->pc_id?>"><?=$pc->category_name?></option>
                                    <?php   
                                   }
                                   ?>
                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label for="sz_id_add" class="control-label text-danger">Size*</label>
                                <select id="sz_id_add" name="sz_id_add" required class="select2 form-control round-input">
                                    <option value="">Select Size</option>
                                   <?php
                                   foreach($sizes as $sz){
									?>
										<option value="<?=$sz->sz_id?>"><?=$sz->size . ' (' . $sz->paper_gsm . ' GSM)'?></option>
									<?php   
								   }
								   ?>
                                </select>
                            </div>                            
                            
                            </div>
                            
                           <div class="form-group "> 
                           <div class="col-lg-3">
                                  <label for="prod_detail_quantity_add" class="control-label text-danger">Quantity *</label>
                                  <input type="number" step="0.01" id="prod_detail_quantity_add" name="prod_detail_quantity_add" required class="form-control" placeholder="Quantity" />
                            </div>
                            <div class="col-lg-3">
                              <label for="e_id_add" class="control-label text-danger">Employee*</label>
                              <select id="e_id_add" name="e_id_add" required class="select2 form-control round-input">
                                <option value="">Select Employee</option>
                                <?php foreach($employees as $em){ ?>
                                <option value="<?=$em->e_id?>"><?=$em->name.' ['.$em->e_code.']'?></option>
                                <?php } ?>
                              </select>
                            </div>
                            
                            <div class="col-lg-3">
                                  <label for="miter_start_reading_add" class="control-label text-danger">Miter Start reading *</label>
                                  <input type="number" id="miter_start_reading_add" name="miter_start_reading_add" required class="form-control" placeholder="Miter Start reading" />
                            </div>
                            
                            <div class="col-lg-3">
                                  <label for="miter_end_reading_add" class="control-label text-danger">Miter End reading *</label>
                                  <input type="number" id="miter_end_reading_add" name="miter_end_reading_add" required class="form-control" placeholder="Miter End reading" />
                            </div>
                          </div>
                          <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Add details</button>
                            </div>
                      </div>
                          <input type="hidden" name="prod_id_add" class="hidden" value="<?= $production_details[0]->prod_id ?>" />
                          <input type="hidden" name="pod_id_detail_add" id="pod_id_detail_add" class="hidden" value="<?= $production_details[0]->pod_id ?>" />
                        </form>
                  </div>
                    </div>
                <div id="po_details_edit" class="tab-pane"> <br/>
                      <div class="form">
                    <form id="form_edit_production_details" method="post" action="<?=base_url('admin/form_edit_production_details')?>" class="cmxform form-horizontal tasi-form">
                          <div class="form-group ">
                              
                            <div class="col-lg-3">
                              <label for="sz_id_edit" class="control-label text-danger">Employee*</label>
                                <select id="e_id_edit" name="e_id_edit" required class="select2 form-control round-input">
                                    <option value="">Select Employee</option>
                                    <?php foreach($employees as $em){ ?>
                                    <option value="<?=$em->e_id?>"><?=$em->name.' ['.$em->e_code.']'?></option>
                                <?php } ?>
                                </select>
                            </div>
                          
                            <div class="col-lg-2">
                                <label for="prod_detail_date_edit" class="control-label text-danger">Production Date *</label>
                                <input id="prod_detail_date_edit" name="prod_detail_date_edit" type="date" class="form-control round-input" />
                            </div>                        	
                            
                            <div class="col-lg-2">
                              <label for="product_category_id_edit" class="control-label text-danger">Product Category*</label>
                                <select id="product_category_id_edit" name="product_category_id_edit" required class="select2 form-control round-input">
                                    <option value="">Select Product Category</option>
                                   <?php
                                   foreach($product_categories as $pc){
                                    ?>
                                        <option value="<?=$pc->pc_id?>"><?=$pc->category_name?></option>
                                    <?php   
                                   }
                                   ?>
                                </select>
                            </div>


                            <div class="col-lg-3">
                                <label for="sz_id_edit" class="control-label text-danger">Size*</label>
                                <select id="sz_id_edit" name="sz_id_edit" required class="select2 form-control round-input">
                                    <option value="">Select Size</option>
                                   <?php
                                   foreach($sizes as $sz){
									?>
										<option value="<?=$sz->sz_id?>"><?=$sz->size?></option>
									<?php   
								   }
								   ?>
                                </select>
                            </div>
                            
                            <div class="col-lg-2">
                                  <label for="prod_detail_quantity_edit" class="control-label text-danger">Quantity *</label>
                                  <input type="number" step="0.01" id="prod_detail_quantity_edit" name="prod_detail_quantity_edit" required class="form-control" placeholder="Quantity" />
                                  <input type="hidden" step="0.01" id="prod_detail_hidden_quantity_edit" name="prod_detail_hidden_quantity_edit" required class="form-control" />
                            </div>
                            
                      </div>
                          
                          <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Update details</button>
                            </div>
                      </div>
                          <input type="hidden" name="prod_id_edit" class="hidden" value="<?= $production_details[0]->prod_id ?>" />
                          <input type="hidden" name="prod_detail_id" id="prod_detail_id" class="hidden" value="" />
                          <input type="hidden" name="pod_id_edit" id="pod_id_edit" class="hidden" value="" />
                          <input type="hidden" name="prod_id_edit" id="prod_id_edit" class="hidden" value="" />
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
                < ?= $purchase_order_details[0]->po_total ?>
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

	$("#total_roll_weight, #prod_avg_weight").on('change', function () {
		$total_roll_weight = $("#total_roll_weight").val();
		$prod_avg_weight = $("#prod_avg_weight").val();
		$total_roll_weight_in_gm = (parseFloat($total_roll_weight) * 1000 );
		$expected_output = $total_roll_weight_in_gm / $prod_avg_weight;
		$("#expected_production").val($expected_output.toFixed(2));
	});
	
	$("#prod_detail_quantity_add").on('change', function () {

      $.ajax({

            url: "<?= base_url('admin/ajax_fetch_last_miter_reading') ?>",
            method: "post",
            dataType: 'json',
            success: function(data){
              console.log(data);
              if(data.reading_option == "Last Value"){
                $("#miter_start_reading_add").val(data.last_reading);  
              }else{
                $("#miter_start_reading_add").val(data.max_reading);  
              }
              
            },
            complete: function(){
              $prod_detail_quantity_add = $("#prod_detail_quantity_add").val();
              $miter_start_reading_add = $("#miter_start_reading_add").val();
              $miter_end_reading_add = parseInt($prod_detail_quantity_add) + parseInt($miter_start_reading_add);
              $("#miter_end_reading_add").val($miter_end_reading_add);
            },
            error: function(e,x){
              alert('Error:' + e + '<->'+ x)
            }
          })
    });

  $("#miter_start_reading_add").on('change', function () {

      $prod_detail_quantity_add = $("#prod_detail_quantity_add").val();
      $miter_start_reading_add = $("#miter_start_reading_add").val();
      $miter_end_reading_add = parseInt($prod_detail_quantity_add) + parseInt($miter_start_reading_add);
      $("#miter_end_reading_add").val($miter_end_reading_add);

  });

	$(document).ready(function() {

        $('#production_details_table').DataTable( {

            "processing": true,

            "language": {

                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',

            },

            "serverSide": true,

            "ajax": {

                "url": "<?=base_url('admin/ajax_production_details_table_data')?>",

                "type": "POST",

                "dataType": "json",

                data: {

                    prod_id: function () {

                        return $("#prod_id").val();

                    },

                },

            },

            //will get these values from JSON 'data' variable

            "columns": [
        				{ "data": "cn_no" },
                { "data": "employee" },
        				{ "data": "production_detail_date" },
                { "data": "prod_paper_size" },
                { "data": "prod_detail_quantity" },
                { "data": "miter_start_reading" },
                { "data": "miter_end_reading" },
                { "data": "action" },

            ],

            //column initialisation properties

            "columnDefs": [{
                "targets": [5,4,3,6,7],
                "orderable": false,
            },{
                "className": "nowrap",
                "targets": [0,2,3]
            }]

        } );  

    });

    $("#form_edit_production").validate({

        rules: {

            prod_number: {

                required: true

            },

            pod_id: {

                required: true

            },

            prod_date: {

                required: true

            },

            total_roll_weight: {

                required: true

            },

            prod_avg_weight: {

                required: true

            }

        },

        messages: {

        }

    });

    $('#form_edit_production').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_production").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {

            obj = JSON.parse(returnData);

            notification(obj);

        }

    });
	
	//add Production details-form validation and submit

    $("#form_add_production_details").validate({

        rules: {

            prod_detail_date_add: {
                required: true,
            },

            product_category_id_add: {
                required: true,
            },

            sz_id_add: {
                required: true,
            },

            prod_detail_quantity_add: {
                required: true,
            },

            e_id_add: {
                required: true,
            },

            miter_start_reading_add: {
                required: true,
            },

            miter_end_reading_add: {
                required: true,
            }

        },

        messages: {



        }

    });

    $('#form_add_production_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_add_production_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);

            $prod_bag_produced_new = parseFloat(obj.prod_bag_produced_new).toFixed(2);
			$prod_wastage_pcs = parseFloat(obj.prod_wastage_pcs).toFixed(2);
			$prod_wastage_kg = parseFloat(obj.prod_wastage_kg).toFixed(2);
            $("#prod_bag_produced").val($prod_bag_produced_new); 
			$("#prod_wastage_pcs").val($prod_wastage_pcs);  
			$("#prod_wastage_kg").val($prod_wastage_kg);                 
            
            $('#form_add_production_details')[0].reset(); //reset form
            $("#form_add_production_details select").select2("val", ""); //reset all select2 fields
            // $('#form_add_purchase_order_details :radio').iCheck('update'); //reset all iCheck fields
            $("#form_add_production_details").validate().resetForm(); //reset validation
            notification(obj);
            //refresh table

            $('#production_details_table').DataTable().ajax.reload();
        }

    });
	
	
	//edit-purchase order details-form validation and submit

    $("#form_edit_production_details").validate({

        rules: {

            prod_detail_date_edit: {
                required: true,
            },

            product_category_id_add: {
                required: true,
            },

            sz_id_edit: {
                required: true,
            },

            prod_detail_quantity_edit: {
                required: true,
            }

        },

        messages: {
        }

    });
	
	 $('#form_edit_production_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_production_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {

            console.log('RD => ' + returnData);

            obj = JSON.parse(returnData);

            $prod_bag_produced_new = parseFloat(obj.prod_bag_produced_new).toFixed(2);
			$prod_wastage_pcs = parseFloat(obj.prod_wastage_pcs).toFixed(2);
			$prod_wastage_kg = parseFloat(obj.prod_wastage_kg).toFixed(2);
            $("#prod_bag_produced").val($prod_bag_produced_new); 
			$("#prod_wastage_pcs").val($prod_wastage_pcs);  
			$("#prod_wastage_kg").val($prod_wastage_kg); 
			
			$prod_detail_hidden_quantity_edit = parseFloat(obj.prod_detail_hidden_quantity_edit).toFixed(2);
			$("#prod_detail_hidden_quantity_edit").val($prod_detail_hidden_quantity_edit);

            notification(obj);

            //refresh table

            $('#production_details_table').DataTable().ajax.reload();

            

        }

    });

    //article-costing-measurement edit button

    $("#production_details_table").on('click', '.production_details_edit_btn', function() {

        $("#pod_edit_loader").removeClass('hidden');

        $prod_detail_id = $(this).attr('prod_detail_id');

        $.ajax({

            url: "<?= base_url('admin/ajax_fetch_production_details_on_pk') ?>",

            method: "post",

            dataType: 'json',

            data: {'prod_detail_id': $prod_detail_id,},

            success: function(pod_data){
                
                console.log(pod_data);
                data = pod_data[0];
        		    $("#prod_detail_date_edit").val(data.prod_detail_date);
        				
                $("#sz_id_edit").select2('destroy'); 
        				$("#sz_id_edit").val(data.sz_id);
        				$("#sz_id_edit").select2();

                $("#product_category_id_edit").select2('destroy'); 
                $("#product_category_id_edit").val(data.pc_id);
                $("#product_category_id_edit").select2();

                $("#e_id_edit").select2('destroy'); 
                $("#e_id_edit").val(data.e_id);
                $("#e_id_edit").select2();

        				$("#prod_detail_quantity_edit").val(data.prod_detail_quantity);
        				$("#prod_detail_hidden_quantity_edit").val(data.prod_detail_quantity);
        				$("#prod_detail_id").val(data.prod_detail_id);
        				$("#pod_id_edit").val(data.pod_id);
        				$("#prod_id_edit").val(data.prod_id);

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


$("#product_category_id_add").change(function(){

        $pc_id = $(this).val();

        $.ajax({

            url: "<?= base_url('admin/product-sizes-on-category') ?>",

            method: "post",

            dataType: 'json',

            data: {'pc_id': $pc_id,},

            success: function(all_items){

                console.log(all_items);
                
                $("#sz_id_add").html("");
                $.each(all_items, function(index, item) {
                    $str = '<option value=' + item.sz_id + ' > '+ item.size + '</option>';
                    $("#sz_id_add").append($str);
                });

                // open the item tray 
                $('#sz_id_add').select2('open');

            },

            error: function(e){console.log(e);}

        });

    });


// delete area 
   

    $(document).on('click', '.delete', function(){

        $this = $(this);

        if(confirm("Are You Sure? This Process Can\'t be Undone.")){

            $pk_val = $(this).attr('data-pk');
			$pk_name = $(this).attr('pk-name');

            $tab = $(this).attr('data-tab');

            $header_tab = $(this).attr('data-ref-tab');

            $data_quantity = $(this).attr('data-quantity');			
			
			$data_quantity = $(this).attr('data-quantity');
			$pod_id = $(this).attr('pod-id');
			
            $prod_id = $("#prod_id").val();


            $.ajax({

                url: "<?= base_url('admin/ajax_delete_production_detail_on_pk') ?>",

                dataType: 'json',

                type: 'POST',

                data: {header_tab: $header_tab, tab: $tab, pk_name : $pk_name, pk_val: $pk_val, data_quantity: $data_quantity, prod_id: $prod_id, pod_id: $pod_id},

                success: function (returnData) {

                    console.log(returnData);

                    $this.closest('tr').remove();
                    //obj = JSON.parse(returnData);

                    notification(returnData);

                    $prod_bag_produced_new = parseFloat(returnData.prod_bag_produced_new).toFixed(2);
                    $prod_wastage_pcs = parseFloat(returnData.prod_wastage_pcs).toFixed(2);
          					$prod_wastage_kg = parseFloat(returnData.prod_wastage_kg).toFixed(2);
          					$("#prod_bag_produced").val($prod_bag_produced_new); 
          					$("#prod_wastage_pcs").val($prod_wastage_pcs);  
          					$("#prod_wastage_kg").val($prod_wastage_kg);  
					
					//refresh table

                    $("#production_details_table").DataTable().ajax.reload();



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
