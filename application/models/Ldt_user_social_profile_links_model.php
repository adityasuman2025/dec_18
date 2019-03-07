<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_social_profile_links_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_user_social_profile_links';
		$this->primaryKey = 'sp_id';
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
	function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}	
	function create_spl($arr,$userid)
	{
        if($arr['emp_social_profile1'] || $arr['emp_social_profile2'] || $arr['emp_social_profile3'] || $arr['emp_social_profile4'])
        {
            $ins['sp_one']  =   $arr['emp_social_profile1'];
            $ins['sp_two']  =   $arr['emp_social_profile2'];
            $ins['sp_three']=   $arr['emp_social_profile3'];
            $ins['sp_four'] =   $arr['emp_social_profile4'];
            $ins['user_id'] =   $userid;
            return $this->insert($ins);
        }
	}	
	public function get_emp_SocialMedia_history($user_id)
	{
		$this->db->where('user_id='.$user_id);
		$result = $this->db->get($this->tableName)->row();
		return $result;
	}
}