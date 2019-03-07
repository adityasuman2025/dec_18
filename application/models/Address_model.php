<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Address_model extends CI_Model 
{
	public function get_state()
	{
		$this->db->order_by("state_name","asc");
        $this->db->where('state_status',1);
		$result = $this->db->get('sdt_state');
		return $result;
	}
	public function get_city($city_state_id)
	{ 
		$this->db->where('city_status', 1);
		$this->db->where('city_state_id', $city_state_id);
        $this->db->order_by("city_name","asc");
		$result = $this->db->get('sdt_city');
        return $result;
	}
    public function get_cityname($city_id)
	{ 
		$this->db->where('city_id', $city_id);
		$result = $this->db->get('sdt_city')->row();
        return $result;
	}
    public function get_statename($state_id)
	{ 
		$this->db->where('state_id', $state_id);
		$result = $this->db->get('sdt_state')->row();
        return $result;
	}
    public function get_stateid($state_name)
	{ 
		$this->db->like('state_name', $state_name);
		$result = $this->db->get('sdt_state')->row();
        return $result;
	}
}