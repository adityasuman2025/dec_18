<?php
	class Ldt_audit_report_summary_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_audit_report_summary';
	        $this->load->model('pagination_model');
		}

	//function to get records for a particular trackshhet_id and level
		public function get_audit_summary_records($tracksheet_id, $level)
		{
			$this->db->select('*');
			$this->db->from('ldt_audit_report_summary');
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->where('level', $level);

			$query_run = $this->db->get();

			$result = $query_run->result_array();
			return $result;
		}

	//function to insert or update audit summary report
		public function update_insert_report_summary_data($tracksheet_id, $level, $count_minor_nc, $count_major_nc, $stage_of_audit, $recomm, $reason, $surv_date, $date, $cm_id)
		{
			$data = array(
				'minor' => $count_minor_nc,
				'major' => $count_major_nc,
				'stage' => $stage_of_audit,
				'recomm' => $recomm,
				'reason' => $reason,
				'surv_date' => $surv_date,
				'date' => $date,
				'cm_id' => $cm_id,
				'status' => 1
			);

			$this->db->select('id');
			$this->db->from('ldt_audit_report_summary');
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->where('level', $level);

			$query_run1 = $this->db->get();
			$count1 = $query_run1->num_rows();

			if($count1 == 1)
			{
				$this->db->where('tracksheet_id', $tracksheet_id);
				$this->db->where('level', $level);
				$query_run = $this->db->update('ldt_audit_report_summary', $data);
			}
			else
			{
				$data['tracksheet_id'] = $tracksheet_id;
				$data['level'] = $level;
				$query_run = $this->db->insert('ldt_audit_report_summary', $data);
			}

			if($query_run)
				echo 1;
			else
				echo 0;
		}
	
	//function to update the approved_by_reviewer status
		public function approved_by_reviewer_report_summary($row_id)
		{
			$this->db->where('id', $row_id);
			$this->db->set('approved_by_reviewer', 2);
			$query_run = $this->db->update('ldt_audit_report_summary');

			if($query_run)
				echo 1;
			else
				echo 0;
		}

	//function to list tracksheet for which re-audit has been marked
		public function list_re_audit()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND mdt_p_tracksheet.certification_type =1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND mdt_p_tracksheet.certification_type =1";							
			}

	        $query						=	$query." ORDER BY ldt_audit_report_summary.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to notify technical about re-audit
	    public function notify_to_technical_about_re_audit($id)
	    {
	    	$this->db->where('id', $id);
			$this->db->set('notify_to_technical', 2);
			$query_run = $this->db->update('ldt_audit_report_summary');

			if($query_run)
				echo 1;
			else
				echo 0;
	    }

	//function to update re-audit date for that report summary
		public function update_re_audit_date($row_id, $re_audit_date)
		{
			$this->db->where('id', $row_id);
			$this->db->set('re_audit_date', $re_audit_date);
			$query_run = $this->db->update('ldt_audit_report_summary');

			if($query_run)
				echo 1;
			else
				echo 0;
		} 

	//function to list tracksheet for which re-audit has been marked in case of surveillance
		public function list_re_audit_surv()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";							
			}

	        $query						=	$query." ORDER BY ldt_audit_report_summary.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}  

	//function to list tracksheet for which re-audit has been marked in case of re-certification
		public function list_re_audit_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 4)";							
			}

	        $query						=	$query." ORDER BY ldt_audit_report_summary.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}  

	//function to list tracksheet for which re-audit has been marked in case of re-certification to the technical team
		public function tech_list_re_audit_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary, mdt_assigned_tracksheet_users WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 4) AND ldt_audit_report_summary.notify_to_technical = 2 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary, mdt_assigned_tracksheet_users";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 4) AND ldt_audit_report_summary.notify_to_technical = 2 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";							
			}

	        $query						=	$query." ORDER BY ldt_audit_report_summary.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}  

	//function to list tracksheet for which re-audit has been marked in case of surveillance to the technical team
		public function tech_list_re_audit_surv()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary, mdt_assigned_tracksheet_users WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3) AND ldt_audit_report_summary.notify_to_technical = 2 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary, mdt_assigned_tracksheet_users";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3) AND ldt_audit_report_summary.notify_to_technical = 2 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";							
			}

	        $query						=	$query." ORDER BY ldt_audit_report_summary.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}  

	//function to list tracksheet for which re-audit has been marked to the technical team
		public function tech_list_re_audit()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary, mdt_assigned_tracksheet_users WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 1) AND ldt_audit_report_summary.notify_to_technical = 2 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_audit_report_summary.* FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_audit_report_summary, mdt_assigned_tracksheet_users";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_audit_report_summary.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_report_summary.recomm = 3 AND ldt_audit_report_summary.reason = 3 AND ldt_audit_report_summary.approved_by_reviewer = 2 AND (mdt_p_tracksheet.certification_type = 1) AND ldt_audit_report_summary.notify_to_technical = 2 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";							
			}

	        $query						=	$query." ORDER BY ldt_audit_report_summary.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}  
	}
?>