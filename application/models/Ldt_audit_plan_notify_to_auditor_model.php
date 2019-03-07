<?php
	class Ldt_audit_plan_notify_to_auditor_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
	        $this->tableName = 'ldt_audit_plan_notify_to_auditor';
		}

	//function to send notification to auditors about the audit plan
		public function notify_auditors_about_audit_plan($tracksheet_id, $level, $auditor_ids)
		{
			//$query = "INSERT INTO ldt_audit_plan_notify_to_auditor VALUES";

			$count = count($auditor_ids);

			$data = array(
				'tracksheet_id'=>$tracksheet_id,
				'level'=>$level,
				'confid_agg_status'=> 1
			);

			if($count !=0)
			{
				foreach ($auditor_ids as $key => $value) 
				{
					$auditor_id = $value;

					$data['auditor_id'] = $auditor_id;

					$query_run = $this->db->insert('ldt_audit_plan_notify_to_auditor', $data);

					//$query = $query . "('', '$auditor_id', '$tracksheet_id', '$level', '1', ''), ";
				}

				return 1;				
			}
			else
			{
				return 1;
			}
		}

	//function to get records for a audit plan notify of a tracksheet of a level and user id
		public function audit_plan_notify_to_auditor_records($tracksheet_id, $level, $userid)
		{
			$query = "SELECT ldt_audit_plan_notify_to_auditor.*, ldt_audit_certificate.user_id FROM ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE tracksheet_id = $tracksheet_id AND level = $level AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid";
			$query_run = $this->db->query($query);

			$result = $query_run->result_array();
			return $result;
		}

	//function to get records of the audit plan notify of a tracksheet for a particular id
		public function audit_plan_notify_to_auditor_records_id($id, $userid)
		{
			$query = "SELECT ldt_audit_plan_notify_to_auditor.*, ldt_audit_certificate.user_id, mdt_users.username FROM ldt_audit_plan_notify_to_auditor, ldt_audit_certificate, mdt_users WHERE id = $id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND mdt_users.user_id = ldt_audit_certificate.user_id";
			$query_run = $this->db->query($query);

			$result = $query_run->result_array();
			return $result;
		}

	//to accept the confid agg form of a particular id
        public function accept_cofid_agg($id)
        {
        	$data = array(
	    		'confid_agg_status'=> 3,
	    		'date_of_acceptance'=> date('Y-m-d')
	    		);

        	$this->db->where('id', $id);
        	$query_run = $this->db->update('ldt_audit_plan_notify_to_auditor', $data);

            if($query_run)
            	return 1;
            else
            	return 0;    
        }

    //function to get all the tracksheet_id s assigned to a particular user_id and a level
        public function get_tracksheet_ids_for_user_id($user_id, $level)
        {
        	$query = "SELECT ldt_audit_plan_notify_to_auditor.tracksheet_id FROM ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE level = $level AND ldt_audit_certificate.user_id = $user_id AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id";        	

        	$query_run = $this->db->query($query);
        	$result = $query_run->result_array();
        	return $result;
        }

    //function to get all the auditors of a tracksheet and level
		public function get_all_auditors($tracksheet_id, $level)
		{
			$query = "SELECT mdt_users.username, mdt_users.user_id FROM ldt_audit_plan_notify_to_auditor, ldt_audit_certificate, mdt_users WHERE ldt_audit_plan_notify_to_auditor.level = $level AND ldt_audit_plan_notify_to_auditor.tracksheet_id = $tracksheet_id AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND mdt_users.user_id = ldt_audit_certificate.user_id ";        	

        	$query_run = $this->db->query($query);
        	$result = $query_run->result_array();
        	return $result;
		}
	}
?>