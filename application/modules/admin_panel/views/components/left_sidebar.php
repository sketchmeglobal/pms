
<?php
$class_name = $this->router->fetch_class();
$method_name = $this->router->fetch_method();
$user_type = $this->session->usertype;
?>
<style>
    .affix{width:240px;height: 100%;overflow:scroll;}
     .affix::-webkit-scrollbar {
      width: 10px;
    }
    
    /* Track */
     .affix::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
     .affix::-webkit-scrollbar-thumb {
      background: #888; 
    }
    
    /* Handle on hover */
     .affix::-webkit-scrollbar-thumb:hover {
      background: #555; 
    }
</style>
<div class="sidebar-left">
    <!--responsive view logo start-->
    <div class="logo theme-logo-bg visible-xs-* visible-sm-*">
        <a href="<?=base_url();?>" target="_blank">
            <img src="<?=base_url();?>assets/img/logo_20px.png" alt="Shilpa Logo">
<!--            <i class="fa fa-home"></i>-->
            <span class="brand-name"><strong><?=WEBSITE_NAME_SHORT;?></strong></span>
        </a>
    </div>
    <!--responsive view logo end-->

    <div class="sidebar-left-info affix">
        <!-- visible small devices start-->
        <div class=" search-field">  </div>
        <!-- visible small devices end-->

        <!--sidebar nav start-->
        <ul class="nav nav-pills nav-stacked side-navigation">
            <li><h3 class="navigation-title">Menu</h3></li>
            <li class="<?=(($class_name == 'Dashboard')) ? 'active' : ''; ?>">
                <a href="<?=base_url();?>admin/dashboard"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a>
            </li>

            <li class="<?= (($class_name == 'Profile') && ($method_name == 'profile')) ? 'active' : ''; ?>">
                <a href="<?=base_url();?>admin/profile"><i class="fa fa-vcard-o"></i> <span>Profile</span></a>
            </li>

            <li class="menu-list <?=($class_name == 'Master') ? 'active' : ''; ?>"><a href=""><i class="fa fa-wrench"></i> <span>Master Tables</span></a>
                <ul class="child-list">

                    <li class="<?=(($class_name == 'Transporter') && ($method_name == 'transporter')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/transporter"><i class="fa fa-caret-right"></i> Transporter</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'mill')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/mill"><i class="fa fa-caret-right"></i> Mill</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'account_master')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/account_master"><i class="fa fa-caret-right"></i> Account Master</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'units')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/units"><i class="fa fa-caret-right"></i> Units</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'product_cateogry')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/product-categories"><i class="fa fa-caret-right"></i> Product Categories</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'sizes')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/sizes"><i class="fa fa-caret-right"></i> Products</a>
                    </li>
                    
                    <li class="<?=(($class_name == 'Master') && ($method_name == 'colors')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/colors"><i class="fa fa-caret-right"></i> Colors</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'departments')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/departments"><i class="fa fa-caret-right"></i> Departments</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'employees')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/employees"><i class="fa fa-caret-right"></i> Employees</a>
                    </li>

                    <li class="<?=(($class_name == 'Master') && ($method_name == 'menusetting')) ? 'active' : ''; ?>">
                       <a href="<?=base_url();?>admin/menusetting"><i class="fa fa-caret-right"></i> Menu Setting</a>
                    </li>
                </ul>
            </li>
            
            <li class="<?=(($class_name == 'Purchase_order')) ? 'active' : ''; ?>">
                <a href="<?=base_url();?>admin/purchase-order"><i class="fa fa-refresh"></i> 
                    <span>Purchase / Production</span>
                    <small style="text-align: center;display: block;color: #dedede;">(distribute / receive)</small>
                </a>
            </li>

            <li class="<?=(($class_name == 'Customer_order')) ? 'active' : ''; ?>">
                <a href="<?=base_url();?>admin/customer-order"><i class="fa fa-shopping-cart"></i> <span>Customer Orders</span></a>
            </li>

            <!-- <li class="menu-list < ?=($class_name == 'Production' || $class_name == 'Checkin') ? 'active' : ''; ?>">
                <a href=""><i class="fa fa-cogs"></i> <span>Production</span></a>
                <ul class="child-list">
                    <li class="< ?=(($class_name == 'Production')) ? 'active' : ''; ?>">
                        <a href="< ?=base_url();?>admin/production"><i class="fa fa-caret-right"></i> <span>Distribute (Check-out)</span></a>
                    </li>
                    
                    <li class="< ?=(($class_name == 'Checkin') && ($method_name == 'checkin')) ? 'active' : ''; ?>">
                        <a href="< ?=base_url();?>admin/checkin"><i class="fa fa-caret-right"></i> Receive (Check-in)</a>
                    </li>
                </ul>
            </li>  --> 

            <li class="<?=(($class_name == 'Customer_invoice')) ? 'active' : ''; ?>">
                <a href="<?=base_url();?>admin/customer-invoice"><i class="fa fa-inr"></i> <span>Customer Invoices</span></a>
            </li>   

            <li class="<?=(($class_name == 'Employee_priority') && ($method_name == 'employee_payment')) ? 'active' : ''; ?>">
                <a href="<?=base_url();?>admin/employee-payment"><i class="fa fa-credit-card"></i> <span>Employee Payment</span></a>
            </li>          
            
            <li class="menu-list <?=($class_name == 'Report_employee_distribute' || $class_name == 'Report_employee') ? 'active' : ''; ?>"><a href=""><i class="fa fa-newspaper-o"></i> <span>Report</span></a>
                <ul class="child-list">
                    <li class="<?=$class_name == 'Report_employee_distribute' ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/employee-priority"><i class="fa fa-caret-right"></i> Employee Priority</a>
                    </li>
                    <li class="<?=(($class_name == 'Report_po_ledger') && ($method_name == 'report_po_ledger')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/check-in-out-report"><i class="fa fa-caret-right"></i> Employee Check-In/Out </a>
                    </li>
                    <!--<li class="<=(($class_name == 'Report_employee_distribute') && ($method_name == 'report_employee_distribute') && ($method_name == 'report_po_ledger')) ? 'active' : ''; ?>">-->
                    <!--    <a href="<=base_url();?>admin/report-employee-distribute"><i class="fa fa-caret-right"></i> Employee Check-out</a>-->
                    <!--</li>-->
                    <!--<li class="<=(($class_name == 'Report_employee') && ($method_name == 'report_employee')) ? 'active' : ''; ?>">-->
                    <!--    <a href="<=base_url();?>admin/report-employee"><i class="fa fa-caret-right"></i> Employee Check-in</a>-->
                    <!--</li>-->
                    <li class="<?=(($class_name == 'Report_po_ledger') && ($method_name == 'report_po_ledger')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/report-po-ledger-init"><i class="fa fa-caret-right"></i> PO Ledger</a>
                    </li>
                    <li class="<?=(($class_name == 'Report_employee') && ($method_name == 'order_details_report')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/order-details-report"><i class="fa fa-caret-right"></i> Order Details</a>
                    </li>
                    <li class="<?=(($class_name == 'Report_po_ledger') && ($method_name == 'invoice_details_report')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/invoice-details-report"><i class="fa fa-caret-right"></i> Invoice Details</a>
                    </li>
                    <li class="<?=(($class_name == 'Report_po_ledger') && ($method_name == 'report_po_ledger')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/wastage-report"><i class="fa fa-caret-right"></i> Wastage Details</a>
                    </li>
                    <li class="<?=(($class_name == 'Report_stock') && ($method_name == 'stock_report')) ? 'active' : ''; ?>">
                        <a href="<?=base_url();?>admin/stock-report"><i class="fa fa-caret-right"></i> Stock Report</a>
                    </li>
                    
                </ul>
            </li>
            
            <!-- ONLY ADMIN RIGHTS -->
            <?php if($user_type == 1){
                ?>

                <li class="menu-list <?=($class_name == 'Settings') ? 'active' : ''; ?>"><a href=""><i class="fa fa-cog"></i> <span>Settings</span></a>
                    <ul class="child-list">
                        <li class="<?=(($class_name == 'Settings') && ($method_name == 'meter_reading')) ? 'active' : ''; ?>">
                            <a href="<?=base_url();?>admin/meter-reading"><i class="fa fa-caret-right"></i> Meter Reading</a>
                        </li>
                        <li class="<?=(($class_name == 'Settings') && ($method_name == '')) ? 'active' : ''; ?>">
                            <a href="<?=base_url();?>admin/employee-attendance"><i class="fa fa-caret-right"></i> Employee attendance</a>
                        </li>
                        <li class="<?=(($class_name == 'Settings') && ($method_name == '')) ? 'active' : ''; ?>">
                            <a href="<?=base_url();?>admin/database-backup"><i class="fa fa-caret-right"></i> Database Backup</a>
                        </li>
                    </ul>
                </li>


                <?php
            } ?>

        </ul>
        <!--sidebar nav end-->

        <!--sidebar widget start-->
        <div class="sidebar-widget">
            <h4>Account Information</h4>
            <ul class="list-group">
                <li>
                    <p>
                        <strong><i class="fa fa-user-circle-o"></i> <span class="username"><?=$this->session->username;?></span></strong>
                        <br/>
                        <strong><i class="fa fa-envelope"></i> <?=$this->session->email;?></strong>
                    </p>
                </li>
                
                <li>
                    <a href="<?=base_url();?>admin/profile" class="btn btn-info btn-sm addon-btn">Edit Info. <i class="fa fa-vcard pull-left"></i></a>
                </li>
            </ul>
        </div>
        <!--sidebar widget end-->

    </div>
</div>