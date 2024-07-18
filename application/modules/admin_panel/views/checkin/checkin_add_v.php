<!DOCTYPE html>

<html lang="en">
<head>
<title>Add Checkin | <?=WEBSITE_NAME;?> </title>
<meta name="description" content="add Purchase order">

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
  
  <div class="body-content" style="min-height: 1500px;"> 
    
    <!-- header section start-->
    
    <?php $this->load->view('components/top_menu'); ?>
    
    <!-- header section end--> 
    
    <!-- page head start-->
    
    <div class="page-head">
      <h3 class="m-b-less">Add Checkin</h3>
      <div class="state-information">
        <ol class="breadcrumb m-b-less bg-less">
          <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
          <li class="active"> Add Checkin</li>
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
              <form id="form_add_checkin" method="post" action="<?=base_url('admin/form_add_checkin')?>"  enctype="multipart/form-data" class="cmxform form-horizontal tasi-form">
                <div class="form-group ">
                  <div class="col-lg-4">
                    <label for="checkin_number" class="control-label text-danger">Check-in Number *</label>
                    <input id="checkin_number" name="checkin_number" type="text" placeholder="Check-in Number" class="form-control round-input" value="<?=$checkin_number?>" />
                  </div>
                  <div class="col-lg-4">
                    <label for="checkin_date" class="control-label text-danger">Check-in Date *</label>
                    <input id="checkin_date" name="checkin_date" type="date" placeholder="Check-in Date" class="form-control round-input" value="<?=$checkin_date?>" />
                  </div>               
                </div>
                
                <div class="form-group ">
                  <div class="col-lg-4">
                    <label for="remarks" class="control-label">Remarks</label>
                    <textarea id="remarks" name="remarks" placeholder="Remarks" class="form-control round-input"></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label for="terms" class="control-label">Terms & Conditions</label>
                    <textarea id="terms" name="terms" placeholder="Terms & Conditions" class="form-control round-input"></textarea>
                  </div>
                  <div class="col-lg-4">
                    <label class="control-label text-danger">Status *</label>
                    <br />
                    <input type="radio" name="status" id="enable" value="1" checked required class="iCheck-square-green">
                    <label for="enable" class="control-label">Enable</label>
                    <input type="radio" name="status" id="disable" value="0" required class="iCheck-square-red">
                    <label for="disable" class="control-label">Disable</label>
                  </div>           
                </div>
                <div class="form-group">
                  <div class="col-lg-6">
                    <button class="btn btn-success pull-right" type="submit"><i class="fa fa-plus"> Add Check-in</i></button>
                  </div>
                  <div class="col-lg-6"> <a id="edit_btn" href="javascript:void(0)" class="hidden btn btn-info"><i class="fa fa-pencil"> Edit Check-in</i></a> </div>
                </div>
              </form>
            </div>
          </section>
        </div>
        
        <!--<div class="col-lg-2">

                    <section class="panel">

                        <div class="panel-body">

                            <label><strong>Total Amount</strong></label><br />

                            <div class="bg-dark text-center" style="padding: 2%">00.00</div>

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

    //add-item-form validation and submit

    $("#form_add_checkin").validate({

        rules: {

            checkin_number: {

                required: true,

                remote: {

                    url: "<?=base_url('admin/ajax_unique_checkin_number')?>",

                    type: "post",

                    data: {

                        checkin_number: function() {

                          return $("#checkin_number").val();

                        }

                    },

                },

            },

            checkin_date: {

                required: true

            }

        },

        messages: {
        }

    });

    $('#form_add_checkin').ajaxForm({

        beforeSubmit: function () {

            // alert($('#order_no').val());

            return $("#form_add_checkin").valid(); // TRUE when form is valid, FALSE will cancel submit

        },

        success: function (returnData) {

            obj = JSON.parse(returnData);
			if(obj.insert_id > 0){
				$('#form_add_checkin')[0].reset(); //reset form
	
				$("#form_add_checkin select").select2("val", ""); //reset all select2 fields
	
				$('#form_add_checkin :radio').iCheck('update'); //reset all iCheck fields
	
				$("#form_add_checkin").validate().resetForm(); //reset validation
	
				notification(obj);
	
				window.location.href = '<?=base_url()?>admin/edit-checkin/'+obj.insert_id;
			}else{
				notification(obj);
			}

        }

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