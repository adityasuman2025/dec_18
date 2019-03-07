<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdt_customer_site_model extends CI_Model 
{
	function __construct()
	{
		$this->tableName = 'mdt_customer_site';
	}

//function to get the intimation of changes records for a tracksheet
	public function get_site_records($cm_id)
	{
		$this->db->select('site_id, site_address, review_date');
		$this->db->from('mdt_customer_site');
		$this->db->where('cm_id', $cm_id);

		$query_run = $this->db->get();
		$result = $query_run->result_array();
		
		return $result;
	}

//function to delete client site
    public function delete_client_site($site_id)
    {
		$this->db->where('site_id', $site_id);

		$query_run = $this->db->delete('mdt_customer_site');

		if($query_run)
			return 1;
		else
			return 0;
    }

//function to delete client site
    public function edit_client_site($site_id, $site_address)
    {
		$this->db->where('site_id', $site_id);
		$this->db->set('site_address', $site_address);

		$query_run = $this->db->update('mdt_customer_site');

		if($query_run)
			return 1;
		else
			return 0;
    }

//function to add new client site
    public function add_client_site($cm_id, $site_address)
    {
        $userid = $_SESSION['userid'];

    	$data = array(
    			'site_address' => $site_address,
    			'cm_id' => $cm_id,
    			'site_address' => $site_address,
    			'added_on' => date('Y-m-d h:i:s'),
    			'added_by' => $userid,
    			'status' => 1
    		);

    	$query_run = $this->db->insert('mdt_customer_site', $data);

    	if($query_run)
    		return 1;
    	else
    		return 0;
    } 

//function to insert the review dates for the stite address of a customer and tracksheet
    public function insert_site_address_review_dates($site_review_dates)
    {
    	foreach ($site_review_dates as $key => $site_review_date)
    	{
    		$site_id = $site_review_date['site_id'];
    		$review_date = $site_review_date['review_date'];

    		$this->db->where('site_id', $site_id);
    		$this->db->set('review_date', $review_date);
    		$this->db->update('mdt_customer_site');
    	}

    	return 1;
    }   
}
?>