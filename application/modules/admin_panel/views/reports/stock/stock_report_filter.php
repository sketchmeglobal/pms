<?php //echo '<pre>', print_r($filter_data), '</pre>';  ?>
<?php
    function order_qnty_on_po_and_gsm($po_id,$paper_gsm){
        $CI =& get_instance();
        $CI->load->model('Report_stock_m');
        $result = $CI->Report_stock_m->order_qnty_on_po_and_gsm($po_id,$paper_gsm);
        if(isset($result[0])){
            return $result[0]->order_quantity;    
        } else{
            return 0;
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title> Stock Report || <?=WEBSITE_NAME;?></title>
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
    footer{position: relative;}
</style>
</head>

<body class="sticky-header">

<section>
    <!-- sidebar left start (Menu)-->
    <?php $this->load->view('components/left_sidebar'); //left side menu ?>
    <!-- sidebar left end (Menu)-->

    <!-- body content start-->
    <div class="body-content" style="min-height: 5500px;">

        <!-- header section start-->
        <?php $this->load->view('components/top_menu'); ?>
        <!-- header section end-->

        <!-- page head start-->
        <div class="page-head">
            <h3 class="m-b-less">Report - Stock Report</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Stock Report </li>
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
                                    <label for="start_date" class="control-label">Start Date</label>
                                    <input id="start_date" name="start_date" type="date"  class="form-control" value="" />
                                </div> 
                                
                                <div class="col-lg-2">
                                    <label for="end_date" class="control-label">End Date</label>
                                    <input id="end_date" name="end_date" type="date"  class="form-control" value="" />
                                </div>
            
                                <div class="col-lg-3">
                                    
                                    <label class="control-label">Select PO </label><br />
                                    <select id="po_list" name="po_list[]" multiple="multiple" class="form-control select2" required >
                                        <option value="">Select From The List</option>
                                        <?php
                                        foreach ($po_report['po_list'] as $pl) {
                                            ?>
                                            <option value="<?= $pl->po_id ?>"><?= $pl->po_number ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label class="control-label">Select Size </label><br />
                                    <select id="size_list" name="size_list[]" multiple="multiple" class="form-control select2">
                                        <option value="">Select From The List</option>
                                       <option value="47">Test Size</option>
                                    </select>
                                </div>
                                
                                <div class="col-lg-3">
                                    <label class="control-label">Select GSM </label><br />
                                    <select id="gsm_list" name="gsm_list[]" multiple="multiple" class="form-control select2">
                                        <option value="">Select From The List</option>
                                        <?php
                                        foreach ($po_report['gsm_list'] as $gl) {
                                            ?>
                                            <option value="<?= $gl->paper_gsm ?>"><?= $gl->paper_gsm ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                
                                <input type="submit" name="print" value="Print" class="btn btn-sm btn-success" />
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            
            <?php if(count($filter_data) > 0){?>
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>#</td>
                                                <td>PO No.</td>
                                                <!--<td>PO Date</td>-->
                                               
                                                <td>PO Quantity</td>
                                                <td>GSM</td>
                                                <td>Order Quantity</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $iter=1; foreach($filter_data as $fd){ ?>
                                            <tr>
                                                <td><?=$iter++?></td>
                                                <td><?=$fd->po_number . ' (' . date('d-m-Y', strtotime($fd->po_date)). ')'?></td>
                                              
                                                <td><?=$fd->received_quantity;//$fd->distribute_pod_quantity?></td>
                                                <td><?=$fd->paper_gsm?></td>
                                                <td><?=order_qnty_on_po_and_gsm($fd->po_id,$fd->paper_gsm)?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            <?php } ?>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<!--Select2-->
<script src="<?=base_url();?>assets/admin_panel/js/select2.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script>
<script src="<?=base_url()?>assets/admin_panel/js/jquery.multi-select.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/jquery.quicksearch.js"></script>

<script>
    $('.select2').select2(); 
     function getSize() {
        var poList = document.getElementById('po_list');
        var selectedValues = Array.from(poList.selectedOptions).map(option => option.value);
        $.ajax({
            url:'<?php echo base_url()?>admin/get-size',
            type:'post',
            dataType:'json',
            data:{poList:poList},
            success:function(response){
                $('#size_list').html(response.html);
            }
        });
    }
</script>

</body>
</html>