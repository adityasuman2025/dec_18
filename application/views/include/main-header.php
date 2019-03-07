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
        <!-- Start Alexa Certify Javascript -->
    <!-- End Alexa Certify Javascript --> 
    <link rel="stylesheet" href="<?php echo $this->config->item('bootstrap_css');?>bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('css');?>font-awesome.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('css');?>ionicons.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>jvectormap/jquery-jvectormap-1.2.2.css"/>
	  <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>select2/select2.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>dist/css/AdminLTE.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>dist/css/skins/_all-skins.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>themecss/jQueryUI/jquery-ui-1.10.3.custom.min.css"/>
     <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>daterangepicker/daterangepicker.css"/>
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>datepicker/datepicker3.css"/>
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>colorpicker/bootstrap-colorpicker.min.css"/>
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>timepicker/bootstrap-timepicker.min.css"/>
  <!-- Select2 -->
  <!-- custom style for the page 
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto" rel="stylesheet">-->
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>css/bootstrap-datetimepicker.min.css"/>
  <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>css/apps_main_page_style.css"/>
   <link rel="stylesheet" href="<?php echo $this->config->item('bootstrap_css');?>bootstrap-multiselect.css"/>
<script type="text/javascript" src="<?php echo $this->config->item('plugins');?>jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('js');?>app.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('aset_url');?>js/bootstrap-datetimepicker.min.js"></script>
<?php
header('Cache-Control: no cache');
$ci            =&  get_instance();
$ci->load->model('ldt_user_document_model');
$ci->load->model('Ldt_message_model');
$ci->load->model('Ldt_notifications_model');
$message_list_recived_to_me             =   $ci->Ldt_message_model->message_inbox($this->session->userdata('userid'),5);   
$message_list_recived_to_me_count       =   $ci->Ldt_message_model->message_inbox_count($this->session->userdata('userid'));   
$notification_list_recived_to_me        =   $ci->Ldt_notifications_model->notifications_inbox($this->session->userdata('userid'),5);   
$notification_list_recived_to_me_count  =   $ci->Ldt_notifications_model->notifications_inbox_count($this->session->userdata('userid'),$this->session->userdata('last_notification_read')); 
$messagedb_list_recived_to_me           =   $ci->Ldt_notifications_model->dashboard_message_header(5); 
$messageDB_list_recived_to_me_count     =   $ci->Ldt_notifications_model->messageDb_inbox_count($this->session->userdata('last_notification_read'));  
$Switchrole            =   '';
 ?>       
</head>
<style type="text/css">
   /* .drpmenu{
      border-radius: 8px 8px 8px 8px; 
      background-color: #3c8dbc;
      color: #fff;
      box-shadow: 1px 1px 3px 2px #8aa4af;
      font-size: 13px; 
      padding: 7px;
    }*/
      .inout_drmenuleft{
        float: left;
      }
      .inout_drmenuright{
        float: right;
      }
      .inout_drmenuleftsmall{
        text-align: center;
      }
      .navbar-nav>.user-menu>.dropdown-menu>.user-footer {
        background-color: #f9f9f9;
        padding: 10px;
        box-shadow: 0px 3px 2px 0px #77BFE4;
      }
      .modal-body {
          width: 100%;
      }

      input[type=date]
    {
      width: 100%;
      /*height: 100%;*/
      /*border: none;*/
    }

    .double
    {
      margin: 0;
      padding: 0
    }

    .qstn
    {
      border: 1.3px #080808 solid;
      padding: 0;
      margin: 0;
    }

    .ans
    {
      border: 1.3px #080808 solid;
      padding: 0;
      margin: 0;
    }

    .ans input[type=date]
    {
      height: 34px;
    }

    .big_qstn
    {
      margin: 0;
      padding: 0;
      height: 75px;
      word-wrap: break-word;      
      word-break: break-all;
      resize: none;
      text-align: left;
      width: 100%;

    }

    .big_qstn::-webkit-scrollbar { 
        display: none;  // Safari and Chrome
    }

    .big_big_qstn
    {
      margin: 0;
      padding: 0;
      height: 97px;
      word-wrap: break-word;      
      word-break: break-all;
      resize: none;
      text-align: left;
      width: 100%;
    }


    .big_ans
    {     
      height: 80px;
      word-wrap: break-word;    
      word-break: break-all;
      resize: none;
      text-align: left;
    }

    .qstn_heading
    {
      text-align: center;
      font-size: 120%;
      font-weight: bold;
    }

    .qstn_min_heading
    {
      font-size: 110%;
      font-weight: bold;
    }

    .sugg_div
    {
      height: 400px;
      overflow-y: scroll;
      overflow-x: hidden;
      display: none;
    }

    .qstn .qstn_qstn
    {
        background: #D0D0D0;
        color; black;
        padding: 3px;
        display: block;
        width: 100%;
        min-height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        overflow-y: scroll;
    }

     .qstn .qstn_qstn::-webkit-scrollbar {
      display: none;
      }

      .audit_prog_proc_list_input
      {
        width: 50%;
        height: 34px;
        padding: 3px;
      }

      .audit_plan_team_input
      {
        width: 100%;
        height: 34px;
        padding: 3px;
      }

      .input_height
      {
        height: 34px;
        padding: 3px;
      }

      .create_q
      {
        background: white;
        width: 60%;
        margin: auto;
        padding: 10px;
        margin-top: 100px;
      }

      .title_q
      {
        font-size: 130%;
        padding: 3px;
        float: left;
      }

      .qstn_sample
      {

        border-radius: 3px;
        background: white;
        padding: 10px;
        margin-bottom: 10px;
      }

      .qstn_sample input, .qstn_sample textarea, .qstn_sample select
      {
        border: 1px lightgrey solid;
      }
    
      .sample_controls
      {
        /*background: #f1f1f1;*/
        padding: 5px;
      }

      .title_qstn_sample
      {
        border: 1px solid lightgrey;
        margin: 5px;
        padding: 5px;
      }

      .sample_add
      {
        display: none;
      }

      .hidden_sample
      {
        display: none;
      }

      .checklist_qstn
      {
        font-size: 120%;
        font-weight: 125%;
      }

      .checklist_title_data
      {
        padding: 3px;
        font-size: 105%;
        font-style: italic;
      }

      .se-pre-con 
      {
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
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="se-pre-con"></div>
<div class="wrapper">
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url();?>dashboard/main" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>ACT</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>ACT</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Message Dashboard -->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bullhorn"></i>
            <?php if($messageDB_list_recived_to_me_count){ echo '<span id="my_notify_msg_id" class="label label-success">'.$messageDB_list_recived_to_me_count.'</span>'; }?>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $messageDB_list_recived_to_me_count; ?> unread announcements</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php
                  foreach($messagedb_list_recived_to_me         as $mgr)
                  {
                    $notification_id            =   $mgr->notification_id;
                    $notification_tittle        =   $mgr->notification_tittle;
                    $notification_time          =   $mgr->notification_time;
                    $notification_message       =   $mgr->notification_message;
                    $notification_approvedOn    =   $mgr->notification_approvedOn;
                    $from_employee              =   $mgr->from_employee;
                    $notification_media_type    =   $mgr->notification_media_type;
                    $notification_media_path    =   $mgr->notification_media_path;
                    $from_employee              =   get_user_name($from_employee);                 
                    if($this->session->userdata('last_notification_read')   <  $notification_approvedOn)
                    {
                      $bgcolorn                 =   'background-color: #bde2f7;';
                    }
                    else
                    {
                      $bgcolorn                 =   '';
                    }
                    ?> 
                    <li style="<?php echo $bgcolorn; ?>">
                      <a href="<?php echo base_url('hrm/notification_message'); ?>">
                      <i class="fa fa-bullhorn text-red"></i> <?php echo $notification_tittle; ?>
                      </a>
                    </li>
                    <?php
                  }
                  ?> 
                </ul>
              </li>
              <li class="footer"><a href="<?php echo base_url('hrm/notification_message'); ?>">See All Messages</a></li>
            </ul>
          </li>
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <?php if($message_list_recived_to_me_count){ echo '<span id="my_notify_msg_id" class="label label-success">'.$message_list_recived_to_me_count.'</span>'; }?>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $message_list_recived_to_me_count; ?> unread messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                <?php
              foreach($message_list_recived_to_me         as $mgr)
              {
                $msg_id                     =   $mgr->msg_id;
                $msg_type                   =   $mgr->msg_type;
                $msg_subject                =   $mgr->msg_subject;
                $msg_content                =   $mgr->msg_content;
                $msg_added                  =   $mgr->msg_added;
                $msg_from                   =   $mgr->msg_from;
                $read_status                =   $mgr->read_status;
                if($read_status             ==  1)
                {
                    $envelop                =   'fa-envelope-o';
                    $bgcolor                =   'background-color: #bde2f7;';
                }
                else
                {
                    $envelop                =   'fa-envelope-open-o';
                    $bgcolor                =   '';
                }
                //$msg_from_name              =   get_user_name($msg_from);
               ?>                
                  <li style="<?php echo $bgcolor; ?>"><!-- start message -->
                    <a href="<?php echo base_url('hrm/my_notification'); ?>">
                    <div class="pull-left">
                        <i class="fa <?php echo $envelop; ?>"></i>
                      </div>
                      <h4>
                        <?php echo $msg_subject; ?>
                        <small><i class="fa fa-clock-o"></i></small>
                      </h4>
                      <p><?php echo $msg_content; ?></p>
                    </a>
                  </li>
                  <?php
                  }
                  ?>                                                      
                </ul>
              </li>
              <li class="footer"><a href="<?php echo base_url('hrm/my_notification'); ?>">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <?php if($notification_list_recived_to_me_count){ echo '<span id="my_notify_msg_id" class="label label-warning">'.$notification_list_recived_to_me_count.'</span>'; }?>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $notification_list_recived_to_me_count; ?> unread notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                <?php
                  foreach($notification_list_recived_to_me         as $notifyk)
                  {
                    $notification_id                    =   $notifyk->notification_id;
                    $notification_time                  =   $notifyk->notification_time;
                    $from_employee                      =   $notifyk->from_employee;
                    $notification_tittle                =   $notifyk->notification_tittle;
                    $notification_message               =   $notifyk->notification_message;
                    $notification_type                  =   $notifyk->notification_type;
                    switch($notification_type)
                    {
                      case 1:{
                                    //1 - Announcements , 
                                    $notfy_type_ICON            =   '<i class="fa fa-bullhorn text-red"></i>';
                                    break;
                            }
                      case 2:{
                                    //2- Notice , 
                                    $notfy_type_ICON            =   '<i class="fa fa-flag text-green"></i>';
                                    break;
                            }
                      case 3:{
                                    //3- Information  
                                    $notfy_type_ICON            =   '<i class="fa fa-info text-info"></i>';
                                    break;
                            }
                      case 4:{
                                    //4- Awards   
                                    $notfy_type_ICON            =   '<i class="fa fa-trophy text-warning"></i>';
                                    break;
                            }
                      case 5:{
                                    //5-Anniversary, 
                                    $notfy_type_ICON            =   '<i class="fa fa-star text-yellow"></i>';
                                    break;
                            }
                      case 6:{
                                    //6-Birthday   
                                    $notfy_type_ICON            =   '<i class="fa fa-birthday-cake text-yellow"></i>';
                                    break;
                            }
                      case 7:{
                                    //7- Others;  
                                    $notfy_type_ICON            =   '<i class="fa fa-envelope text-aqua"></i>';
                                    break;
                            }
                      default: {
                                    //code block default; 
                                    $notfy_type_ICON            =   '<i class="fa fa-bars text-default"></i>';
                                    break;
                                }
                    }
                    if($this->session->userdata('last_notification_read')   <   $notification_time)
                    {
                        $bgcolorn               =   'background-color: #bde2f7;';
                    }
                    else
                    {
                        $bgcolorn               =   '';
                    }
                   ?> 
                      <li style="<?php echo $bgcolorn; ?>">
                        <a href="<?php echo base_url('hrm/my_notification'); ?>">
                          <?php echo $notfy_type_ICON; ?> <?php echo $notification_tittle; ?>
                        </a>
                      </li>
                  <?php
                  }
                  ?> 
                </ul>
              </li>
              <li class="footer"><a href="<?php echo base_url('hrm/my_notification'); ?>">View all</a></li>
            </ul>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php 
            $photo =   $ci->ldt_user_document_model->get_emp_photo($this->session->userdata('userid'));
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
              <li class="user-header" style="height:auto;">
                <img src="<?php echo $photo;?>" class="img-circle" alt="User Image"  onerror="this.src='<?php echo $this->config->item('img_url');?><?=$usr_img_name?>';"  /><br>
                <span style="text-align: center;color: #fff;font-size: 18px;" ><?php echo $this->session->userdata('EmpName'); ?></span>
                 <p class="drpmenu" style="border-radius: 8px 8px 8px 8px;color: #fff;box-shadow: 1px 1px 3px 2px #8aa4af;font-size: 13px;padding: 7px;">
                                   
                  <span class="inout_drmenuleft">Role</span><span class="inout_drmenuright"> <?php echo ($this->session->userdata('RoleName'))?$this->session->userdata('RoleName'):'Employee'; ?></span>
                  <?php
                  $date1 = new DateTime(date('Y-m-d', strtotime($this->session->userdata('EmpDoj')) ));
                  $date2 = new DateTime(date('Y-m-d'));
                  $tenure = $date2->diff($date1)->format("%a");
                  ?>
                   <br><span class="inout_drmenuleft">User Code</span> <span class="inout_drmenuright"><?=$this->session->userdata('usercode')?></span><br>
                     
                     <?php 
                          if($this->session->userdata('employee'))
                          {
                            $type = 'Employee';
                          }
                          else if($this->session->userdata('partner'))
                          {
                            $type = 'Partner';
                          }

                      ?>
                     <span class="inout_drmenuleft">User Type</span> <span class="inout_drmenuright"><?=$type?></span><br>
                  <small><span class="inout_drmenuleftsmall">Tenure <?=$tenure?> Days</span></small>                             
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url();?>dashboard/roleselect" class="btn btn-default btn-flat <?=$Switchrole?>">Switch Role</a>
                </div>
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
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
   </header>