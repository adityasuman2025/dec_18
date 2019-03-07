<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_notify_reviewer_about_audit_report_model extends CI_Model 
{
	function __construct() 
    {
        $this->tableName    =   'ldt_notify_reviewer_about_audit_report';
	}

//function to notify reviewer team about the submitted audit report
	public function notify_reviewer_about_audit_report($tracksheet_id, $level, $auditor_ids)
	{		
		$data = array(
				'tracksheet_id' => $tracksheet_id,
				'level' => $level,
			);

		$count = count($auditor_ids);
		$c = 0;
		foreach ($auditor_ids as $key => $auditor_id)
		{
			$c++;

			$data['ac_id'] = $auditor_id;
			$this->db->insert('ldt_notify_reviewer_about_audit_report', $data);
		}

		if($c == $count)
			return 1;
		else
			return 0;
	}

//function to get notify status of reviewer for a tracksheet
	public function get_notify_status($tracksheet_id)
	{
		$this->db->select('*');
		$this->db->from('ldt_notify_reviewer_about_audit_report');
		$this->db->where('tracksheet_id', $tracksheet_id);
		$query_run = $this->db->get();

		$result = $query_run->result_array();
		return $result;
	}	

//function to get notify status of reviewer for a level
	public function get_notify_status_level_wise($level)
	{
		$this->db->select('tracksheet_id');
		$this->db->group_by('tracksheet_id'); 
		$this->db->from('ldt_notify_reviewer_about_audit_report');
		$this->db->where('level', $level);
		$query_run = $this->db->get();

		$result = $query_run->result_array();
		return $result;
	}

//function to get all the tracksheet_id s assigned to a particular user_id and a level to a reviewer
    public function get_tracksheet_ids_for_user_id($user_id, $level)
    {
    	$query = "SELECT ldt_notify_reviewer_about_audit_report.tracksheet_id FROM ldt_notify_reviewer_about_audit_report, ldt_audit_certificate WHERE level = $level AND ldt_audit_certificate.user_id = $user_id AND ldt_audit_certificate.ac_id = ldt_notify_reviewer_about_audit_report.ac_id";
    	
    	$query_run = $this->db->query($query);
    	$result = $query_run->result_array();
    	return $result;
    }

//function to update done for a tracskheet id and level
	public function update_done_status($tracksheet_id, $level)
	{
		$this->db->set('done', 2);
		$this->db->where('tracksheet_id', $tracksheet_id);
		$this->db->where('level', $level);
		$query_run = $this->db->update('ldt_notify_reviewer_about_audit_report');

		if($query_run)
			return 1;
		else
			return 0;
	}    
}
?>