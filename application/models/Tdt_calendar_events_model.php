<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tdt_calendar_events_model extends CI_Model {
	function __construct() {
		$this->tableName = 'tdt_calendar_events';
	}
	public function get_events_details($emp_id,$start,$end)
	{
		$this->db->where("emp_id=".$emp_id);
		$this->db->where("ce_start >=", $start);
		$this->db->where("ce_end <=", $end);
		$result                         =	$this->db->get($this->tableName)->result();
		return $result;
	}
	public function add_event() 
	{		
		$name								=	$this->input->post('name');
		$description						=	$this->input->post('description');
		$start_date							=	$this->input->post('start_date');
		$end_date							=	$this->input->post('end_date');
		$start_date							=	date("Y-m-d", strtotime($start_date));
		if($end_date)
		{
			$end_date							=	date("Y-m-d", strtotime($end_date));	
		}
		$emp_id								=	$this->session->userdata('employee');	
		if($emp_id)
		{
			$ins_arr						=	array();
			$ins_arr['emp_id']      		=   $emp_id;
			$ins_arr['ce_title']     		=   $name;
			$ins_arr['ce_description']     	=   $description;
			$ins_arr['ce_start']     		=   $start_date;
			$ins_arr['ce_end']   			=   ($end_date)?$end_date:$start_date;
			$addevent						=	$this->insert($ins_arr);
		}			
	}
	public function edit_event() 
	{		
		$name								=	$this->input->post('name');
		$description						=	$this->input->post('description');
		$start_date							=	$this->input->post('start_date');
		$end_date							=	$this->input->post('end_date');
		$start_date							=	date("Y-m-d", strtotime($start_date));
		if($end_date)
		{
			$end_date							=	date("Y-m-d", strtotime($end_date));	
		}
		$eventid							=	$this->input->post('eventid');
		$event_type							=	$this->input->post('event_type');
		$delete								=	$this->input->post('delete');
		$emp_id								=	$this->session->userdata('employee');
		$cond								=	array('ce_id'=>$eventid);
		if($delete)
		{
			$delete_event					=	$this->delete($cond);	
		}
		else
		{
			$upate_arr						=	array();		
			$upate_arr['emp_id']      		=   $emp_id;
			$upate_arr['ce_title']     		=   $name;
			$upate_arr['ce_description']    =   $description;
			$upate_arr['ce_start']     		=   $start_date;
			$upate_arr['ce_end']   			=   ($end_date)?$end_date:$start_date;
			$update_event					=	$this->update($upate_arr,$cond);	
		}		
	}
	public function insert($insArr)
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
    public function delete($cond)
	{
        $this->db->where($cond);
        $this->db->delete($this->tableName);
        return true;        
    }
}
?>