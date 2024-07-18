<?php
/**
 * Coded by: Pran Krishna Das
 * Social: www.fb.com/pran93
 * CI: 3.0.6
 * Date: 11-03-2020
 * Time: 09:15
 */
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer Invoice List | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="article costing">

    <!--Data Table-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>

    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <style type="text/css">
        .cmxform .form-group label.error {
            display: inline-block;
            margin: 8px 0;
            color: #e55957;
            font-weight: 400;
            white-space: nowrap;
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
            <h3 class="m-b-less">Customer Invoice</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Customer Invoice </li>
                </ol>
            </div>
        </div>
        <!-- page head end-->

        <!--body wrapper start-->
        <div class="wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <a href="<?= base_url('admin/add-customer-invoice') ?>" class="btn btn-success"><i class="fa fa-plus"></i> Create Invoice</a>

                            <table id="customer_invoice_table" class="table data-table dataTable">
                                <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Invoice Date</th>
                                        <th>E-WAY Bill no</th>
                                        <th>Party Name</th>
                                        <th>Transporter</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Payment details for invoice no <span id="invoice-id"></span></h4>
            </div>
            <div class="modal-body">

                <div class="row" style="background-color: #dcdcdc;">
                    <form class="row table-responsive col-md-12 cmxform tasi-form" id="form_update_transport_payment" method="post" action="<?=base_url('admin/form_update_transport_payment')?>" enctype="multipart/form-data">
                        <table class="table table-condensed" id="transport_payment_table">
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center h4">Transport Payment Details</th>
                                </tr>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Payment Type</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </form>
                </div>
                <hr>
                <div class="row" style="background-color: #f5f5dc">
                    <div class="col-sm-12">
                        <button id="add-invoice-payment" class="pull-right btn btn-info"> 
                            <fa class="fa fa-plus"></fa> Add Invoice Payment
                        </button>
                    </div>
                
                    <div class="table-responsive col-md-12">
                        <table class="table table-condensed" id="invoice_payment_table">
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center h4">Invoice Payment Details</th>
                                </tr>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Payment Date</th>
                                    <th>Payment Amount</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                
                <form class="row hidden cmxform tasi-form" id="form_add_invoice_payment" method="post" action="<?=base_url('admin/form_add_invoice_payment')?>" enctype="multipart/form-data">
                    <div class="form-group ">
                        <div class="col-md-6">
                            <label class="control-label">Payment Date</label>
                            <input type="date" id="payment_date" name="payment_date" required="" value="" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Payment Amount</label>
                            <input class="form-control" id="payment_amount" name="payment_amount" type="number" value="" step="0.50">
                        </div>
                    </div>
                    <div class="form-group ">
                        <br>
                        <label class="control-label col-md-4">Payment Remarks</label>
                        <div class="col-md-12">
                            <textarea id="payment_remarks" name="payment_remarks" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-md-12">
                            <!-- hidden data details -->
                            <input type="hidden" name="invoice-id-submit" id="invoice-id-submit" value="">
                            <br>
                            <input type="submit" name="payment_submit" id="payment_submit" value="Add Payment" class="btn btn-success pull-right">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
               <!--  <button data-dismiss="modal" class="btn btn-default" type="button">Close</button> -->
               <div class="row">
                    <div class="col-sm-9">
                        <p><strong>Total Invoice Value (including packing charges):</strong></p>
                    </div>
                    <div class="col-sm-3" id="total_invoice_value">
                        calculating...
                    </div>
                    <div class="col-sm-9">
                        <p><strong>Total Paid:</strong></p>
                    </div>
                    <div class="col-sm-3" id="total_invoice_paid">
                        calculating...
                    </div>
                     <div class="col-sm-9">
                        <p><strong>Due Amount:</strong></p>
                    </div>
                    <div class="col-sm-3" id="total_invoice_due">
                        calculating...
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>


<!-- Placed js at the end of the document so the pages load faster -->
<script src="<?=base_url()?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>

<script type="text/javascript">
    $(document).on('click','.payment',function(){
        // alert();
        $("#invoice-id").text($(this).attr('inv-num'));
        $("#invoice-id-submit").val($(this).attr('co-id'));
        $in_id = $(this).attr('co-id');

        // fetch invoice data
        $.ajax({
            url: "<?= base_url('admin/ajax-fetch-invoice-payment') ?>",
            type: 'POST',
            data: {in_id:$in_id},
            cache: true,
            dataType: 'json',
            success: function(returnData){
                console.log(returnData);
                $("#invoice_payment_table").find("tbody").html("");
                $iter = 1;
                total_paid=0;
                $.each(returnData.payment, function(i, item) {
                    total_paid += parseFloat(item.amount);
                    $str = "<tr><td>"+ $iter +"</td><td>"+item.payment_date+"</td><td>"+ item.amount +"</td><td>"+item.remarks+"</td><td><button pk="+item.cp_id+" class='delete-payment btn btn-danger'>Delete</button></td></tr>";
                    $("#invoice_payment_table").find("tbody").append($str);
                    // console.log($str);
                    $iter++;
                });
                $("#total_invoice_paid").text(total_paid.toFixed(2));
                
                $total_amount = returnData.invoice[0].total_amount;
                $packing_rate =  returnData.invoice[0].packing_rate;
                $packing_tax =  returnData.invoice[0].packing_tax;
                $packing_with_tax = parseFloat($packing_rate) + parseFloat($packing_rate * ($packing_tax/100))

                $grand = parseFloat($total_amount) + parseFloat($packing_with_tax);
                $("#total_invoice_value").text($grand.toFixed(2));
                due = parseFloat($grand) - parseFloat(total_paid.toFixed(2));
                $("#total_invoice_due").text(due.toFixed(2));

                // Transport payment area
                $action ="<button data-pk="+returnData.invoice[0].cus_inv_id+" class='btn btn-warning form-control' type='button' id='transport_payment_update'>Update</button>";
                
                status1 = "<select id='transport_payment_status' class='form-control'>";
                $slctd = (returnData.invoice[0].transport_payment_status == 1) ? "selected" : "";
                status2 = "<option value='1' "+ $slctd +">Paid</option>";
                $slctd = (returnData.invoice[0].transport_payment_status == 0) ? "selected" : "";
                status3 = "<option value='0' "+ $slctd +">Pending</option>";
                status4 = "</select>"; 

                $status = status1+status2+status3+status4;

                $str = "<tr><td>1</td><td>"+returnData.invoice[0].transport_payment_type+"</td><td>"+ returnData.invoice[0].transport_payment_amount +"</td><td>"+$status+"</td><td>"+$action+"</td></tr>";
                $("#transport_payment_table").find("tbody").append($str);

            },
            error: function(data){
                console.log(data + "ERROR");
            }
        });

    })
</script>

<!-- common js -->
<?php $this->load->view('components/_common_js'); //left side menu ?>

<script src="<?=base_url();?>assets/admin_panel/js/jquery.validate.min.js"></script>
<!--ajax form submit-->
<script src="<?=base_url();?>assets/admin_panel/js/jquery.form.min.js"></script>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script>
    $(document).ready(function() {
        $('#customer_invoice_table').DataTable( {
            "processing": true,
            "language": {
                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',
            },
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url('ajax_customer_invoice_table_data')?>",
                "type": "POST",
                "dataType": "json",
            },
            //will get these values from JSON 'data' variable
            "columns": [
                { "data": "cus_inv_number" },
                { "data": "invoice_create_date" },
                { "data": "cus_inv_e_way_bill_no" },
                { "data": "am_id" },
                { "data": "transporter_name" },
                { "data": "status" },
                { "data": "action" },
            ],
            //column initialisation properties
            "columnDefs": [{
                "targets": [6], //disable 'Image','Actions' column sorting
                "orderable": false,
            }]
        } );
    } );
    
  
    // delete area 

    $(document).on('click', '.delete', function(){
        $this = $(this);
        if(confirm("Are You Sure? This Process Can\'t be Undone.")){
            $pk_name = $(this).attr('pk-name');
            $pk_val = $(this).attr('pk-value');
            $tab = $(this).attr('tab');
            $ref_table = $(this).attr('ref-table');
            $ref_pk_name = $(this).attr('ref-pk-name');
            $co_id = $(this).attr('co-id');

            $.ajax({
                url: "<?= base_url('admin/del-row-on-table-pk-invoice') ?>",
                dataType: 'json',
                type: 'POST',
                data: {pk_name: $pk_name, pk_val : $pk_val, tab: $tab, ref_table: $ref_table, ref_pk_name: $ref_pk_name, co_id: $co_id},
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
                },
                error: function (returnData) {
                    obj = JSON.parse(returnData);
                    notification(obj);
                }
            });
        }
    });

    $(document).on('click', '.delete-payment', function(){
        $this = $(this);
        if(confirm("Are You Sure? This Process Can\'t be Undone.")){
            $pk_name = $(this).attr('pk');

            $.ajax({
                url: "<?= base_url('admin/del-payment-received') ?>",
                dataType: 'json',
                type: 'POST',
                data: {pk_name: $pk_name},
                success: function (returnData) {
                    console.log(returnData);
                    $this.closest('tr').remove();
                    notification(returnData);
                },
                error: function (returnData) {
                    obj = JSON.parse(returnData);
                    notification(obj);
                }
            });
        }
    });


    $(document).on('click', '.print_all_invoices',function(){
        $poi = $(this).attr('co-id');
        // alert($poi); 
         $.confirm({
            title: 'Printing Options',
            content: 'Choose printing methods from the below options',
            buttons: {
                print_proforma: {
                    text: 'Proforma Invoice',
                    btnClass: 'btn-blue',
                    keys: ['enter', 'shift'],
                    action: function(){
                     window.open("<?= base_url('admin/print-invoice') ?>/proforma/"+ $poi, "_blank");
                    }
                },
                print_invoice: {
                    text: 'Tax Invoice',
                    btnClass: 'btn-blue',
                    keys: ['enter', 'shift'],
                    action: function(){
                     window.open("<?= base_url('admin/print-invoice') ?>/tax/"+ $poi, "_blank");
                    }
                },
                print_rcpt: {
                    text: 'Payment Receipt',
                    btnClass: 'btn-blue',
                    keys: ['enter', 'shift'],
                    action: function(){
                     window.open("<?= base_url('admin/print-receipt') ?>/"+ $poi, "_blank");
                    }
                },
                cancel: function () {}
            }
        });   
    });
    
    // Payment of invoice starts

    $(document).ready(function(){
        $("#add-invoice-payment").click(function(){
            $("#form_add_invoice_payment").toggleClass("hidden");
        });
    });
    
    $("#form_add_invoice_payment").validate({
        rules: {
            payment_date: {
                required: true
            },
            payment_amount: {
                required: true
            }
        },
        messages: {
        }
    });

    $('#form_add_invoice_payment').ajaxForm({
        beforeSubmit: function () {
            return $("#form_add_invoice_payment").valid(); // TRUE when form is valid, FALSE will cancel submit
        },
        success: function (returnData) {
            obj = JSON.parse(returnData);
        
            $('#form_add_invoice_payment')[0].reset(); //reset form
            $("#form_add_invoice_payment").validate().resetForm(); //reset validation
            notification(obj);
            
        }
    });

    // Transport Payment
    $(document).on('click', '#transport_payment_update', function(){
        // alert();
        $tps = $("#transport_payment_status").find('option:selected').val();
        $inv_id = $("#transport_payment_update").data('pk');
        $.ajax({
            url: "<?= base_url('admin/ajax-form-update-transport-payment') ?>",
            dataType: 'json',
            type: 'POST',
            data: {tps: $tps, inv: $inv_id},
            success: function (returnData) {
                console.log(returnData);
                obj = JSON.parse(JSON.stringify(returnData));
                notification(obj);
            }
        });
    }); 
    

    $('#myModal').on('show.bs.modal', function (e) {
        // code here
    })

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