
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Report | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="article costing">

    <!--Data Table-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <h3 class="m-b-less">Report Distribute</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Report Distribute</li>
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
                            <form id="form_add_customer_invoice" method="post" enctype="multipart/form-data" class="cmxform form-horizontal tasi-form">
                                <div class="form-group ">
                                 	<div class="col-lg-4">
                                        <label for="start_date" class="control-label text-danger">Start Date *</label>
                                        <input id="start_date" name="start_date" type="date"  class="form-control round-input" value="<?=$employee_report['start_date']?>" required />
                                    </div> 
                                    
                                    <div class="col-lg-4">
                                        <label for="end_date" class="control-label text-danger">End Date *</label>
                                        <input id="end_date" name="end_date" type="date"  class="form-control round-input" value="<?=$employee_report['end_date']?>" required />
                                    </div>
                                    <div class="col-lg-4">
                                      <label for="e_id" class="control-label text-danger">Employee*</label>
                                      <select id="e_id" name="e_id" required class="select2 form-control round-input">
                                        <option value="">Select Employee</option>
                                        <?php 
										$employees = $employee_report['employees'];
										foreach($employees as $em){ ?>
                                        <option value="<?=$em->e_id?>" <?php if($em->e_id == $employee_report['e_id']){?> selected="selected" <?php } ?>><?=$em->name.' ['.$em->e_code.']'?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                    
                                 </div>
                                 
                                 <div class="form-group">
                                    <div class="col-lg-6">
                                        <button class="btn btn-success pull-right" type="submit"><i class="fa fa-plus"> Generate Report</i></button>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-lg-12">
                <?php
					 if(isset($employee_report["due_amount"])){
						 $due_amount = $employee_report["due_amount"][0];
						// print_r($due_amount);
						echo "Emp Name: <strong>".$due_amount->name."</strong> Adhar Card: <strong>".$due_amount->emp_adhar_card_number."</strong>";
						 
					 }
                ?>
                    <section class="panel">
                        <div class="panel-body">

                            <table id="item_details_table" class="table data-table dataTable">
                                <thead>
                                    <tr>
                                        <th>Checkout Date</th>
                                        <th>C.N. No.</th>
                                        <th>Roll/Handel</th>
                                        <th>Size</th>
                                        <th>Unit</th>
                                        <th class="text-right">Distribute Quantity </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
								
								if(isset($employee_report["emp_data"])){
									$emp_date = $employee_report["emp_data"];
									$i = 0;
									$emp_id = 0;
									$sz_id = 0;
									$distribute_pod_quantity = 0;
									$previous_distribute_pod_quantity = 0;
									
									$total_size = sizeof($emp_date);
									$subtotal_distribute_pod_quantity = 0;
									
									foreach($emp_date as $emp_d){	
									$subtotal_distribute_pod_quantity = $subtotal_distribute_pod_quantity + $emp_d->distribute_pod_quantity;
									
									if($i == 0){
										$emp_id = $emp_d->e_id;
										$sz_id = $emp_d->sz_id;
										$distribute_pod_quantity = $distribute_pod_quantity + $emp_d->distribute_pod_quantity;
										$previous_distribute_pod_quantity = $emp_d->distribute_pod_quantity;										
										
										?>
										<tr>
											<td><?=date('d-m-Y', strtotime($emp_d->create_date))?></td>
											<td><?=$emp_d->consignement_number?></td>
                                            <td>
											<?php
												if($emp_d->roll_handel == '0'){
													echo "Roll";
												}else{
													echo "Handel";
												}
											?>
                                            </td>
											<td><?=$emp_d->size?></td>
											<td><?=$emp_d->unit?></td>
											<td class="text-right"><?=$emp_d->distribute_pod_quantity?></td>
										<tr>
									<?php
                                	}else{
                                    	if($emp_id == $emp_d->e_id && $sz_id == $emp_d->sz_id){
                                        $distribute_pod_quantity = $distribute_pod_quantity + $emp_d->distribute_pod_quantity;
										$previous_distribute_pod_quantity = $emp_d->distribute_pod_quantity;
                                    ?>
                                        <tr>
                                        <td><?=date('d-m-Y', strtotime($emp_d->create_date))?></td>
                                        <td><?=$emp_d->consignement_number?></td>
                                        <td>
											<?php
												if($emp_d->roll_handel == '0'){
													echo "Roll";
												}else{
													echo "Handel";
												}
											?>
                                            </td>
                                        <td><?=$emp_d->size?></td>
                                        <td><?=$emp_d->unit?></td>
										<td class="text-right"><?=$emp_d->distribute_pod_quantity?></td>
                                    <tr>
									<?php
                                    }else{
										$distribute_pod_quantity = $distribute_pod_quantity + $previous_distribute_pod_quantity;
                                        
                                        ?>
                                       <tr>
                                            <td colspan="5"></td>
                                            <td class="text-right" style="font-weight:bold"><?=$distribute_pod_quantity?></td>
                                       </tr>
                                       <tr>
                                            <td><?=date('d-m-Y', strtotime($emp_d->create_date))?></td>
                                            <td><?=$emp_d->consignement_number?></td>
                                            <td>
											<?php
												if($emp_d->roll_handel == '0'){
													echo "Roll";
												}else{
													echo "Handel";
												}
											?>
                                            </td>
                                            <td><?=$emp_d->size?></td>
                                            <td><?=$emp_d->unit?></td>
											<td class="text-right"><?=$emp_d->distribute_pod_quantity?></td>
                                        <tr>	
                                        <?php
											$emp_id = 0;
											$sz_id = 0;
											$distribute_pod_quantity = 0;
											
											$emp_id = $emp_d->e_id;
											$sz_id = $emp_d->sz_id;
											
											$distribute_pod_quantity = $distribute_pod_quantity + $emp_d->distribute_pod_quantity;
											
											$previous_distribute_pod_quantity = 0;
										}
										}
										$previous_distribute_pod_quantity = 0;
										
										$i++;
										if($i == $total_size){
										?>
                                            <tr>
                                            <td colspan="5"></td>
                                            <th class="text-right" style="font-weight:bold"><?=$distribute_pod_quantity?></td>
                                            </tr>	
                                        <?php
										}//end if																			
										}//end foreach
										?>
										<tr>
                                            <td colspan="5" class="text-right" style="font-weight:bold">Subtotal</td>
                                            <th class="text-right" style="font-weight:bold"><?=$subtotal_distribute_pod_quantity?></td>
                                            </tr>
										<?php
									 }//end if 
									 ?>
                                    
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.select2').select2();
</script>
<script>
    $(document).ready(function() {
        $('#item_details_table').DataTable( {
			"dom": 'lBfrtip',
			"buttons": [
				{
					extend: 'pdfHtml5',
					download: 'open'
				}
			],
            "processing": true,
            "language": {
                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',
            },
            /*"serverSide": true,
            "ajax": {
                "url": "<?=base_url('admin/ajax-item-detail-table-data')?>",
                "type": "POST",
                "dataType": "json",
            },
            //will get these values from JSON 'data' variable
            "columns": [
                { "data": "item_group_name" },
                { "data": "item_code" },
                { "data": "size" },
                { "data": "shape" },
				{ "data": "item_name" },
                { "data": "unit" },
                { "data": "thickness" },
				{ "data": "color" },
				{ "data": "opening_stock" },
				{ "data": "opening_rate" },
            ],
            //column initialisation properties
            "columnDefs": [{
                "targets": [1,2,3,4,5,6,7,8,9], //disable 'Image','Actions' column sorting
                "orderable": false,
            }]*/
        } );
    } );


    $(document).on('click', '.print_all',function(){
        $poi = $(this).attr('po-id');
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
	
	
	
	// delete area 
    $(document).on('click', '.delete', function(){
        if(confirm('Are you sure?')){
            $tab = $(this).attr('tab');
            $pk_name = $(this).attr('pk-name');
            $pk_value = $(this).attr('pk-value');
            $child = $(this).attr('child');
            $ref_table = $(this).attr('ref-table');
            $ref_pk_name = $(this).attr('ref-pk-name');
            
            $.ajax({
                url: "<?= base_url('admin/delete-supp-purchase-order-details') ?>",
                type: 'POST',
                dataType: 'json',
                data:{tab: $tab, pk_name: $pk_name, pk_value: $pk_value, child: $child, ref_table: $ref_table, ref_pk_name: $ref_pk_name},
                success: function(returnData){
                    console.log(JSON.stringify(returnData));
                    notification(returnData);
                    $('#item_details_table').DataTable().ajax.reload();
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