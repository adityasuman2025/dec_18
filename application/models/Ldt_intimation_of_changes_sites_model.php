<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_intimation_of_changes_sites_model extends CI_Model 
{
	function __construct()
	{
		$this->tableName = 'ldt_intimation_of_changes_sites';
	}

//function to insert intimation of changes sites records in the databse
    public function insert_inti_of_changes_site_records($intimation_of_changes_id, $site_address_records)
    {       
    	$data['intimation_of_changes_id']                  =    $intimation_of_changes_id;
        $data['added_on']                                  =    date('Y-m-d');
        $data['status']                                    =    1;

        $records_count = count($site_address_records);
        
        if($records_count != 0)
        {
            foreach ($site_address_records as $key => $site_address_record)
            {
                $data['site_address'] = $site_address_record['site_address'];

                $this->db->insert('ldt_intimation_of_changes_sites', $data);
            }
        }    
    	
    	return 1;
    }

//to get intimation of changes form sites records for a intimation_of_changes_id
    public function get_site_changes_records($intimation_of_changes_id)
    {
        $this->db->select('*');
        $this->db->from('ldt_intimation_of_changes_sites');
        $this->db->where('intimation_of_changes_id', $intimation_of_changes_id);

        $query_run = $this->db->get();
        $result = $query_run->result_array();

        return $result;
    }
}
?>