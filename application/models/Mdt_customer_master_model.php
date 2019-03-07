<?php
	class Mdt_customer_master_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'mdt_customer_master';
	        $this->load->model('pagination_model');
		}

	//function to list all the customers
		public function customer_list()
		{
			$searchText                     =   $this->input->input_stream('searchText', TRUE); 

			$query                     =   "SELECT * FROM mdt_customer_master";

		//searching specific cb_type
			$cb_type = 0;
			if($searchText == "EAS")		
				$cb_type = 1;
			else if($searchText == "IAS")
				$cb_type = 2;

		//preparing filter search query
			if($searchText!='')
			{
				$query						=	$query ." WHERE client_id  = '".$searchText."' OR  client_name  LIKE '%".$searchText."%' OR cb_type = $cb_type OR scope LIKE '%" . $searchText . "%'";
				
				//$query						=	$query ." WHERE cb_type = '$cb_type'";
			}

	        $query						=	$query." ORDER BY cm_id DESC";
		
		//adding pagination to break results into parts 	
			$get_pageList  					=   $this->pagination_model->get_pagination_sql($query,25);	

	        return $get_pageList;
		}

	//function to view details of a customer
		public function view_customer_info($data_id)
		{
			$this->db->select('mdt_customer_master.*, mdt_users.username as consultant_name, mdt_customer_contact.contact_number, mdt_customer_contact.contact_email');
	        $this->db->from('mdt_customer_master, mdt_users, mdt_customer_contact');
	        $this->db->where('mdt_customer_master.cm_id', $data_id);
	        $this->db->where('mdt_users.user_id = mdt_customer_master.consultant_id');
	        $this->db->where('mdt_customer_contact.cm_id = mdt_customer_master.cm_id');

	        $query = $this->db->get();
	        $result = $query->result();  
	        return $result;
		}

	//function to update the tracksheet_status of that customer in mdt_customer_master table
		public function update_customer_tracksheet_status($cm_id)
	    {
	        $this->db->where('cm_id', $cm_id);
	        $this->db->set('tracksheet_status', 1);
	        $query_run = $this->db->update('mdt_customer_master');

	        if($query_run)
	            return 1;
	        else 
	            return 0;	       
	    }

	//function to edit client details
	    public function edit_client_details($cm_id, $client_name, $contact_address, $scope)
	    {
	    	$userid = $_SESSION['userid'];

	    	$this->db->where('cm_id', $cm_id);

	        $this->db->set('client_name', $client_name);
	        $this->db->set('contact_address', $contact_address);
	        $this->db->set('updated_by', $userid);
	        $this->db->set('updated_on', date('Y-m-d h:i:s'));

	        $query_run = $this->db->update('mdt_customer_master');

	        if($query_run)
	            return 1;
	        else 
	            return 0;	       
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