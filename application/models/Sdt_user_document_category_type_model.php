<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_user_document_category_type_model extends CI_Model {
	function __construct() 
	{
		$this->tableName = 'sdt_user_document_category_type';
	}
	public function get_document_category_type($doc_category)
	{
		$data               = '';
		$this->db->distinct('doc_type_id');
		$this->db->select('doc_type_id,doc_type_name');
		$this->db->where("category_id = ".$doc_category." AND doc_type_status =1");
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
	
	public function get_document_type_name($doc_type_id)
	{
		$this->db->select('doc_type_name');
        $this->db->where('doc_type_id',$doc_type_id);
		$this->db->where("doc_type_status = 1");
		$retval	                =	$this->db->get($this->tableName)->row();    
        if($retval             !=    null)
        {
            return $retval->doc_type_name;
        }
		else
        {
            return true;
        }
	}
}
?>