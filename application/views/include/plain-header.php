<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $this->uri->segment(1);?></title>
    <link rel="icon" href="<?php echo $this->config->item('img_url');?>favicon.ico" type="image/x-icon" media="all" media="print"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('bootstrap_css');?>bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('css');?>font-awesome.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('css');?>ionicons.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('plugins');?>jvectormap/jquery-jvectormap-1.2.2.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>dist/css/AdminLTE.min.css"/>
    <link rel="stylesheet" href="<?php echo $this->config->item('aset_url');?>dist/css/skins/_all-skins.min.css"/>
</head>
<body class="hold-transition login-page">
<div class="modal-dialog">
<?php 
$error_message                      =   $this->session->flashdata('error_message');
$success_message                    =   $this->session->flashdata('success_message');
if(!$error_message=='')
{
    ?>
        <div class="alert alert-danger alert-dismissible text-center">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $error_message; ?>
        </div>
    <?php
}
if(!$success_message=='')
{
    ?>
        <div class="alert alert-success alert-dismissible text-center">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $success_message; ?>
        </div>
    <?php
}
?>