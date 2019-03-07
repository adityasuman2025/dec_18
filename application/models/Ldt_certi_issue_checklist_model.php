<?php
	class Ldt_certi_issue_checklist_model extends CI_Model
	{
		function __construct() 
		{
			$this->tableName = 'ldt_certi_issue_checklist';
	        $this->load->model('pagination_model');
		}

	//function to insert the certificate issue checlist data into database
		public function insert_in_ldt_certi_issue_checklist($data)
		{
			$userid = $_SESSION['userid'];

			$data['submitted_to_md_date'] = date('Y-m-d h:i:s');
			$data['added_by'] = $userid;
			$data['added_on'] = date('Y-m-d');

			$query_run = $this->db->insert('ldt_certi_issue_checklist', $data);

			if($query_run)
				echo 1;
			else
				echo 0;
		}

	//function to insert the certificate issue checlist data into database
		public function certi_issue_records()
		{
			$this->db->select('tracksheet_id, approved_by_md');
			$this->db->from('ldt_certi_issue_checklist');

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
		}

	//function to insert the certificate issue checlist data into database
		public function get_certi_issue_checklist_records($tracksheet_id, $level)
		{
			$this->db->select('*');
			$this->db->where('level', $level);
			$this->db->where('tracksheet_id', $tracksheet_id);
			$this->db->from('ldt_certi_issue_checklist');
			$this->db->order_by("id", "dsc");

			$query_run = $this->db->get();
			$result = $query_run->result_array();

			return $result;
		}

	//function to update approval_by_md status in ldt_certi_issue_checklist table
		public function update_md_approval_status_of_certi_checklist($id)
	    {
	    	$this->db->where('id', $id);
	    	$this->db->set('approved_by_md', 2);
	    	$this->db->set('approved_by_md_date', date('Y-m-d h:i:s'));
	    	
			$query_run = $this->db->update('ldt_certi_issue_checklist');

			if($query_run)
				echo 1;
			else
				echo 0;
	    }

	//function to update a particular column of the table for a id
		public function update_column_in_table($id, $column_name, $value)
		{			
			$this->db->where('id', $id);
			$this->db->set($column_name, $value);
			$query_run = $this->db->update('ldt_certi_issue_checklist');

			if($query_run)
				echo 1;
			else
				echo 0;
		}
	}
?>