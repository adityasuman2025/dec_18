<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Mdt_p_tracksheet extends CI_Model 
	{
		function __construct() 
		{
			$this->tableName = 'mdt_p_tracksheet';
		}
		
	//function to view the create tracksheet page
		public function create_tracksheet_page($data_id)
		{
			$this->db->select('*');
			$this->db->from('mdt_customer_master');
			$this->db->where('cm_id', $data_id);

	        $query = $this->db->get();
	        $result = $query->result();
	        return $result;
		}

	//function to create (or start) a new tracksheet
		public function create_new_tracksheet($send)
		{
			$send['flow_id'] = 1;
			$send['status'] = 1;

			$query_run = $this->db->insert('mdt_p_tracksheet', $send);

	        if($query_run)
	            return 1;
	        else 
	            return 0;
		}

	//function to list all the tracksheets to the planning team
		public function list_tracksheet()
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type =1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type =1";
				
				//$query						=	$query ." WHERE cb_type = '$cb_type'";
			}

		//preparing for special list_tracksheet from the list_customer page			
			$special_cm_id                     =   $this->input->input_stream('special_cm_id', TRUE); 

			if($special_cm_id !='')
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type =1 AND mdt_p_tracksheet.cm_id = '" . $special_cm_id . "' ";
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to view details of a tracksheet
		public function view_tracksheet_info($data_id)
		{
			$this->db->select('mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, mdt_customer_master.cb_type, mdt_customer_master.location, mdt_customer_master.contact_name, mdt_customer_master.contact_address, sdt_schemes.scheme_name, sdt_schemes.scheme_system, sdt_tracksheet_flow.tsf_name, mdt_customer_contact.contact_number, mdt_customer_contact.contact_email');
	        $this->db->from('mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, mdt_customer_contact');
	        $this->db->where('mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id');
	        $this->db->where('mdt_customer_contact.cm_id = mdt_p_tracksheet.cm_id');
	        $this->db->where('sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id');
	        $this->db->where('sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id');
	        $this->db->where('mdt_p_tracksheet.tracksheet_id', $data_id);
	        

	        $query = $this->db->get();
	        $result = $query->result();  
	        return $result;
		}

	//function to list all the tracksheets of a partcular technical employee
		public function list_my_tracksheet_page($userid)
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
			if($searchText == '')
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_assigned_tracksheet_users, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
				
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
	
	//function to list all the tracksheet flow
		public function list_tracksheet_flow()
        {        	
            $query = "SELECT tsf_name FROM sdt_tracksheet_flow ORDER BY tsf_id";
            $query_run = $this->db->query($query);
            $result = $query_run->result_array();  

            return $result;
        }

    //function to update the tech_emp_assigned status of that tracksheet  
        public function update_tech_emp_assigned_status($tracksheet_id)
        {
        	$data = array(
        		'tech_emp_assigned' => 2
        	);

        	$this->db->where('tracksheet_id', $tracksheet_id);
        	$query_run = $this->db->update('mdt_p_tracksheet', $data);
        }
	
    //function to list the filled application review form
        public function list_filled_app_rev_form()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND mdt_p_tracksheet.certification_type =1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND mdt_p_tracksheet.certification_type =1";
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

    //function to list all the tracksheets for which filling of audit program form is pending
        public function fill_audit_program()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 1";
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }
	
	//function to increase the flow status of the tracksheet
        public function incr_flow_status_of_tracksheet($tracksheet_id)
        {
        	$this->db->select('flow_id');
        	$this->db->from('mdt_p_tracksheet');
        	$this->db->where('tracksheet_id', $tracksheet_id);

        	$query1_run = $this->db->get();
        	$result_array1 = $query1_run->result_array();

        	$old_flow_id = $result_array1[0]['flow_id'];
        	$new_flow_id = $old_flow_id + 1;

        	$data = array(
        		'flow_id' => $new_flow_id
        	);

        	$this->db->where('tracksheet_id', $tracksheet_id);
        	$query2_run = $this->db->update('mdt_p_tracksheet', $data);

        	if($query2_run)
        		return 1;
        	else
        		return 0;
        }

    //function to increase the flow status of a trackksheet to a desired flow step
        public function incr_desired_flow_status_of_tracksheet($tracksheet_id, $flow_id)
        {        	
        	$data = array(
        		'flow_id' => $flow_id
        	);

        	$this->db->where('tracksheet_id', $tracksheet_id);
        	$query2_run = $this->db->update('mdt_p_tracksheet', $data);

        	if($query2_run)
        		return 1;
        	else
        		return 0;
        }
	
    //function to list the filled audit plan forms to the planning department
        public function list_filled_audit1_plan_form()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form  WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 4 AND ldt_audit_plan_form.level = 1 AND mdt_p_tracksheet.certification_type =1 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 4 AND ldt_audit_plan_form.level = 1 AND mdt_p_tracksheet.certification_type =1 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }
	
    //function to get general records of a tracksheet and customer of that tracksheet
        public function get_tracksheet_gen_records($tracksheet_id)
        {
        	$this->db->select('mdt_p_tracksheet.cm_id, mdt_customer_master.*');
        	$this->db->from('mdt_p_tracksheet, mdt_customer_master');
        	$this->db->where('tracksheet_id', $tracksheet_id);
        	$this->db->where('mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id');

        	$query_run = $this->db->get();
	        $result = $query_run->result_array();
	        
	        return $result;
        }
	
    //function to list the tracksheets for which audit report 1 has been submitted
	    public function list_submitted_audit_report_1()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 5 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id  AND ldt_audit_plan_form.level = 1 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 5 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id  AND ldt_audit_plan_form.level = 1 AND mdt_p_tracksheet.certification_type = 1";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to render the page to list all the tracksheet for which stage 1 audit has been done
	    public function list_stage1_audit_done_tracksheet()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 7 AND ldt_audit_plan_form.level = 1 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 7 AND ldt_audit_plan_form.level = 1 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to list the filled audit plan forms to the planning department
        public function list_filled_audit2_plan_form()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form  WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 9 AND ldt_audit_plan_form.level = 2 AND mdt_p_tracksheet.certification_type =1 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 9 AND ldt_audit_plan_form.level = 2 AND mdt_p_tracksheet.certification_type =1 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

    //function to list the tracksheets for which audit report 2 has been submitted
	    public function list_submitted_audit_report_2()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_form.level = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_form.level = 2 AND mdt_p_tracksheet.certification_type = 1";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to list the filled re-audit plan forms to the planning department
        public function list_filled_re_audit_plans()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form  WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.level = 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.level = 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_p_tracksheet.certification_type = 1";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }
	
   	//function to list the tracskheet for which certificate issue checklist is pending
    	public function list_certi_issue_checklist()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 12 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 12 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to fill the certificate issue checklist for a tracksheet
	    public function fill_certi_issue_checklist($tracksheet_id)
	    {
	    	$this->db->select('mdt_p_tracksheet.*,  mdt_customer_master.*, sdt_schemes.scheme_name, ldt_scope_of_cert.scope');
	        $this->db->from('mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_scope_of_cert');
	        $this->db->where('mdt_p_tracksheet.tracksheet_id', $tracksheet_id);
	        $this->db->where('mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id');
	        $this->db->where('sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id');
	        $this->db->where('ldt_scope_of_cert.tracksheet_id = mdt_p_tracksheet.tracksheet_id');

	        $query = $this->db->get();
	        $result = $query->result();  
	        return $result;
	    }

	//function to list the filled tracksheet for which approval from MD of certificate issue checklistis pending
    	public function list_filled_certi_issue_checklist()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_certi_issue_checklist.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_certi_issue_checklist WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 12 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_certi_issue_checklist.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_certi_issue_checklist.approved_by_md != 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name, ldt_certi_issue_checklist.level FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow, ldt_certi_issue_checklist";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . "AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 12 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND ldt_certi_issue_checklist.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_certi_issue_checklist.approved_by_md != 2";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to update certifciation dates for a tracksheet
	    public function update_certification_dates_for_tracsheet($tracksheet_id, $level)
	    {
	    	$data = array();

	    	if($level == 1 OR $level == 2)
	    	{
	    		$data['initial_certification_date'] = date('Y-m-d');
	    		$data['cert_date_from'] = date('Y-m-d');
	    		$data['cert_date_to'] = date('Y-m-d', strtotime('+1 years'));
	    	}
	    	else
	    	{
	    		$data['cert_date_from'] = date('Y-m-d');
	    		$data['cert_date_to'] = date('Y-m-d', strtotime('+1 years'));
	    	}

	    //increasing flow of that tracksheet
	    	$this->incr_flow_status_of_tracksheet($tracksheet_id);

	 		$this->db->where('tracksheet_id', $tracksheet_id);
	 		$query_run = $this->db->update('mdt_p_tracksheet', $data);

	 		if($query_run)
	 			echo 1;
	 		else
	 			echo 0;
	    }

	//function to list the tracskheet for which certificate issue checklist is pending
    	public function list_certificate()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 13 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 13 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }
	
	//function to notify account section about the completed certification process
	    public function notify_acc_sec($tracksheet_id)
	    {
	    	$this->db->where('tracksheet_id', $tracksheet_id);
	    	$this->db->set('acc_sec_notified', 1);
	 		$query_run = $this->db->update('mdt_p_tracksheet');

	 		if($query_run)
	 			return 1;
	 		else
	 			return 0;
	    }

	//funtion to get basic info of a tracksheet
		public function get_tracksheet_basic_info($tracksheet_id)
		{
			$query = "SELECT mdt_p_tracksheet.*, mdt_customer_master.*, mdt_customer_contact.* FROM mdt_p_tracksheet, mdt_customer_master, mdt_customer_contact WHERE tracksheet_id = $tracksheet_id AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND mdt_customer_contact.cm_id =  mdt_p_tracksheet.cm_id";        
        	
        	$query_run = $this->db->query($query);
        	$result = $query_run->result();

        	return $result;
		}

	//function to list all the surveillance tracksheets
		public function list_surveillance_tracksheets()
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 
			
			$start_date = strtotime(date('Y-m'));
			$start_date = date('Y-m-d', $start_date);
			$end_date = strtotime(date('Y-m-d')) + 5616000;
			$end_date = date('Y-m-d', $end_date);

			$today = date('Y-m-d');

			$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name FROM mdt_p_tracksheet, mdt_customer_master WHERE cert_date_to >= '" . $start_date . "' AND cert_date_to <= '" . $end_date . "' AND (certification_type = 1 OR certification_type = 2 OR certification_type = 4) AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id";
	        $query						=	$query." ORDER BY cert_date_to";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}
		
	//updating new_ts_started status and surveillance_notify status of that tracksheet
		public function update_surveillance_status($tracksheet_id, $notify_no)
		{
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->set('surveillance_notify', $notify_no);
			$this->db->set('new_ts_started', 1);
			$query_run = $this->db->update('mdt_p_tracksheet');

			if($query_run)
				return 1;
			else
				return 0;
		}

	//function to create new trackhsheet for surveillance
		public function create_surv_tracksheet($tracksheet_id, $notify_no)
        {
        	$this->db->select('*');
        	$this->db->from('mdt_p_tracksheet');
        	$this->db->where('tracksheet_id', $tracksheet_id);

        	$query_run = $this->db->get();
        	$result = $query_run->result_array();
        	$data1 = $result[0];

        	$data['cm_id'] 			= $data1['cm_id'];
        	$data['cb_type'] 		= $data1['cb_type'];
        	$data['track_month'] 	= $data1['track_month'];
        	$data['track_year'] 	= date('Y');
        	$data['track_date']	 	= date('Y-m-d');
        	$data['scheme_id'] 		= $data1['scheme_id'];        
        	$data['initial_certification_date'] = $data1['initial_certification_date'];
        	$data['cert_date_from'] = $data1['cert_date_from'];
        	$data['cert_date_to'] 	= $data1['cert_date_to'];
        	$data['scope'] 			= $data1['scope'];        	
        	$data['status'] 		= 2;

        	if($data1['certification_type'] == 1) //initial cert
    		{
    			$data['certification_type'] = 2; //S1
    			$data['old_tracksheet_id'] = $tracksheet_id;
    			$data['flow_id'] = 8;
    		}
    		else if($data1['certification_type'] == 2) //S1
    		{
    			$data['certification_type'] = 3; //S2
    			$data['old_tracksheet_id'] = $data1['old_tracksheet_id'];
    			$data['flow_id'] = 8;
    		}
    		else if($data1['certification_type'] == 3) //S2
    		{
    			$data['certification_type'] = 4; //RC
    			$data['old_tracksheet_id'] = $tracksheet_id;
    			$data['flow_id'] = 1;
    		}
    		else if($data1['certification_type'] == 4) //RC
    		{
    			$data['certification_type'] = 2; //S1
    			$data['old_tracksheet_id'] = $tracksheet_id;
    			$data['flow_id'] = 8;
    		}
        	
        	$query_run = $this->db->insert('mdt_p_tracksheet', $data);

        	if($query_run)
        		return 1;
        	else
        		return 0;
        }

    //function to list all the surveillance tracksheets to the planning team
		public function list_s_tracksheet()
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id,  mdt_customer_master.client_name, mdt_customer_contact.contact_email, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, mdt_customer_contact, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND mdt_customer_contact.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 2 ";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 2";
				
				//$query						=	$query ." WHERE cb_type = '$cb_type'";
			}

		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to edit scope for a tracksheet
	    public function edit_tracksheet_scope($tracksheet_id, $scope)
	    {
	    	$this->db->where('tracksheet_id', $tracksheet_id);
	    	$this->db->set('scope', $scope);

	    	$query_run = $this->db->update('mdt_p_tracksheet');

	    	if($query_run)
	    		return 1;
	    	else
	    		return 0;
	    }

	//updating the tracksheet status 
		public function update_tracksheet_status($tracksheet_id, $status)
		{
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->set('status', $status);

			$query_run = $this->db->update('mdt_p_tracksheet');

			if($query_run)
				return 1;
			else
				return 0;
		}
	
	//function to list the filled audit plan surv forms to the planning department
        public function list_filled_audit_plan_surv_form()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form  WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 9 AND (ldt_audit_plan_form.level = 3 OR ldt_audit_plan_form.level = 4) AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 9 AND (ldt_audit_plan_form.level = 3 OR ldt_audit_plan_form.level = 4) AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }
	
	//function to list the tracksheets for which audit report for surveillance has been submitted
        public function list_submitted_audit_report_surv()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 3 OR ldt_audit_plan_form.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 3 OR ldt_audit_plan_form.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

    //function to list the tracksheets for which audit report for surveillance has been submitted
        public function list_change_of_scope_req()
        {
        	$userid = $_SESSION['userid'];

        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, ldt_intimation_of_changes.technical_accept_scope_change FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_intimation_of_changes, mdt_assigned_tracksheet_users WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND ldt_intimation_of_changes.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_intimation_of_changes.notify_technical_of_scope_change = 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, ldt_intimation_of_changes.technical_accept_scope_change FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_intimation_of_changes, mdt_assigned_tracksheet_users";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND ldt_intimation_of_changes.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_intimation_of_changes.notify_technical_of_scope_change = 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }
	
    //function to list the filled re-audit plan forms to the planning department
        public function list_filled_re_audit_plans_surv()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form  WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.level = 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.level = 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

    //function to list the tracksheets to MD for which tracksheet status change request is done
        public function list_tracksheet_status_change_req()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_tracksheet_status.tracksheet_id, ldt_tracksheet_status.md_approval_req, ldt_tracksheet_status.md_approved, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_tracksheet_status WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND ldt_tracksheet_status.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_tracksheet_status.md_approval_req = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_tracksheet_status.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_tracksheet_status";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND ldt_tracksheet_status.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_tracksheet_status.md_approval_req = 1";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;	
        }

    //function to list all the surveillance tracksheets
		public function list_re_certification_tracksheet()
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 
			
			$start_date = strtotime(date('Y-m'));
			$start_date = date('Y-m-d', $start_date);
			$end_date = strtotime(date('Y-m-d')) + 5616000;
			$end_date = date('Y-m-d', $end_date);

			$today = date('Y-m-d');

			$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name FROM mdt_p_tracksheet, mdt_customer_master WHERE cert_date_to >= '" . $start_date . "' AND cert_date_to <= '" . $end_date . "' AND certification_type = 3 AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id ";
	        $query						=	$query." ORDER BY cert_date_to";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to list all the surveillance tracksheets to the planning team
		public function list_re_tracksheet()
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name,  sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 4";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%' OR sdt_tracksheet_flow.tsf_name LIKE '%" . $searchText . "%' )";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 4";
				
				//$query						=	$query ." WHERE cb_type = '$cb_type'";
			}

		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to list the filled application review form
        public function list_filled_app_rev_re_cert()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND mdt_p_tracksheet.certification_type = 4";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND mdt_p_tracksheet.certification_type = 4";
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

      //function to list all the re-certificarion tracksheets for which filling of audit program form is pending
        public function fill_audit_program_re_cert()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 4";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name, sdt_tracksheet_flow.tsf_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, sdt_tracksheet_flow";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 2 AND sdt_tracksheet_flow.tsf_id = mdt_p_tracksheet.flow_id AND mdt_p_tracksheet.certification_type = 4";
			}

	        $query						=	$query." ORDER BY tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

    //function to list the filled audit plan re-certification forms to the planning department
        public function list_filled_audit_plan_form_re_cert()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id,mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form  WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 9 AND (ldt_audit_plan_form.level = 2) AND mdt_p_tracksheet.certification_type = 4 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 9 AND (ldt_audit_plan_form.level = 2) AND mdt_p_tracksheet.certification_type = 4 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

    //function to list the tracksheets for which audit report for re-certification has been submitted
        public function list_submitted_audit_report_re_cert()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 2) AND mdt_p_tracksheet.certification_type = 4";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 10 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 2) AND mdt_p_tracksheet.certification_type = 4";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

     //function to list the filled re-audit plan forms to the planning department
        public function list_filled_re_audit_plan_re_cert()
        {
        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form  WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.level = 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (mdt_p_tracksheet.certification_type = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.level = 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND AND (mdt_p_tracksheet.certification_type = 4)";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
        }

    //function to list the tracksheets for which re-audit report has been submitted
	    public function list_submitted_re_audit_report()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_form.level = 2 AND mdt_p_tracksheet.certification_type = 1";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_form.level = 2 AND mdt_p_tracksheet.certification_type = 1";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list the re-certification tracksheets for which re-audit report has been submitted
	    public function list_submitted_re_audit_report_re_cert()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 2) AND (mdt_p_tracksheet.certification_type = 4)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 2) AND (mdt_p_tracksheet.certification_type = 4)";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list the surveillance tracksheets for which re-audit report has been submitted
	    public function list_submitted_re_audit_report_surv()
	    {
	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 3 OR ldt_audit_plan_form.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.*, mdt_customer_master.client_id, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 11 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND (ldt_audit_plan_form.level = 3 OR ldt_audit_plan_form.level = 4) AND (mdt_p_tracksheet.certification_type = 2 OR mdt_p_tracksheet.certification_type = 3)";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list all the audit reports to the technical team
	    public function list_audit_reports()
	    {
	    	$userid = $_SESSION['userid'];

	    	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.level, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form, mdt_assigned_tracksheet_users WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 7 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_form.level != 11 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, ldt_audit_plan_form.level, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_audit_plan_form, mdt_assigned_tracksheet_users";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND mdt_p_tracksheet.flow_id >= 12 AND ldt_audit_plan_form.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_audit_plan_form.level != 11";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
	    }

	//function to list the tracksheets for which audit report for surveillance has been submitted
        public function list_scope_of_cert_req()
        {
        	$userid = $_SESSION['userid'];

        	$searchText                     =   $this->input->input_stream('searchText', TRUE); 

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
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
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name,ldt_scope_of_cert.id, ldt_scope_of_cert.tech_accepted FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_scope_of_cert, mdt_assigned_tracksheet_users WHERE mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND ldt_scope_of_cert.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_scope_of_cert.tech_accept_req = 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}
			else
			{
				$query                     =   "SELECT mdt_p_tracksheet.*, mdt_customer_master.client_id, mdt_customer_master.client_name, sdt_schemes.scheme_name,ldt_scope_of_cert.id, ldt_scope_of_cert.tech_accepted FROM mdt_p_tracksheet, mdt_customer_master, sdt_schemes, ldt_scope_of_cert, mdt_assigned_tracksheet_users";

				$query						=	$query . " WHERE (mdt_p_tracksheet.tracksheet_id  = '".$searchText."' OR mdt_p_tracksheet.cb_type = $cb_type OR track_month = '" . $searchText . "' OR track_year = '".$searchText."' OR certification_type = '".$cert_type_text."' OR mdt_customer_master.client_id = '" . $searchText . "' OR sdt_schemes.scheme_name LIKE '%" . $searchText . "%')";

				$query						=	$query . " AND mdt_customer_master.cm_id = mdt_p_tracksheet.cm_id AND sdt_schemes.scheme_id = mdt_p_tracksheet.scheme_id AND ldt_scope_of_cert.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND ldt_scope_of_cert.tech_accept_req = 1 AND mdt_assigned_tracksheet_users.tracksheet_id = mdt_p_tracksheet.tracksheet_id AND mdt_assigned_tracksheet_users.user_id = $userid AND mdt_assigned_tracksheet_users.type = 2";
			}

	        $query						=	$query." ORDER BY mdt_p_tracksheet.tracksheet_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
    }
    public function getCount($cond)
	{
		$query	=	$this->db->where($cond)->get($this->tableName);
		$count= $query->result();
        //echo $this->db->last_query();
		return count($count);
	}
	}
?>