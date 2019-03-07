<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_department_model extends CI_Model {
	function __construct() {
        $this->tableName    = 'sdt_department';
        $this->primaryKey   = 'dept_id';
	}
    public function get_department_list()
	{
		$where			=	"dept_status=1";
		$this->db->select('dept_id as id,dept_name as name');
		$this->db->where($where);
		$this->db->order_by('dept_name', 'ASC'); 
		$result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
    public function get_department_name($id)
	{
		$this->db->select('dept_name');
		$this->db->where('dept_id',$id);
		$this->db->where("dept_status=1");
		$result             =   $this->db->get($this->tableName)->row();
        if($result)
		{
			return $result->dept_name;
		}
		else
		{
			return "";
		}
	}    
    public function get_active_departments()
	{
		$this->db->select('dept_id,dept_name');
		$this->db->where('dept_status','1');
		$result             =   $this->db->get($this->tableName)->result();        
		return $result;
	}
	public function departmentlist()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
		$where 							=	'dept_id != 0';
		if($key)
		{
			$where						=	$where." AND dept_name LIKE '%".$key."%'";
		}	
		$dept_list  					=   $this->pagination_model->get_pagination($this->tableName,$where,"dept_status ASC,dept_name",25);	
        return $dept_list;
	}
	public function get_department_details($dept_id)
	{
		$this->db->where('dept_id='.$dept_id);
		$result =   $this->db->get($this->tableName)->row(0);        
		return $result;
	}
	public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
	public function change_department_status()
	{
		$dept_id					            =	$this->input->post('pkey');
	 	$status                  			    =	$this->input->post('status');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        if($status                              ==  1)
        {
            $dept_status                      =   0;
        }
        else
        {
            $dept_status                      =   1;
        }
		$upArray								=	array();   
		$upArray['dept_status']					=	$dept_status;			
        $this->update($upArray,"dept_id=".$dept_id);         
        
        $this->session->set_flashdata('success_message', 'status changed successfully');
		redirect(base_url('hrm/departmentlist'));
	}
	public function add_department()
	{
		$insArray								=	array();
		$upArray								=	array();	
		$dept_id								=	$this->input->post('dept_id');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
		if($dept_id)
		{
			$upArray['dept_name']				=	$this->input->post('dept_name');
			$updatedepartment					=	$this->update($upArray,"dept_id=".$dept_id);
            
			$this->session->set_flashdata('success_message', 'Department Updated Successfully');
		}
		else
		{
			$insArray['dept_name']				=	$this->input->post('dept_name');
			$last_id							=	$this->insert($insArray);	
			$this->session->set_flashdata('success_message', 'Department Added Successfully');
		}
		redirect(base_url('hrm/departmentlist'),'refresh');
	}
	
}