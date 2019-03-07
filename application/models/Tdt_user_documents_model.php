<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tdt_user_documents_model extends CI_Model {
	function __construct() {
		$this->tableName = 'Tdt_user_documents';
	}
    public function get_emp_photo($emp_id)
	{
        $image_path         =   '';
        $retval             =   '';
		$this->db->select('document_path');
        $this->db->where('emp_id',$emp_id);
        $this->db->where('document_category','1');
        $this->db->where('document_type','12');
        $this->db->order_by('document_added_on DESC');		
        $this->db->limit('1','0');	
        
        $resval             =   $this->db->get($this->tableName)->result();  
        if($resval         !=   null)
        {
            foreach($resval as  $res)
            {
                $retval     =   $res->document_path;
            }   
        }                 
        if($retval          ==  '')
        {
            $image_path		=   $this->config->item('uploaded_img_display_url').'Document/no_image.png';
        }
        else
        {
            $image_path		=	$this->config->item('uploaded_img_display_url').'Document/'.$retval;
        }
        return $image_path;
	}
    public function get_count($cond)
	{
		$query	=	$this->db->where($cond)
					->get($this->tableName);
		$count= $query->num_rows();
		return $count;
	}
}