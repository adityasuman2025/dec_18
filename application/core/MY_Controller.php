<?php
Class My_Controller extends CI_Controller
{
	function __construct()
	{
		// Construct our parent class
		parent::__construct(); 
        ini_set("memory_limit","512M"); //Allowed memory size of 1073741824 bytes exhausted (tried to allocate 4096 bytes) 
		$this->userip                 =   $_SERVER["REMOTE_ADDR"];
        $this->module_array	=	array("HRM","MARKETING", "PLANNING", "TECHNICAL", "AUDITOR", "FINANCE","ACCOUNTS", "REPORTS","IT", "MANAGE", "SETTINGS");
        if($this->router->fetch_class()     == 'app')
        {
            if(!$this->session->userdata('userid')  && $this->router->fetch_class() != 'app')
    		{
    			return redirect(base_url('app'));
    		}
        }
        else if ($this->router->fetch_class()     == 'app_bde')
        {
            if(!$this->session->userdata('userid')  && $this->router->fetch_class() != 'app_bde')
    		{
    			return redirect(base_url('app_bde'));
    		}
        }
        else if ($this->router->fetch_class()     == 'customer')
        {
            
            /*if(!$this->session->userdata('cm_id')  && $this->router->fetch_class() != 'customer')
    		{
    			return redirect(base_url('customer'));
    		}
            */
        }
        else
        {
    		if(!$this->session->userdata('userid'))
    		{
    			return redirect(base_url());
    		}
            else if($this->session->userdata('userid')  && $this->session->userdata('RoleID')   ==  "")
            {
                if(strtoupper($this->uri->segment(1))   ==  'LEAVE' || strtoupper($this->uri->segment(1))   ==  'DASHBOARD') 
                {
                    //ro redirection
                }
                else
                {
                    redirect(base_url('dashboard'));
                }
            }
            if($this->session->userdata('userip')   != $this->userip)
            {
    			$this->load->model('cdt_ip_approved_model');
                $ip_approved             =   $this->cdt_ip_approved_model->check_ip_approved($this->userip);
                if($ip_approved)
                {
                    $this->session->set_userdata('userip', $this->userip);   
                }
                else if($this->session->userdata('ip_permission')	==	2)
                {
                    $this->session->set_userdata('userip', $this->userip);
                }
    			else 
                {
                    return redirect(base_url('noaccess'));
                }
            }       
    		$this->load->helper('array');
    		$this->load->helper('key_value');		
    		$this->load->helper('common');
    		$this->load->helper('mail_template');
            $this->load->helper('send_mail');
    		$this->load->helper('pr');
        }
	}
    /*
    function paginationCompress($link, $count, $perPage = 10) 
    {
    	$this->load->library ( 'pagination' );
    
    	$config ['base_url'] = base_url () . $link;
    	$config ['total_rows'] = $count;
    	$config ['uri_segment'] = SEGMENT;
    	$config ['per_page'] = $perPage;
    	$config ['num_links'] = 3;
    	$config ['full_tag_open'] = '<nav><ul class="pagination">';
    	$config ['full_tag_close'] = '</ul></nav>';
    	$config ['first_tag_open'] = '<li class="arrow">';
    	$config ['first_link'] = 'First';
    	$config ['first_tag_close'] = '</li>';
    	$config ['prev_link'] = 'Previous';
    	$config ['prev_tag_open'] = '<li class="arrow">';
    	$config ['prev_tag_close'] = '</li>';
    	$config ['next_link'] = 'Next';
    	$config ['next_tag_open'] = '<li class="arrow">';
    	$config ['next_tag_close'] = '</li>';
    	$config ['cur_tag_open'] = '<li class="active"><a href="#">';
    	$config ['cur_tag_close'] = '</a></li>';
    	$config ['num_tag_open'] = '<li>';
    	$config ['num_tag_close'] = '</li>';
    	$config ['last_tag_open'] = '<li class="arrow">';
    	$config ['last_link'] = 'Last';
    	$config ['last_tag_close'] = '</li>';
    
    	$this->pagination->initialize ( $config );
    	$page = $config ['per_page'];
    	$segment = $this->uri->segment ( SEGMENT );
    
    	return array ("page" => $page,"segment" => $segment);
   }
   */
}
