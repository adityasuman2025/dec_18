<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_education_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_user_education';
		$this->primaryKey = 'user_edu_id';
	}
    public function get_count($cond)
	{
		$this->db->where($cond);
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();       
		return $retval;  
	}
	public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
	public function get_users_education_details($user_id)
	{
		$this->db->where('user_id='.$user_id);
		$result                          =   $this->db->get($this->tableName);
		if($result						 != NULL)
		{
			return $result->result_array();
		}
		else
		{
			return '';
		}
	}
	function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function insert_user_education($user_education)
	{
		$insert_array						=	array();
		$insert_array['user_id']			=	$user_education['user_id'];
		$educount							=	count($user_education['edu_qualification']);
		for($edarray=0; $edarray<$educount; $edarray++)
		{
			$insert_array['edu_qualification']		=	$user_education['edu_qualification'][$edarray];
			$insert_array['edu_from']				=	date('Y-m-d',strtotime($user_education['edu_from'][$edarray]));
			$insert_array['edu_to']					=	date('Y-m-d',strtotime($user_education['edu_to'][$edarray]));
			$insert_array['edu_institution_name']	=	$user_education['edu_institution'][$edarray];
			$insert_array['edu_institution_city']	=	$user_education['edu_city'][$edarray];
			$insert_array['edu_institution_state']	=	$user_education['edu_state'][$edarray];
			$insert_array['edu_percentage']			=	$user_education['edu_gpa'][$edarray];
			$this->db->insert($this->tableName, $insert_array);
		}
        return $this->db->insert_id();
	}
}