<?php
/**
 * Coded by: Pran Krishna Das
 * Social: www.fb.com/pran93
 * CI: 3.0.6
 * Date: 09-07-17
 * Time: 12:00
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard | <?=WEBSITE_NAME;?></title>
    <meta name="keyword" content="user dashboard">
    <meta name="description" content="account statistic">

    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->
</head>

<body class="sticky-header">

<section>
    <!-- sidebar left start (Menu)-->
    <?php $this->load->view('components/left_sidebar'); //left side menu ?>
    <!-- sidebar left end (Menu)-->
    <style>
        .p-1{padding: 1%;}
        .pt-0{padding-top: 0}
        .px-1{padding: 1rem 0;}
        .mb-1{margin-bottom: 1rem;}
        .mb-2{margin-bottom: 2rem;}
        .panel{min-height: 400px;}
        .panel-footer {background-color: rgb(0 0 0 / 15%);position: absolute;bottom: 0;width: 100%;}
        .text-white{color:#fff;}
        .text-dark{color:#000;}
        .border-bottom{border-bottom: 1px solid #787878;}
    </style>
     <style>
    @media(max-width: 768px){
.chosen-container-single .chosen-single {
  height: 37px !important;
  display: flex !important;
  align-items: center !important;
overflow:hidden !important;
}

.chosen-container-single .chosen-single abbr {
  top: 13px !important;
}
.chosen-container-single .chosen-single div {
  top: 7px !important;
}
}
</style>
    <!-- body content start-->
    <div class="body-content" style="min-height: 1500px;">

        <!-- header section start-->
        <?php $this->load->view('components/top_menu'); ?>
        <!-- header section end-->

        <!-- page head start-->
        <div class="page-head">
            <h3>Dashboard</h3>
            <span class="sub-title">Welcome to <?=WEBSITE_NAME;?> dashboard</span>
        </div>
        <!-- page head end-->

        <!--body wrapper start-->
        <!--<div class="wrapper">-->
        <!--    <div class="col-md-6 col-lg-4">-->
        <!--        <div class="panel">-->
                    <?php //print_r($lastest_costings) ?>
        <!--            <div class="panel-header bg-success text-white text-center p-1"><h4>Costing</h4></div>-->
        <!--            <div class="panel-body pt-0">-->
        <!--                <p class="text-muted border-bottom px-1">Last 5 transactions</p>-->
        <!--                <ul>-->
        <!--                    < ?php-->
        <!--                    foreach($lastest_costings as $lc){-->
        <!--                        ?>-->
        <!--                        <li class="mb-1"><a href="<?= base_url('admin/edit-article-costing/'.$lc->ac_id) ?>" class=""><?= $lc->art_no ?></a> (<?= date('d/m/Y H:i:s', strtotime($lc->modify_date)) ?>) by <?= $lc->username ?></li>-->
        <!--                        < ?php-->
        <!--                    }-->
        <!--                    ?>-->
                            
        <!--                </ul>-->
        <!--            </div>-->
        <!--            <div class="panel-footer text-center">-->
        <!--                <a href="<?= base_url('admin/article_costing') ?>" class="text-dark">Segment List</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="col-md-6 col-lg-4">-->
        <!--        <div class="panel">-->
                    <?php //print_r($lastest_costings) ?>
        <!--            <div class="panel-header bg-success text-white text-center p-1"><h4>Customer Order</h4></div>-->
        <!--            <div class="panel-body pt-0">-->
        <!--                <p class="text-muted border-bottom px-1">Last 5 transactions</p>-->
        <!--                <ul>-->
        <!--                    < ?php-->
        <!--                    foreach($lastest_orders as $lo){-->
        <!--                        ?>-->
        <!--                        <li class="mb-1"><a href="<?= base_url('admin/edit-customer-order/'.$lo->co_id) ?>" class=""><?= $lo->co_no ?></a> (<?= date('d/m/Y H:i:s', strtotime($lo->modify_date)) ?>) by <?= $lo->username ?></li>-->
        <!--                        < ?php-->
        <!--                    }-->
        <!--                    ?>-->
                            
        <!--                </ul>-->
        <!--            </div>-->
        <!--            <div class="panel-footer text-center">-->
        <!--                <a href="<?= base_url('admin/customer-order') ?>" class="text-dark">Segment List</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="col-md-6 col-lg-4">-->
        <!--        <div class="panel">-->
                    <?php //print_r($lastest_costings) ?>
        <!--            <div class="panel-header bg-success text-white text-center p-1"><h4>Cutting Issue</h4></div>-->
        <!--            <div class="panel-body pt-0">-->
        <!--                <p class="text-muted border-bottom px-1">Last 5 transactions</p>-->
        <!--                <ul>-->
        <!--                    < ?php-->
        <!--                    foreach($lastest_cutting_issues as $lci){-->
        <!--                        ?>-->
        <!--                        <li class="mb-1"><a href="<?= base_url('admin/edit-cutting-issue-challan/'.$lci->cut_id) ?>" class=""><?= $lci->co_no ?></a> (<?= date('d/m/Y H:i:s', strtotime($lci->modify_date)) ?>) by <?= $lci->username ?></li>-->
        <!--                        < ?php-->
        <!--                    }-->
        <!--                    ?>-->
                            
        <!--                </ul>-->
        <!--            </div>-->
        <!--            <div class="panel-footer text-center">-->
        <!--                <a href="<?= base_url('admin/cutting-issue-challan') ?>" class="text-dark">Segment List</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="col-md-6 col-lg-4">-->
        <!--        <div class="panel">-->
                    <?php //print_r($lastest_costings) ?>
        <!--            <div class="panel-header bg-success text-white text-center p-1"><h4>Cutting Receive</h4></div>-->
        <!--            <div class="panel-body pt-0">-->
        <!--                <p class="text-muted border-bottom px-1">Last 5 transactions</p>-->
        <!--                <ul>-->
        <!--                    < ?php-->
        <!--                    foreach($lastest_cutting_receive as $lcr){-->
        <!--                        ?>-->
        <!--                        <li class="mb-1"><a href="<?= base_url('admin/edit-cutting-receive/'.$lcr->cut_rcv_id) ?>" class=""><?= $lcr->co_no ?></a> (<?= date('d/m/Y H:i:s', strtotime($lcr->modify_date)) ?>) by <?= $lcr->username ?></li>-->
        <!--                        < ?php-->
        <!--                    }-->
        <!--                    ?>-->
                            
        <!--                </ul>-->
        <!--            </div>-->
        <!--            <div class="panel-footer text-center">-->
        <!--                <a href="<?= base_url('admin/cutting-receive') ?>" class="text-dark">Segment List</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="col-md-6 col-lg-4">-->
        <!--        <div class="panel">-->
                    <?php //print_r($lastest_costings) ?>
        <!--            <div class="panel-header bg-success text-white text-center p-1"><h4>Skiving Receive</h4></div>-->
        <!--            <div class="panel-body pt-0">-->
        <!--                <p class="text-muted border-bottom px-1">Last 5 transactions</p>-->
        <!--                <ul>-->
        <!--                    < ?php-->
        <!--                    foreach($lastest_skiving_receive as $lsr){-->
        <!--                        ?>-->
        <!--                        <li class="mb-1"><a href="<?= base_url('admin/skiving-receive-edit/'.$lsr->cut_rcv_id) ?>" class=""><?= $lsr->skiving_receive_challan_number ?></a> (<?= date('d/m/Y H:i:s', strtotime($lsr->modified_date)) ?>) by <?= $lsr->username ?></li>-->
        <!--                        < ?php-->
        <!--                    }-->
        <!--                    ?>-->
                            
        <!--                </ul>-->
        <!--            </div>-->
        <!--            <div class="panel-footer text-center">-->
        <!--                <a href="<?= base_url('admin/skiving-receive') ?>" class="text-dark">Segment List</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--</div>-->
        <!--body wrapper end-->

        <!--footer section start-->
        <?php $this->load->view('components/footer'); ?>
        <!--footer section end-->

    </div>
    <!-- body content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="<?=base_url()?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>
<!--<script src="--><?//=base_url();?><!--assets/admin_panel/js/jquery-migrate.js"></script>-->

<!-- common js -->
<?php $this->load->view('components/_common_js'); //left side menu ?>

</body>
</html>