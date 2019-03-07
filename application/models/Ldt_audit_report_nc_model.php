<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_audit_report_nc_model extends CI_Model 
{
	function __construct() 
    {
        $this->tableName    =   'ldt_audit_report_nc';
	}

//function to get all the NCs of audit report for that tracksheet_id and level
	public function get_audit_report_ncs($tracksheet_id, $level)
	{
		$this->db->select('*');
		$this->db->from('ldt_audit_report_nc');
		$this->db->where('tracksheet_id', $tracksheet_id);
		$this->db->select('level', $level);

		$query_run = $this->db->get();
		$result = $query_run->result_array();
		return $result;
	}

//function to add a new nc in the database
	public function add_audit_report_nc_in_db($records)
	{
		$query_run = $this->db->insert('ldt_audit_report_nc', $records);

		if($query_run)
			echo 1;
		else
			echo 0;
	}

//function to get the questions, answers and other other infos about that question for which NC has been marked for a tracksheet and a level
	public function get_all_infos_of_nc($tracksheet_id, $level)
	{
		$query = "SELECT ldt_audit_report_nc.*, mdt_questionnaire_qstns.qstn_title, mdt_questionnaire_qstns.qstn_help, GROUP_CONCAT(ldt_questionnaire_ans.answer SEPARATOR '^') answers, GROUP_CONCAT(ldt_questionnaire_ans.ans_type SEPARATOR '^') answers_type FROM ldt_audit_report_nc, mdt_questionnaire_qstns, ldt_questionnaire_ans WHERE ldt_audit_report_nc.tracksheet_id = $tracksheet_id AND ldt_audit_report_nc.level = $level AND mdt_questionnaire_qstns.qstn_id = ldt_audit_report_nc.qstn_id AND (ldt_questionnaire_ans.ans_id = ldt_audit_report_nc.option_ans_id OR ldt_questionnaire_ans.ans_id = ldt_audit_report_nc.remarks_ans_id) GROUP BY id";

		$query_run = $this->db->query($query);
		$result = $query_run->result_array();

		return $result;
	}

//function to update the nc statment for a nc id
	public function update_nc_statement_in_db($nc_id, $nc_statement)
	{
		$this->db->set('nc_statement', $nc_statement);
		$this->db->where('id', $nc_id);
		
		$query_run = $this->db->update('ldt_audit_report_nc');

		if($query_run)
			echo 1;
		else
			echo 0;
	}	

//function to clear nc
	public function clear_audit_report_nc($nc_id)
	{
		$this->db->where('id', $nc_id);
		$this->db->set('nc_clear_status', 2);
		$query_run = $this->db->update('ldt_audit_report_nc');

		if($query_run)
			echo 1;
		else
			echo 0;
	}

//function to edit some details for a nc_ic
    public function edit_nc_details($id, $data)
    {
    	$this->db->where('id', $id);

		$query_run = $this->db->update('ldt_audit_report_nc', $data);

		if($query_run)
			echo 1;
		else
			echo 0;
    }
     
}
?>