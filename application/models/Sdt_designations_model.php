<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_designations_model extends CI_Model {
	function __construct() {
        $this->tableName    = 'sdt_designations';
        $this->primaryKey   = 'd_id';
	}
    public function get_designation()
	{
        $data               = '';
		$this->db->select('d_id,d_name');
		$this->db->where('d_status','1');
		$this->db->order_by('d_name','ASC');
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
    public function get_designation_name($id)
	{
		$this->db->select('d_name');
		$this->db->where('d_id',$id);
		$result             =   $this->db->get($this->tableName)->row();
        if($result)
		{
			 return $result->d_name;
		}
		else
		{
			return "";
		}
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
	public function designationlist()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
		//$selDep                         =   $this->input->input_stream('selDep', TRUE); 
		//$selLevel                       =   $this->input->input_stream('desg_level', TRUE); 
		$where 							=	'd_id != 0';
		if($key)
		{
			$where						=	$where." AND d_name LIKE '%".$key."%'";
		}
        /*	
		if($selDep)
		{
			$where						=	$where." AND dept_id =".$selDep;
		}
		if($selLevel)
		{
			$where						=	$where." AND desg_level =".$selLevel;
		}
        */
		$designation_list  				=   $this->pagination_model->get_pagination($this->tableName,$where,"d_status,d_name",25);	
        return $designation_list;
	}
	public function change_designation_status()
	{
		$d_id					            =	$this->input->post('pkey');
	 	$status                  			    =	$this->input->post('status');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        if($status                              ==  1)
        {
            $d_status                      =   2;
        }
        else
        {
            $d_status                      =   1;
        }
		$upArray								=	array();   
		$upArray['d_status']					=	$d_status;			
        $update_desg							=	$this->update($upArray,"d_id=".$d_id);
        $this->session->set_flashdata('success_message', 'status changed successfully');
		redirect(base_url('hrm/designationlist'));
	}
	public function get_designation_details($d_id)
	{
		$this->db->where('d_id='.$d_id);
		$result =   $this->db->get($this->tableName)->row(0);        
		return $result;
	}
	public function add_designation()
	{
		$insArray								=	array();
		$upArray								=	array();	
		$d_id								    =	$this->input->post('d_id');
		$dept_id								=	$this->input->post('dept_id');
		$d_name								    =	$this->input->post('d_name');
		$desg_level								=	$this->input->post('desg_level');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
		if($d_id)
		{
			$upArray['d_name']			        =	$d_name;
			$upArray['dept_id']					=	$dept_id;
			$updatedepartment					=	$this->update($upArray,"d_id=".$d_id);	            
			$this->session->set_flashdata('success_message', 'Designation Updated Successfully');
		}
		else
		{
			$insArray['d_name']				    =	$d_name;
			$insArray['d_status']				=	'1';
			$insArray['dept_id']				=	$dept_id;
			$last_id							=	$this->insert($insArray);	            
			$this->session->set_flashdata('success_message', 'Designation Added Successfully');
		}
		redirect(base_url('hrm/designationlist'),'refresh');
	}
    public function get_designation_list()
	{
		$this->db->where('d_status=1');
		$result             =   $this->db->get($this->tableName);
        return $result->result();
	}
    public function get_desig_dept_list($dept_id)
	{
        $this->db->select('d_id as id,d_name as name');
		$this->db->where('d_status=1 AND dept_id='.$dept_id);
		$result             =   $this->db->get($this->tableName);
        return $result->result();
	}
}