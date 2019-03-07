<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo ucfirst($this->uri->segment(1));?></title>
    <link rel="icon" href="<?php echo $this->config->item('img_url');?>favicon.ico" type="image/x-icon" media="all" media="print"/>
        <!-- Start Alexa Certify Javascript -->
    
   <!-- End Alexa Certify Javascript --> 
    <link rel="stylesheet" href="<?php echo $this->config->item('bootstrap_css');?>bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('css');?>font-awesome.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('css');?>ionicons.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>jvectormap/jquery-jvectormap-1.2.2.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>dist/css/AdminLTE.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>dist/css/skins/_all-skins.min.css"/>
    <style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  
.table thead th { 
  background-color: #3c8dbc;
  color: #ffffff;
}
.se-pre-con {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url(<?php echo base_url(); ?>assets/images/loading.gif) center no-repeat #fff;
}
.preLoading_icon{
	background: url(<?php echo base_url(); ?>assets/images/loading.gif) center no-repeat #fff;
    z-index: 9999;
    background-color: #fff !important;
}
.preLoading_div
{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    font-size: 30px;
    color: #000;
    line-height: 100px;
    text-align: center;
    background-color: #dbff00;
    font-weight: 700;
 }
  </style>
</head>   
<body class="hold-transition skin-blue-light sidebar-mini login-page">
 <div class="se-pre-con"></div>
<header class="main-header">

    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>ACT</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>ACT</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php                 
                $ci                 =&  get_instance();
                $ci->load->model('ldt_ip_employee_model'); 
                //$photo            =   $ci->Tdt_user_documents_model->get_emp_photo($this->session->userdata('AppsLogID'));
                $photo              =  "";
                $gen                =   $this->session->userdata('gender');
                if($gen             ==  2)
                {
                    $usr_img_name   =   'user_female.png';
                }
                else
                {
                    $usr_img_name   =   'user_male.png';
                }
                ?>
              <img src="<?php echo $photo;?>" class="user-image" onerror="this.src='<?php echo $this->config->item('img_url');?><?=$usr_img_name?>';" alt="User Image" />
              <span class="hidden-xs"><?php echo $this->session->userdata('EmpName'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">                
                <img src="<?php echo $photo;?>" class="user-image" onerror="this.src='<?php echo $this->config->item('img_url');?><?=$usr_img_name?>';" alt="User Image" />
                 <p>
                  <?php echo $this->session->userdata('EmpName'); /*?> - <?php echo ($this->session->userdata('RoleName'))?$this->session->userdata('RoleName'):'Employee';*/ ?>
                  <?php
                  $date1 = new DateTime(date('Y-m-d', strtotime($this->session->userdata('EmpDoj')) ));
                  $date2 = new DateTime(date('Y-m-d'));
                  $tenure = $date2->diff($date1)->format("%a");
                  ?>
                  <small>Tenure  <?=$tenure?> Days</small>
                </p>
              </li>
              <!-- Menu Body -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <?php
                $emp_lst            =   $ci->ldt_ip_employee_model->get_approved_ips(); # check emp in ip approved #
                if(in_array($this->session->userdata('employee'), $emp_lst))
                {                    
                    $ses_val        = $this->session->userdata('employee').'TRUE';
                    $this->session->set_userdata('SPOTPVRFD',$ses_val);
                    if($this->uri->segment(2)   ==  'add_ip_page' || $this->uri->segment(2)   ==  'approved_ip_list' )
                    {
                    ?>
                    <div class="pull-left">
                        <a href="<?php echo base_url('dashboard');?>" class="btn btn-default btn-flat" title="Dashboard"  ><i class="fa fa-home"></i></a>
                    </div>
                    <?php
                    }
                    else
                    {
                     ?>
                    <div class="pull-left">
                        <a href="<?php echo base_url('verify/approved_ip_list');?>" class="btn btn-default btn-flat" title="IP List"  ><i class="fa fa-globe"></i></a>
                    </div>
                    <?php                           
                    }             
                }
                ?>  
                <div class="pull-right">
                  <a href="<?php echo base_url();?>logout" class="btn btn-default btn-flat" title="Sign Out"><i class="fa fa-sign-out"></i></a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('dashboard/punchout');?>" class="btn btn-default btn-flat" title="Punch Out"><i class="fa fa-blind"></i></a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>

    </nav>
   </header>