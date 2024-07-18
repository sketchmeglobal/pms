<?php
//echo '<pre>', print_r($purchase_order_details), '</pre>';die;
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Purchase Order</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Normalize or reset CSS with your favorite library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
        <!-- Load paper.css for happy printing -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
        <link href="https://fonts.googleapis.com/css?family=Chivo|Signika" rel="stylesheet">
        <link href="http://localhost/sopl-new/assets/img/favicon.ico" rel="shortcut icon" type="image/png">
        <!-- Set page size here: A5, A4 or A3 -->
        <!-- Set also "landscape" if you need -->
        <style>
            body{
                /*font-family: 'Chivo', sans-serif;*/
                font-family: Calibri;
            }
            p {
                margin: 0 0 5px;
            }
            table{ border: 1px solid #777; }
            .table{
                margin-bottom: 3px;
            }
            .head_font{
                /*font-family: 'Signika', sans-serif;*/
                font-family: Calibri;
            }
            .container{width: 100%}
            .border_all{
                border: 1px solid #000;
            }
            .mar_0{
                margin: 0
            }
            .mar_bot_3{
                margin-bottom: 3px
            }

            .header_left, .header_right{
                height: 185px
            }

            .width-100{width: 100%}

            .height_60{ height: 60px }
            .height_42{ height: 42px }
            .height_135{height: 150px}
            .height_90{height: 90px}
            .height_100{height: 100px}
            .height_41{ height: 41px }
            .height_23{ height: 23px }
            .height_63{ height: 63px }
            .height_21{ height: 21px }
            .height_215{ height: 191px }

            .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000!important;  text-align: center;}
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px}

            .border-bottom{border-bottom:  1px solid #000}

            @page { size: A4 }

            @media print{
                .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000;  text-align: center;}
                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px}
                .col-sm-3 { width: 25%;float: left;}
                .col-sm-6 { width: 50%!important;float:left; }
                .col-sm-5 { width: 41.66666667%;float:left; }
                .col-sm-7 { width: 58.33333333%;float:left; }
                .col-sm-9 { width: 75%;float: left;}

                .border-bottom{border-bottom:  1px solid #000}
            }
            .p-0 {
                padding-left: 4px;
                padding-right: 4px;
                padding-top: 0;
                padding-bottom: 0;
            }
        </style>
    </head>

    <!-- Set "A5", "A4" or "A3" for class name -->
    <!-- Set also "landscape" if you need -->
    <body class="A4" id="page-content">
    
        <?php
        $page_no = 1;
        ?>
        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <div>
                <header class="pull-right">
                    <small>Page No. <?= $page_no ?></small>
                </header>
                <div class="clearfix"></div>
                
                <div class="container">
                    <div class="row border_all text-center text-uppercase mar_bot_3">
                        <h3 class="mar_0 head_font">CN Numbers of <?=$purchase_order_details[0]->po_number?></h3>
                    </div>
                    
                    <div class="row">
                    <?php
                    $array_po = array(); 
                    $pod = $purchase_order_details[0]->po_id;
        $purchase_order_details_id = $this->db
        ->select('purchase_order.*, purchase_order_details.*')
        ->join('purchase_order', 'purchase_order.po_id = purchase_order_details.po_id', 'left')
        ->group_by('purchase_order_details.po_id, purchase_order_details.size_in_text')
        ->order_by('purchase_order_details.pod_id')
        ->get_where('purchase_order_details', array('purchase_order_details.po_id' => $this->uri->segment(3)))
        ->result();
                     ?>
                    <?php  
        foreach($purchase_order_details_id as $p_o_d_i) {
            $size_num = $this->db->get_where('purchase_order_details', array('po_id' => $p_o_d_i->po_id, 'size_in_text' => $p_o_d_i->size_in_text))->num_rows();
            for($i = 1; $i <= $size_num; $i++){
             $arr = array(
             'number' => $i.' of '.$size_num  
        );
             array_push($array_po, $arr);
            }
        }

        // echo '<pre>',print_r($array_po),'</pre>'; die();
                    $iter = 0;
                    $new_iter = 18; 
                    foreach($purchase_order_details as $pod){
                        $size_in_text = $pod->size_in_text;
        $size_num = $this->db->get_where('purchase_order_details', array('po_id' => $pod->po_id, 'size_in_text' => $size_in_text))->num_rows(); 
                        if($iter == 18 or $iter == $new_iter) { 
                          $new_iter += 18;
                            ?>
                     </div>
                 </div>
             </div>
         </section>
     </body>

     <body class="A4" id="page-content">
        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <div>
                <header class="pull-right">
                    <?php $page_no = ($page_no + 1); ?>
                    <small>Page No. <?= $page_no ?></small>
                </header>
                <div class="clearfix"></div>
                
                <div class="container">
                    <div class="row border_all text-center text-uppercase mar_bot_3">
                        <h3 class="mar_0 head_font">CN Numbers of <?=$purchase_order_details[0]->po_number?></h3>
                    </div>
                    
                    <div class="row">
                        <?php }                   
                        ?>
              <div class="col-sm-6" style="font-size:50px; text-align:center; border-style: dotted;">
                  <?php # echo '<pre>',print_r($pod),'</pre>' ?>
                <?=$pod->consignement_number?>/<?=$pod->size_in_text ?>

               <?php 
           foreach($array_po as $key=>$val) {
           if($key == $iter) { ?>
            <div class="row">
                <div class="col-sm-9 p-0">
                <p style="font-size: 12px; text-align: left;"><?=$purchase_order_details[0]->po_number?> (PO NO.) </p>
            </div>
            <div class="col-sm-3 p-0">
            <p style="font-size: 12px; text-align: right;"><?= $val['number'] ?></p>
        </div>
            </div>
      <?php }
       }
       ?>

                </div>
            

                      <?php
                      $iter++; 
                  }
                   ?>
                    </div>                            
                            
                        </div>
                    </div>
                </section>
    </body>
</html>
