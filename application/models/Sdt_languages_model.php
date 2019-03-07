<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_languages_model extends CI_Model {
	function __construct() 
	{
		$this->tableName = 'sdt_languages';
	}
	public function get_all_languages()
	{
		$data               = '';
		$this->db->select('languages_id,languages_name');
		$this->db->where('languages_status','1');
        $this->db->order_by('languages_name ASC');
		$result             =   $this->db->get($this->tableName)->result();
        if($result         !=   null)
        {
            foreach ($result as $row) 
            {
                $data[]     =   $row;
            }
            return $data;
        }
		return $data;
	}
	
		public function get_lang_name($languages_id)
	{
		$this->db->select('languages_name');
        $this->db->where('languages_id',$languages_id);
		$this->db->where("languages_status = 1");
		$retval	                =	$this->db->get($this->tableName)->row();    
        if($retval             !=    null)
        {
            return $retval->languages_name;
        }
		else
        {
            return true;
        }
	}
}
?>