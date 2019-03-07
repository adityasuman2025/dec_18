<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
$myproject                              =   'http://localhost/works/dec_18';
$myprojectold                           =   'http://localhost/works/dec_18/';
$raw_file_path                          =   $_SERVER['DOCUMENT_ROOT']."/";
$raw_file_path_new                      =   $_SERVER['DOCUMENT_ROOT']."/";

$config['css']                          =   $myproject.'/assets/css/';
$config['js']                           =   $myproject.'/assets/js/';
$config['fonts']                        =   $myproject.'/assets/fonts/';

$config['bootstrap_css']                =   $myproject.'/assets/bootstrap/css/';
$config['bootstrap_js']                 =   $myproject.'/assets/bootstrap/js/';
$config['bootstrap_fonts']              =   $myproject.'/assets/bootstrap/fonts/';

$config['plugins']                      =   $myproject.'/assets/plugins/';
$config['img_url']                      =   $myproject.'/assets/images/';
$config['aset_url']                     =   $myproject.'/assets/';

$config['uploaded_img_display_url']     =   $myproject.'/uploads/';
$config['oldapp_img_display_url']       =   $myprojectold.'/uploads/';
$config['backup_url']                   =   $myproject.'/assets/backup/';

$config['img_upload_url']               =   './uploads/';
$config['upload_sys_img_url']           =   './assets/images/';
$config['file_path_root']               =   $raw_file_path;
$config['SUPER_ADMIN']                  =   1;
$config['HR_permission']                =   array(6,9,1);

$config['myprojectold']                 =   $myprojectold;
$config['ad_img_mi_h']     				=   700;
$config['ad_img_mi_w']     				=   380;
$config['ad_img_mx_h']     				=   800;
$config['ad_img_mx_w']     				=   480;
$config['ad_img_dim']     				=   "480*800";
$config['news_img_mi_h']     			=   400;
$config['news_img_mi_w']     			=   500;
$config['news_img_mx_h']     			=   500;
$config['news_img_mx_w']     			=  	600;
$config['news_img_dim']     			=   "600*400";
$config['ip_check']     		        =   0;//1 check ip , 0 not check ip
//end//






