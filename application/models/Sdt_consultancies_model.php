<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_consultancies_model extends CI_Model {
	function __construct() {
        $this->tableName    = 'sdt_consultancies';
	}
	public function consultancy_list()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
		$where 							=	'c_id != 0';
		if($key)
		{
			$where						=	$where." AND c_name LIKE '%".$key."%'";
		}	
		$get_pageList  					=   $this->pagination_model->get_pagination($this->tableName,$where,"status DESC,c_name",25);	
        return $get_pageList;
	}
	public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
	public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function add_consultancy()
	{
		$insArray								=	array();
		$upArray								=	array();	
		$c_id									=	$this->input->post('c_id');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
		if($c_id)
		{
			$upArray['c_name']					=	$this->input->post('c_name');
			$upArray['c_address']				=	$this->input->post('c_address');
			$upArray['c_contact_number']		=	$this->input->post('c_contact_number');
			$upArray['c_alternate_contact']		=	$this->input->post('c_alternate_contact');
			$upArray['c_email']					=	$this->input->post('c_email');
			$upArray['edited_on']				=	$timestamp;
			$upArray['edited_by']				=	$emp_id;	
			$updatebranch						=	$this->update($upArray,"c_id=".$c_id);				
			$this->session->set_flashdata('success_message', 'Consultancy Updated Successfully');
		}
		else
		{
			$insArray['c_name']					=	$this->input->post('c_name');
			$insArray['c_address']				=	$this->input->post('c_address');
			$insArray['c_contact_number']		=	$this->input->post('c_contact_number');
			$insArray['c_alternate_contact']	=	$this->input->post('c_alternate_contact');
			$insArray['c_email']				=	$this->input->post('c_email');
			$insArray['added_on']				=	$timestamp;
			$insArray['added_by']				=	$emp_id;
			$last_id							=	$this->insert($insArray);		
			$this->session->set_flashdata('success_message', 'Consultancy Added Successfully');
		}
		redirect(base_url('hrm/consultancy_list'),'refresh');
	}
	public function get_consultancy_details($c_id)
	{
		$this->db->where('c_id='.$c_id);
		$result =   $this->db->get($this->tableName)->row(0);        
		return $result;
	}
	public function change_consultancy_status()
	{
		$c_id					                =	$this->input->post('pkey');
	 	$status                  			    =	$this->input->post('status');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        if($status                              ==  1)
        {
            $c_status                      =   0;
        }
        else
        {
            $c_status                      =   1;
        }
		$upArray								=	array();   
		$upArray['edited_by']					=	$emp_id;
		$upArray['edited_on']					=	$timestamp;			
		$upArray['status']						=	$c_status;			
        $this->update($upArray,"c_id=".$c_id);
        $this->session->set_flashdata('success_message', 'status changed successfully');
		redirect(base_url('hrm/consultancy_list'));
	}
	public function get_consultancy_list()
	{
        $data               = '';
		$this->db->select('c_id,c_name');
		$this->db->where('status','1');
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
    
    public function get_consultancy_name($cons_id)
	{
        $this->db->select('c_name');
		$this->db->where('c_id',$cons_id);
		$result                     =   $this->db->get($this->tableName)->row();    
        if($result                 !=    null)
        {
            return $result->c_name;
        }
		else
        {
            return '';
        }
	}
}