<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_configuration_model extends CI_Model 
{

	function __construct() 
	{
		parent::__construct();
        $this->tableName = 'configuration';
		
    }
	
	public function disp_general_configuration_list()
	{
		$this->db->select ('*');
		$where = 'c_id in(123,124)';
		$this->db->where($where);
		$results 		=	$this->db->get($this->tableName)->result();
		return $results; 
	}
	public function get_general_configuration_list($c_id)
	{
		$this->db->where('c_id='.$c_id);
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
	public function edit_general_configuration_list()
	{
		$insArray								=	array();
		$upArray								=	array();	
		$c_id								    =	$this->input->post('c_id');
		$c_name									=	$this->input->post('c_name');
		$c_value								=	$this->input->post('c_value');
		$c_desc									=	$this->input->post('c_desc');
		if($c_id)
		{
			$upArray['c_name']			=	$c_name;
			$upArray['c_value']			=	$c_value;
			$upArray['c_desc']			=	$c_desc;
			$updatedepartment			=	$this->update($upArray,"c_id=".$c_id);	            
			$this->session->set_flashdata('success_message', 'configuration Updated Successfully');
			redirect(base_url('admin/general_configuration_list'),'refresh');
		}
		else
		{
			$insArray['c_name']			=	$c_name;
			$insArray['c_value']		=	$c_value;
			$insArray['c_desc']			=	$c_desc;
			$last_id					=	$this->insert($insArray);	            
			
			$this->session->set_flashdata('success_message', 'configuration Added Successfully');
		}
		redirect(base_url('admin/general_configuration_list'),'refresh');
	}
	
    public function change_configuration_status()
	{
		$c_id					            	    =	$this->input->post('pkey');
        $status                  			    =	$this->input->post('status');
        if($status                              ==  1)
        {
            $d_status                      		=   2;
        }
        else
        {   
				 $d_status                      =   1;
		}
		$upArray								=	array();
		$upArray['c_status']			    	=	$d_status;			
        $update_desg							=	$this->update($upArray,"c_id=".$c_id);
        $this->session->set_flashdata('success_message', 'status changed successfully');
		redirect(base_url('admin/general_configuration_list'),'refresh');
	}
	public function getConfigVal()
	{
		$this->db->select('c_value');
		$where 		=	'c_id=34';
		$this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
}


?>