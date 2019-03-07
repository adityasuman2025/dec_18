<?php
	class Ldt_app_rev_audit_team_plan extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
            $this->tableName        =   'ldt_app_rev_audit_team_plan';
		}

	//function to update from date and to date in app_rev_audit_team_plan
	    public function update_date_in_app_rev_audit_team_plan($row_id, $date_from, $date_to)
	    {
	    	$data = array(
	    			'planning_date_from'=> $date_from,
	    			'planning_date_to'=> $date_to
	    		);

	    	$this->db->where('id', $row_id);
	    	$query_run = $this->db->update('ldt_app_rev_audit_team_plan', $data);

	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }		

	//function to insert into app_rev_audit_team_plan
	    public function insert_in_app_rev_audit_team_plan($tracksheet_id, $app_rev_form_id, $level, $auditor_id, $type, $date_from, $date_to, $sector)
	    {
	    	$data = array(
	    			'tracksheet_id'=> $tracksheet_id,
	    			'app_rev_form_id'=> $app_rev_form_id,
	    			'level'=> $level,
	    			'auditor_id'=> $auditor_id,
	    			'type'=> $type,
	    			'planning_date_from' => $date_from,
	    			'planning_date_to'=> $date_to,
	    			'sector'=> $sector
	    		);

	    	$query_run = $this->db->insert('ldt_app_rev_audit_team_plan', $data);
		   
	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }	

	//function to insert auditor team plan records of the application review form for a tracksheet
	    public function insert_app_rev_audit_team_plan_records($tracksheet_id, $app_rev_form_id, $audit_auditor_records)
	    {
	    	$data = array(
	    			'tracksheet_id'=> $tracksheet_id,
	    			'app_rev_form_id'=> $app_rev_form_id,
	    			'sector'=> 1
	    		);

	    	$audit_auditor_records_count = count($audit_auditor_records);

	    	if($audit_auditor_records_count != 0)
	    	{
	    		foreach ($audit_auditor_records as $key => $audit_auditor_record)
		    	{	    	
		    		$data['level'] 		= $audit_auditor_record['level'];
		    		$data['auditor_id'] = $audit_auditor_record['auditor_id'];
		    		$data['type'] 		= $audit_auditor_record['type'];

		    		$query_run = $this->db->insert('ldt_app_rev_audit_team_plan', $data);		 
		    	}	    	
	    	}
	  
	    	return 1;
	    }

	//function to delete a specified row from ldt_app_rev_audit_team_plan table
		public function delete_row_in_app_rev_audit_team_plan($row_id)
	    {
	    	$this->db->where('id', $row_id);
	    	$query_run = $this->db->delete('ldt_app_rev_audit_team_plan');
	    	
	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }
	
	//function to get the team plan for a tracksheet
	    public function get_audit_team_plan_records($tracksheet_id)
	    {	    	
	    	$app_rev_audit_team_query = "SELECT ldt_app_rev_audit_team_plan.*, mdt_users.username FROM ldt_app_rev_audit_team_plan, mdt_users, ldt_audit_certificate WHERE ldt_app_rev_audit_team_plan.tracksheet_id = $tracksheet_id AND ldt_audit_certificate.ac_id = ldt_app_rev_audit_team_plan.auditor_id AND mdt_users.user_id = ldt_audit_certificate.user_id";
	        $app_rev_audit_team_query_run = $this->db->query($app_rev_audit_team_query);
	        $app_rev_audit_team_record = $app_rev_audit_team_query_run->result_array();

	        return $app_rev_audit_team_record;
	    }

	//function to get the team plan for a tracksheet for a particular stage(level)
	    public function get_audit_team_plan__stage_records($tracksheet_id, $level)
	    {
	    	$app_rev_audit_team_query = "SELECT ldt_app_rev_audit_team_plan.*, mdt_users.username FROM ldt_app_rev_audit_team_plan, mdt_users, ldt_audit_certificate WHERE ldt_app_rev_audit_team_plan.tracksheet_id = $tracksheet_id AND ldt_app_rev_audit_team_plan.level = $level AND ldt_audit_certificate.ac_id = ldt_app_rev_audit_team_plan.auditor_id AND mdt_users.user_id = ldt_audit_certificate.user_id";
	        $app_rev_audit_team_query_run = $this->db->query($app_rev_audit_team_query);
	        $app_rev_audit_team_record = $app_rev_audit_team_query_run->result_array();

	        return $app_rev_audit_team_record;
	    }

	//function to list all the reviewers for a particular level    
	    public function get_audit_team_plan_reviewers($level)
	    {	    	
	    	$app_rev_audit_team_query = "SELECT ldt_app_rev_audit_team_plan.*, mdt_users.username FROM ldt_app_rev_audit_team_plan, mdt_users, ldt_audit_certificate WHERE ldt_audit_certificate.ac_id = ldt_app_rev_audit_team_plan.auditor_id AND mdt_users.user_id = ldt_audit_certificate.user_id AND ldt_app_rev_audit_team_plan.level = $level AND ldt_app_rev_audit_team_plan.type = 3";
	        $app_rev_audit_team_query_run = $this->db->query($app_rev_audit_team_query);
	        $get_audit_team_plan_reviewers = $app_rev_audit_team_query_run->result_array();

	        return $get_audit_team_plan_reviewers;
	    }
	}
?>