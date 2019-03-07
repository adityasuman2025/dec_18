<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_otp_manager_model extends CI_Model 
{
	function __construct() {
		$this->tableName = 'ldt_user_otp_manager';
	}
    public function get_employee_otp($emp_id)
	{
		$this->db->select('eom_otp');
		$this->db->where('user_id',$emp_id);
		$this->db->where('eom_type','1');
		$this->db->where('eom_status','1');
		$result = $this->db->get($this->tableName)->row();
        if($result != null)
        {
            return $result->eom_otp;
        }
        else
        {
            return 0;
        }
	}
	public function get_password_reset_otp($emp_id)
	{
		$this->db->select('eom_otp');
		$this->db->where('user_id',$emp_id);
		$this->db->where('eom_type','3');
		$this->db->where('eom_status','1');
		$result = $this->db->get($this->tableName)->row();
        if($result != null)
        {
            return $result->eom_otp;
        }
        else
        {
            return 0;
        }
	}
	public function get_special_permission_otp($emp_id)
	{
		$this->db->select('eom_otp');
		$this->db->where('user_id',$emp_id);
		$this->db->where('eom_type','1');
		$this->db->where('eom_status','1');
		$result = $this->db->get($this->tableName)->row();
        if($result != null)
        {
            return $result->eom_otp;
        }
        else
        {
            return 0;
        }
	}
    public function insert_user_otp_manager($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    public function check_correct_otp($emp_id,$otp)
	{
		$this->db->select('eom_id');
		$this->db->where('user_id',$emp_id);
		$this->db->where('eom_type','1');
		$this->db->where('eom_status','1');
		$this->db->where('eom_otp',$otp);
		$result =   $this->db->get($this->tableName);
        $retval =   $result->num_rows();
		return $retval;
	}
	public function check_password_otp($emp_id,$otp)
	{
		$this->db->select('eom_id');
		$this->db->where('user_id',$emp_id);
		$this->db->where('eom_type','3');
		$this->db->where('eom_status','1');
		$this->db->where('eom_otp',$otp);
		$result =   $this->db->get($this->tableName);
        $retval =   $result->num_rows();
		return $retval;
	}
    public function update_user_otp_manager($up_arr,$cond)
	{
        $this->db->update($this->tableName, $up_arr, $cond);
        return 1;
	}
	public function get_joining_form_otp($emp_id)
	{
		$this->db->select('eom_otp');
		$this->db->where('user_id',$emp_id);
		$this->db->where('eom_type','4');
		$this->db->where('eom_status','1');
		$result = $this->db->get($this->tableName)->row();
        if($result != null)
        {
            return $result->eom_otp;
        }
        else
        {
            return 0;
        }
	}
	public function check_joining_form_otp($emp_id,$otp)
	{
		$this->db->select('eom_id');
		$this->db->where('user_id',$emp_id);
		$this->db->where('eom_type','4');
		$this->db->where('eom_status','1');
		$this->db->where('eom_otp',$otp);
		$result =   $this->db->get($this->tableName);
        $retval =   $result->num_rows();
		return $retval;
	}
}