<!DOCTYPE html>

<html lang="en">

<head>

    <title>Production List | <?=WEBSITE_NAME;?></title>

    <meta name="description" content="article costing">



    <!--Data Table-->

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

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

    <div class="body-content" style="min-height: 700px;">



        <!-- header section start-->

        <?php $this->load->view('components/top_menu'); ?>

        <!-- header section end-->



        <!-- page head start-->

        <div class="page-head">

            <h3 class="m-b-less">Production</h3>

            <div class="state-information">

                <ol class="breadcrumb m-b-less bg-less">

                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>

                    <li class="active"> Production </li>

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

                            <a href="<?= base_url('admin/add-production') ?>/<?=$po_id?>" class="btn btn-success"><i class="fa fa-plus"></i> Add Production</a>

                            <input type="hidden" name="purchase_order_id" id="purchase_order_id" value="<?=$po_id?>">

                            <table id="production_table" class="table data-table dataTable">

                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>PO Number</th>
                                        <th>CN Number</th>
                                        <th>Bag Production</th>
                                        <th>Average Weight(gm/10 Pcs)</th>
                                        <th>Wastage(Pcs.)</th>
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

<script>

    $(document).ready(function() {

        $('#production_table').DataTable( {

            "processing": true,

            "language": {

                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',

            },

            "serverSide": true,

            "ajax": {

                "url": "<?=base_url('admin/ajax_production_table_data')?>",
                "type": "POST",
                "dataType": "json",
                "data": function ( d ) {
                    d.purchase_order_id = $('#purchase_order_id').val();
                }
            },

            //will get these values from JSON 'data' variable

            "columns": [

                { "data": "prod_date" },
                { "data": "po_number" },
                { "data": "consignement_number" },
                { "data": "prod_bag_produced" },

                { "data": "prod_avg_weight" },

                { "data": "prod_wastage" },

                { "data": "status" },

                { "data": "action" },

            ],

            //column initialisation properties

            "columnDefs": [{
                "targets": [2,3,4,5,6], //disable 'Image','Actions' column sorting
                "orderable": false,

            }]

        } );

    } );
	
	// delete area 
    $(document).on('click', '.delete', function(){

        if(confirm('Are you sure?')){
			$pod_id = $(this).attr('pod_id');
			
            $tab = $(this).attr('tab');

            $pk_name = $(this).attr('pk-name');

            $pk_value = $(this).attr('pk-value');            

            $.ajax({

                url: "<?= base_url('admin/ajax_delete_production_on_pk') ?>",

                type: 'POST',

                dataType: 'json',

                data:{tab: $tab, pk_name: $pk_name, pk_value: $pk_value, pod_id: $pod_id},

                success: function(returnData){

                    console.log(JSON.stringify(returnData));

                    notification(returnData);

                    $('#production_table').DataTable().ajax.reload();

                },

                error: function(e,v){

                    console.log(e + v);

                }

            });

        }

    })

    // delete area ends 

    //toastr notification

    function notification(obj) {

        // console.log(obj);

        toastr[obj.type](obj.msg, obj.title, {

            "closeButton": true,

            "debug": false,

            "newestOnTop": false,

            "progressBar": true,

            "positionClass": "toast-top-right",

            "preventDuplicates": false,

            "onclick": null,

            "showDuration": "300",

            "hideDuration": "500",

            "timeOut": "10000",

            "extendedTimeOut": "5000",

            "showEasing": "swing",

            "hideEasing": "linear",

            "showMethod": "fadeIn",

            "hideMethod": "fadeOut"

        })

    }



</script>



</body>

</html>