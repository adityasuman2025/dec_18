<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cdt_permission_role_page_model extends CI_Model 
{
	function __construct() {
		$this->tableName = 'cdt_permission_role_page';
	}
	public function getRoleActivity($role_id)
	{
        $this->db->select('page_id');
		$this->db->where('role_id',$role_id);
		$result          = $this->db->get($this->tableName);
        return $result->result();
	}
	public function get_active_pages_by_role($page_id,$role_id)
	{     
        $data            =   '';
		$this->db->where('role_id',$role_id);
		$this->db->where('page_id',$page_id);
		$result          = $this->db->get($this->tableName)->result();
        if($result      != null) 
        {
            foreach($result as $row) 
            {
                $data[]  =   $row;
            }            
        }
        return $data;
	}
	public function delete($cond)
	{
        $this->db->where($cond);
        $this->db->delete($this->tableName);
        return true;
	}
	public function insert($arr)
	{
        $this->db->insert($this->tableName, $arr);
        return true;
	}    
    public function get_role_pages($role_id)
    {
        $newarr             =   array();
        $this->db->select('page_id');
        $this->db->where('role_id',$role_id);
        $retval             =   $this->db->get($this->tableName)->result();   
        if($retval         !=   null)
        {
            foreach($retval as $val)
            {
                $newarr[]   =   $val->page_id;
            }
        }
        return $newarr;
    }   
    public function update_role_permissions()
    {   
        $roleid             =   $this->input->input_stream('roleid', TRUE); 
        $copy_to_role       =   $this->input->input_stream('copy_to_role', TRUE); 
        $pages              =   $this->getRoleActivity($roleid);
        if(count($pages))
        {
            // delete and insert section
            $delete             =   $this->delete('role_id='.$copy_to_role);
            foreach($pages as $page_det)
            {   
                $radetails      =	$this->insert(array('page_id'=>$page_det->page_id,'role_id'=>$copy_to_role)); 
            }
        }
    }   
}
?>