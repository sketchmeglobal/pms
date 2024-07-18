
    <?php 
    // error_reporting(0); 
    #echo '<pre>', print_r($invoice_print),'</pre>' 
    if(!isset($invoice_print[0])){
        $link = base_url('admin/customer-invoice');
        echo "Invoice is incomplete. <a href=$link>Click here is to view Invoice List</a>";
        die();
    }
    ?>

    <?php 
        if($invoice_type == 'tax'){
            $invoice_type = 'Tax Invoice';
        }else{
            $invoice_type = 'Proforma Invoice';
        }
        $state = mb_substr($invoice_print[0]->vat_no, 0, 2);
     ?>

    <!DOCTYPE html>
    <html lang="en">

        <head>
            <meta charset="utf-8">
            <title><?= $invoice_type ?></title>

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
                    /*font-family: 'Chivo', sans-serif;*/
                    font-family: Calibri;
                }
                p {
                    margin: 0 0 5px;
                }
                table{ border: 1px solid #777; }
                table td{text-align: right;}
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
                    height: 150px
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
                .height_82{ height: 82px }
                .height_109{ height: 109px; }

                .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000!important;  text-align: center;}
                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; font-size: 13px}

                .border-bottom{border-bottom:  1px solid #000}
                .border-right{border-right:  1px solid #000}
                .text-navyblue{color: #17178a;text-shadow: 1px 1px 2px;}

                @page { size: A4 }

                @media print{
                    .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000;  text-align: center;}
                    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; font-size: 13px}
                    .col-sm-6{ width: 50%!important;float:left; }.col-sm-5 { width: 41.66666667%;float:left; }.col-sm-7 { width: 58.33333333%;float:left; }
                    .border-bottom{border-bottom:  1px solid #000}
                    .border-right{border-right:  1px solid #000}
                    .col-md-8 {width: 66.66666667%;float: left;}
                    .col-md-4 {width: 33.33333333%;float: left}
                }
            </style>
        </head>

        <!-- Set "A5", "A4" or "A3" for class name -->
        <!-- Set also "landscape" if you need -->
        <body class="A4" id="page-content">
           <section class="sheet padding-10mm" style="height: auto;">
              <div>
                 <header class="pull-right">
                    <small>Page No. 1</small>
                 </header>
                 <div class="clearfix"></div>
                 <div class="container">
                    <div class="row border_all text-center text-uppercase mar_bot_3">
                       <h3 class="mar_0 head_font"><?= $invoice_type ?></h3>
                    </div>
                    <div class="row mar_bot_3">
                       <div class="col-sm-6 border_all header_left">
                          <p class="mar_0"><strong>Sender</strong></p>
                          <h4 class="mar_0"><strong>A. R. ENTERPRISE </strong></h4>
                          <p class="mar_0">OFFICE: Vill - Chharar Kuthi, PO: Rajarhat, Cooch Behar, West Bengal (WB - 19), PIN Code - 736165, India</p>
                          <p class="mar_0">TEL: +91-9832725484, +91-8116744538</p>
                          <p class="mar_0">WEB: https://ecoudyog.com</p>
                          <p class="mar_0">EMAIL: arenterprisecb@gmail.com</p>
                          
                       </div>
                       <div class="col-sm-6 header_right">
                          <div class="row mar_bot_3">
                             <div class="col-sm-6 border_all height_60">
                                <div class="">
                                   <p class="mar_0">Invoice No. &amp; Date</p>
                                   <p class="mar_0"><strong><?= $invoice_print[0]->cus_inv_number ?></strong></p>
                                   <p class="mar_0"><strong><?= date('d-m-Y',  strtotime($invoice_print[0]->invoice_create_date)) ?></strong></p>
                                </div>
                             </div>
                             <div class="col-sm-6 border_all height_60 text-right">
                                <h4 class="mar_0 text-navyblue">ORIGINAL FOR RECIPIENT</h3>
                                <h5 class="mar_0" style="">(<?= date('d-m-Y') ?>)</h5>
                             </div>
                          </div>
                          <div class="row border_all height_63 mar_bot_3">
                            <p class="mar_0 text-center"> <strong>Sender Ref. Details</strong></p>
                             <div class="col-sm-6">
                                <p style="font-size: 13px;" class="mar_0">GSTIN: 19ALGPR7231P1ZC</p>
                                <p style="font-size: 13px;" class="mar_0">TIN: 19821074382</p>
                             </div>
                             <div class="col-sm-6">
                                <p style="font-size: 13px;" class="mar_0">CST TIN: 19821074382</p>
                                <p style="font-size: 13px;" class="mar_0">PAN: ALGPR7231P</p>
                             </div>
                          </div>
                          <div class="row border_all height_21">
                             <div class="col-sm-12">
                                <p class="mar_0">Other Reference(s): </p>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div class="row height_109 mar_bot_3">
                       <div class="col-sm-6 border_all height_109">
                          <p class="mar_0"><strong>Consignee</strong></p>
                          <h4 class="mar_0"><strong><?= $invoice_print[0]->name ?></strong></h4>
                          <article style="font-size:12px;line-height:1"><?= $invoice_print[0]->address ?></article>
                          <p class="mar_0" style="font-size:12px">TEL: <?= $invoice_print[0]->phone ?></p>
                          <p class="mar_0" style="font-size:12px">GSTIN: <?= $invoice_print[0]->vat_no ?></p>
                       </div>
                       <div class="col-sm-6 height_109 border_all">
                            <div class="row border-bottom">
                                <div class="col-sm-12">
                                    <p class="mar_0"><strong>Bill To</strong></p>
                                    <h5 class="text-uppercase mar_0">
                                       <p style="font-size:12px;line-height:1"><?= strip_tags($invoice_print[0]->address) ?></p>                                
                                    </h5>    
                                </div>
                             </div>

                              <div class="row">
                                <div class="col-sm-12">
                                    <p class="mar_0"><strong>Ship To</strong></p>
                                    <h5 class="text-uppercase mar_0">
                                       <p style="font-size:12px;line-height:1"><?= strip_tags($invoice_print[0]->place_of_supply) ?></p>                                
                                    </h5>    
                                </div>
                             </div>
                       </div>
                    </div>
                    <div class="row height_109 mar_bot_3">
                       <div class="col-sm-6 border_all height_109">
                            <p class="mar_0"><strong>Bank Details</strong></p>
                            <p class="mar_0" style="font-size: 13px">
                                <strong>Comapny:</strong> A.R.Enterprise <br>
                                <strong>Bank:</strong> Bank of Baroda<br>
                                <strong>Branch:</strong> Golakganj<br>
                                <strong>A/c. No.</strong> 22170500000685<br>
                                <strong>IFSC Code:</strong> BARB0GOLAKG
                            </p>
                       </div>
                       <div class="col-sm-6 height_109 border_all">
                            <div class="row">
                                <p class="mar_0 text-center"><strong>Terms & Informations</strong></p>
                                <div class="col-sm-6 border-right">
                                    <h5 class="mar_0"><strong>Total Cartoons:</strong> <?=$invoice_print[0]->cus_inv_number_of_cartons?></h5>
                                    <h5 class="mar_0"><strong>Gross Weight:</strong> <?= $invoice_print[0]->cus_inv_total_weight ?> Kgs</h5>    
                                </div>
                                <div class="col-sm-6">
                                    <h5 class="mar_0">
                                        <?php 
                                        $size_array = array();
                                        foreach($invoice_print as $ip){
                                            array_push($size_array, $ip->size);
                                        }
                                        $uarray = array_unique($size_array);
                                        $total_cartoon = array();
                                        foreach($uarray as $ua){
                                            array_push($total_cartoon, count(array_keys($size_array, $ua)));
                                            //echo $ua . ' = ' . count(array_keys($size_array, $ua)) . '<br>';
                                        }
                                        ?>
                                    </h5>
                                </div>
                            </div>
                       </div>
                    </div>
                  

                    <!--table data-->
                    <div class="row table-responsive">
                       <h4 class="text-center"><u><b><?= $invoice_type ?> Details</b></u></h4>
                       <table class="table table-bordered table-hover table2excel">
                          <thead>
                             <tr>
                                <th class="text-left">Sr. No.</th>
                                <th class="text-left">Product Name</th>
                                <th>HSN Code</th>
                                <th class="text-right">Qnty in PCS</th>
                                <th>Unit Price</th>
                                <th>CGST (@ 6%)</th>
                                <th>SGST (@ 6%)</th>
                                <th>IGST (@ 12%)</th>
                                <th>Total Amount</th>
                             </tr>
                          </thead>
                          <tbody class="actual_table">
                            <?php 
                                $iter = 1;
                                $net_total = array();
                                $net_gst = array();
                                $total_qnty = array();
                                foreach($invoice_print as $ip){
                                    ?>
                                    <tr>
                                      <td><?= $iter++ ?></td>
                                      <td nowrap style="text-align: left;"><?= $ip->product_name . '<br>' . $ip->size ?></td>
                                      <td><?= $ip->hsn_code ?></td>
                                      <td><?php array_push($total_qnty, $ip->delivered_quantity); echo $ip->delivered_quantity ?></td>
                                      <td><?= $ip->rate_per_unit ?></td>
                                      <td>
                                        <?php
                                        $cgst = ($ip->delivered_quantity * $ip->rate_per_unit * 6)/100 ;
                                        echo ($state == '19') ? $cgst : '-';

                                        ?>
                                      </td>
                                      <td>
                                        <?php
                                        $sgst = ($ip->delivered_quantity * $ip->rate_per_unit * 6)/100; 
                                        echo ($state == '19') ? $sgst : '-';
                                        ?>
                                      </td>
                                      <td>
                                          <?php 
                                          $net_gst[] = ($ip->delivered_quantity * $ip->rate_per_unit * 12)/100;
                                          $igst = ($ip->delivered_quantity * $ip->rate_per_unit * 12)/100;
                                          echo ($state != '19') ? $igst : '-';
                                          ?>
                                      </td>
                                      <td>
                                        <?= $net_total[] =  ($ip->delivered_quantity * $ip->rate_per_unit) + ($ip->delivered_quantity * $ip->rate_per_unit * 12)/100 ?>
                                        </td>
                                  </tr>
                                    <?php
                                } 
                            ?>
                            <tr>
                              <th colspan="3">Sub Total</th>
                              <th><?= array_sum($total_qnty) ?></th>
                              <th class="text-right" colspan="4">(GST) <?= array_sum($net_gst) ?> </th>
                              <th><?= array_sum($net_total) ?></th>
                            </tr> 
                          </tbody>
                       </table>
                    </div>
                    <div class="row">
                       <footer>
                          <div class="col-sm-6 border_all height_135">
                            <div class="row">
                                <div class="col-md-8">
                                     <p class="mar_0"><strong>Gross Amount (with tax)</strong></p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <p class="mar_0"><strong> ₹ <?= $gross = array_sum($net_total) ?></strong> </p>
                                </div>

                                <!-- <div class="col-md-8">
                                    <p class="mar_0"><i>Tax Amount (Products)</i> </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <p class="mar_0"><i>₹ <?= $gross_tax = array_sum($net_gst) ?> </i> </p>
                                </div> -->

                                <div class="col-md-8">
                                    <p class="mar_0"><strong>Packaging Charges</strong></p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <p class="mar_0"><strong> ₹ <?= $packing = $invoice_print[0]->packing_rate ?> </strong></p>
                                </div>

                                <div class="col-md-8">
                                    <p class="mar_0"><strong>Tax Amount (Packing) @ <?= $invoice_print[0]->packing_tax ?>%</strong></p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <p class="mar_0">
                                        <strong> 
                                            ₹ 
                                            <?= $packing_tax = ($invoice_print[0]->packing_rate * ($invoice_print[0]->packing_tax/100)) 
                                            ?> 
                                        </strong>
                                    </p>
                                </div>

                                <div class="col-md-8">
                                    <p class="mar_0"><strong>Net Amount </strong></p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <p class="mar_0"><strong>₹ <?= $net = ($gross+$packing+$packing_tax) ?></strong></p>
                                </div>
                            </div>
                            <!-- <p class="mar_0"><strong>Round Off: ₹ </strong></p> -->
                            <p class="mar_0"><strong>(Rupees <?= convertNumberToWord($net) ?>)</strong></p>

                          </div>
                          <div class="col-sm-6 border_all height_135">
                             <p class="mar_0">Signature &amp; Date</p>
                             <h6 class="mar_0 text-uppercase"><strong>A. R. Enterprise</strong></h6>
                             <img src="<?= base_url() ?>assets/img/eco-stamp.png" style="height:75px; ">
                             <img src="<?= base_url() ?>assets/img/eco-sign.png" style="height:75px; ">
                             <div class="row">
                                <div class="col-sm-6">
                                   <div class="">
                                      <p class="mar_0">Authorised Signatory</p>
                                   </div>
                                </div>
                                <div class="col-sm-6">
                                   <div class="">
                                      <p class="mar_0 text-right"><?= date('d-m-Y',  strtotime($invoice_print[0]->invoice_create_date)) ?></p>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </footer>
                    </div>
                 </div>
              </div>
           </section>
        </body>
    </html>
<?php

        function convertNumberToWord($number) {
            $hyphen = '-';
            $conjunction = ' and ';
            $separator = ', ';
            $negative = 'negative ';
            $decimal = ' point ';
            $dictionary = array(
                0 => 'zero',
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
                7 => 'seven',
                8 => 'eight',
                9 => 'nine',
                10 => 'ten',
                11 => 'eleven',
                12 => 'twelve',
                13 => 'thirteen',
                14 => 'fourteen',
                15 => 'fifteen',
                16 => 'sixteen',
                17 => 'seventeen',
                18 => 'eighteen',
                19 => 'nineteen',
                20 => 'twenty',
                30 => 'thirty',
                40 => 'fourty',
                50 => 'fifty',
                60 => 'sixty',
                70 => 'seventy',
                80 => 'eighty',
                90 => 'ninety',
                100 => 'hundred',
                1000 => 'thousand',
                100000 => 'lakh',
                10000000 => 'crore'
            );

            if (!is_numeric($number)) {
                return false;
            }

            if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
                // overflow
                trigger_error(
                        'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
                );
                return false;
            }

            if ($number < 0) {
                return $negative . convertNumberToWord(abs($number));
            }

            $string = $fraction = null;

            if (strpos($number, '.') !== false) {
                list($number, $fraction) = explode('.', $number);
            }

            switch (true) {
                case $number < 21:
                    $string = $dictionary[$number];
                    break;
                case $number < 100:
                    $tens = ((int) ($number / 10)) * 10;
                    $units = $number % 10;
                    $string = $dictionary[$tens];
                    if ($units) {
                        $string .= $hyphen . $dictionary[$units];
                    }
                    break;
                case $number < 1000:
                    $hundreds = $number / 100;
                    $remainder = $number % 100;
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                    if ($remainder) {
                        $string .= $conjunction . convertNumberToWord($remainder);
                    }
                    break;
                case $number < 100000:
                    $thousands = ((int) ($number / 1000));
                    $remainder = $number % 1000;

                    $thousands = convertNumberToWord($thousands);

                    $string .= $thousands . ' ' . $dictionary[1000];
                    if ($remainder) {
                        $string .= $separator . convertNumberToWord($remainder);
                    }
                    break;
                case $number < 10000000:
                    $lakhs = ((int) ($number / 100000));
                    $remainder = $number % 100000;

                    $lakhs = convertNumberToWord($lakhs);

                    $string = $lakhs . ' ' . $dictionary[100000];
                    if ($remainder) {
                        $string .= $separator . convertNumberToWord($remainder);
                    }
                    break;
                case $number < 1000000000:
                    $crores = ((int) ($number / 10000000));
                    $remainder = $number % 10000000;

                    $crores = convertNumberToWord($crores);

                    $string = $crores . ' ' . $dictionary[10000000];
                    if ($remainder) {
                        $string .= $separator . convertNumberToWord($remainder);
                    }
                    break;
                default:
                    $baseUnit = pow(1000, floor(log($number, 1000)));
                    $numBaseUnits = (int) ($number / $baseUnit);
                    $remainder = $number % $baseUnit;
                    $string = convertNumberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if ($remainder) {
                        $string .= $remainder < 100 ? $conjunction : $separator;
                        $string .= convertNumberToWord($remainder);
                    }
                    break;
            }

            if (null !== $fraction && is_numeric($fraction)) {
                $string .= $decimal;
                $words = array();
                foreach (str_split((string) $fraction) as $number) {
                    $words[] = $dictionary[$number];
                }
                $string .= implode(' ', $words);
            }

            return ucfirst($string);
        }
        ?>