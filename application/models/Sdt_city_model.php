<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_city_model extends CI_Model {
	function __construct() {
		$this->tableName = 'sdt_city';
	}
	public function get_city_list_dropdown($state_id)
	{	
		$where			=	'city_status=1 and city_state_id='.$state_id;
		$this->db->select('city_id as id,city_name name');
		$this->db->where($where);
		$this->db->order_by('city_name', 'ASC'); 
		$result             =   $this->db->get($this->tableName)->result_array();
		return $result;
	}
	public function get_city_list($state_id)
	{	
		$where			=	'city_status=1 and city_state_id='.$state_id;
		$this->db->select('city_id,city_name');
		$this->db->where($where);
		$this->db->order_by('city_name', 'ASC'); 
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
}
?>