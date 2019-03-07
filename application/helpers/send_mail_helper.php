<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function simple_mail($from,$fromName,$to,$toName,$subject,$body)
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
		$CI->email->to($to,$toName);
		$CI->email->from($from,$fromName);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
	}
	function send_mail_ci($from,$fromName,$to,$toName,$cc,$subject,$body)
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
        if($cc)
        {
            $CI->email->cc($cc);
        }
		$CI->email->from($from,$fromName);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
		//echo $CI->email->print_debugger();
	}
    function advanced_mail($from,$fromName,$to,$toName,$ccArray,$subject,$body)
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
        if($ccArray)
        {
            $CI->email->cc($ccArray);
        }
        if($bccArray)
        {
            $CI->email->bcc($bccArray);
        }
		$CI->email->initialize($config);
		$CI->email->set_mailtype("html");
		$CI->email->set_newline("\r\n");
		$CI->email->to($to,$toName);
		$CI->email->from($from,$fromName);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
    } 
	function multy_cc_mail($from,$fromName,$to,$toName,$ccarray,$subject,$body)
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
        if($ccArray)
        {
            $this->email->cc($ccArray);
        }
        if($bccArray)
        {
            $this->email->bcc($bccArray);
        }
		$CI->email->initialize($config);
		$CI->email->set_mailtype("html");
		$CI->email->set_newline("\r\n");
		$CI->email->to($to,$toName);
		$CI->email->from($from,$fromName);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
	}
	function attached_mail($from,$fromName,$to,$toName,$cc='',$filename,$subject,$body)
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
        if($attachArr)
        {
            foreach($attachArr      as $atchkey=>$atchval)
            {
                $baseurl            =   base_url().'uploads/';
                $filename                       =   str_replace($baseurl,'',$atchkey);
                $url	         				=	$this->config->item('img_upload_url').$filename;
                if(file_exists($url))
                { 
                    $this->email->attach($url);
                }  
            }
        }
        if($filename)
        {
            $baseurl            =   base_url().'uploads/';
            $filename                       =   str_replace($baseurl,'',$atchkey);
            $url	         				=	$this->config->item('img_upload_url').$filename;
            if(file_exists($url))
            { 
                $this->email->attach($url);
            }  
        }
        if($cc)
        {
            $this->email->cc($cc);
        }
        if($bccArray)
        {
            $this->email->bcc($bccArray);
        }
		$CI->email->initialize($config);
		$CI->email->set_mailtype("html");
		$CI->email->set_newline("\r\n");
		$CI->email->to($to,$toName);
		$CI->email->from($from,$fromName);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
    }
    function sendemail($fromMail,$frmName,$toArray,$subject,$message,$attachArr="",$ccArray="",$bccArray="")
    {
        $CI =&get_instance();
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = SMTP_HOST;
        $config['smtp_port']    = SMTP_PORT;
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = SMTP_USER;
        $config['smtp_pass']    = SMTP_PASS;
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; //         
        $CI->load->library('email',$config); 
        $CI->email->from($fromMail, $frmName);
        $CI->email->to($toArray);
        if($ccArray)
        {
            $CI->email->cc($ccArray);
        }
        if($bccArray)
        {
            $CI->email->bcc($bccArray);
        }
        if($attachArr)
        {
            foreach($attachArr      as $atchkey=>$atchval)
            {
                $baseurl            =   base_url().'uploads/';
                $filename                       =   str_replace($baseurl,'',$atchkey);
                $url	         				=	$CI->config->item('img_upload_url').$filename;
                if(file_exists($url))
                { 
                    $CI->email->attach($url);
                } 
            }
        }
        $CI->email->set_mailtype('html');;
        $CI->email->subject($subject);
        $CI->email->message($message);
        $CI->email->send();
        //echo $message.$CI->email->print_debugger();
        //echo $CI->email->print_debugger();
        $myfile = fopen("logs/send_email_logs_".date('d_m_Y').".csv", "a") or die("Unable to open file!");
        $txt = date('d-m-Y h:i:s').' ; '.implode (", ", $toArray) .' ; '.$subject.' ; '.$message.'; ';
        fwrite($myfile, "\n". $txt);
        fclose($myfile);
    }
    
    
    
?>