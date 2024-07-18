<!DOCTYPE html>

<html lang="en">
    <head>
    <title>Edit Distribute | <?=WEBSITE_NAME;?> </title>
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
      
      <div class="body-content" style="min-height: 700px;"> 
    
    <!-- header section start-->
    
    <?php $this->load->view('components/top_menu'); ?>
    
    <!-- header section end--> 
    
    <!-- page head start-->
    
    <div class="page-head">
          <h3 class="m-b-less">Edit Distribute</h3>
          <div class="state-information">
        <ol class="breadcrumb m-b-less bg-less">
              <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
              <li class="active"> Edit Distribute </li>
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
                  <?= $distribute_details[0]->dis_number ?>
                  <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            <div class="panel-body">
                  <?php #print_r($purchase_order_details); die;?>
                  <form id="form_edit_distribute" method="post" action="<?=base_url('admin/form_edit_distribute')?>" class="cmxform form-horizontal tasi-form">
                <div class="form-group ">
                  <div class="col-lg-4">
                    <label for="dis_number" class="control-label text-danger">Distribute Number *</label>
                    <input id="dis_number" name="dis_number" type="text" placeholder="Distribute Number" class="form-control round-input" value="<?= $distribute_details[0]->dis_number ?>" />
                  </div>
                  
                  <div class="col-lg-4">
                    <label for="dis_date" class="control-label text-danger">Distribute Date *</label>
                    <input id="dis_date" name="dis_date" type="date" placeholder="Distribute Date" class="form-control round-input" value="<?= date('Y-m-d', strtotime($distribute_details[0]->dis_date)) ?>" />
                  </div>
                  
                  <div class="col-lg-4">
                    <label for="dis_return_date" class="control-label text-danger">Return Date *</label>
                    <input id="dis_return_date" name="dis_return_date" type="date" placeholder="Return Date" class="form-control round-input" value="<?= date('Y-m-d', strtotime($distribute_details[0]->dis_return_date)) ?>" />
                  </div>                 
                  
                </div>
                
                <div class="form-group ">
                  <div class="col-lg-4">
                    <label for="remarks" class="control-label">Remarks</label>
                    <textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"><?= $distribute_details[0]->remarks ?></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label for="terms" class="control-label">Terms & Conditions</label>
                    <textarea id="terms" name="terms" placeholder="Terms & Conditions" class="form-control round-input"><?= $distribute_details[0]->terms ?></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label class="control-label text-danger">Status *</label>
                    <br />
                    <input type="radio" name="status" id="enable" value="1" <?php if($distribute_details[0]->status == '1'){ ?> checked <?php } ?> required class="iCheck-square-green">
                    <label for="enable" class="control-label">Enable</label>
                    <input type="radio" name="status" id="disable" value="0" <?php if($distribute_details[0]->status == '0'){ ?> checked <?php } ?> class="iCheck-square-red">
                    <label for="disable" class="control-label">Disable</label>
                  </div>           
                </div>                
                
                <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-3">
                    <button class="btn btn-success" type="submit"><i class="fa fa-refresh"> Update Distribute</i></button>
                  </div>
                    </div>
                <input type="hidden" id="dis_id" name="dis_id" class="hidden" value="<?= $distribute_details[0]->dis_id ?>" />
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
            <header class="panel-heading"> Add Distribute details for
                  <?= $distribute_details[0]->dis_number ?>
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
                      <table id="distribute_details_table" class="table data-table dataTable">
                    <thead>
                      <tr>
                        <th>Employee</th>
                        <th>C.N. No.</th>
                        <th>Roll/Handle</th>
                        <th>Size</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                      </tr>
                        </thead>
                    <tbody>
                        </tbody>
                  </table>
                    </div>
                <div id="po_add" class="tab-pane fade"> <br/>
                      <div class="form">
                    <form id="form_add_distribute_details" method="post" action="<?=base_url('admin/form_add_distribute_details')?>" class="cmxform form-horizontal tasi-form">
                          <div class="form-group ">
                          	<div class="col-lg-3">
                              <label for="e_id_add" class="control-label text-danger">Employee*</label>
                              <select id="e_id_add" name="e_id_add" required class="select2 form-control round-input">
                                <option value="">Select Employee</option>
                                <?php 
                                foreach($employees as $em){ 
                                  if($em->name == ''){
                                    continue;
                                  }
                                ?>
                                <option value="<?=$em->e_id?>"><?=$em->name.' ['.$em->e_code.']'?></option>
                                <?php } ?>
                              </select>
                            </div>
                            
                          	<div class="col-lg-3">
                              <label for="pod_id_add" class="control-label text-danger">C.N. No.*</label>
                              <select id="pod_id_add" name="pod_id_add" required class="select2 form-control round-input">
                                <option value="">Select C.N. No.</option>
                                <?php foreach($consignement_number as $cn){ ?>
                                <option value="<?=$cn->pod_id?>"><?=$cn->consignement_number ?></option>
                                <?php } ?>
                              </select>
                            </div>                             
                            
                        	<div class="col-lg-3">
                              <label for="roll_handel_add" class="control-label text-danger">Roll/Handel*</label>
                              <select id="roll_handel_add" name="roll_handel_add" required class="select2 form-control round-input">
                                <option value="">Select Roll/Handel</option>
                                <option value="0">Roll</option>
                                <option value="1">Handel</option>
                              </select>
                              <input type="hidden" name="roll_handel_add_hidden" id="roll_handel_add_hidden" value="">
                            </div>
                            
                            <div class="col-lg-3">
                                <label for="sz_id_add" class="control-label text-danger">Size*</label>
                                <select id="sz_id_add" name="sz_id_add" required class="select2 form-control round-input">
                                    <option value="">Select Size</option>
                                </select>
                            </div>
                            </div>
                            <div class="form-group ">
                            
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
                                  <label for="prod_detail_quantity" class="control-label text-danger">Total Production *</label>
                                  <input type="number" step="0.01" id="prod_detail_quantity" name="prod_detail_quantity" required class="form-control" readonly />
                            </div>
                            
                            <div class="col-lg-3">
                                  <label for="distribute_pod_quantity_add" class="control-label text-danger">Distribute Quantity *</label>
                                  <input type="number" step="0.01" id="distribute_pod_quantity_add" name="distribute_pod_quantity_add" required class="form-control" placeholder="Distribute Quantity" />
                                  <input type="hidden" step="0.01" id="distribute_pod_quantity_add_hidden" name="distribute_pod_quantity_add_hidden" required class="form-control" />
                            </div>
                            
                            <div class="col-lg-3">
                                <label for="remarks_add" class="control-label">Remarks</label>
                                <textarea id="remarks_add" name="remarks_add" placeholder="Remarks" class="form-control round-input"> </textarea>
                              </div>
                              <div class="col-lg-3">
                                <label for="terms_add" class="control-label">Terms & Conditions</label>
                                <textarea id="terms_add" name="terms_add" placeholder="Terms & Conditions" class="form-control round-input"> </textarea>
                              </div>
                              
                            </div>
                          
                          <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Add details</button>
                            </div>
                      </div>
                          <input type="hidden" name="dis_id_add" class="hidden" value="<?= $distribute_details[0]->dis_id ?>" />
                        </form>
                  </div>
                    </div>
                <div id="po_details_edit" class="tab-pane"> <br/>
                      <div class="form">
                    <form id="form_edit_distribute_details" method="post" action="<?=base_url('admin/form_edit_distribute_details')?>" class="cmxform form-horizontal tasi-form">
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
                              <label for="roll_weight_edit" class="control-label text-danger">Roll Weight*</label>
                              <input type="number" step="0.01" id="roll_weight_edit" name="roll_weight" required class="form-control" />
                            </div>
                            
                            <div class="col-lg-3">
                              <label for="c_id_edit" class="control-label text-danger">Colour*</label>
                              <select id="c_id_edit" name="c_id" required class="select2 form-control round-input">
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
                                <label for="sz_id_edit" class="control-label text-danger">Size*</label>
                                <select id="sz_id_edit" name="sz_id" required class="select2 form-control round-input">
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
                      </div>
                          <div class="form-group ">
                        <div class="col-lg-3">
                              <label for="pod_quantity_edit" class="control-label text-danger">Quantity *</label>
                              <input type="number" step="0.01" id="pod_quantity_edit" name="pod_quantity" required class="form-control" />
                            </div>
                            <div class="col-lg-3">
                                <label for="u_id" class="control-label text-danger">Unit*</label>
                                <select id="u_id_edit" name="u_id" required class="select2 form-control round-input">
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
                            <div class="col-lg-3">
                              <label for="rate_per_unit_edit" class="control-label text-danger">Weight/Unit *</label>
                              <input type="number" step="0.01" id="rate_per_unit_edit" name="rate_per_unit" required class="form-control" />
                            </div>
                            <div class="col-lg-1 border-black-bottom">
                              <label for="pod_total_edit" class="control-label">Total</label>
                              <br />
                              <label id="pod_total_edit"></label>
                            </div>
                            <div class="col-lg-5">
                              <label for="pod_remarks_edit" class="control-label">Remarks</label>
                              <input type="text" id="pod_remarks_edit" name="pod_remarks_edit" class="form-control" />
                            </div>
                            
                           <div class="col-lg-3">
                              <label for="consignement_number_edit" class="control-label text-danger">Consignement Number*</label>
                              <input type="text" id="consignement_number_edit" name="consignement_number" value="" required class="form-control" />
                            </div>
                             
                      </div>
                          <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Update details</button>
                            </div>
                      </div>
                          <input type="hidden" name="dis_id_edit" class="hidden" value="<?= $distribute_details[0]->dis_id ?>" />
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

        $('#distribute_details_table').DataTable( {

            "processing": true,

            "language": {

                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',

            },

            "serverSide": true,

            "ajax": {

                "url": "<?=base_url('admin/ajax_distribute_details_table_data')?>",

                "type": "POST",

                "dataType": "json",

                data: {

                    dis_id: function () {

                        return $("#dis_id").val();

                    },

                },

            },

            //will get these values from JSON 'data' variable

            "columns": [

                { "data": "employee_name" },

                { "data": "consignment_number" },

                { "data": "roll_handel" },

                { "data": "size" },

                { "data": "unit" },

                { "data": "quantity" },

                { "data": "action" },

            ],

            //column initialisation properties

            "columnDefs": [{

                "targets": [6],

                "orderable": false,

            }]

        } );  

    });
	
    $("#pod_id_add").change(function(){

        $pod_id_add = $(this).val();

        $.ajax({

            url: "<?= base_url('admin/all-details-in-purchase-order') ?>",

            method: "post",

            dataType: 'json',

            data: {'pod_id': $pod_id_add,},

            success: function(all_items){

                console.log(JSON.stringify(all_items));
				data = all_items.purchase_order_details[0];

                if(data.roll_handel == '0'){
					var roll_txt = 'Roll';	
				}else{
					var roll_txt = 'Handel';	
				}//end
				

                $("#sz_id_add").html("");
				$("#sz_id_add").append('<option>Select Size</option>');
				var all_sizes = all_items.all_size;
                $.each(all_sizes, function(index, item) {

                    $str = '<option value=' + item.sz_id + ' data-prod-detail-quantity = '+ item.prod_detail_quantity +' max-allowed-to-distribute = '+ item.max_allowed_to_distribute +' > '+ item.size + '</option>';

                    $("#sz_id_add").append($str);

                });
                // open the item tray 
                $('#sz_id_add').select2('open');
				
                $("#roll_handel_add").html("<option>"+roll_txt+"</option>").trigger('change');
				$("#roll_handel_add_hidden").val(data.roll_handel);
				
				$("#u_id_add").html("<option>"+data.unit+"</option>").trigger('change');
				$("#u_id_add_hidden").val(data.u_id);
				
				$("#prod_detail_quantity").val('');
				$("#distribute_pod_quantity_add").val('');
				$("#distribute_pod_quantity_add_hidden").val('');

            },

            error: function(e){console.log(e);}

        });

    });
	
	
	$(document).on('change', '#sz_id_add', function(){
		$prod_detail_quantity = $('#sz_id_add option:selected').attr('data-prod-detail-quantity')
		$("#prod_detail_quantity").val($prod_detail_quantity);
		
		$max_allowed_to_distribute = $('#sz_id_add option:selected').attr('max-allowed-to-distribute')
		$("#distribute_pod_quantity_add").val($max_allowed_to_distribute);
		$("#distribute_pod_quantity_add_hidden").val($max_allowed_to_distribute);
		
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

    $("#distribute_pod_quantity_add").on('change', function () {

        $distribute_pod_quantity_add = $("#distribute_pod_quantity_add").val();

        $distribute_pod_quantity_add_hidden = $("#distribute_pod_quantity_add_hidden").val();

        if(parseFloat($distribute_pod_quantity_add) > parseFloat($distribute_pod_quantity_add_hidden)){
			alert('Maximum Distribute Quantity: '+$distribute_pod_quantity_add_hidden);
			$("#distribute_pod_quantity_add").val($distribute_pod_quantity_add_hidden);
		}

    });

// EDIT - multiply for item_amount

    $("#pod_quantity_edit, #rate_per_unit_edit").on('change', function () {

        $pod_quantity_edit = $("#pod_quantity_edit").val();

        $pod_rate_edit = $("#rate_per_unit_edit").val();

        $("#pod_total_edit").html("<b>" +($pod_quantity_edit * $pod_rate_edit).toFixed(2)+ "</b>");

    });
	
    $("#form_edit_distribute").validate({

        rules: {

            dis_number: {

                required: true

            },

            dis_date: {

                required: true

            },

            dis_return_date: {

                required: true

            },

            dis_date: {

                required: true

            }

        },

        messages: {

        }

    });

    $('#form_edit_distribute').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_distribute").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {

            obj = JSON.parse(returnData);

            notification(obj);

        }

    });



    //add-purchase order details-form validation and submit

    $("#form_add_distribute_details").validate({

        rules: {

            e_id_add: {

                required: true,

            },

            pod_id_add: {

                required: true,

            },
            roll_handel_add: {

                required: true,

            },

            sz_id_add: {

                required: true,

            },

            u_id_add: {

                required: true,

            },

            distribute_pod_quantity_add: {

                required: true,

            }

        },

        messages: {



        }

    });

    $('#form_add_distribute_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_add_distribute_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);

            $purchase_order_total_amount = parseFloat(obj.pod_total).toFixed(2);
            $("#purchase_order_total_amount").text($purchase_order_total_amount);
			
            
            $('#form_add_distribute_details')[0].reset(); //reset form
            $("#form_add_distribute_details select").select2("val", ""); //reset all select2 fields
            // $('#form_add_distribute_details :radio').iCheck('update'); //reset all iCheck fields
            $("#form_add_distribute_details").validate().resetForm(); //reset validation
            notification(obj);
            //refresh table
			
			//obj = JSON.parse(returnData);
			var cn_list = obj.cn_list;
			//console.log('cn_list: '+JSON.stringify(cn_list));
            $("#pod_id_add").html("");
			$("#pod_id_add").append('<option>Select CN No.</option>');

			$.each(cn_list, function(index, item) {

				$str = '<option value=' + item.pod_id + '> '+ item.consignement_number + '</option>';

				$("#pod_id_add").append($str);

			});

			// open the item tray 
			$('#pod_id_add').select2('open');

            $('#distribute_details_table').DataTable().ajax.reload();
        }

    });



    //edit-purchase order details-form validation and submit

    $("#form_edit_distribute_details").validate({

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

            sz_id_edit: {

                required: true,

            },

            pod_quantity_edit: {

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



    $('#form_edit_distribute_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_distribute_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);

            $purchase_order_total_amount = parseFloat(obj.total_amount).toFixed(2);

            $purchase_order_total_quantity = parseFloat(obj.total_qnty).toFixed(2);

            $('#form_add_distribute_details')[0].reset(); //reset form

            $("#form_add_distribute_details").validate().resetForm(); //reset validation

            notification(obj);

            $po_total = parseFloat(obj.pod_total).toFixed(2);

            //refresh table

            $('#distribute_details_table').DataTable().ajax.reload();

            

        }

    });



    //article-costing-measurement edit button

    $("#distribute_details_table").on('click', '.purchase_details_edit_btn', function() {

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
				
				//$("#roll_handel_edit").val(data.roll_handel);
                $("#roll_handel_edit").html("<option>"+roll_txt+"</option>").trigger('change');
				$("#roll_weight_edit").val(data.roll_weight);
				$("#c_id_edit").html("<option>"+data.color+"</option>").trigger('change');
				$("#sz_id_edit").html("<option>"+data.size+"</option>").trigger('change');

                $("#pod_unit_edit").html('<b>'+data.unit+'</b>');

                $("#pod_quantity_edit").val(data.pod_quantity);
				
				$("#u_id_edit").html("<option>"+data.unit+"</option>").trigger('change');

                $("#rate_per_unit_edit").val(data.rate_per_unit);

                $("#pod_total_edit").html('<b>'+(Number(data.pod_total)).toFixed(2)+'</b>');

                $("#pod_remarks_edit").val(data.pod_remarks);
				$("#consignement_number_edit").val(data.consignement_number);

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

            $pk_name = $(this).attr('pk-name');

            $pod_id = $(this).attr('pod-id');



            $.ajax({

                url: "<?= base_url('admin/del-row-on-table-pk-distribute-details') ?>",

                dataType: 'json',

                type: 'POST',

                data: {pk: $pk, tab : $tab, pk_name: $pk_name, pod_id: $pod_id},

                success: function (returnData) {

                    console.log(returnData);

                    $this.closest('tr').remove();
					notification(returnData);

                    $("#distribute_details_table").DataTable().ajax.reload();
					
                    obj = returnData;
					var cn_list = obj.cn_list;
					console.log('cn_list: '+JSON.stringify(cn_list));
					$("#pod_id_add").html("");
					$("#pod_id_add").append('<option>Select CN No.</option>');
		
					$.each(cn_list, function(index, item) {
		
						$str = '<option value=' + item.pod_id + '> '+ item.consignement_number + '</option>';
		
						$("#pod_id_add").append($str);
		
					});
					//$('#pod_id_add').select2('open');
                    
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
