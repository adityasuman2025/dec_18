<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_exit_form_questions_model extends CI_Model {
	function __construct() {
		$this->tableName            = 'sdt_exit_form_questions'; 
		$this->primaryKey           = 'efq_id';
	}   
    public function get_count($cond)
    {
        $query	                    =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                      =	$query->result();
		return count($count);
    }   
    public function update($arr,$cond)
	{
        $this->db->update($this->tableName,$arr,$cond);
        return true;
	}
    public function insert($arr)
	{
        $this->db->insert($this->tableName,$arr);
        return true;
	}
    public function get_exit_form_questions()
	{
        $this->db->select('efq_ques,efq_id');
        $this->db->where('efq_status','1');
        $this->db->where('efq_type','1');
        $result                     =   $this->db->get($this->tableName);
        if($result                 !=   null)
        {
            return $result->result_array(); 
        }
        else
        {
            return '';
        } 
	}
    public function get_exit_form_feed_back_questions()
	{
        $this->db->select('efq_ques,efq_id');
        $this->db->where('efq_status','1');
        $this->db->where('efq_type','2');
        $result                     =   $this->db->get($this->tableName)->row();
        if($result                 !=   null)
        {
            return $result; 
        }
        else
        {
            return '';
        } 
	}
}