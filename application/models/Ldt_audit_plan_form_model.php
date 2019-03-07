<?php
	class Ldt_audit_plan_form_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
	        $this->tableName = 'ldt_audit_plan_form';
		}

	//function to list the tracksheets for which audit plan 1 is pending
	    public function audit_plan1()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS" OR $searchText == "eas")		
				$cb_type = 1;
			else if($searchText == "IAS" OR $searchText == "ias")
				$cb_type = 2;

		//searching specific cert_type
			$cert_type_text = 0;
			if($searchText == "Cert" OR $searchText == "cert")
				$cert_type_text = 1;
			else if($searchText == "S1" OR $searchText == "s1")
				$cert_type_text = 2;
			else if($searchText == "S2" OR $searchText == "s2")
				$cert_type_text = 3;
			else if($searchText == "RC" OR $searchText == "rc")
				$cert_type_text = 4;

		//preparing filter search query
			if($searchText =='')
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 3 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 3 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to insert audit plan form record in database
	    public function insert_audit_plan_form_record($tracksheet_id, $level)
	    {
	    	$userid = $_SESSION['userid'];

	    	$data = array(
	    			'tracksheet_id' => $tracksheet_id,
	    			'level' => $level,
	    			'form_filled' => 1,
	    			'sent_to_auditor' => 2,
	    			'sent_to_client' => 2,
	    			'created_by' => $userid,
	    			'created_on' => date('Y-m-d'),
	    			'status' => 1,
	    		);
	    
	    	$query_run = $this->db->insert('ldt_audit_plan_form', $data);

	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }

	//function to update sent status of the audit plan for a level of a tracksheet
	    public function update_audit_plan_sent_status($tracksheet_id, $level, $to_update, $value)
	    {
	    	$data = array(
	    			$to_update => $value
	    		);

	    	$this->db->where('tracksheet_id', $tracksheet_id);
	    	$this->db->where('level', $level);
	    	$query_run = $this->db->update('ldt_audit_plan_form', $data);
	    	
	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }
	
	//function to list all the audit plans for a particular auditor
		public function list_my_audit_plan1()
		{
			$userid = $_SESSION['userid'];
	        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS" OR $searchText == "eas")		
				$cb_type = 1;
			else if($searchText == "IAS" OR $searchText == "ias")
				$cb_type = 2;

		//searching specific cert_type
			$cert_type_text = 0;
			if($searchText == "Cert" OR $searchText == "cert")
				$cert_type_text = 1;
			else if($searchText == "S1" OR $searchText == "s1")
				$cert_type_text = 2;
			else if($searchText == "S2" OR $searchText == "s2")
				$cert_type_text = 3;
			else if($searchText == "RC" OR $searchText == "rc")
				$cert_type_text = 4;

		//preparing filter search query
			if($searchText =='')
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_auditor.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id >= 3 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id ";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_plan_notify_to_auditor.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id >= 3 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id ";							
			}

	        $query						=	$query." ORDER BY ldt_audit_plan_notify_to_auditor.id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}   
	
	//function to list the tracksheets for which audit plan 2 is pending
	    public function audit_plan2()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS" OR $searchText == "eas")		
				$cb_type = 1;
			else if($searchText == "IAS" OR $searchText == "ias")
				$cb_type = 2;

		//searching specific cert_type
			$cert_type_text = 0;
			if($searchText == "Cert" OR $searchText == "cert")
				$cert_type_text = 1;
			else if($searchText == "S1" OR $searchText == "s1")
				$cert_type_text = 2;
			else if($searchText == "S2" OR $searchText == "s2")
				$cert_type_text = 3;
			else if($searchText == "RC" OR $searchText == "rc")
				$cert_type_text = 4;

		//preparing filter search query
			if($searchText =='')
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name,  sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 8 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 8 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to list all the audit plan for a particular level
	    public function listing_audit_plan_for_level($level)
	    {
	    	$this->db->select('tracksheet_id');
	    	$this->db->from('ldt_audit_plan_form');
	    	$this->db->where('level', $level);

	    	$query_run = $this->db->get();
	    	$result = $query_run->result_array();

	    	return $result;
	    }
	
	//function to list the tracksheets for which audit plan of surveillance is pending
	    public function audit_plan_surv()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS" OR $searchText == "eas")		
				$cb_type = 1;
			else if($searchText == "IAS" OR $searchText == "ias")
				$cb_type = 2;

		//searching specific cert_type
			$cert_type_text = 0;
			if($searchText == "Cert" OR $searchText == "cert")
				$cert_type_text = 1;
			else if($searchText == "S1" OR $searchText == "s1")
				$cert_type_text = 2;
			else if($searchText == "S2" OR $searchText == "s2")
				$cert_type_text = 3;
			else if($searchText == "RC" OR $searchText == "rc")
				$cert_type_text = 4;

		//preparing filter search query
			if($searchText =='')
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, mdt_customer_master.client_name,  sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 8 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 8 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";							
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list the tracksheets for which audit plan of re-certification is pending
	    public function audit_plan_re_cert()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS" OR $searchText == "eas")		
				$cb_type = 1;
			else if($searchText == "IAS" OR $searchText == "ias")
				$cb_type = 2;

		//searching specific cert_type
			$cert_type_text = 0;
			if($searchText == "Cert" OR $searchText == "cert")
				$cert_type_text = 1;
			else if($searchText == "S1" OR $searchText == "s1")
				$cert_type_text = 2;
			else if($searchText == "S2" OR $searchText == "s2")
				$cert_type_text = 3;
			else if($searchText == "RC" OR $searchText == "rc")
				$cert_type_text = 4;

		//preparing filter search query
			if($searchText =='')
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name,  sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 8 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND (mdt_p_tracksheet.certification_type = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 8 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND (mdt_p_tracksheet.certification_type = 4)";							
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	}
?>