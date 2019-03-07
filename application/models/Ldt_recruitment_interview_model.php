<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_recruitment_interview_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_recruitment_interview';
		$this->jointableName1 = 'ldt_recruitment_process';
		$this->primaryKey = 'iid';
	}
    function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
    public function change_interview_status()
    {
        $this->load->model('mdt_users_model');
        $upArray								=	array();
        $interview_status_array                 =   interview_status();	
        $rounds_of_interviews_array             =   rounds_of_interviews();
		$iid								    =	$this->input->post('iid');
        $rp_id								    =	$this->input->post('rp_id');
		$status								    =	$this->input->post('status');
		$note								    =	$this->input->post('note');
		$communication_skills					=	$this->input->post('communication_skills');
        $process_skills                         =	$this->input->post('process_skills');
        $attitude                               =	$this->input->post('attitude');
        $overall_rating					        =	$this->input->post('overall_rating');
        $timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        $emp_name                               =   $this->mdt_users_model->get_employee_name($emp_id);
        if($rp_id)
		{
            $Autonote                       =   '-- On '.$timestamp.' - '.$interview_status_array[$status].' - '.$emp_name.' note : '.$note;
            $this->db->set('communication_skills', $communication_skills);
            $this->db->set('process_skills', $process_skills);
            $this->db->set('attitude', $attitude);
            $this->db->set('overall_rating', $overall_rating);
            $this->db->set('interview_note', $note);
            $this->db->set('interview_conducted', date('Y-m-d'));
            $this->db->set('status', $status);
            $this->db->where("iid=".$iid); 	
            $this->db->update($this->tableName);    
            
            $this->load->model('ldt_recruitment_process_model');
            $update                             =	$this->ldt_recruitment_process_model->update_comment($Autonote,$status," rp_id=".$rp_id); 
            //echo $this->db->last_query();   
            return true;    
    	}
    }
    public function interview_scheduled($interviewer="")
    {
        $department_id                  =   $this->input->input_stream('department_id', TRUE);
        $interviewer_id                 =	$this->input->input_stream('interviewer_id', TRUE); 
        $position_id                    =	$this->input->input_stream('position_id', TRUE); 
        $interview_type                 =	$this->input->input_stream('interview_type', TRUE); 
        $interview_added                =	$this->input->input_stream('interview_added', TRUE); 
        $overall_rating                 =	$this->input->input_stream('overall_rating', TRUE); 
        $status                         =	$this->input->input_stream('status', TRUE); 
        $interview_conducted            =	$this->input->input_stream('interview_conducted', TRUE);
        $rp_application_id              =	$this->input->input_stream('rp_application_id', TRUE); 
        $Emp_name                       =   $this->input->input_stream('Emp_name', TRUE); 
		$Emp_email				        =	$this->input->input_stream('Emp_email', TRUE); 
        $Emp_mobile                     =	$this->input->input_stream('Emp_mobile', TRUE); 
        
        $where 							=	$this->primaryKey.' != 0';
		if($department_id)
		{
			$where						=	$where." AND t1.department_id = ".$department_id;
		}
        if($interviewer)
        {
            $where						=	$where." AND t1.interviewer_id =".$interviewer;//for perticular manager
        }
        elseif($interviewer_id)
        {
            $where						=	$where." AND t1.interviewer_id =".$interviewer_id;//for search
        }
        if($rp_application_id)
		{
			$where						=	$where." AND t2.rp_application_id = '".$rp_application_id."' ";
		}
        if($Emp_name)
		{
			$where						=	$where." AND t2.rp_emp_name LIKE '%".$Emp_name."%'";
		}
		if($Emp_email)
		{
			$where						=	$where." AND t2.rp_emp_email = '".$Emp_email."' ";
		}
        if($Emp_mobile)
		{
			$where						=	$where." AND t2.rp_emp_mobile = '".$Emp_mobile."' ";
		}
        if($position_id)
		{
			$where						=	$where." AND t1.position_id =".$position_id;
		}
        if($interview_type)
		{
			$where						=	$where." AND t1.interview_type = ".$interview_type;
		}
		if($interview_added)
		{
			$where						=	$where." AND t1.interview_added = '".$interview_added."' ";
		}
        if($overall_rating)
		{
			$where						=	$where." AND t1.overall_rating = ".$overall_rating;
		}
        if($interview_conducted)
		{
			$where						=	$where." AND t1.interview_conducted = '".$interview_conducted."' ";
		}
        if($status)
		{
			$where						=	$where." AND t1.status = ".$status;//application_id
		}                
        if($this->session->userdata('office') != 1)
        {
            //$where                      =  $where. " AND rp_office =".$this->session->userdata('office');
        } 
        $sql                            =   'SELECT * FROM '.$this->tableName.' t1 JOIN '.$this->jointableName1.' t2 ON t1.application_id=t2.rp_id WHERE '.$where;
        return $Result_data  			=   $this->pagination_model->get_pagination_sql($sql,25);
    }
}