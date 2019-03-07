<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
	function simple_email($from,$fromName,$to,$toName,$subject,$body)
	{
		$CI =&get_instance();
		$CI->load->library('email');
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => SMTP_HOST,
			'smtp_port' => SMTP_PORT,
			'smtp_user' => SMTP_USER,
			'smtp_pass' => SMTP_PASS,
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$CI->email->initialize($config);
		$CI->email->set_mailtype("html");
		$CI->email->set_newline("\r\n");
		$CI->email->to($to);
		$CI->email->from($from,'WealthDoctor');
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
		echo $CI->email->print_debugger();
	}



    function wishes_email($from,$fromName,$to,$toName,$cc,$subject,$body)

	{
		$CI =&get_instance();
		$CI->load->library('email');
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => SMTP_HOST,
			'smtp_port' => SMTP_PORT,
			'smtp_user' => SMTP_USER,
			'smtp_pass' => SMTP_PASS,
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$CI->email->initialize($config);
		$CI->email->set_mailtype("html");
		$CI->email->set_newline("\r\n");
		$CI->email->to($to);
		$CI->email->cc($cc);
		$CI->email->from($from,'HR');
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
		echo $CI->email->print_debugger();
	}
?>
