<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration | <?=WEBSITE_NAME;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login into admin panel!">
    <meta name="author" content="">

    <link href="<?=base_url();?>assets/img/favicon.ico"  rel="shortcut icon"/>
    <link href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?=base_url();?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?=base_url();?>assets/login/css/login-css.css" rel="stylesheet" type="text/css"/>
    <style>
        form{padding:5px} 
        form input{padding: 2%;font-size:14px;}
        legend{font-size:15px;background: brown;width: 250px;padding: 0 10px;margin: 0;border: 1px solid #9d9292;border-bottom: 0;}
        fieldset{margin-bottom:25px;}
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container-fluid">

        <!--<image src="<?=base_url();?>assets/login/img/admin_login_logo.png" width="75"></image>-->
        <h1 class="text-center" style="font-weight: bold;">Attendance for <?=date('d-m-Y')?></h1>

        
        <form class="form" action="" method="post" enctype= multipart/form-data>
            <?php
            if($attendance_status1 == 0){
            ?>
                <fieldset>
                    <legend style="color: #fff"><?=$user_details[0]->name . ' ('. $user_details[0]->e_code .')'?> - <small>Let's start the day</small></legend>
                    <input class="col-xs-8 col-md-9" style="height: 40px;" type="file" name="file1" id="file1" placeholder="Image..." required="" capture="image/*">
                    <input type="hidden" name="eid" value="<?=$eid?>" />
                    <input type="hidden" name="phase" value="1" />
                    <button class="col-xs-4 col-md-3" style="height: 40px;" type="submit" name="submit" value="submit1"><span>Upload <i class="fa fa-sign-in"></i></span><label class="hidden">Uploading... <i class="fa fa-upload"></i></label></button>
                </fieldset>    
            <?php
            }
            ?>
        </form>    
        <form class="form" action="" method="post" enctype= multipart/form-data>
            <?php
            if($attendance_status2 == 0){
            ?>
            <fieldset>
                <legend style="color: #fff"><?=$user_details[0]->name . ' ('. $user_details[0]->e_code .')'?> - <small>It's lunch time</small></legend>
                <input class="col-xs-8 col-md-9" style="height: 40px;" type="file" name="file2" placeholder="Image..." required="" capture="image/*">
                <input type="hidden" name="eid" value="<?=$eid?>" />
                <input type="hidden" name="phase" value="2" />
                <button class="col-xs-4 col-md-3" style="height: 40px;" type="submit" name="submit" value="submit2"><span>Upload <i class="fa fa-sign-in"></i></span><label class="hidden">Uploading... <i class="fa fa-upload"></i></label></button>
            </fieldset>
            <?php
            }
            ?>
        </form>
        <form class="form" action="" method="post" enctype= multipart/form-data>
            <?php
            if($attendance_status3 == 0){
            ?>
            <fieldset>
                <legend style="color: #fff"><?=$user_details[0]->name . ' ('. $user_details[0]->e_code .')'?> - <small>Post lunch efforts</small></legend>
                <input class="col-xs-8 col-md-9" style="height: 40px;" type="file" name="file3" placeholder="Image..." required="" capture="image/*">
                <input type="hidden" name="eid" value="<?=$eid?>" />
                <input type="hidden" name="phase" value="1" />
                <button class="col-xs-4 col-md-3" style="height: 40px;" type="submit" name="submit" value="submit3"><span>Upload <i class="fa fa-sign-in"></i></span><label class="hidden">Uploading... <i class="fa fa-upload"></i></label></button>
            </fieldset>
            <?php
            }
            ?>
        </form>
        <form class="form" action="" method="post" enctype= multipart/form-data>
            <?php
            if($attendance_status4 == 0){
            ?>
            <fieldset>
                <legend style="color: #fff"><?=$user_details[0]->name . ' ('. $user_details[0]->e_code .')'?> - <small>Let's relax</small></legend>
                <input class="col-xs-8 col-md-9" style="height: 40px;" type="file" name="file4" placeholder="Image..." required="" capture="image/*">
                <input type="hidden" name="eid" value="<?=$eid?>" />
                <input type="hidden" name="phase" value="1" />
                <button class="col-xs-4 col-md-3" style="height: 40px;" type="submit" name="submit" value="submit4"><span>Upload <i class="fa fa-sign-in"></i></span><label class="hidden">Uploading... <i class="fa fa-upload"></i></label></button>
            </fieldset>
            <?php
            }
            ?>
        </form>
        <?php
        if($attendance_status1 != 0 and $attendance_status2 != 0 and $attendance_status3 != 0 and $attendance_status4 != 0){
            echo '<hr><h3 style="display:block;text-align:center;margin:auto">Hi <b>'. $user_details[0]->name .'</b> your attendance for today is already listed.</h3><hr>';
        }
        ?>
        <div class="small" style="display:block;text-align:center;margin:auto">
            <?php echo date('Y'); ?> &copy; <?=WEBSITE_NAME;?>
        </div>
    </div>

    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

<!--core jquery-->
<script src="<?=base_url();?>assets/login/js/jquery-2.1.4.min.js" type="text/javascript"></script>
<!--/.core jquery-->
<!--notification components-->
<link href="<?=base_url();?>assets/login/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
<script src="<?=base_url();?>assets/login/js/jquery.gritter.min.js" type="text/javascript"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-resize/1.1/jquery.ba-resize.min.js"></script>-->
<!--/.notification components-->

<script>

   /* $("#file1").resizeImg({
	    quality: 0.7 // 70%
	})*/


    $("button").click(function(){
        
        if($(this).prevAll("input[type=file]").val()){
            $(this).find('span').remove();
            $(this).find('label').removeClass('hidden');
        }
    })
</script>

<?php
if($this->session->flashdata('msg')) {
    ?>
    <script> //notification pop-up
        $.gritter.add({
            title: '<?=$this->session->flashdata('title');?>',
            text: '<?=$this->session->flashdata('msg');?>',
            image: '<?=base_url();?>assets/login/img/info.png',
            sticky: false,
        });
    </script>
    <?php
}

//notification for validation errors during login
?>

</body>
</html>
