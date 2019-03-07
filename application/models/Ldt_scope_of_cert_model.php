<?php
	class Ldt_scope_of_cert_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_scope_of_cert';
	        $this->load->model('pagination_model');
		}

	//get all scope of cert records
		public function get_scope_of_cert_records()
		{
			$this->db->select('id, tracksheet_id, tech_accept_req, tech_accepted');
			$this->db->from('ldt_scope_of_cert');

			$query_run = $this->db->get();

			$result = $query_run->result_array();
			return $result;
		}

	//function to insert scope of cert records for a tracksheet
		public function add_scope_of_cert_in_db($data)
		{
			$query_run = $this->db->insert('ldt_scope_of_cert', $data);
			
			if($query_run)
				return 1;
			else
				return 0;
		}

	//function to update scope of cert records for a tracksheet
		public function update_scope_of_cert_in_db($id, $data)
		{
			$this->db->where('id', $id);
			$query_run = $this->db->update('ldt_scope_of_cert', $data);
			
			if($query_run)
				return 1;
			else
				return 0;
		}

	//get scope of cert records for a tracksheet
		public function get_scope_of_cert($tracksheet_id)
		{
			$this->db->select('*');
			$this->db->from('ldt_scope_of_cert');
			$this->db->where('tracksheet_id', $tracksheet_id);

			$query_run = $this->db->get();

			$result = $query_run->result_array();
			return $result;
		}

	//function to notify technical team of scope of cert
		public function notify_technical_of_scope_of_cert($id)
		{
			$this->db->where('id', $id);
			$this->db->set('tech_accept_req', 1);
			$query_run = $this->db->update('ldt_scope_of_cert');
			
			if($query_run)
				return 1;
			else
				return 0;
		}	

	//function to update the technical_accept request for that row_id in the table
    	public function update_tech_accepted_in_scope_of_cert($id)
	    {          
	      	$this->db->where('id', $id);
			$this->db->set('tech_accepted', 1);
			$query_run = $this->db->update('ldt_scope_of_cert');
			
			if($query_run)
				return 1;
			else
				return 0;
	    }	
	}
?>