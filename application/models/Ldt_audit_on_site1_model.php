<?php
	class Ldt_audit_on_site1_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_audit_on_site';
	        $this->load->model('pagination_model');
		}

	//function to list the tracksheet for which audit on-site 1 pending    
		public function audit_on_site1()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 4 AND mdt_p_tracksheet.flow_id < 7) AND mdt_p_tracksheet.certification_type = 1 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 1 AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 4 AND mdt_p_tracksheet.flow_id < 7) AND mdt_p_tracksheet.certification_type = 1 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 1 AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to list the tracksheet for which report review is pending for a particular level
		public function list_to_review_audit_reports()
		{
			$userid = $_SESSION['userid'];
	        $searchText		=   $this->input->input_stream('searchText', TRUE); 

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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_notify_reviewer_about_audit_report.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_notify_reviewer_about_audit_report, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 5 OR mdt_p_tracksheet.flow_id = 10 OR mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_notify_reviewer_about_audit_report.ac_id AND ldt_audit_certificate.user_id = $userid AND ldt_notify_reviewer_about_audit_report.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_notify_reviewer_about_audit_report.done !=2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_notify_reviewer_about_audit_report.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_notify_reviewer_about_audit_report, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 5 OR mdt_p_tracksheet.flow_id = 10  OR mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_notify_reviewer_about_audit_report.ac_id AND ldt_audit_certificate.user_id = $userid AND ldt_notify_reviewer_about_audit_report.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_notify_reviewer_about_audit_report.done !=2 ";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list tracksheets to a auditor for which audit report 1 review is going on
		public function list_audit_report1_review()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText		=   $this->input->input_stream('searchText', TRUE); 

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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 5) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 1 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 5 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 1";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }    
	
	//function to list tracksheet for which NC 1 has not been cleared yet
	    public function list_nc1()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 6) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 1 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 6 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 1 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to list the tracksheet for which audit on-site 2 pending    
		public function audit_on_site2()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 9 AND mdt_p_tracksheet.flow_id < 12) AND mdt_p_tracksheet.certification_type = 1 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 9 AND mdt_p_tracksheet.flow_id < 12) AND mdt_p_tracksheet.certification_type = 1 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";							
			}

	        $query						=	$query." ORDER BY ldt_audit_plan_notify_to_auditor.id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to list the scope of certification for tracksheet assigned to that auditor
		public function list_fill_scope_of_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 10) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY ldt_audit_plan_notify_to_auditor.id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
	
	//function to list the client feedback for tracksheet assigned to that auditor
		public function list_client_feedback()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_client_feedback WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 12) AND ldt_client_feedback.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_client_feedback.level = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_client_feedback";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 12) AND ldt_client_feedback.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_client_feedback.level = 2";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
	
	//function to list tracksheets to a auditor for which audit report 2 review is going on
		public function list_audit_report2_review()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText		=   $this->input->input_stream('searchText', TRUE); 

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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 10) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 10 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }  
	
	//function to list tracksheet for which NC 2 has not been cleared yet
	    public function list_nc2()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 1";
				//
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 11 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list tracksheet for doing their re-audit on-site
	    public function list_re_audit_on_site()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 11 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 11 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 11 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to list the tracksheet for which audit on-site for surveillance pending    
		public function audit_on_site_surv()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 9 AND mdt_p_tracksheet.flow_id < 12) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 9 AND mdt_p_tracksheet.flow_id < 12) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
	
	//function to list tracksheets to a auditor for which review of audit report for surveillance is going on
		public function list_audit_report_surv_review()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText		=   $this->input->input_stream('searchText', TRUE); 

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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 10) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 10 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }    

	//function to list tracksheet for which NC for surveillance has not been cleared yet
	    public function list_nc_surv()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
				//
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 11 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list the scope of certification for tracksheet assigned to that auditor
		public function list_fill_scope_of_cert_surv()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 10) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";							
			}

	        $query						=	$query." ORDER BY ldt_audit_plan_notify_to_auditor.id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
	
	//function to list the client feedback for tracksheet for surveillance stage assigned to that auditor
		public function list_client_feedback_surv()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate, ldt_client_feedback WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 10) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND ldt_client_feedback.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_client_feedback.level = 3 OR ldt_client_feedback.level = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate, ldt_client_feedback";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 3 OR ldt_audit_plan_notify_to_auditor.level = 4) AND ldt_client_feedback.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_client_feedback.level = 3 OR ldt_client_feedback.level = 4)";							
			}

	        $query						=	$query." ORDER BY ldt_audit_plan_notify_to_auditor.id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
		
	//function to list the surveillance tracksheet for doing their re-audit on-site
	    public function list_re_audit_on_site_surv()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 11 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 11 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 11 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to list the tracksheet for which audit on-site for surveillance pending    
		public function audit_on_site_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 9 AND mdt_p_tracksheet.flow_id < 12) AND mdt_p_tracksheet.certification_type = 4 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 2) AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 9 AND mdt_p_tracksheet.flow_id < 12) AND mdt_p_tracksheet.certification_type = 4 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 2 OR ldt_audit_plan_notify_to_auditor.level = 4) AND ldt_audit_plan_notify_to_auditor.confid_agg_status = 3";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to list tracksheets to a auditor for which review of audit report for surveillance is going on
		public function list_audit_report_re_cert_review()
	    {
	    	$userid = $_SESSION['userid'];
	        $searchText		=   $this->input->input_stream('searchText', TRUE); 

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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 10) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 2) AND (mdt_p_tracksheet.certification_type = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 10 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 2) AND (mdt_p_tracksheet.certification_type = 4)";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }   
		
	//function to list tracksheet for which NC for surveillance has not been cleared yet
	    public function list_nc_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_notify_to_auditor.level = 2) AND (mdt_p_tracksheet.certification_type = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 11 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND AND (ldt_audit_plan_notify_to_auditor.level = 2) AND (mdt_p_tracksheet.certification_type = 4)";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list the scope of certification for re-certification tracksheet assigned to that auditor
		public function list_fill_scope_of_cert_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id >= 10) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 4";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 2 AND mdt_p_tracksheet.certification_type = 4";							
			}

	        $query						=	$query." ORDER BY ldt_audit_plan_notify_to_auditor.id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
	
	//function to list re-certification tracksheet for doing their re-audit on-site
	    public function list_re_audit_on_site_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND (mdt_p_tracksheet.flow_id = 11) AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 11 AND (mdt_p_tracksheet.certification_type = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_plan_notify_to_auditor, ldt_audit_certificate";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.flow_id = 11 AND ldt_audit_certificate.ac_id = ldt_audit_plan_notify_to_auditor.auditor_id AND ldt_audit_certificate.user_id = $userid AND ldt_audit_plan_notify_to_auditor.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_notify_to_auditor.level = 11 AND (mdt_p_tracksheet.certification_type = 4)";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	}
?>