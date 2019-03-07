<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_user_document_category_model extends CI_Model {
	function __construct() 
	{
		$this->tableName = 'sdt_user_document_category';
	}
	public function get_document_category()
	{
		$data               = '';
		$this->db->distinct('category_id');
		$this->db->select('category_name');
		$this->db->where('category_status','1');
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
	
	public function get_categoty_name($category_id)
	{
		$this->db->select('category_name,category_id');
        $this->db->where('category_id',$category_id);
		$this->db->where('category_status = 1');
		$retval	                =	$this->db->get($this->tableName)->row();    
        if($retval             !=    null)
        {
            return $retval->category_name;
        }
		else
        {
            return true;
        }
	}
}
?>