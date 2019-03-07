<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_intimation_of_changes_model extends CI_Model 
{
	function __construct()
	{
		$this->tableName = 'ldt_intimation_of_changes';
	}

//function to get the intimation of changes records for a tracksheet
	public function get_changes_records($tracksheet_id)
	{
		$this->db->select('*');
		$this->db->from('ldt_intimation_of_changes');
		$this->db->where('tracksheet_id', $tracksheet_id);
		$this->db->order_by('id', 'dsc');

		$query_run = $this->db->get();
		$result = $query_run->result_array();
		
		return $result;
	}

//function to insert intimation of changes records in the databse
	public function insert_inti_of_changes_records($data)
	{
		$query_run = $this->db->insert('ldt_intimation_of_changes', $data);

		$insert_id = $this->db->insert_id();

		if($query_run)
			return $insert_id;
		else
			return 0;
	}

//function to notify technical of scope of change in intimation of changes
    public function notify_technical_of_scope_change($id)
    {	
    	$this->db->where('id', $id);
		$this->db->set('notify_technical_of_scope_change', 1);
		
		$query_run = $this->db->update('ldt_intimation_of_changes');

		if($query_run)
			return 1;
		else
			return 0;
    }

//function to update the change of scope request status
    public function update_scope_change_status_nd_notify_account($id)
    {	
    	$this->db->where('id', $id);
		$this->db->set('technical_accept_scope_change', 1);
		$this->db->set('notify_account_of_scope_change', 1);
		
		$query_run = $this->db->update('ldt_intimation_of_changes');

		if($query_run)
			return 1;
		else
			return 0;
    }

//function to list all the tarcksheets for which intimation of changes form has been filled
    public function list_filled_inti_filled_tracksheets()
    {
    	$this->db->select('tracksheet_id');
    	$this->db->from('ldt_intimation_of_changes');

    	$query_run = $this->db->get();
    	$result = $query_run->result_array();

    	return $result;
    }
}
?>