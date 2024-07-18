<!DOCTYPE html>
<html lang="en">

<head>
    <title>PO LEDGER</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link href="https://fonts.googleapis.com/css?family=Chivo|Signika" rel="stylesheet">
    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        body{
                        font-family: 'Signika', sans-serif;
                        /*font-size: 12.5px;*/
                        font-family: Calibri;
                    }
                    p {
                        margin: 0 0 5px;
                    }
                    .padding-5mm{padding: 5mm;}
                    table{ border: 1px solid #777; }
                    .table{
                        margin-bottom: 3px;
                    }
                    .head_font{
                        font-family: 'Signika', sans-serif;
                        font-family: Calibri;
                    }
                    .container{width: 100%}
                    .border_all{
                        border: 1px solid #000;
                    }
                    .border_bottom{
                        border-bottom: 1px solid #000;
                    }
                    .mar_0{
                        margin: 0
                    }
                    .mar_bot_3{
                        margin-bottom: 3px
                    }

                    #sky_blue {
                        background-color: #b9d0e4;
                    }
        
                    .header_left, .header_right{
                        height: 75px
                    }
        
                    .width-100{width: 100%}
        
                    .height_60{ height: 60px }
                    .height_42{ height: 42px }
                    .height_135{height: 150px}
                    .height_90{height: 90px}
                    .height_100{height: 100px}
                    .height_110{height: 110px}
                    .height_41{ height: 41px }
                    .height_23{ height: 23px }
                    .height_63{ height: 63px }
                    .height_21{ height: 21px }
        
                    .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000!important;  text-align: center;}
                    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px; height: 50px;}
        
                    .border-bottom{border-bottom:  1px solid #000}.text-center{text-align: center!important;}.text-right{text-align: right!important;}
                
                    @page { size: A4 }
        
                    @media print{
                        .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid black!important;}
                        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 12px; height: 50px;}
                        .col-sm-6{ width: 50%!important;float:left; }.col-sm-5 { width: 41.66666667%;float:left; }.col-sm-7 { width: 58.33333333%;float:left; }
                        .text-center{text-align: center!important;}.text-right{text-align: right!important;}
                        .table-bordered {
                            overflow: hidden;
                        }
                        #sky_blue {
                        background-color: #b9d0e4 !important;
                    }
                    }
        table.order-summary td {position: relative;}                    
        table.order-summary span{left: 0;background: #d4ecea;position: absolute;bottom: 0;width: 100%;text-align: right;border-top: 1px solid;color: #000;font-size: 10px;}
    </style>
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>
    <div class="A4" id="page-content">

    <?php if($segment == 'po_ledger_report'){
        // echo '<pre>',print_r($result),'</pre>';
        
        $temp_co_name_array = array();
        foreach($result as $co_name) {
            foreach($co_name as $c_n) {
            if(!in_array($c_n->consignement_number, $temp_co_name_array)){
                array_push($temp_co_name_array, $c_n->consignement_number);
            }
            }
        }
        
        ?>
        <section class="sheet padding-5mm" style="height: auto">
        <div>
            <!--<header class="pull-right">-->
            <!--    <small>Page No. </small>-->
            <!--</header>-->
            <div class="clearfix"></div>
            <div class="container">
                <div class="row border_all text-center text-uppercase mar_bot_3">
                    <h3 class="mar_0 head_font">PO LEDGER DETAILS</h3>
                </div>
                <div class="row mar_bot_3">
                    <div class="col-sm-12 border_all text-center">
                        <h4 class=""><strong><?php if(isset($result)){
                            if($start_date != '' && $end_date != '') {
                        echo "Detailed PO Ledger Report of ".implode(', ', $temp_co_name_array)." From Date ".date('d-M-Y', strtotime($start_date))." To ".date('d-M-Y', strtotime($end_date));
                            } else {
                         echo "Detailed PO Ledger Report of ".implode(', ', $temp_co_name_array);       
                            } 
                     } ?> </strong></h4>
                    </div>
                </div>
                <!--table data-->
                <div class="row">
                    <div class="container">
                        <div class="row">
                            <div class="table-responsive">
                                <!--<h5>Retrieve Table</h5>-->
                                <table id="all_det" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>CN Number</th>
                                        <th>Employee Name</th>
                                        <th>Distributed Date & Type</th>
                                        <th style="text-align:right">Distributed Qty.</th>
                                        <th>Received Date</th>
                                        <th style="text-align:right">Received Qty.</th>
                                        <th style="text-align:right">Extra</th>
                                        <th style="text-align:right">Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $po_number_detail = $result;

                                $groups = array();
                                $extra_quantity = 0;
                                $due_quantity = 0;
                                $receive_quantity = 0;
                                $total_distribute = 0;
                                $total_receive = 0;
                                $total_extra = 0;
                                $total_due = 0;
                                $new_received_quantity = 0;
                                $new_distributed_quantity = 0;
                            foreach ($po_number_detail as $fasd) {
                                foreach ($fasd as $f) {
                                    if($f->distribute_pod_quantity == 0) {
                                        continue 2;
                                    }
                                    $distribute = $this->db->select_sum('received_quantity')->get_where('checkin_detail', array('pod_id' => $f->pod_id, 'e_id' => $f->e_id))->row();
                                    if(count($distribute) > 0) {
                                      $distribute_quantity = $distribute->received_quantity;  
                                    } else {
                                      $distribute_quantity = 0;  
                                    }
                                    $key = $f->consignement_number;
                                    if($distribute_quantity > $f->distribute_pod_quantity) {
                                            $extra_quantity = $distribute_quantity - $f->distribute_pod_quantity;
                                            } else {
                                                $due_quantity = $f->distribute_pod_quantity - $distribute_quantity;
                                            }
                                    if (!isset($groups[$key])) {
                                        $groups[$key] = array(
                                            'consignement_number' => $f->consignement_number,
                                            'distribute_pod_quantity' => $f->distribute_pod_quantity,
                                            'received_quantity' => $distribute_quantity,
                                            'extra_quantity' => $extra_quantity,
                                            'due_quantity' => $due_quantity,
                                        );
                                    } else {
                                        $groups[$key]['consignement_number'] = $f->consignement_number;
                                        $groups[$key]['distribute_pod_quantity'] += $f->distribute_pod_quantity;
                                        $groups[$key]['received_quantity'] += $distribute_quantity;
                                        $groups[$key]['extra_quantity'] += $extra_quantity;
                                        $groups[$key]['due_quantity'] += $due_quantity;
                                    }
                                }
                            }

                                if(isset($po_number_detail)){ 
                                //echo json_encode($po_data);

                                
                                foreach($po_number_detail as $result){
                                   foreach($result as $curr_key=>$res) {
                                    if($res->distribute_pod_quantity == 0) {
                                        continue 2;
                                    }

                                $keys = array();
                                    foreach($result as $key=>$val) {
                                    if ($val->consignement_number == $res->consignement_number) {
                                            array_push($keys, $key);
                                        }
                                    }
                                                                    // echo '<pre>', print_r($keys), '</pre>'; die();


                                ?>                                    
                                    <tr>
                                        <?php if(current($keys) == $curr_key) { ?>
                                        <td><span><?=$res->consignement_number?>/<?=$res->size_in_text?></span></td>
                                    <?php } else { ?>
                                        <td><span style="display: none;"><?=$res->consignement_number?></span></td>
                                    <?php } ?>
                                    <td>
                                        <?php
                                              echo $res->name;
                                        ?>
                                        
                                        </td>
                                    <td>
                                        <?= date('d-M-Y', strtotime($res->dis_create_date)) . ' ('. $res->category_name .')';
                                        ?>
                                        
                                        </td>
                                        <td style="text-align:right">
                                        <?php
                                            if($res->name != ''){
                                                $total_distribute += $res->distribute_pod_quantity;
                                                echo $res->distribute_pod_quantity."Pcs. (" . $res->size . ')';
                                            }else{
                                                echo "Not Distributed";
                                            }
                                        ?>
                                        
                                        </td>
                                        
                                        <td>
                                            <?php 
        $distribute = $this->db->get_where('checkin_detail', array('pod_id' => $res->pod_id, 'e_id' => $res->e_id))->result();
        ?>
                                        <?php
                                        if(count($distribute) > 0){
                                            foreach($distribute as $de) {
                                                echo date('d-M-Y', strtotime($de->create_date))."<br/>";
                                            }
                                        } else {
                                            echo '';
                                        }
                                        ?>
                                        
                                        </td>
                                        
                                        <td style="text-align:right">
        <?php 
        $distribute = $this->db->join('sizes','sizes.sz_id = checkin_detail.sz_id', 'left')->get_where('checkin_detail', array('pod_id' => $res->pod_id, 'e_id' => $res->e_id))->result();
        ?>
        
        <?php  ?>
                                        <?php
                                            if(count($distribute) > 0){
                                                foreach($distribute as $de) {
                                                  $receive_quantity += $de->received_quantity;
                                                $total_receive += $de->received_quantity;
                                                echo $de->received_quantity."Pcs. (".$de->size.")<br/>";
                                                }
                                                
                                            }else{
                                                $receive_quantity = 0;
                                                echo "Not Received";
                                            }
                                        ?>
                                        
                                        </td>
                                        <td style="text-align:right">
                                            <?php
                                            if($receive_quantity > $res->distribute_pod_quantity) {
                                                $total_extra += ($receive_quantity - $res->distribute_pod_quantity);
                                                $new_received_quantity += ($receive_quantity - $res->distribute_pod_quantity);
                                            echo ($receive_quantity - $res->distribute_pod_quantity);
                                            } else {
                                                echo '0';
                                            } 
                                            ?>
                                        </td>
                                        <td style="text-align:right">
                                            <?php
                                            if($res->distribute_pod_quantity > $receive_quantity) {
                                                $total_due += ($res->distribute_pod_quantity - $receive_quantity);
                                                $new_distributed_quantity += ($res->distribute_pod_quantity - $receive_quantity);
                                            echo ($res->distribute_pod_quantity - $receive_quantity);
                                            } else {
                                                echo '0';
                                            }
                                            $receive_quantity = 0;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    if(end($keys) == $curr_key) {
                                        ?>
                                        <tr style="background-color: #b9d0e4;">
                                            <th colspan="3">Total for <?=$groups[$res->consignement_number]['consignement_number']?></th>
                                            <th style="text-align: right;"><?= number_format( $groups[$res->consignement_number]['distribute_pod_quantity'], 2)?></th>
                                            <th></th>
                                            <th style="text-align: right;"><?= number_format( $groups[$res->consignement_number]['received_quantity'], 2)?></th>
                                            <th style="text-align: right;"><?= number_format($new_received_quantity, 2); $new_received_quantity = 0;?></th>
                                            <th style="text-align: right;"><?= number_format( $new_distributed_quantity, 2); $new_distributed_quantity = 0;?></th>
                                        </tr>
                                        <?php
                                    }
                                ?>
                                   <?php
                                   } 
} ?>
                                        <tr style="background-color: #4680b3; color: white;">
                                            <th colspan="3">Grand Total</th>
                                            <th style="text-align: right;"><?= number_format( $total_distribute, 2)?></th>
                                            <th></th>
                                            <th style="text-align: right;"><?= number_format( $total_receive, 2)?></th>
                                            <th style="text-align: right;"><?= number_format( $total_extra, 2)?></th>
                                            <th style="text-align: right;"><?= number_format( $total_due, 2)?></th>
                                        </tr>
<?php }
                                    ?> 
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <?php
    } ?>
    
    <?php if($segment == 'wastage_report'){
        // echo '<pre>',print_r($result),'</pre>';
        
        $temp_co_name_array = array();
        foreach($result as $co_name) {
            foreach($co_name as $c_n) {
            if(!in_array($c_n['consignment_no'], $temp_co_name_array)){
                array_push($temp_co_name_array, $c_n['consignment_no']);
            }
            }
        }
        
        ?>
        <section class="sheet padding-5mm" style="height: auto">
        <div>
            <!--<header class="pull-right">-->
            <!--    <small>Page No. </small>-->
            <!--</header>-->
            <div class="clearfix"></div>
            <div class="container">
                <div class="row border_all text-center text-uppercase mar_bot_3">
                    <h3 class="mar_0 head_font">WASTAGE REPORT</h3>
                </div>
                <div class="row mar_bot_3">
                    <div class="col-sm-12 border_all text-center">
                        <h4 class=""><strong><?php if(isset($result)){
                            if($start_date != '' && $end_date != '') {
                        echo "Detailed Wastage Report of ".implode(', ', $temp_co_name_array)." From Date ".date('d-M-Y', strtotime($start_date))." To ".date('d-M-Y', strtotime($end_date));
                            } else {
                         echo "Detailed Wastage Report Of ".implode(', ', $temp_co_name_array);       
                            } 
                     } ?> </strong></h4>
                    </div>
                </div>
                <!--table data-->
                <div class="row">
                    <div class="container">
                        <div class="row">
                            <div class="table-responsive">
                                <!--<h5>Retrieve Table</h5>-->
                                <table id="all_det" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>CN Number</th>
                                        <th>Roll/Handel</th>
                                        <th>Size</th>
                                        <th>Unit</th>
                                        <th>Prod. No.</th>
                                        <th>Prod. Dt.</th>
                                        <th style="text-align:right">Tot. Roll. Wt.</th>
                                        <th style="text-align:right">Exptd. Prod.</th>
                                        <th style="text-align:right">Bag Prod.</th>
                                        <th style="text-align:right">Wastage(Pcs)</th>
                                        <th style="text-align:right">Wastage(gm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $groups = array();
                                $extra_quantity = 0;
                                $total_roll_weight1 = 0;
                                $total_expected_production = 0;
                                $total_bag_produced = 0;
                                $total_wastage_pcs = 0;
                                $total_wastage_gm = 0;

                                if(isset($result)){ 
                                //echo json_encode($po_data);

                                
                                foreach($result as $resl){

                                   foreach($resl as $curr_key=>$res) {

                                ?>                                    
                                    <tr>
                                        <td><?=$res['consignment_no']?></td>
                                        <td><?=$res['roll_handel']?></td>
                                        <td><?=$res['size']?></td>
                                        <td><?=$res['unit']?></td>
                                        <td><?=$res['production_no']?></td>
                                    <td>
                                        <?php
                                                echo date('d-M-Y', strtotime($res['production_date']));
                                        ?>
                                        
                                        </td>
                                        <td style="text-align:right;">
                                        <?php
                                                echo $res['total_roll_weight'];
                                                $total_roll_weight1 += $res['total_roll_weight']; 
                                        ?>
                                        
                                        </td>
                                        
                                        <td style="text-align:right;">
                                        <?php
                                                echo $res['expected_production'];
                                                $total_expected_production += $res['expected_production']; 
                                        ?>
                                        
                                        </td>
                                        
                                        <td style="text-align:right">
                                        <?php
                                                echo $res['bag_produced'];
                                                $total_bag_produced += $res['bag_produced']; 
                                        ?>
                                        
                                        </td>
                                        <td style="text-align:right">
                                            <?php
                                            echo $res['wastage_pcs'];
                                            $total_wastage_pcs += $res['wastage_pcs'];  
                                            ?>
                                        </td>
                                        <td style="text-align:right">
                                            <?php
                                            echo $res['wastage_gm'];
                                            $total_wastage_gm += $res['wastage_gm'];  
                                            ?>
                                        </td>
                                    </tr>

                                   <?php
                                   } 
                                   ?>

<?php                                  
} 
?>

                                        <tr style="background-color: #4680b3; color: white;">
                                            <th colspan="6"></th>
                                            <th style="text-align: right;"><?= number_format($total_roll_weight1, 2)?></th>
                                            <th style="text-align: right;"><?= number_format($total_expected_production, 2)?></th>
                                            <th style="text-align: right;"><?= number_format($total_bag_produced, 2)?></th>
                                            <th style="text-align: right;"><?= number_format($total_wastage_pcs, 2)?></th>
                                            <th style="text-align: right;"><?= number_format($total_wastage_gm, 2)?></th>
                                        </tr>

<?php }
                                    ?> 
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <?php
    } ?>

<?php if($segment == 'order_details'){
        // echo '<pre>',print_r($result),'</pre>';
        
        $temp_co_name_array = array();
        foreach($result as $co_name) {
            if(!in_array($co_name['size'], $temp_co_name_array)){
                array_push($temp_co_name_array, $co_name['size']);
            }
        }
        
        ?>
        <section class="sheet padding-5mm" style="height: auto">
        <div>
            <!--<header class="pull-right">-->
            <!--    <small>Page No. </small>-->
            <!--</header>-->
            <div class="clearfix"></div>
            <div class="container">
                <div class="row border_all text-center text-uppercase mar_bot_3">
                    <h3 class="mar_0 head_font">ORDER SUMMARY</h3>
                </div>
                <div class="row mar_bot_3">
                    <div class="col-sm-12 border_all text-center">
                        <h4 class=""><strong><?php if(isset($result)){
                            if($start_date1 != '' && $end_date1 != '') {
                        echo "Detailed Order Summary Report From Date ".date('d-M-Y', strtotime($start_date1))." To ".date('d-M-Y', strtotime($end_date1));
                            } else {
                         echo "Detailed Order Summary Report";       
                            } 
                     } ?> </strong></h4>
                    </div>
                </div>
                <!--table data-->
                <div class="row">
                    <div class="container">
                        <div class="row">
                            <div class="table-responsive">
                                <!--<h5>Retrieve Table</h5>-->
                                <table id="all_det" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <thead>
                                            <th class="text-center" colspan="3">Purchase Order</th>
                                            <th class="text-center" colspan="3">Distribute Details</th>
                                            <th class="text-center" colspan="1">Received Details</th>
                                            <th class="text-center" colspan="3">Customer Order Details</th>
                                            <!--<th class="text-center" colspan="4">Invoice Details</th>-->
                                            <th class="text-center">Stock</th>
                                        </thead>
                                    </tr>
                                    <tr>
                                        <th style="width: 6%;">Size</th>
                                        <th style="width: 6%;">Date</th>
                                        <th style="width: 6%;">Po. No.</th>
                                        <th style="width: 6%;">Date</th>
                                        <th style="width: 6%;">Cn. No.</th>
                                        <th style="width: 6%;">Quantity/Cn.</th>
                                        <!--<th>Total Quantity Cn</th>-->
                                        <th style="width: 6%;">Receive <br/> quantities</th>
                                        <!--<th>Finished Stock</th>-->
                                        <!--customer order-->
                                        <th style="width: 6%;">Date</th>
                                        <th style="width: 6%;">Order No.</th>
                                        <th style="width: 6%;">Quantity</th>
                                        <!--customer order-->
                                        <!--<th>Date</th>-->
                                        <!--<th>Client Name</th>-->
                                        <!--<th>Invoice No.</th>-->
                                        <!--<th>Quantities</th>-->
                                        <th style="width: 6%;">Balance <br/> Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $groups = array();
                                $order_quantity_grand_total = 0;
                                $order_quantity_grand_total1 = 0;
                                $order_detail_quantity_total_cn = 0;
                                $order_detail_quantity_total_cn1 = 0;
                                $order_detail_received_quantity_total = 0;
                                $order_detail_received_quantity_total1 = 0;
                                $order_details_invoice_quantities = 0;
                                $order_details_invoice_quantities_balance = 0;
                                $final_stock = 0;
                                $final_stock1 = 0;
                                if(isset($result)){ 
                                //echo json_encode($po_data);
                                foreach($result as $resl){

                                ?>
                                <tr>
                                    <td nowrap><?= $resl['size'] ?></td>
                                    <td nowrap><?= $resl['po_date'] ?></td>
                                    <td nowrap><?= $resl['po_number'] ?></td>
                                    <td nowrap><?= $resl['order_detail_date'] ?></td>
                                    <td style="text-align: right;"><?= $resl['order_detail_number_cn'] ?></td>
                                    <td style="text-align: right;"><?= $resl['order_detail_quantity_cn'] ?></td>
                                    <?php $order_detail_quantity_total_cn += $resl['order_detail_quantity_total_cn']; $order_detail_quantity_total_cn1 += $resl['order_detail_quantity_total_cn'];?>
                                    <td style="text-align: right;"><?php echo $resl['order_detail_received_quantity'];$order_detail_received_quantity_total += $resl['order_detail_received_quantity_total']; $order_detail_received_quantity_total1 += $resl['order_detail_received_quantity_total'];?></td>
                                    <!--<td style="text-align: right;"><?= $resl['order_detail_received_quantity_total'] ?></td>-->
                                    <!--customer order-->
                                        
                                    <td nowrap><?=$resl['order_date']?></td>
                                    <td nowrap><?=$resl['order_name']?></td>
                                    <td style="text-align: right;"><?=$resl['order_quantity'] ?></td>
                                    <?php $order_quantity_grand_total += $resl['order_quantity_total']; $order_quantity_grand_total1 += $resl['order_quantity_total']; ?>
                                    <!--customer order-->
                                    
                                    <!--<td nowrap><?= $resl['order_details_invoice_date'] ?></td>-->
                                    <!--<td nowrap><?= $resl['order_details_accn_name'] ?></td>-->
                                    <!--<td nowrap><?php echo $resl['order_details_invoice_number'];$order_details_invoice_quantities = $resl['order_details_invoice_quantities_total'];?></td>-->
                                    <!--<td nowrap style="text-align: right;"><?= $resl['order_details_invoice_quantities'] . '<span class="highlight">'. $resl['order_details_invoice_quantities_balance'] .'</span>' ?></td>-->
                                    <!--<td nowrap style="text-align: right;">-->
                                    <!--<?php $order_details_invoice_quantities_balance += $resl['order_details_invoice_quantities_balance']; ?></td>-->
                                    <td style="text-align: right;"><?php echo $bal_stock = $resl['order_detail_received_quantity_total'] - $resl['order_quantity_total']; $final_stock += $bal_stock; $final_stock1 += $bal_stock; ?></td>
                                </tr>

                                <tr style="background-color: #c3d8eb;">
                                     <th colspan="5">Total</th>
                                     <th style="text-align: right;"><?php echo $order_detail_quantity_total_cn1; $order_detail_quantity_total_cn1 = 0; ?></th>
                                     <th style="text-align: right;"><?php echo $order_detail_received_quantity_total1; $order_detail_received_quantity_total1 = 0; ?></th>
                                     <!--<th style="text-align: right;"><?= $order_detail_received_quantity_total ?></th>-->
                                     <!--<th style="text-align: right;"><?= $order_detail_received_quantity_total ?></th>-->
                                     <th colspan="3" style="text-align: right;"><?php echo $order_quantity_grand_total1; $order_quantity_grand_total1 = 0; ?></th>
                                     <!--<th colspan="4" style="text-align: right;"><?= $order_details_invoice_quantities ?></th>-->
                                     <th style="text-align: right;"><?php echo $final_stock1; $final_stock1 = 0; ?></th>
                                 </tr>

                                <?php 

                                }

                                ?>

                            <?php

                            }

                                 ?>
                                 <tr style="background-color: #4e687e; color: white;">
                                     <th colspan="5">Grand Total</th>
                                     <th style="text-align: right;"><?= $order_detail_quantity_total_cn ?></th>
                                     <th style="text-align: right;"><?= $order_detail_received_quantity_total ?></th>
                                     <!--<th style="text-align: right;"><?= $order_detail_received_quantity_total ?></th>-->
                                     <!--<th style="text-align: right;"><?= $order_detail_received_quantity_total ?></th>-->
                                     <th colspan="3" style="text-align: right;"><?=$order_quantity_grand_total?></th>
                                     <!--<th colspan="4" style="text-align: right;"><?= $order_details_invoice_quantities ?></th>-->
                                     <th style="text-align: right;"><?= $final_stock ?></th>
                                 </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <?php
    } ?>


    <?php 
        if($segment == 'invoice_details_report'){
            
            ?>

        <section class="sheet padding-5mm" style="height: auto">
        
            <div class="container">
                <div class="row border_all text-center text-uppercase mar_bot_3">
                    <h3 class="mar_0 head_font">INVOICE SUMMARY</h3>
                </div>
                <div class="row mar_bot_3">
                    <div class="col-sm-12 border_all text-center">
                        <h4 class="">Detailed Invoice Summary Report, dated <?= date('d-M-Y') ?> </h4>
                    </div>
                </div>    
                <div class="row">
                    <div class="table-responsive">
                        <table id="all_det_invoice" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Invoice No.</th>
                                    <th>Party Name</th>
                                    <th>E-Way Bill No.</th>
                                    <th>Transport Name</th>
                                    <th>Transport CN</th>
                                    <th>Total Carton</th>
                                    <th>Total Weight</th>
                                    <th>Transport Payment Amount</th>
                                    
                                    <th>Payment Type</th>
                                    <th>Invoice Amount</th>
                                    <th>Transport Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $iter=1; 
                                foreach($invoice_details_report as $idr){ 
                                    $total_invoice_amount = $idr->invoice_total_amount + ($idr->invoice_total_amount * (12/100));
                                    $total_invoice_amount = $total_invoice_amount + ($idr->packing_rate + ($idr->packing_rate * ($idr->packing_tax/100)));
                                ?>
                                <?php ($idr->transport_payment_status == 'Pending') ? $bg = '#f2dede' : $bg = '' ?>
                                <tr style="background-color: <?=$bg?>;">
                                    <td><?= $iter++ ?></td>
                                    <td><?=$idr->cus_inv_number?></td>
                                    <td><?=$idr->customer_name?></td>
                                    <td><?=$idr->cus_inv_e_way_bill_no?></td>
                                    <td><?=$idr->transporter_name?></td>
                                    <td><?=$idr->transporter_cn_number?></td>
                                    <td><?=$idr->cus_inv_number_of_cartons?></td>
                                    <td><?=$idr->cus_inv_total_weight?></td>
                                    <td><?=$idr->transport_payment_amount?></td>
                                    
                                    <td><?=$idr->transport_payment_type?></td>
                                    <td><?=$total_invoice_amount?></td>
                                    <td><?=$idr->transport_payment_status?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
        </section>            
        <?php
        }
    ?>
    
</div>

</body>

<?php if($segment == 'check_in_out_report'){
        // echo '<pre>',print_r($result),'</pre>';
        
        $temp_co_name_array = array();
        foreach($result as $co_name) {
            foreach($co_name as $c_n) {
            if(!in_array($c_n['employee_name'], $temp_co_name_array)){
                array_push($temp_co_name_array, $c_n['employee_name']);
            }
            }
        }
        
        ?>
        <body class="A4 landscape">
        <section class="sheet padding-5mm" style="height: auto">
        <div>
            <!--<header class="pull-right">-->
            <!--    <small>Page No. </small>-->
            <!--</header>-->
            <div class="clearfix"></div>
            <div class="container">
                <div class="row border_all text-center text-uppercase mar_bot_3">
                    <h3 class="mar_0 head_font">CHECK IN/OUT REPORT</h3>
                </div>
                <div class="row mar_bot_3">
                    <div class="col-sm-12 border_all text-center">
                        <h4 class=""><strong><?php if(isset($result)){
                            if($start_date != '' && $end_date != '') {
                        echo "Detailed Report of ".implode(', ', $temp_co_name_array)." From Date ".date('d-M-Y', strtotime($start_date))." To ".date('d-M-Y', strtotime($end_date));
                            } else {
                         echo "Detailed Report Of ".implode(', ', $temp_co_name_array);       
                            } 
                     } ?> </strong></h4>
                    </div>
                </div>
                <!--table data-->
                <div class="row">
                    <div class="container">
                        <div class="row">
                            <div class="table-responsive">
                                <!--<h5>Retrieve Table</h5>-->
                                <table id="all_det" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <tr>
                                        <th style="width: 8%; text-align: center;">Employee <br/> Name</th>
                                        <th style="width: 7%; text-align: center;">CN Number</th>
                                        <th style="width: 4%; text-align: center;">Product <br/> Type</th>
                                        <th style="width: 8%; text-align: center;">Size</th>
                                        <th style="width: 4%; text-align: center;">Unit</th>
                                        <th style="width: 8%; text-align: center;">Distributed <br/> Date</th>
                                        <th style="text-align:right; width: 4%;">Distributed <br/> Qty.</th>
                                        <th style="width: 14%; text-align: center;">Checkin No.</th>
                                        <th style="width: 6%; text-align: center;">Actual Received <br/> Date</th>
                                        <th style="text-align:right; width: 4%;">Received <br/> Qty.</th>
                                        <th style="text-align:right; width: 4%;">Due/Extra</th>
                                        <th style="width: 4%; text-align: center;">Payment Status</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $groups = array();
                                $extra_quantity = 0;
                                $due_quantity = 0;
                                $total_distribute = 0;
                                $total_receive = 0;
                                $total_extra = 0;
                                $total_due = 0;
                                $new_received_quantity = 0;
                                $new_distributed_quantity = 0;
                                $total_receive_paid = 0;
                                $total_receive_unpaid = 0;
                                $nw_rcvd_qnty_paid = 0;
                                $nw_rcvd_qnty_unpaid = 0;
                            foreach ($result as $fasd) {
                                foreach ($fasd as $f) {
                                    $key = $f['size'];
                                    if (!isset($groups[$key])) {
                                        $groups[$key] = array(
                                            'size' => $f['size'],
                                            'distributed_qnty' => $f['distributed_qnty'],
                                            'received_qnty' => $f['received_qnty']
                                        );
                                    } else {
                                    $groups[$key]['size'] = $f['size'];
                                    $groups[$key]['distributed_qnty'] += $f['distributed_qnty'];
                                    $groups[$key]['received_qnty'] += $f['received_qnty'];
                                    }
                                }
                            }
                            
                            $groups1 = array();
                            
                            foreach ($result as $fasd) {
                                foreach ($fasd as $f) {
                                    $key = $f['size'];
                                    if (!isset($groups1[$key])) {
                                        $groups1[$key] = array(
                                            'employee_name' => $f['employee_name'],
                                            'distributed_qnty' => $f['distributed_qnty'],
                                            'nw_rcvd_qnty_paid' => $f['nw_rcvd_qnty_paid'],
                                            'nw_rcvd_qnty_unpaid' => $f['nw_rcvd_qnty_unpaid'],
                                            'received_qnty' => $f['received_qnty']
                                        );
                                    } else {
                                    $groups1[$key]['employee_name'] = $f['employee_name'];
                                    $groups1[$key]['distributed_qnty'] += $f['distributed_qnty'];
                                    $groups1[$key]['nw_rcvd_qnty_paid'] += $f['nw_rcvd_qnty_paid'];
                                    $groups1[$key]['nw_rcvd_qnty_unpaid'] += $f['nw_rcvd_qnty_unpaid'];
                                    $groups1[$key]['received_qnty'] += $f['received_qnty'];
                                    }
                                }
                            }

                                if(isset($result)){ 
                                //echo json_encode($po_data);

                                
                                foreach($result as $resl){

                                    $see_count = count($resl);

                                   foreach($resl as $curr_key=>$res) {

                                $keys = array();
                                    foreach($resl as $key=>$val) {
                                    if ($val['size'] == $res['size']) {
                                            array_push($keys, $key);
                                        }
                                    }
                                    
                                    $keys1 = array();
                                    foreach($resl as $key=>$val) {
                                    if ($val['employee_name'] == $res['employee_name']) {
                                            array_push($keys1, $key);
                                        }
                                    }
                                                                    // echo '<pre>', print_r($keys), '</pre>'; die();


                                ?>                                    
                                    <tr>
                                        <?php
                                    if(current($keys1) == $curr_key) {
                                        ?>
                                        <th style="text-align: center;" ><?=$res['employee_name']?></th>
                                        <?php 
                                        } else { ?> 
                                        <th style="text-align: center;"><span style="display: none;"><?=$res['employee_name']?></span></th>
                                        <?php } ?>
                                        <td style="text-align: center;"><?=$res['consignment_no']?></td>
                                        <td style="text-align: center;"><?=$res['category']?></td>
                                        <td style="text-align: center;"><?=$res['size']?></td>
                                        <td style="text-align: center;"><?=$res['unit']?></td>
                                        <td style="text-align: center;">
                                        <?php
                                                echo date('d-M-Y', strtotime($res['distributed_date']));
                                        ?>
                                        
                                        </td>
                                        <td style="text-align:right">
                                        <?php
                                                $total_distribute += $res['distributed_qnty'];
                                                $new_distributed_quantity += $res['distributed_qnty'];
                                                echo $res['distributed_qnty'];
                                        ?>
                                        
                                        </td>

                                        <td nowrap style="text-align: center;">
                                            <?php
                                            echo '<b>PO No.: </b>' . $res['po_number'] . '<br>';
                                            echo '<b>Checkin No.: </b>';
                                            foreach($res['checkin_no'] as $ci) {
                                                echo $ci."<br/>";
                                            }
                                            ?>
                                        </td>
                                        
                                        <td nowrap style="text-align: center;">
                                            <?php
                                            foreach($res['received_date'] as $rt) {
                                                echo $rt."<br/>";
                                            }
                                            ?>
                                        
                                        </td>
                                        
                                        <td style="text-align:right">
                                            <?php
                                            foreach($res['received_qnty'] as $rn) {
                                                echo $rn."<br/>";
                                            }
                                            ?>
                                        <?php
                                                $total_receive += $res['new_received_qnty'];
                                                $new_received_quantity += $res['new_received_qnty'];
                                                $total_receive_paid += $res['nw_rcvd_qnty_paid'];
                                                $total_receive_unpaid += $res['nw_rcvd_qnty_unpaid'];
                                                $nw_rcvd_qnty_paid += $res['nw_rcvd_qnty_paid'];
                                                $nw_rcvd_qnty_unpaid += $res['nw_rcvd_qnty_unpaid'];
                                        ?>
                                        
                                        </td>
                                        <td style="text-align:right">
                                            <?php
                                            echo $res['extra_due']; 
                                            ?>
                                        </td>
                                        <td nowrap style="width: 4%; text-align: center;">
                                            <?php
                                            foreach($res['payment'] as $pn) {
                                                echo $pn."<br/>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    if(end($keys) == $curr_key) {
                                        ?>
                                        <tr id="sky_blue">
                                            <th colspan="6"></th>
                                            <th style="text-align: right;"><?php echo number_format($new_distributed_quantity, 2);
                                            $new_distributed_quantity = 0; ?></th>
                                            <th></th>
                                            <th></th>
                                            <th colspan="3" style="text-align: left;">
                                            <?php 
                                            if($nw_rcvd_qnty_paid != 0) {
                                             echo "<span style='color: green;'>".number_format($nw_rcvd_qnty_paid, 2)." (Paid)</span><br/>"; $nw_rcvd_qnty_paid = 0;   
                                            }
                                            ?>
                                            <?php 
                                            if($nw_rcvd_qnty_unpaid != 0) {
                                             echo "<span style='color: red;'>".number_format($nw_rcvd_qnty_unpaid, 2)." (Unpaid)</span><br/>"; $nw_rcvd_qnty_unpaid = 0;   
                                            }
                                            ?>
                                            </th>
                                        </tr>
                                        <?php
                                    }
                                ?>
                                   <?php
                                   } 
} ?>

                                        <tr style="background-color: #64b0f3; color: white;">
                                            <th colspan="6"></th>
                                            <th style="text-align: right; font-size: 16px;"><?= number_format($total_distribute, 2)?></th>
                                            <th></th>
                                            <th></th>
                                            <th colspan="3" style="text-align: left; font-size: 16px;">
                                            <?php 
                                            if($total_receive_paid != 0) {
                                             echo "<span style='color: green;'><b>".number_format($total_receive_paid, 2)." (Paid)</b></span><br/>";   
                                            }
                                            ?>
                                            <?php 
                                            if($total_receive_unpaid != 0) {
                                             echo "<span style='color: red;'><b>".number_format($total_receive_unpaid, 2)." (Unpaid)</b></span><br/>";   
                                            }
                                            ?>
                                            <b><?=number_format( $total_receive, 2)." (Total)</b><br/>"?>
                                            </th>
                                        </tr>

<?php }
                                    ?> 
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </body>
        <?php
    } ?>

</html>