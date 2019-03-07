<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_emergency_contact_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_user_emergency_contact';
		$this->primaryKey = 'ref_id';
	}
    public function get_count($cond)
	{
		$this->db->where($cond);
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();       
		return $retval;      
	}
	 public function get_emergency_contact_details($user_id)
	{
		$this->db->where('user_id',$user_id);
		$this->db->where('ref_status','1');
		$result                         =   $this->db->get($this->tableName)->row();;
		return $result;
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
	function create_emgc($arr,$userid)
	{
		$ins['ref1_name']             =   $arr['emp_emergency_contact1_name'];
        $ins['ref2_name']             =   $arr['emp_emergency_contact2_name'];
        $ins['ref1_relationship']     =   $arr['emp_emergency_contact1_relation'];
        $ins['ref2_relationship']     =   $arr['emp_emergency_contact2_relation'];
        $ins['ref1_number']           =   $arr['emp_emergency_contact1_phone'];
        $ins['ref2_number']           =   $arr['emp_emergency_contact2_phone'];
        $ins['user_id']               =   $userid;
        return $this->insert($ins);
	}	
}