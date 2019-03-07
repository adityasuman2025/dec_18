<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_schemes_model extends CI_Model 
{
	function __construct() {
		$this->tableName = 'sdt_schemes';
	}
	public function get_schemes_list()
	{
		$this->db->select('scheme_id,scheme_name,scheme_system,scheme_type,cb_type');
		$this->db->where('status','1');
		$this->db->order_by('scheme_name', 'ASC'); 
		$result             =   $this->db->get($this->tableName)->result();
		if($result               !=   null)
        {
            return $result;
        }
        else
        {
            return '';
        }	
	}

	public function get_scheme_list($scheme_type)
    {
    	$this->db->select('*');
    	$this->db->where('status','1');
		$this->db->where('scheme_type', $scheme_type);

        $result = $this->db->get($this->tableName)->result_array();

        return $result;
    }
}
?>