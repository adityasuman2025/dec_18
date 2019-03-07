<?php
	class Ldt_audit_plan_notify_to_client_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
	        $this->tableName = 'ldt_audit_plan_notify_to_client';
		}

	//function to notify client about the audit plan
		public function notify_client_about_audit_plan($tracksheet_id, $level, $cm_id)
		{
			$data = array(
				'cm_id' => $cm_id,
				'tracksheet_id' => $tracksheet_id,
				'level' => $level
			);

			$query_run = $this->db->insert('ldt_audit_plan_notify_to_client', $data);

			if($query_run)
				return 1;
			else
				return 0;
		}

	//to check if that tracksheet belongs to that client or not for a level
		public function tracksheet_to_client_relation_for_a_level($tracksheet_id, $cm_id, $level)
		{
			$this->db->select('id');
			$this->db->from('ldt_audit_plan_notify_to_client');
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->where('cm_id', $cm_id);
			$this->db->where('level', $level);

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			if($query_run)
				return count($result);
			else
				return 0;
		}
	}
?>