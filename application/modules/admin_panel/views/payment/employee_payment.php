<?php
// echo '<pre>', print_r($employee_payment), '</pre>'; die;
$employee_payment = array_filter($employee_payment);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Payments | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="Order Status">

    <!--Data Table-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="<?=base_url()?>assets/admin_panel/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.0.4/css/rowGroup.dataTables.min.css" />
    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->
    <!--Select2-->
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">
<style>
    .jobber_type {
    border: 1px solid #cac8c8;
    padding: 6px;
    }
    input[type="submit"] {
        margin-top: 26px;
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
            <h3 class="m-b-less">Employee Payment</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active">Employee Payment</li>
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
                            <form class="row" method="post" action="">
                                <div class="col-lg-2">
                                    <label class="control-label text-danger">Select Employee* </label><br />
                                    <select id="e_id" name="e_id" class="form-control select2" required >
                                        <option value="">Select From The List</option>
                                        <?php
                                        foreach ($employee_list as $fae) {
                                            ?>
                                            <option value="<?= $fae->e_id ?>"><?= $fae->name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label">Payment date (start) </label><br />
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label">Payment date (end) </label><br />
                                    <input type="date" class="form-control" name="end_date" />
                                </div>
                                <div class="col-lg-2 text-center">
                                    <input type="checkbox" id="unpaid" name="unpaid" class="btn btn-sm btn-success" /><br>
                                    <label for="unpaid" class="control-label">Show Unpaid only</label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="submit" name="employee" value="Show" class="btn btn-sm btn-success" />
                                </div>
                                <div class="col-lg-2 text-right">
                                    <label>Action</label><br>
                                    <button type="button" id="pay_all" class="btn btn-primary">Pay all <span class="badge" id="pay_no">0</span></button>
                                </div>
                                <?php 
                                if($post_return){                                    
                                ?>
                                <div class="col-lg-1"></div>
                                <!-- <div class="col-lg-2">
                                    Total Amount:<br> 
<strong>< ?=$val1 = (isset($employee_payment['total_amount'][0]) ? $employee_payment['total_amount'][0]->todays_payable : 0)?></strong>
                                </div>
                                <div class="col-lg-2">
                                    Total Paid:<br> 
<strong>< ?=$val2 = (isset($employee_payment['total_paid'][0]) ? $employee_payment['total_paid'][0]->todays_payable : 0)?>                                    </strong>
                                </div>
                                <div class="col-lg-2">
                                    Total Pending:<br> 
                                    <strong><?=($val1-$val2)?></strong>
                                </div> -->
                                <?php 
                                }
                                ?>

                                
                            </form>
                        </div>
                    </section>
                </div>
            </div>

            <?php 
            if($post_return){

                if(isset($employee_payment['payment_details'])){
                    $employee_payment = $employee_payment['payment_details'];
                }

            ?>

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                           
                           <table class="table table-responsive" id="datatable">
                               <thead>
                                   <tr>
                                       <th>Sr. No.</th>
                                       <th>Date</th>
                                       <th>PO Number</th>
                                       <th>Employee</th>
                                       <th>Product</th>
                                       <th>Received Quantity</th>
                                       <th>Rate/Bag</th>
                                       <th>Total</th>
                                       <th>Status</th>
                                       <th>Actions</th>
                                   </tr>
                               </thead> 
                               <tbody>
                                   <?php 
                                   $iter = 1;
                                   foreach($employee_payment as $ep){
                                    // echo '<pre>', print_r($ep), '</pre>'; die;
                                    ?>

                                    <tr>
                                        <td><?=$iter++?></td>
                                        <td><?=$ep['checkin_no']?></td>
                                        <td><?=$ep['po_number']?></td>
                                        <td><?=$ep['name'] ?></td>
                                        <td><?=$ep['category'] . ' ('.$ep['size'].')'?></td>
                                        <td><?=$ep['received_quantity']?></td>
                                        <td><?=$ep['employee_payment_rate_per_unit']?></td>
                                        <td><?=($ep['new_received_qnty']*$ep['employee_payment_rate_per_unit'])?></td>
                                        <td><?= ($ep['payment_status'] == 1) ?  'Paid' : 'Pending' ?></td>
                                        <?php if($ep['new_received_qnty'] == 0){ ?>
                                            <td><button disabled class="btn btn-warning">Undo Payment</button></td>    
                                        <?php } else { ?>
                                            <td><?= ($ep['payment_status'] == 1) ?  '<button data-checkin_detail_id='.$ep['checkin_detail_id'].' class="btn btn-warning undo_pay">Undo Payment</button>' : '<button data-checkin_detail_id='.$ep['checkin_detail_id'].' class="btn btn-primary pay">Pay Employee</button> 
                                            <label for="pay_cart" style="font-weight:bold">Add to Pay Cart</label> <input type="checkbox" class="pay_cart" name="pay_cart" value="'.$ep['checkin_detail_id'].'"/>' ?></td>
                                        <?php } ?>
                                        
                                    </tr>

                                    <?php
                                   }
                                   ?>
                               </tbody>
                           </table>
                                                            
                        </div>
                    </section>
                </div>
            </div>

            <?php  
            }
            ?>            
        </div>
        
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<!--Select2-->
<script src="<?=base_url();?>assets/admin_panel/js/select2.js" type="text/javascript"></script>
<!-- <script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script> -->
<!-- <script src="<?=base_url()?>assets/admin_panel/js/jquery.multi-select.js" type="text/javascript"></script> -->
<!-- <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/jquery.quicksearch.js"></script> -->

<script>
    $('.select2').select2(); 
    // $("#datatable").DataTable();
    
    $(".pay_cart").change(function() {
        if($(this).prop('checked')) {
            $('#pay_no').text(parseInt($('#pay_no').text()) + 1);
        } else {
            $('#pay_no').text(parseInt($('#pay_no').text()) - 1);
        }
    });
    
    $(document).on('click', '#pay_all', function(){

        if(confirm("Are You Sure?")){
            
        //    console.log($('input[name="pay_cart"]:checked').serialize());
            var ids = '';
            $('input[name="pay_cart"]:checked').each(function() {
               ids += ','+this.value;
            });
            // console.log(ids.slice(1));
            
            $checkin_detail_id = ids.slice(1);
            // alert($checkin_detail_id);
            $.ajax({
                url: "<?= base_url('admin/update-payment-status') ?>",
                method: "post",
                dataType: 'json',
                data: {'checkin_detail_id': $checkin_detail_id,'pay': 'pay', 'type': 'bulk'},
                success: function(data){
                    console.log(data);
                    alert('Payment Updated. Reload to continue.') ;
                    location.reload();
                    // notification(data);   
                }
            });

        }
    })

    $(document).on('click', '.pay', function(){

        if(confirm("Are You Sure?")){

            $checkin_detail_id = $(this).data('checkin_detail_id');
            // alert($checkin_detail_id);
            $.ajax({
                url: "<?= base_url('admin/update-payment-status') ?>",
                method: "post",
                dataType: 'json',
                data: {'checkin_detail_id': $checkin_detail_id,'pay': 'pay', 'type': 'single'},
                success: function(data){
                    console.log(data);
                    alert('Payment Updated. Reload to continue.') ;
                    location.reload();
                    // notification(data);   
                }
            });

        }
    })
    
    

    $(document).on('click', '.undo_pay', function(){

        if(confirm("Are You Sure?")){

            $checkin_detail_id = $(this).data('checkin_detail_id');
            // alert($checkin_detail_id);
            $.ajax({
                url: "<?= base_url('admin/update-payment-status') ?>",
                method: "post",
                dataType: 'json',
                data: {'checkin_detail_id': $checkin_detail_id,'pay': 'undo_pay', 'type': 'single'},
                success: function(data){
                    console.log(data);
                    alert('Payment Revoked. Reload to continue.') ;
                    location.reload();
                    // notification(data);   
                }
            });

        }
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
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "7000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        })
    }

</script>

</body>
</html>