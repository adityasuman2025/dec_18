<?php
	class Ldt_audit_program_form_model extends CI_Model
	{
		function __construct() 
		{
	        $this->load->model('pagination_model');
            $this->tableName        =   'ldt_audit_program_form';
		}

	//function to insert data into dtabase of audit program form by planning department
	    public function insert_data_in_audit_program_form_planning($tracksheet_id, $cm_id, $no_of_sites, $stage1_auditor_sector, $stage1_date_from, $stage1_date_to, $stage2_auditor_sector, $stage2_date_from, $stage2_date_to, $surv1_auditor_sector, $surv1_date_from, $surv1_date_to, $surv2_auditor_sector, $surv2_date_from, $surv2_date_to)
	    {
	    	$userid = $_SESSION['userid'];

    		$data = array(
    			'tracksheet_id' => $tracksheet_id,
    			'cm_id' => $cm_id,
    			'no_of_sites' => $no_of_sites,
    			'dates_filled' => 1,

    			'stage1_auditor_sector' => $stage1_auditor_sector,
    			'stage1_date_from' => $stage1_date_from,
    			'stage1_date_to' => $stage1_date_to,
    			'stage2_auditor_sector' => $stage2_auditor_sector,
    			'stage2_date_from' => $stage2_date_from,
    			'stage2_date_to' => $stage2_date_to,
    			'surv1_auditor_sector' => $surv1_auditor_sector,
    			'surv1_date_from' => $surv1_date_from,
    			'surv1_date_to' => $surv1_date_to,
    			'surv2_auditor_sector' => $surv2_auditor_sector,
    			'surv2_date_from' => $surv2_date_from,
    			'surv2_date_to' => $surv2_date_to,

    			'created_by' => $userid,
	    		'created_on' => date('Y-m-d'),
    			'status'=>1
    		);

    		$query_run = $this->db->insert('ldt_audit_program_form', $data);

	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }	

	//function to update data into database of audit program form by planning department
	    public function update_data_in_audit_program_form_planning($audit_prog_form_id, $stage1_auditor_sector, $stage1_date_from, $stage1_date_to, $stage2_auditor_sector, $stage2_date_from, $stage2_date_to, $surv1_auditor_sector, $surv1_date_from, $surv1_date_to, $surv2_auditor_sector, $surv2_date_from, $surv2_date_to)
	    {
	    	$userid = $_SESSION['userid'];

	    	$data = array(
	    			'stage1_auditor_sector' => $stage1_auditor_sector,
	    			'stage1_date_from' => $stage1_date_from,
	    			'stage1_date_to' => $stage1_date_to,
	    			'stage2_auditor_sector' => $stage2_auditor_sector,
	    			'stage2_date_from' => $stage2_date_from,
	    			'stage2_date_to' => $stage2_date_to,
	    			'surv1_auditor_sector' => $surv1_auditor_sector,
	    			'surv1_date_from' => $surv1_date_from,
	    			'surv1_date_to' => $surv1_date_to,
	    			'surv2_auditor_sector' => $surv2_auditor_sector,
	    			'surv2_date_from' => $surv2_date_from,
	    			'surv2_date_to' => $surv2_date_to,
	    			'edited_by' => $userid,
	    			'edited_on' => date('Y-m-d')
	    		);

	    	$this->db->where('audit_prog_form_id', $audit_prog_form_id);
	    	$query_run = $this->db->update('ldt_audit_program_form', $data);
	    	
	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }

	//function to get the records of a particular audit program form by planning department
	    public function get_audit_program_form_records($tracksheet_id)
	    {
	    	$query = "SELECT * FROM ldt_audit_program_form WHERE tracksheet_id = $tracksheet_id";
	    	$query_run = $this->db->query($query);
	    	$result = $query_run->result_array();

	    	return $result;
	    }
	
	//function to list the dated audit program form belonging to that technical guy
	    public function technical_list_dated_audit_program_form()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, ldt_audit_program_form.tracksheet_id ,sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, ldt_audit_program_form, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id >= 2 AND ldt_audit_program_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, ldt_audit_program_form.tracksheet_id ,sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, ldt_audit_program_form, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id >= 2 AND ldt_audit_program_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 1";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to fill any_add_reso_req in the audit program form for a tracksheet_id
        public function fill_any_add_reso_req_in_audit_program($tracksheet_id, $any_add_reso_req)
        {
        	$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->set('any_add_reso_req', $any_add_reso_req);
        	$query_run = $this->db->update('ldt_audit_program_form');
        	
        	if($query_run)
        		return 1;
        	else
        		return 0;
        }
	
    //function to update intimation status of that tracksheet
        public function update_intimation_status($tracksheet_id, $stage)
        {
        	$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->set('intimation_status', $stage);
        	$query_run = $this->db->update('ldt_audit_program_form');

        	if($query_run)
        		return 1;
        	else
        		return 0;
        }

    //function to list the dated audit program form of re-certification tracksheet belonging to that technical guy
	    public function technical_list_dated_audit_program_form_re_cert()
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id,  mdt_customer_master.client_name, ldt_audit_program_form.tracksheet_id ,sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, ldt_audit_program_form, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id >= 2 AND ldt_audit_program_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 4";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, ldt_audit_program_form.tracksheet_id ,sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, ldt_audit_program_form, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND flow_id >= 2 AND ldt_audit_program_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2 AND mdt_p_tracksheet.certification_type = 4";							
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	}
?>