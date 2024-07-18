
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Customer Invoice | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="add customer order">

    <!--Select2-->
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">

    <!--iCheck-->
    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">

    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->
</head>

<body class="sticky-header">

<section>
    <!-- sidebar left start (Menu)-->
    <?php $this->load->view('components/left_sidebar'); //left side menu ?>
    <!-- sidebar left end (Menu)-->

    <!-- body content start-->
    <div class="body-content" style="min-height: 800px;">

        <!-- header section start-->
        <?php $this->load->view('components/top_menu'); ?>
        <!-- header section end-->

        <!-- page head start-->
        <div class="page-head">
            <h3 class="m-b-less">Add Customer Invoice</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Add Customer Invoice</li>
                </ol>
            </div>
        </div>
        <!-- page head end-->

        <!--body wrapper start-->
        <div class="wrapper">

            <div class="row">
                <div class="col-lg-10">
                    <section class="panel">
                        <div class="panel-body">
                            <form id="form_add_customer_invoice" method="post" action="<?=base_url('admin/form_add_customer_invoice')?>" enctype="multipart/form-data" class="cmxform form-horizontal tasi-form">
                                
                                 <div class="form-group ">
                                
                                	<div class="col-lg-3">
                                        <label for="co_id" class="control-label text-danger">Customer Order Number*</label>
                                        <select name="co_id" id="co_id" class="form-control select2">
                                        <option value="">Select Customer Order</option>
                                                <?php
                                                foreach($customer_order as $co){
                                                ?> 
                                                   <option value="<?= $co->co_id ?>" co-no="<?= $co->co_no ?>" am-id="<?= $co->am_id ?>" acc-master-name="<?= $co->name ?>"><?= $co->co_no .' ['.$co->name.']' ?></option>
                                                <?php
                                                }
                                                ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <label for="cus_inv_number" class="control-label text-danger">Invoice Number *</label>
                                        <input id="cus_inv_number" name="cus_inv_number" value="" type="text" placeholder="Invoice Number" class="form-control round-input" />
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <label for="cus_inv_e_way_bill_no" class="control-label">E-WAY Bill no.</label>
                                        <input id="cus_inv_e_way_bill_no" name="cus_inv_e_way_bill_no" value="" type="text" placeholder="E-WAY Bill no." class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="party_name" class="control-label text-danger">Party Name/Buyer/Customer *</label>
                                        <input id="party_name" name="party_name" value="" type="text" placeholder="Party Name/Buyer/Customer" class="form-control round-input" readonly />
                                        <input id="am_id" name="am_id" value="" type="hidden" class="form-control round-input" />
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
                                                    <option value="<?= $tr->transporter_id ?>"><?= $tr->transporter_name ?></option>
                                                    <?php
                                                }
                                                ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="transporter_cn_number" class="control-label">Transporter CN Number</label>
                                        <input id="transporter_cn_number" name="transporter_cn_number" type="text" placeholder="Transporter CN Number" class="form-control round-input"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="cus_inv_number_of_cartons" class="control-label">Total number of Cartons</label>
                                        <input id="cus_inv_number_of_cartons" name="cus_inv_number_of_cartons" type="text" placeholder="Total number of Cartons" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="cus_inv_total_weight" class="control-label">Total weight</label>
                                        <input id="cus_inv_total_weight" name="cus_inv_total_weight" type="text" placeholder="Total weight" class="form-control round-input" />
                                    </div> 
                                 </div>
                                 <div class="form-group "> 
                                 	<div class="col-lg-3">
                                        <label for="invoice_create_date" class="control-label text-danger">Invoice Create Date *</label>
                                        <input id="invoice_create_date" name="invoice_create_date" type="date"  class="form-control round-input" value="<?=$invoice_create_date?>" />
                                    </div> 
                                    <div class="col-lg-3">
                                        <label for="packing_rate" class="control-label">Packaging Rate</label>
                                        <input id="packing_rate" name="packing_rate" type="number" placeholder="Packaging Rate" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="packing_tax" class="control-label">Packaging Tax (%)</label>
                                        <input id="packing_tax" name="packing_tax" type="number" placeholder="Packaging Tax" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="remarks" class="control-label">Remarks</label>
                                        <textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"></textarea>
                                    </div>    
                                 </div>
                                 <div class="form-group ">
                                    <div class="col-lg-3">
                                        <label for="pay_type" class="control-label text-danger">Transporter Payment Type*</label>
                                        <select name="pay_type" id="pay_type" class="select2 form-control">
                                            <option value="client">By Client (To Pay)</option>
                                            <option value="company">By Company (Paid)</option>
                                        </select>
                                    </div>     
                                    <div class="col-lg-3">
                                        <label for="pay_type" class="control-label">Transporter Payment Amount</label>
                                        <input placeholder="Payment Amount" value="0" type="number" step="0.50" id="cn_pay_amount" name="cn_pay_amount" class="form-control round-input">
                                    </div>    
                                 	<div class="col-lg-3">
                                    	<label for="terms" class="control-label">Terms & Condition</label>
                                    	<textarea id="terms" name="terms" placeholder="Terms & Condition" class="form-control round-input"></textarea>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label text-danger">Status *</label><br />
                                        <input type="radio" name="status" id="enable" value="1" checked required class="iCheck-square-green">
                                        <label for="enable" class="control-label">Enable</label>

                                        <input type="radio" name="status" id="disable" value="0" required class="iCheck-square-red">
                                        <label for="disable" class="control-label">Disable</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <button class="btn btn-success pull-right" type="submit"><i class="fa fa-plus"> Create Invoice</i></button>
                                    </div>
                                    <div class="col-lg-6">
                                        <a id="edit_btn" href="javascript:void(0)" class="hidden btn btn-info"><i class="fa fa-pencil"> Edit Customer Order</i></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <div class="col-lg-2">
                    <section class="panel">
                        <div class="panel-body">
                            <label><strong>Total Amount</strong></label><br />
                            <div class="bg-dark text-center" style="padding: 2%">00.00</div>
                            <hr />
                            <label><strong>Total Quantity</strong></label><br /> 
                            <div class="bg-dark text-center" style="padding: 2%">00.00</div>
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

		$("#co_id").change(function(){
			$co_id = $(this).val();
			
			$co_no = $('#co_id option:selected').attr('co-no');
			$co_no1 = $co_no.split("/");		
			$co_no2 = 'INV/'+$co_no1[1]+'/'+$co_no1[2];
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
		
    //add-item-form validation and submit
    $("#form_add_customer_invoice").validate({
        
        rules: {
            
            co_id: {
                required: true
            },
			cus_inv_number: {
                required: true,
                remote: {
                    url: "<?=base_url('ajax_unique_customer_invoice_number')?>",
                    type: "post",
                    data: {
                        cus_inv_number: function() {
                          return $("#cus_inv_number").val();
                        }
                    },
                },
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
    $('#form_add_customer_invoice').ajaxForm({
        beforeSubmit: function () {
            return $("#form_add_customer_invoice").valid(); // TRUE when form is valid, FALSE will cancel submit
        },
        success: function (returnData) {
            obj = JSON.parse(returnData);
		
            $('#form_add_customer_invoice')[0].reset(); //reset form
            $("#form_add_customer_invoice select").select2("val", ""); //reset all select2 fields
            $('#form_add_customer_invoice :radio').iCheck('update'); //reset all iCheck fields
            $("#form_add_customer_invoice").validate().resetForm(); //reset validation
            notification(obj);
			if(parseInt(obj.insert_id) > 0){
            	window.location.href = '<?=base_url()?>admin/edit-customer-invoice/'+obj.insert_id;
			}
		}
    });


    // $("#pay_type").change(function(){
    //     if($(this).find('option:selected').val() == 'company'){
    //         $("#cn_pay_amount").removeAttr('readonly');
    //     }else{
    //         $("#cn_pay_amount").val('0');
    //         $("#cn_pay_amount").attr('readonly', 'true');
    //     }
    // })

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
            "hideDuration": "1000",
            "timeOut": "15000",
            "extendedTimeOut": "10000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        })
    }
</script>

</body>
</html>