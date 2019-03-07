<!DOCTYPE html>
<html>
<head>
    <?php header('X-Frame-Options: deny'); ?>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        <?php
        $titleFull          =   str_replace('_',' ',$this->uri->segment(2));
        $titleFull          =   str_replace('list',' List',$titleFull);
        echo ucwords($titleFull);?>
    </title>
     <link rel="icon" href="<?php echo $this->config->item('img_url');?>favicon.ico" type="image/x-icon" media="all" media="print"/>
    <link href="<?php echo base_url('assets/css/bootstrap-combined.min.css');?>" rel="stylesheet" id="bootstrap-css"/>
    <link href="<?php echo base_url('assets/dist/css/AdminLTE.min.css');?>" rel="stylesheet" id="bootstrap-css"/>
    <link href="<?php echo base_url('assets/css/apps_main_page_style.css');?>" rel="stylesheet" id="bootstrap-css"/>
    <style type="text/css">
        .help_div_area
        {
        padding: 0px;
        margin: 0px;
        }

        .help_div
        {
        padding: 0px;
        padding: 3px;
        margin: 0px;
        box-shadow: 0px 0px 3px grey;
        border-radius: 10px;
        display: none;
        }

        .help_btn
        {
        background: none;
        border: none;
        color: blue;
        padding: 3px;
        font-size: 110%;
        }
    </style>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>

<!------ Include the above in your HEAD tag ---------->
</head>
<?php 
$menuListarray      =   array();
$menuListarray[]    =   array('url'=>'list_audit_plans','name'=>'List Audit Plans');
$menuListarray[]    =   array('url'=>'list_nc','name'=>'List NC');
$menuListarray[]    =   array('url'=>'list_scope_of_certi','name'=>'List Scope Of Certification');
$menuListarray[]    =   array('url'=>'list_feedback','name'=>'List Feedback');
$menuListarray[]    =   array('url'=>'list_audit_report_summary','name'=>'List Audit Report Summary');
$menuListarray[]    =   array('url'=>'fill_intimation_of_changes','name'=>'Fill Intimation of Changes');

$currentClass       =   $this->router->fetch_class();
$currentMethord     =   $this->router->fetch_method();
?>
<div class="container">
    <div class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="#">
            <img src="<?php echo base_url();?>assets/images/ias_logo.png" width="60px" alt=""/>
            </a>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a class="men_btn" href="<?php echo base_url('customer/dashboard');?>">Home</a></li>
                    <?php 
                    foreach($menuListarray  as $key)
                    {
                    ?>
                    <li><a class="men_btn" href="<?php echo base_url('customer/'.$key['url']);?>"><?php echo $key['name'];?></a></li>
                    <?php 
                    }
                    ?>
                    <li><a class="pull-right" href="<?php echo base_url('customer/logout');?>"><i class="fa fa-user"></i>Logout</a></li>
                    
                </ul>
            </div>
        </div>

    </div>
</div>
<script>
var ActualUrl    = '<?php echo base_url($currentClass.'/'.$currentMethord);?>';
jQuery('.men_btn').each(function() {
    var currentUrl = $(this).attr('href');
    if(currentUrl==ActualUrl)
    {
        $(this).parent().addClass('active');
    }
});

</script>
