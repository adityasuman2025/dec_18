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
  </style>
  <script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.4.min.js"></script>
<script src="<?php echo $this->config->item('plugins');?>datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
        jQuery(document).ready(function()
        {
            $('.datepicker').datepicker({ 
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
        });
    </script>
</head>   
<body class="hold-transition skin-blue sidebar-mini login-page">
