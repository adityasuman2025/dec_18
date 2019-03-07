<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Send_sms_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        return true;
    }
    public function send_sms($mob,$msg)
	{
        $sid                =   'IASISO';
        $usename            =   'iasiso';
        $password           =   'abcd1234';
		$smsurl		 	    =	"http://ind.just4sms.in/api/mt/SendSMS?";
		$username			=	"user=" . $usename;
		$password			=	"password=".$password;
		$senderId			=	"senderid=".$sid."&channel=Trans&DCS=0&flashsms=0";
		$MobileNo			=	"number=" . $mob;
		$Message			=	"text=" . urlencode($msg);
		$smsStr				=	$smsurl.$username."&".$password."&".$senderId."&".$MobileNo."&".$Message.'&route=1';
		$curl 				= 	curl_init();
		$url				=	$smsStr;
        //$this->load->library('curl');
        $post_data          =   array (             
        );
        //$output             =   $this->curl->simple_post($url, $post_data);
        $output             =   $this->curl->simple_get($url, $post_data);
        //var_dump($output);
	    return true;
	} 
}
?>