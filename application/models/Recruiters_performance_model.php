<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruiters_performance_model extends CI_Model {
	function __construct() {
		$this->tableName                =   'ldt_recruitment_process'; 
	}
    ///////////////////////////////////////////////////////////
    //////////////// /get_recruiters_performance_list//////////
    ///////////////////////////////////////////////////////////
    function get_recruiters_performance_list()
    {      
        $where                      =   " emp_id =0";  
        if($this->session->userdata('RoleID') ==  7)
        {
            $where                  = " emp_id IN (".$this->session->userdata('userid').")";
        }
        else if($this->session->userdata('RoleID')     ==  6 || $this->session->userdata('RoleID')     ==  1 || $this->session->userdata('RoleID')     ==  67 || $this->session->userdata('RoleID')     ==  148)
        {            
            $this->load->model('Tdt_user_roles_model');
            $rec_list               =   $this->Tdt_user_roles_model->get_users_in_role(7);            
            if($rec_list)
            {
                $where              =   " emp_id IN (".$rec_list.")";
            }          
        }
        $where                      =   $where." AND employee_status=1";
        
        $from_date                  =	$this->input->input_stream('from_date', TRUE);   
        $to_date                    =	$this->input->input_stream('to_date', TRUE);   
        /*if($Emp_name)
        {
            $where                  =	$where." AND employee_name LIKE '%".$Emp_name."%'";
        }*/
        return $Result_data         =   $this->pagination_model->get_pagination("mdt_employees",$where,"employee_name ASC",25);
    }   
    ///////////////////////////////////////////////////////////
    //////////////// /get_recruiters_performance     //////////
    ///////////////////////////////////////////////////////////
    function get_recruiters_performance($emp_id,$from_date='',$to_date='')
    {    
        $where                         =    " rp_emp_referral_detail =".$emp_id." AND rp_emp_referral=1 ";   
        if($from_date                  &&   $to_date)
        {
            $where                     =    $where." AND rp_interview_date between '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'";
        }
        else if($from_date)
        {
            $where                     =    $where." AND rp_interview_date ='".date('Y-m-d',strtotime($from_date))."'";
        }
        else if($to_date)
        {
            $where                     =    $where." AND rp_interview_date ='".date('Y-m-d',strtotime($to_date))."'";
        }
        else
        {
            $where                     =    $where." AND rp_interview_date ='".date('Y-m-d')."'";
        }                
        $sql                           =    "SELECT count(rp_id) AS walkin, count(CASE WHEN rp_interview_status=4 then rp_id END) AS shortlisted, count(CASE WHEN rp_interview_status=10 then rp_id END) AS selected, count(CASE WHEN rp_interview_status =14 then rp_id END) AS joined from ".$this->tableName." WHERE ".$where;
        $result                        =   $this->db->query($sql)->row();
        if($result                    !=   null) 
        {
            return $result;
        }
        else
        {
            return '';
        } 
    }
    ///////////////////////////////////////////////////////////////////////////////
    //////////////	        get_recruiters_talk_time              		  	 //////
    ///////////////////////////////////////////////////////////////////////////////
    function get_recruiters_talk_time($emp_id,$from_date='',$to_date='')
    {    
        return '-';
    }
    ///////////////////////////////////////////////////////////
    //////////////// /get_recruiters_performance_list//////////
    ///////////////////////////////////////////////////////////
    function get_recruiters_performance_candidate_list()
    {  
        $from_date              = 	$this->input->input_stream('from_date', TRUE);  
        $to_date                = 	$this->input->input_stream('to_date', TRUE);  
        $type                   = 	$this->input->input_stream('type', TRUE);  
        $emp_id                 = 	$this->input->input_stream('id', TRUE);         
        $where                  =    " rp_emp_referral_detail =".$emp_id." AND rp_emp_referral=1 "; 
        if($from_date           &&   $to_date)
        {
            $where              =    $where." AND rp_interview_date between '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'";
        }
        else if($from_date)
        {
            $where              =    $where." AND rp_interview_date ='".date('Y-m-d',strtotime($from_date))."'";
        }
        else if($to_date)
        {
            $where              =    $where." AND rp_interview_date ='".date('Y-m-d',strtotime($to_date))."'";
        }
        else
        {
            $where              =    $where." AND rp_interview_date ='".date('Y-m-d')."'";
        }        
        if($emp_id)
        {
            if($type            ==  'walkin')
            {
                // all
            }
            else if($type       ==  'shortlisted')
            {
                $where          =    $where." AND rp_interview_status=4";
            }
            else if($type       ==  'selected')
            {
                $where          =    $where." AND rp_interview_status=10";
            }
            else if($type       ==  'joined')
            {
                $where          =    $where." AND rp_interview_status=14";
            }
            return $Result_data =   $this->pagination_model->get_pagination($this->tableName,$where,"rp_emp_name ASC",25);
        }
        else
        {
            return  '';
        }
        
    } 
}