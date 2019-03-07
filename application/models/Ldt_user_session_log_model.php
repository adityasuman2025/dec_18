<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_session_log_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->tableName = 'user_session_log';
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
   	public function check_user_session($user_id)
	{
		$this->db->select("us_id");
		$this->db->where("user_id",$user_id);
		$result = $this->db->get($this->tableName)->row();
		if($result)
		{
			return $result->us_id;
		}
		else
		{
			return "";
		}
	}
}
?>