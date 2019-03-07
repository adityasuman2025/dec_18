<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_state_model extends CI_Model {
	function __construct() {
		$this->tableName = 'sdt_state';
	}
	public function get_state_list()
	{
		$where			=	"state_status=1";
		$this->db->select('state_id,state_name');
		$this->db->where($where);
		$this->db->order_by('state_name', 'ASC'); 
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