<!DOCTYPE html>

<html lang="en">
    <head>
    <title>Edit Check-in | <?=WEBSITE_NAME;?> </title>
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
      
      <div class="body-content" style="min-height: 700px;"> 
    
    <!-- header section start-->
    
    <?php $this->load->view('components/top_menu'); ?>
    
    <!-- header section end--> 
    
    <!-- page head start-->
    
    <div class="page-head">
          <h3 class="m-b-less">Edit Check-in</h3>
          <div class="state-information">
        <ol class="breadcrumb m-b-less bg-less">
              <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
              <li class="active"> Edit Check-in </li>
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
                  <?= $check_in_details[0]->checkin_number ?>
                  <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            <div class="panel-body">
                  <?php #print_r($purchase_order_details); die;?>
                  <form id="form_edit_checkin" method="post" action="<?=base_url('admin/form_edit_checkin')?>" class="cmxform form-horizontal tasi-form">
                <div class="form-group ">
                  <div class="col-lg-4">
                    <label for="checkin_number" class="control-label text-danger">Check-in Number *</label>
                    <input id="checkin_number" name="checkin_number" type="text" placeholder="Check-in Number" class="form-control round-input" value="<?= $check_in_details[0]->checkin_number ?>" />
                  </div>
                  <div class="col-lg-4">
                    <label for="checkin_date" class="control-label text-danger">Check-in Date *</label>
                    <input id="checkin_date" name="checkin_date" type="date" placeholder="Check-in Date" class="form-control round-input" value="<?= date('Y-m-d', strtotime($check_in_details[0]->checkin_date)) ?>" />
                  </div>               
                </div>
                
                <div class="form-group ">
                  <div class="col-lg-4">
                    <label for="remarks" class="control-label">Remarks</label>
                    <textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"><?= $check_in_details[0]->remarks ?></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label for="terms" class="control-label">Terms & Conditions</label>
                    <textarea id="terms" name="terms" placeholder="Terms & Conditions" class="form-control round-input"><?= $check_in_details[0]->terms ?></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label class="control-label text-danger">Status *</label>
                    <br />
                    <input type="radio" name="status" id="enable" value="1" <?php if($check_in_details[0]->status == '1'){ ?> checked <?php } ?> required class="iCheck-square-green">
                    <label for="enable" class="control-label">Enable</label>
                    <input type="radio" name="status" id="disable" value="0" <?php if($check_in_details[0]->status == '0'){ ?> checked <?php } ?> class="iCheck-square-red">
                    <label for="disable" class="control-label">Disable</label>
                  </div>           
                </div>                
                
                <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-3">
                    <button class="btn btn-success" type="submit"><i class="fa fa-refresh"> Update Check-in</i></button>
                  </div>
                    </div>
                <input type="hidden" id="checkin_id" name="checkin_id" class="hidden" value="<?= $check_in_details[0]->checkin_id ?>" />
              </form>
                </div>
          </section>
            </div>
            
            
      </div>
          <div class="row">
        <div class="col-md-12">
              <section class="panel">
            <header class="panel-heading"> Add Check-in details for
                  <?= $check_in_details[0]->checkin_number ?>
                  <span class="tools pull-right"> <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a> </span> </header>
            <div class="panel-body"> 
                  
                  <!--Tabs-->
                  
                  <ul id="purchase_order_tabs" class="nav nav-tabs nav-justified">
                <li class="active"><a href="#po_list" data-toggle="tab">List</a></li>
                <li><a href="#po_add" data-toggle="tab">Add</a></li>
                <li id="checkin_details_edit_tab" class="disabled"><a href="#checkin_details_edit" data-toggle="">Edit</a></li>
              </ul>
                  
                  <!--Tab Content-->
                  
                  <div class="tab-content"> <img id="pod_edit_loader" class="hidden" style="display:block; margin: auto" src="<?= base_url('assets/img/ellipsis.gif') ?>" alt="" />
                <div id="po_list" class="tab-pane fade in active">
                      <table id="checkin_details_table" class="table data-table dataTable">
                    <thead>
                      <tr>
                        <th>Employee</th>
                        <th>PO Number</th>
                        <th>C.N. No.</th>
                        <th>Size</th>
                        <th>Unit</th>
                        <th>Received Quantity</th>
                        <th>Actions</th>
                      </tr>
                        </thead>
                    <tbody>
                        </tbody>
                  </table>
                    </div>
                <div id="po_add" class="tab-pane fade"> <br/>
                      <div class="form">
                    <form id="form_add_checkin_details" method="post" action="<?=base_url('admin/form_add_checkin_details')?>" class="cmxform form-horizontal tasi-form">
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
                                <option value="<?=$em->e_id?>" rate-per-bag="<?=$em->rate_per_bag?>" total-due-amount="<?=$em->total_due_amount?>"><?=$em->name.' ['.$em->e_code.']'?></option>
                                <?php } ?>
                              </select>
                            </div>
                            
                            <div class="col-lg-3">
                              <label for="pod_id_add" class="control-label text-danger">C.N. No.*</label>
                              <select id="pod_id_add" name="pod_id_add" required class="select2 form-control round-input">
                                <option value="">Select C.N. No.</option>
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
                                  <label for="distribute_pod_quantity" class="control-label text-danger">Total Distributed Quantity *</label>
                                  <input type="number" step="0.01" id="distribute_pod_quantity" name="distribute_pod_quantity" required class="form-control" readonly placeholder="Total Distributed Quantity" />
                            </div>
                            
                            <div class="col-lg-3">
                                  <label for="received_quantity_add" class="control-label text-danger">Received Quantity *</label>
                                  <input type="number" step="0.01" id="received_quantity_add" name="received_quantity_add" required class="form-control" placeholder="Received Quantity"/>
                            </div>
                            
                            <div class="col-lg-3">
                                <label for="remarks_add" class="control-label">Remarks</label>
                                <textarea id="remarks_add" name="remarks_add" placeholder="Remarks" class="form-control round-input"> </textarea>
                              </div>
                            </div>  
                              
                            <div class="form-group ">  
                              <div class="col-lg-3">
                                <label for="terms_add" class="control-label">Terms & Conditions</label>
                                <textarea id="terms_add" name="terms_add" placeholder="Terms & Conditions" class="form-control round-input"> </textarea>
                              </div>
                              
                              <div class="col-lg-2">
                                 <label for="rate_per_bag" class="control-label text-danger">Rate per bag</label>
                                 <input type="number" step="0.01" id="rate_per_bag" name="rate_per_bag" required class="form-control" placeholder="Rate per bag" readonly />
                               </div> 
                               
                               <div class="col-lg-2">
                                 <label for="todays_payable" class="control-label text-danger">Today's Payable</label>
                                 <input type="number" step="0.01" id="todays_payable" name="todays_payable" required class="form-control" placeholder="Today's Payable" readonly />
                               </div> 
                               <div class="col-lg-2">
                                 <label for="due_amount" class="control-label text-danger">Due Amount</label>
                                 <input type="number" step="0.01" id="due_amount" name="due_amount" required class="form-control" placeholder="Due Amount" readonly value="0" />
                                 <input type="hidden" step="0.01" id="due_amount_hidden" name="due_amount_hidden" required class="form-control" value="0" />
                               </div> 
                               
                               <div class="col-lg-2">
                                 <label for="net_payable" class="control-label text-danger">Net Payable</label>
                                 <input type="number" step="0.01" id="net_payable" name="net_payable" required class="form-control" placeholder="Net Payable" />
                                 <input type="hidden" step="0.01" id="net_payable_hidden" name="net_payable_hidden" required class="form-control" />
                               </div>                            
                              
                            </div>
                          
                          <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Add details</button>
                            </div>
                      </div>
                          <input type="hidden" name="checkin_id_add" class="hidden" value="<?= $check_in_details[0]->checkin_id ?>" />
                        </form>
                  </div>
                    </div>
                <div id="checkin_details_edit" class="tab-pane"> <br/>
                      <div class="form">
                    <form id="form_edit_checkin_details" method="post" action="<?=base_url('admin/form_edit_checkin_details')?>" class="cmxform form-horizontal tasi-form">
                          <div class="form-group ">
                            <div class="col-lg-4">
                              <label for="e_id_edit" class="control-label text-danger">Employee*</label>
                              <select id="e_id_edit" name="e_id_edit" required class="select2 form-control round-input">
                                <option value="">Select Employee</option>
                                <?php 
                                foreach($employees as $em){ 
                                  if($em->name == ''){
                                    continue;
                                  }
                                ?>
                                <option value="<?=$em->e_id?>" rate-per-bag="<?=$em->rate_per_bag?>" total-due-amount="<?=$em->total_due_amount?>"><?=$em->name.' ['.$em->e_code.']'?></option>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-lg-4">
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
                            
                            <div class="col-lg-4">
                                  <label for="received_quantity_edit" class="control-label text-danger">Received Quantity *</label>
                                  <input type="number" step="0.01" id="received_quantity_edit" name="received_quantity_edit" required class="form-control" placeholder="Quantity" />
                                  <input type="hidden" step="0.01" id="chekin_detail_hidden_quantity_edit" name="chekin_detail_hidden_quantity_edit" required class="form-control"/>
                            </div>

                            <div class="col-lg-3">
                                  <label for="todays_payable_edit" class="control-label text-danger">Today's Payable</label>
                                  <input type="number" step="0.01" id="todays_payable_edit" name="todays_payable_edit" required class="form-control" placeholder="Today's Payable" readonly />
                                  <input type="hidden" step="0.01" id="prod_detail_hidden_quantity_edit" name="prod_detail_hidden_quantity_edit" required class="form-control" />
                            </div>

                            <div class="col-lg-3">
                                  <label for="prod_detail_quantity_edit" class="control-label text-danger">Rate per bag</label>
                                  <input type="number" step="0.01" id="rate_per_bag_edit" name="rate_per_bag_edit" required class="form-control" placeholder="Net Payable" readonly/>
                            </div>

                            <div class="col-lg-3">
                                  <label for="prod_detail_quantity_edit" class="control-label text-danger">Due Amount</label>
                                  <input type="number" step="0.01" id="due_amount_edit" name="due_amount_edit" class="form-control" placeholder="Due Amount" readonly/>
                                  <input type="hidden" step="0.01" id="due_amount_hidden_edit" name="due_amount_hidden_edit" required class="form-control" placeholder="Due Amount"/>
                            </div>
                            
                            <div class="col-lg-3">
                                  <label for="prod_detail_quantity_edit" class="control-label text-danger">Net Payable</label>
                                  <input type="number" step="0.01" id="net_payable_edit" name="net_payable_edit" required class="form-control" placeholder="Net Payable" />
                                  <input type="hidden" step="0.01" id="net_payable_hidden_edit" name="net_payable_hidden_edit" required class="form-control" />
                            </div>
                      </div>
                          <div class="form-group ">
                        <div class="col-lg-4 col-lg-offset-4">
                              <label for="" class="control-label">&nbsp;</label>
                              <br>
                              <button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Update details</button>
                            </div>
                      </div>
                          <input type="hidden" name="checkin_id_edit" class="hidden" value="<?= $check_in_details[0]->checkin_id ?>" />
                          <input type="hidden" name="checkin_detail_id_rdit" id="checkin_detail_id_rdit" class="hidden" value="" />
                          <input type="hidden" name="pod_id_edit" id="pod_id_edit" class="hidden" value="" />
                          <!-- <input type="hidden" name="e_id_edit" id="e_id_edit" class="hidden" value="" /> -->
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

        $('#checkin_details_table').DataTable( {

            "processing": true,

            "language": {

                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',

            },

            "serverSide": true,

            "ajax": {

                "url": "<?=base_url('admin/ajax_checkin_details_table_data')?>",

                "type": "POST",

                "dataType": "json",

                data: {

                    checkin_id: function () {

                        return $("#checkin_id").val();

                    },

                },

            },

            //will get these values from JSON 'data' variable

            "columns": [

                { "data": "employee_name" },

                { "data": "po_number" },

                { "data": "consignment_number" },

                { "data": "size" },

                { "data": "unit" },

                { "data": "quantity" },

                { "data": "action" },

            ],

            //column initialisation properties

            "columnDefs": [{

                "targets": [1,2,3,4,5],
                "orderable": false

            }]

        } );  

    });
  
  //Get All CN. Distributed to the Emoloyee
  $("#e_id_add").change(function(){

        $e_id_add = $(this).val();
    $rate_per_bag = $('#e_id_add option:selected').attr('rate-per-bag');
    
    $("#rate_per_bag").val($rate_per_bag);
    
    $total_due_amount = $('#e_id_add option:selected').attr('total-due-amount');
    $("#due_amount").val($total_due_amount);
    $("#due_amount_hidden").val($total_due_amount);
    
    $received_quantity_add = $('#received_quantity_add').val();
    
    $todays_payable = parseFloat($rate_per_bag) * parseFloat($received_quantity_add);
    $("#todays_payable").val($todays_payable.toFixed(2));

        $.ajax({

            url: "<?= base_url('admin/all-get-details-from-distribute-detail-table') ?>",

            method: "post",

            dataType: 'json',

            data: {'e_id': $e_id_add,},

            success: function(all_items){

                console.log(JSON.stringify(all_items));
        $("#pod_id_add").html("");
        $("#pod_id_add").append('<option>Select CN. No.</option>');
        var dd = all_items.distribute_detail;
                $.each(dd, function(index, item) {

                    $str = '<option value=' + item.pod_id + ' > '+ item.consignement_number + ' /' + item.size_in_text + '</option>';

                    $("#pod_id_add").append($str);

                });
                // open the item tray 
                $('#pod_id_add').select2('open');
            },

            error: function(e){console.log(e);}

        });

    });
  
    $("#pod_id_add").change(function(){

        $pod_id_add = $(this).val();
    $e_id_add = $('#e_id_add').val();

        $.ajax({

            url: "<?= base_url('admin/all-details-in-purchase-order-checkin') ?>",

            method: "post",

            dataType: 'json',

            data: {'pod_id': $pod_id_add, 'e_id': $e_id_add},

            success: function(all_items){
                console.log(JSON.stringify(all_items));       

                $("#sz_id_add").html("");
        $("#sz_id_add").append('<option>Select Size</option>');
        var all_sizes = all_items.all_distribute_detail;
        var roll_handel = all_sizes[0].roll_handel;
        var u_id = all_sizes[0].u_id;
        
                $.each(all_sizes, function(index, item) {
                    $str = '<option value=' + item.sz_id + ' distribute_pod_quantity = '+ item.distribute_pod_quantity +' max_allowed_to_received = '+ item.max_allowed_to_received +' > '+ item.size + '</option>';
                    $("#sz_id_add").append($str);

                });
                // open the item tray 
                $('#sz_id_add').select2('open');
        
        $("#roll_handel_add").select2('destroy');
                $("#roll_handel_add").val(roll_handel);
        $("#roll_handel_add").select2();
        $("#roll_handel_add_hidden").val(roll_handel);
        
        $("#u_id_add").select2('destroy');
                $("#u_id_add").val(u_id);
        $("#u_id_add").select2();
        $("#u_id_add_hidden").val(u_id);
        
        $("#distribute_pod_quantity").val('');
        $("#received_quantity_add").val('');
            },

            error: function(e){console.log(e);}

        });

    });
  
  
  $(document).on('change', '#sz_id_add', function(){
    $distribute_pod_quantity = $('#sz_id_add option:selected').attr('distribute_pod_quantity')
    $("#distribute_pod_quantity").val($distribute_pod_quantity);
    
    $max_allowed_to_received = $('#sz_id_add option:selected').attr('max_allowed_to_received')
    $("#received_quantity_add").val($max_allowed_to_received);
    // $( "#received_quantity_add" ).attr( "max", $max_allowed_to_received );
    
    $rate_per_bag = $("#rate_per_bag").val();   
    $received_quantity_add = $('#received_quantity_add').val();
    
    $todays_payable = parseFloat($rate_per_bag) * parseFloat($received_quantity_add);
    $("#todays_payable").val($todays_payable.toFixed(2));
    
    $due_amount = $("#due_amount_hidden").val();

    $net_payable = parseFloat($todays_payable) + parseFloat($due_amount);
    console.log('net_payable:'+$net_payable);
    $("#net_payable").val($net_payable.toFixed(2));
    $("#net_payable").attr('max', $net_payable.toFixed(2));
    $("#net_payable_hidden").val($net_payable.toFixed(2));

    
  });
  
  $("#received_quantity_add").on('change', function () {
    $rate_per_bag = $("#rate_per_bag").val();
        $received_quantity_add = $("#received_quantity_add").val();

        $todays_payable = parseFloat($rate_per_bag) * parseFloat($received_quantity_add);
    $("#todays_payable").val($todays_payable.toFixed(2));
    
    $due_amount = $("#due_amount_hidden").val();

    $net_payable = parseFloat($todays_payable) + parseFloat($due_amount);
    console.log('net_payable:'+$net_payable);
    $("#net_payable").val($net_payable.toFixed(2));
    $("#net_payable").attr('max', $net_payable.toFixed(2));
    $("#net_payable_hidden").val($net_payable.toFixed(2));
    $("#due_amount").val($due_amount.toFixed(2));
    });
  
  $("#net_payable").on('change', function () {
    $due_amount = $("#due_amount_hidden").val();
    $net_payable_hidden = $("#net_payable_hidden").val();
    $net_payable = $("#net_payable").val();
    $todays_payable = $("#todays_payable").val();
    
    $due_amount1 = parseFloat($net_payable_hidden) - parseFloat($net_payable);
    
    $("#due_amount").val($due_amount1.toFixed(2));

    });

  $("#received_quantity_edit").on('change', function () {
    $rate_per_bag = $("#rate_per_bag_edit").val();
        $received_quantity_add = $("#received_quantity_edit").val();

        $todays_payable = parseFloat($rate_per_bag) * parseFloat($received_quantity_add);
    $("#todays_payable_edit").val($todays_payable.toFixed(2));
    
    $due_amount = $("#due_amount_edit").val();

    $net_payable = parseFloat($todays_payable) + parseFloat($due_amount);
    console.log('net_payable_edit:'+$net_payable);
    $("#net_payable_edit").val($net_payable.toFixed(2));
    $("#net_payable_edit").attr('max', $net_payable.toFixed(2));
    $("#net_payable_hidden_edit").val($net_payable.toFixed(2));
    $("#due_amount_edit").val($due_amount);
    });
  
  $("#net_payable_edit").on('change', function () {
    $due_amount = $("#due_amount_edit").val();
    $net_payable_hidden = $("#net_payable_hidden_edit").val();
    $net_payable = $("#net_payable_edit").val();
    $todays_payable = $("#todays_payable_edit").val();
    
    $due_amount1 = parseFloat($net_payable_hidden) - parseFloat($net_payable);

    $("#due_amount_edit").val($due_amount1);

    });
  
  
    $("#form_edit_checkin").validate({

        rules: {

            checkin_number: {

                required: true

            },

            checkin_date: {

                required: true

            }

        },

        messages: {

        }

    });

    $('#form_edit_checkin').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_checkin").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {

            obj = JSON.parse(returnData);

            notification(obj);

        }

    });



    //add-purchase order details-form validation and submit

    $("#form_add_checkin_details").validate({

        rules: {

            e_id_add: {

                required: true,

            },

            pod_id_add: {

                required: true,

            },

            sz_id_add: {

                required: true,

            },

            u_id_add: {

                required: true,

            },

            distribute_pod_quantity: {

                required: true,

            },

            received_quantity_add: {

                required: true,

            },

            net_payable: {

                required: true,

            }

        },

        messages: {
        }

    });

    $('#form_add_checkin_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_add_checkin_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);

            /*$purchase_order_total_amount = parseFloat(obj.pod_total).toFixed(2);
            $("#purchase_order_total_amount").text($purchase_order_total_amount);*/
      
            
            $('#form_add_checkin_details')[0].reset(); //reset form
            $("#form_add_checkin_details select").select2("val", ""); //reset all select2 fields
            // $('#form_add_checkin_details :radio').iCheck('update'); //reset all iCheck fields
            $("#form_add_checkin_details").validate().resetForm(); //reset validation
            notification(obj);
            //refresh table
      
      var employees = obj.employees;
      //console.log('cn_list: '+JSON.stringify(cn_list));
      $("#pod_id_add").html("");
            $("#e_id_add").html("");
      $("#e_id_add").append('<option>Select Employee</option>');

      $.each(employees, function(index, item) {

        $str = '<option value=' + item.e_id + ' rate-per-bag=' + item.rate_per_bag + ' total-due-amount=' + item.total_due_amount + ' > '+ item.name + '['+ item.e_code +']</option>';

        $("#e_id_add").append($str);

      });

      // open the item tray 
      $('#e_id_add').select2('open');

            $('#checkin_details_table').DataTable().ajax.reload();
        }

    });



    //edit-purchase order details-form validation and submit

    $("#form_edit_checkin_details").validate({

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



    $('#form_edit_checkin_details').ajaxForm({

        beforeSubmit: function () {

            return $("#form_edit_checkin_details").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);

            $purchase_order_total_amount = parseFloat(obj.total_amount).toFixed(2);

            $purchase_order_total_quantity = parseFloat(obj.total_qnty).toFixed(2);

            $('#form_add_checkin_details')[0].reset(); //reset form

            $("#form_add_checkin_details").validate().resetForm(); //reset validation

            notification(obj);

            $po_total = parseFloat(obj.pod_total).toFixed(2);

            //refresh table

            $('#checkin_details_table').DataTable().ajax.reload();

            

        }

    });



    //check-in details edit button

    $("#checkin_details_table").on('click', '.checkin_detail_edit_btn', function() {

        $("#pod_edit_loader").removeClass('hidden');



        $checkin_id = $(this).attr('checkin_detail_id');



        $.ajax({

            url: "<?= base_url('admin/ajax_fetch_checkin_details_on_pk') ?>",

            method: "post",

            dataType: 'json',

            data: {'checkin_id': $checkin_id},

            success: function(pod_data){

                console.log(pod_data);

                data = pod_data[0];
        
        //$("#roll_handel_edit").val(data.roll_handel);
        $("#sz_id_edit").html("<option>"+data.size + ' /' + data.size_in_text+"</option>").trigger('change');
        $("#received_quantity_edit").val(data.received_quantity);
        $("#chekin_detail_hidden_quantity_edit").val(data.received_quantity);
        $("#rate_per_bag_edit").val(data.rate_per_bag);
        $("#checkin_detail_id_rdit").val(data.checkin_detail_id);
        
        $("#due_amount_edit").val(data.due_amount);
        $("#due_amount_hidden_edit").val(data.due_amount);
        $("#pod_id_edit").val(data.pod_id);
        
        $("#e_id_edit").select2('destroy'); 
        $("#e_id_edit").val(data.e_id);
        $("#e_id_edit").select2();

        $todays_payable = parseFloat(data.rate_per_bag) * parseFloat(data.received_quantity);
        $("#todays_payable_edit").val($todays_payable.toFixed(2));
        $due_amount = data.due_amount;
        $net_payable = parseFloat($todays_payable) + parseFloat($due_amount); 

        $("#todays_payable_edit").val(data.todays_payable);
        $("#net_payable_edit").val($net_payable.toFixed(2));
        $("#net_payable_hidden_edit").val($net_payable.toFixed(2));

        $("#todays_payable").val($todays_payable.toFixed(2));
    
        $due_amount = $("#due_amount_hidden").val();
        $net_payable = parseFloat($todays_payable) + parseFloat($due_amount);
        $('#checkin_details_edit_tab').removeClass('disabled');
        $('#checkin_details_edit_tab').children("a").attr("data-toggle", 'tab');

        // $('#po_details_edit_tab li:eq(2) a').tab('show');

        $('a[href="#checkin_details_edit"]').tab('show');

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
      $due_amount = $(this).attr('due-amount');
      $e_id = $(this).attr('e-id');

            $.ajax({

                url: "<?= base_url('admin/del-row-on-table-pk-checkin-details') ?>",

                dataType: 'json',

                type: 'POST',

                data: {pk: $pk, tab : $tab, pk_name: $pk_name, pod_id: $pod_id, due_amount: $due_amount, e_id: $e_id},

                success: function (returnData) {

                    console.log(returnData);

                    $this.closest('tr').remove();
          notification(returnData);

                    $("#checkin_details_table").DataTable().ajax.reload();
          
                    obj = returnData;
          var employees = obj.employees;
          console.log('employees: '+JSON.stringify(employees));
          $("#e_id_add").html("");
          $("#e_id_add").append('<option>Select Employee</option>');
    
          $.each(employees, function(index, item) {
    
            $str = 
            '<option value=' + item.e_id + ' rate-per-bag=' + item.rate_per_bag + ' total-due-amount=' + item.total_due_amount + ' > '+ item.name + '['+ item.e_code +']</option>';
    
            $("#e_id_add").append($str);
    
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
