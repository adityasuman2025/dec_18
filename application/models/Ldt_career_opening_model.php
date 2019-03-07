<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_career_opening_model extends CI_Model {
	function __construct() 
    {
		$this->tableName = 'ldt_career_opening';
        $this->load->model('pagination_model');
	}
	public function getCount($cond)
	{
		$query	=	$this->db->where($cond)->get($this->tableName);
		$count= $query->result();
		return count($count);
	}
	public function get_career_opening()
	{
		$this->db->where('opStatus =1');
		$this->db->order_by("opPosition","ASC");
		$result =   $this->db->get($this->tableName)->result_array();
		return $result;
	}
	public function get_career_opening_position($opID)
	{
		$this->db->where('opID',$opID);
		$result =   $this->db->get($this->tableName)->row();
        if($result   !=    null)
        {
            return $result->opPosition;
        }
		else
        {
            return true;
        }
	}
	public function get_career_opening_required_details($opID,$fields)
	{
		$this->db->select($fields);
		$this->db->where('opID',$opID);
		$result =   $this->db->get($this->tableName)->row();
        if($result   !=    null)
        {
            return $result;
        }
		else
        {
            return true;
        }
	}
    public function get_career_opening_names()
	{
		$this->db->select('opID,opPosition');
		$this->db->order_by("opPosition","ASC");
        $career_opening_array = array();
		$result =   $this->db->get($this->tableName)->result_array();
        foreach($result             as $val)
        {
            $career_opening_array[$val['opID']]         =   $val['opPosition'];            
        }
		return $career_opening_array;
	}
    public function get_career_opening_details($opID)
	{
		$this->db->where('opID',$opID);
		$result           =   $this->db->get($this->tableName)->row();        
		return $result;
	}  
	public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function update($arr,$cond)
	{
		$this->db->update($this->tableName,$arr,$cond);
        return true;
	}
	public function update_custom($integer_arr,$cond,$character_arr='')
	{		
        if($integer_arr)
        {
            foreach ($integer_arr as $akey => $item) 
            {
                $this->db->set($akey,$item,FALSE); 
            }
        }
        if($character_arr)
        {    
            foreach ($character_arr as $akey => $item) 
            {
                $this->db->set($akey,$item); 
            }
        }
        $this->db->where($cond); 	
        $this->db->update($this->tableName); 
        return true;
	}
	public function change_career_opening_status()
	{
        $opID   					            =	$this->input->post('pkey', TRUE);
	 	$status                  			    =	$this->input->input_stream('status', TRUE);
        if($status                              ==  1)
        {
            $opStatus                           =   2;
        }
        else
        {
            $opStatus                           =   1;
        }              
        $this->update(array("opStatus"=>$opStatus),"opID=".$opID);
        $this->session->set_flashdata('success_message', 'Status changed successfully');         
	}
	public function careerOpeningList()
	{        
		$opPosition                     =   $this->input->input_stream('opPosition', TRUE);        
		$opStatus                       =   $this->input->input_stream('opStatus', TRUE);         
        if($opPosition                  ==  null    && $this->session->flashdata())
        {
            $opPosition                 =   $this->session->flashdata('opPosition');
        }     
        if($opStatus                    ==  null    && $this->session->flashdata())
        {
            $opStatus                   =   $this->session->flashdata('opStatus');
        }        
		$where 							=	'opID != 0';
		if($opPosition)
		{
			$where						=	$where." AND opPosition LIKE '%".$opPosition."%'";
		} 
		if($opStatus)
		{
			$where						=	$where." AND opStatus = ".$opStatus;
		}        	
        $data  			                =   $this->pagination_model->get_pagination($this->tableName,$where,"opStatus,opID",10);
        $data['opPosition']             =   $opPosition;        
		$data['opStatus']               =   $opStatus;         
        return $data;
	}
	public function addCareerOpening()
	{    
        $data                           =   array();
        $opPosition   				    =	$this->input->post('opPosition', TRUE);
        $opStatus	                    =	$this->input->post('opStatus', TRUE);
        $data                           =   '';
        if($this->uri->segment(3))
		{
			$opID    				    =	$this->uri->segment(3);
		}
        else
        {
            $opID                       =   '';
        }
        if($opID)
        {
            $data                       =   $this->get_career_opening_details($opID);
        }
        return $data;
	}
	public function add_career_openings()      //   ACTION
	{   
        $opPosition   		    		=	$this->input->post('opPosition', TRUE);
        $opDescription        	        =	$this->input->post('opDescription', TRUE);
        $opExperience	                =	$this->input->post('opExperience', TRUE);
        $opLocation	                    =	$this->input->post('opLocation', TRUE);
        $opStatus	                    =	$this->input->post('opStatus', TRUE);        
        $op_num_positions               =	$this->input->post('op_num_positions', TRUE);        
        if($this->uri->segment(3))
		{
			$opID    				    =	$this->uri->segment(3);
		}
        else
        {
            $opID                       =   '';
        }
        
        if($opID)
        {
            $this->update(array('opPosition'=>$opPosition,'opDescription'=>$opDescription,'opExperience'=>$opExperience,'opLocation'=>$opLocation,'opStatus'=>$opStatus,'op_num_positions'=>$op_num_positions),'opID ='.$opID);
        }
        else
        {
            $this->insert(array('opPosition'=>$opPosition,'opDescription'=>$opDescription,'opExperience'=>$opExperience,'opLocation'=>$opLocation,'opStatus'=>$opStatus,'op_num_positions'=>$op_num_positions));
        }  
        $post_type                      =   $this->input->post('post_type', TRUE);
        if($post_type                   ==  'publish')
        {
            $publishto                  =   $this->input->post('publishto', TRUE);
            if(count($publishto))
            {
                if(in_array('Facebook',$publishto))
                {
                   // echo 'send to Facebook API';
                }
                if(in_array('LinkedIn',$publishto))
                {
                  //  echo 'send to LinkedIn API';
                }
                if(in_array('Twitter',$publishto))
                {
                   // echo 'send to Twitter API';
                }
                if(in_array('Employee Referral',$publishto))
                {
                   // echo 'send notification';
                }
            }
            $consult                    =   $this->input->post('consultancies', TRUE);
            if(count($consult))
            {
                $this->load->model('sdt_consultancies_model');
                foreach($consult        as  $ckey=>$cval)
                {
                    $cons_det           =   $this->sdt_consultancies_model->get_consultancy_details($cval);
                    $mail_arr           =   jd_to_consultancy_mail($cons_det->c_name,$opPosition,$opDescription,$opExperience,$opLocation);  
                    $body               =   $mail_arr['to_name'].$mail_arr['greetings'].$mail_arr['contents'].$mail_arr['note'].$mail_arr['regards'];
                 }                
            }
        }  
        $this->session->set_flashdata('opPosition',$this->input->post('sear_opPosition', TRUE));        
        $this->session->set_flashdata('opDescription',$this->input->post('sear_opStatus', TRUE));   
        $from_page                      =   $this->input->post('from_page', TRUE);
        if($from_page                   ==  'hiring_request')
        {                   
            $this->session->set_flashdata('hhr_status',$this->input->post('hhr_status', TRUE));  
            $this->session->set_flashdata('success_message', 'Carrer Opening Published Successfully');
            redirect(base_url('hrm/hiring_request'));   
        }
        else
        {
            redirect(base_url('hrm/careerOpeningList'));   
        }	
	}
	public function selected_candidate()      //   ACTION
	{    
       
	}
}
?>