<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Ldt_tracksheet_status_model extends CI_Model 
	{
		function __construct() 
		{
			$this->tableName = 'ldt_tracksheet_status';
		}
		
	//function to list all the tracksheets in the table
		public function list_tracksheet()
		{
			$this->db->select('tracksheet_id');
			$this->db->from('ldt_tracksheet_status');

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
		}

	//function to automatically add a tracksheet in table
		public function insert_tracksheet_in_db_automatically($tracksheet_id, $new_status)
		{
			$data['tracksheet_id'] = $tracksheet_id;
			$data['status'] = $new_status;
			$data['md_approval_req'] = 2;
			$data['added_on'] = date('Y-m-d');

			$query_run = $this->db->insert('ldt_tracksheet_status', $data);

			if($query_run)
				return 1;
			else
				return 0;
		}

	//function to insert a tracksheet into ldt_tracksheet_status table for MD approval
		public function insert_tracksheet_for_md_approval_for_status($tracksheet_id, $status, $remarks)
	    {
	    	$data['tracksheet_id'] = $tracksheet_id;
			$data['status'] = $status;
			$data['remarks'] = $remarks;
			$data['md_approval_req'] = 1;
			$data['added_on'] = date('Y-m-d');

			$query_run = $this->db->insert('ldt_tracksheet_status', $data);

			if($query_run)
				return 1;
			else
				return 0;
	    }

	//function to get the tracksheet for which md approval for withdrawn status is pending
	    public function list_tracksheets_with_pending_withdrwan_status()
	    {
	    	$this->db->select('tracksheet_id');
	    	$this->db->where('status', 3); //withdrawn
	    	$this->db->where('md_approval_req', 1);
	    	$this->db->where('md_approved != 1');
			$this->db->from('ldt_tracksheet_status');

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
	    }

	//function to check if this tracksheet has withdrawn MD approval request 
        public function withdrawn_md_approval_req($tracksheet_id)
        {
        	$this->db->select('*');

	    	$this->db->where('tracksheet_id', $tracksheet_id);
	    	$this->db->where('status', 3); //withdrawn
	    	$this->db->where('md_approval_req', 1);

			$this->db->from('ldt_tracksheet_status');
			$this->db->order_by('id', 'dsc');

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
        }

        public function update_md_approved_status_in_tracksheet_status_table($row_id, $md_approved)
        {
        	$this->db->where('id', $row_id);
        	$this->db->set('md_approved', $md_approved);

        	$query_run = $this->db->update('ldt_tracksheet_status');

        	if($query_run)
        		return 1;
        	else
        		return 0;
        }
	}
?>