<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_audit_report_nc_comments_model extends CI_Model 
{
	function __construct() 
    {
        $this->tableName    =   'ldt_audit_report_nc_comments';
	}

//function to add audit report nc comment in databse
	public function add_audit_report_nc_comments_in_db($data)
	{
		$query_run = $this->db->insert('ldt_audit_report_nc_comments', $data);

		if($query_run)
			echo 1;
		else
			echo 0;
	}

//function to get all the comments for a tracksheet id and a level
	public function get_audit_report_nc_comments($tracksheet_id, $level)
	{
		$this->db->select('ldt_audit_report_nc_comments.*, mdt_users.username');
		$this->db->from('ldt_audit_report_nc_comments, mdt_users');
		$this->db->where('tracksheet_id', $tracksheet_id);
		$this->db->where('mdt_users.user_id = ldt_audit_report_nc_comments.commented_by');
		$this->db->where('level', $level);

		$query_run = $this->db->get();
		$result = $query_run->result_array();

		return $result;
	}	

//function to get all the comments for a tracksheet id and a level
	public function get_audit_nc_comments($tracksheet_id, $level)
	{
		$this->db->select('ldt_audit_report_nc_comments.*');
		$this->db->from('ldt_audit_report_nc_comments');
		$this->db->where('tracksheet_id', $tracksheet_id);
		$this->db->where('level', $level);

		$query_run = $this->db->get();
		$result = $query_run->result_array();

		return $result;
	}	
}
?>