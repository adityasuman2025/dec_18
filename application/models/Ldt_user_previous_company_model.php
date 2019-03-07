<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_previous_company_model extends CI_Model 
{
	function __construct() 
    {
		$this->tableName = 'ldt_user_previous_company';
	}
	public function get_count($cond)
	{
        $this->db->where($cond);
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();       
		return $retval;  
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
	
	public function get_users_previous_company_details($user_id)
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
	/*function getPreCompanyCount($emp_id)
	{
		$empEduList					=	$obj_education->getAll('emp_id='.$emp_id.' and edu_qualification !=""');
		return ($empEduList);
	}*/
	public function insert_user_profession($user_profession)
	{
		$insert_array								=	array();
		$insert_array['user_id']					=	$user_profession['user_id'];
		$procount									=	count($user_profession['pro_company']);
		for($proarray=0; $proarray<$procount; $proarray++)
		{
			$insert_array['work_company']			=	$user_profession['pro_company'][$proarray];
			$insert_array['work_job_title']			=	$user_profession['pro_designation'][$proarray];
			$insert_array['work_from']				=	date('Y-m-d',strtotime($user_profession['pro_from'][$proarray]));
			$insert_array['work_to']				=	date('Y-m-d',strtotime($user_profession['pro_to'][$proarray]));
			$insert_array['work_ctc']				=	$user_profession['pro_ctc'][$proarray];
			$this->db->insert($this->tableName, $insert_array);
		}
        return $this->db->insert_id();
	}
	
  
}
?>