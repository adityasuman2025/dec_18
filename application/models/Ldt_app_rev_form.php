<?php
	class Ldt_app_rev_form extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
	        $this->tableName = 'ldt_app_rev_form';
		}

	//function to insert general app rev form record into the database	
		public function insert_app_rev_gen_record($tracksheet_id, $cm_id, $application_form_id, $address_review_date, $site1_review_date, $site2_review_date, $site3_review_date, $scope, $assesment_standard, $assesment_type, $scope_clear, $total_emp_as_per, $perma_emp, $part_emp, $contract_lab, $temp_skill_un_worker, $total_eff_emp, $just_for_eff_pers, $no_of_sites, $repetitiveness, $complexity_level, $scope_size, $site_remarks, $accr_ava_as_req, $applicant_lang, $statuary_applicable, $safety_req, $threat_impart, $no_surv_audit_plan, $stage1_man_days, $stage2_man_days, $surv1_man_days, $surv2_man_days, $oth_reas_inc_tym, $oth_reas_desc_tym, $tym_change_warning, $reviewed_by_name, $apporved_by_name, $reviewed_by_date, $apporved_by_date)
		{
			$data = array(
					'tracksheet_id' => $tracksheet_id,
					'cm_id' => $cm_id,
					'application_form_id' => $application_form_id,
					'address_review_date' => $address_review_date,
					'site1_review_date' => $site1_review_date,
					'site2_review_date' => $site2_review_date,
					'site3_review_date' => $site3_review_date,
					'scope' => $scope,
					'assesment_standard' => $assesment_standard,
					'assesment_type' => $assesment_type,
					'scope_clear' => $scope_clear,
					'total_emp_as_per' => $total_emp_as_per,
					'perma_emp' => $perma_emp,
					'part_emp' => $part_emp,
					'contract_lab' => $contract_lab,
					'temp_skill_un_worker' => $temp_skill_un_worker,
					'total_eff_emp' => $total_eff_emp,
					'just_for_eff_pers' => $just_for_eff_pers,
					'no_of_sites' => $no_of_sites,
					'repetitiveness' => $repetitiveness,
					'complexity_level' => $complexity_level,
					'scope_size' => $scope_size,
					'site_remarks' => $site_remarks,

					'accr_ava_as_req' => $accr_ava_as_req,
					'applicant_lang' => $applicant_lang,
					'statuary_applicable' => $statuary_applicable,
					'safety_req' => $safety_req,
					'threat_impart' => $threat_impart,
					'no_surv_audit_plan' => $no_surv_audit_plan,
					'stage1_man_days' => $stage1_man_days,
					'stage2_man_days' => $stage2_man_days,
					'surv1_man_days' => $surv1_man_days,
					'surv2_man_days' => $surv2_man_days,
					'oth_reas_inc_tym' => $oth_reas_inc_tym,
					'oth_reas_desc_tym' => $oth_reas_desc_tym,
					'tym_change_warning' => $tym_change_warning,
					'reviewed_by_name' => $reviewed_by_name,
					'apporved_by_name' => $apporved_by_name,
					'reviewed_by_date' => $reviewed_by_date,
					'apporved_by_date' => $apporved_by_date,
					'just_for_eff_pers' => $just_for_eff_pers,
					'no_of_sites' => $no_of_sites,
					'repetitiveness' => $repetitiveness,
					'complexity_level' => $complexity_level,
					'scope_size' => $scope_size,
					'site_remarks' => $site_remarks,
					'status' => 1,
				);

			$query_run = $this->db->insert('ldt_app_rev_form', $data);

	        $query2 = "SELECT max(app_rev_form_id) AS max_id FROM ldt_app_rev_form";
	        $query2_run = $this->db->query($query2);
	        $result = $query2_run->result_array();

	        echo $result[0]['max_id'];
		}

	//function to list the application review required tracksheets
	    public function application_review()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id = 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }	

	//function to retrive info about the customer and tracksheet
	    public function application_review_form($tracksheet_id)
	    {
			$this->db->select('mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, mdt_customer_master.cb_type, mdt_customer_master.location, mdt_customer_master.contact_name, mdt_customer_master.contact_address, sdt_schemes.*');
	        $this->db->from('mdt_p_tracksheet, mdt_customer_master, sdt_schemes');
	        $this->db->where('mdt_p_tracksheet.tracksheet_id', $tracksheet_id);
	        $this->db->where('mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id');
	        $this->db->where('sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id');

	        $query = $this->db->get();
	        $result = $query->result();  
	        return $result;
	    }

	//function to retrive app_rev form record
	    public function app_rev_form_record($tracksheet_id)
	    {
			$this->db->select('ldt_app_rev_form.*');
	        $this->db->from('ldt_app_rev_form');
	        $this->db->where('ldt_app_rev_form.tracksheet_id', $tracksheet_id);

	        $query = $this->db->get();
	        $result = $query->result();  
	        return $result;
	    }

	//function to update general record of the app rev form
	    public function update_app_rev_gen_record($insArray, $tracksheet_id)
	    {
	    	$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->update('ldt_app_rev_form', $insArray);
	    }

	//function to update ldt_app_rev_enms_qstn table
		public function update_ldt_app_rev_enms_qstn($schemeSpecific, $tracksheet_id)
		{
			$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->update('ldt_app_rev_enms_qstn', $schemeSpecific);
		}

	//function to update ldt_app_rev_fsms_qstn table
		public function update_ldt_app_rev_fsms_qstn($schemeSpecific, $tracksheet_id)
		{
			$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->update('ldt_app_rev_fsms_qstn', $schemeSpecific);
		}

	//function to update ldt_app_rev_ohsas_qstn table
		public function update_ldt_app_rev_ohsas_qstn($schemeSpecific, $tracksheet_id)
		{
			$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->update('ldt_app_rev_ohsas_qstn', $schemeSpecific);
		}

	//function to update ldt_app_rev_ems_qstn table
		public function update_ldt_app_rev_ems_qstn($schemeSpecific, $tracksheet_id)
		{
			$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->update('ldt_app_rev_ems_qstn', $schemeSpecific);
		}

	//function to update ldt_app_rev_isms_qstn table
		public function update_ldt_app_rev_isms_qstn($schemeSpecific, $tracksheet_id)
		{
			$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->update('ldt_app_rev_isms_qstn', $schemeSpecific);
		}
	
	//function to list the re-certification tracksheets for which application review is pending
	    public function app_rev_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id >= 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 4";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id >= 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 4";							
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	}
?>