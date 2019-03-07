<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_ip_employee_model extends CI_Model {
	function __construct() {
		$this->tableName  = 'ldt_ip_employee';
		$this->primaryKey = 'ipe_id';
	}
    public function get_approved_ips()
	{
		$this->db->select('emp_id');
		$this->db->where('ipe_status','1');
		$result =   $this->db->get($this->tableName)->result();
    $empIDs     =   array();
        if($result != null)
        {
            foreach($result as $row)
            {
                $empIDs[]                   =   $row->emp_id;
            }
        }        
		return $empIDs;
	}
}