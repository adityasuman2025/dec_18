<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdt_company_structures_model extends CI_Model {
	function __construct() {
        $this->tableName    = 'mdt_company_structures';
        $this->primaryKey   = 'comp_structure_id';
        $this->load->model('pagination_model');
	}
    public function get_company_list()
	{
		$this->db->where('comp_status=1');
		$result             =   $this->db->get($this->tableName);
        return $result->result();
	}
	public function get_company_name($comp_structure_id)
	{
        $this->db->select('title');
		$this->db->where('comp_structure_id',$comp_structure_id);
		$result =   $this->db->get($this->tableName)->row();        
		return $result->title;
	}
	public function companylist()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
		$where 							=	'comp_structure_id != 0';
		if($key)
		{
			$where						=	$where." AND title LIKE '%".$key."%'";
		}	
		$get_pageList  					=   $this->pagination_model->get_pagination("mdt_company_structures",$where,"parent ASC",25);	
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
	public function get_company_details($comp_structure_id)
	{
		$this->db->where('comp_structure_id='.$comp_structure_id);
		$result =   $this->db->get($this->tableName)->row(0);        
		return $result;
	}
	public function add_company()
	{
		$insArray								=	array();
		$upArray								=	array();	
		$comp_structure_id						=	$this->input->post('comp_structure_id');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        $old_vendor                             =   $this->input->post('old_vendor_id');
        $old_vendor                             =   ($old_vendor)?($old_vendor):0;
		if($comp_structure_id)
		{
            $upArray['title']			        =	$this->input->post('title');
            $upArray['description']             =	$this->input->post('description');
            $upArray['address']                 =	$this->input->post('address');
            $upArray['type']                    =	$this->input->post('type');
            $upArray['parent']                  =	$this->input->post('parent');
            $upArray['pay_roll']                =	$this->input->post('pay_roll');
            $upArray['company_code']            =	$this->input->post('company_code');
            $upArray['vrm_id']                  =	$this->input->post('vrm_id');
            //$upArray['old_vendor_id']           =	$old_vendor;
            $this->db->where("comp_structure_id=".$comp_structure_id);
            $updatecompany						=	$this->db->update($this->tableName,$upArray);	
            
		}
		else
		{
			$insArray['title']			        =	$this->input->post('title');
            $insArray['description']            =	$this->input->post('description');
            $insArray['address']                =	$this->input->post('address');
            $insArray['type']                   =	$this->input->post('type');
            $insArray['parent']                 =	$this->input->post('parent');
            $insArray['pay_roll']               =	$this->input->post('pay_roll');
            $insArray['company_code']           =	$this->input->post('company_code');
            $insArray['vrm_id']                 =	$this->input->post('vrm_id');
            //$insArray['old_vendor_id']          =	$old_vendor;
			$last_id							=	$this->db->insert($this->tableName,$insArray);
            
		}
		redirect(base_url('hrm/company_list'));
	}
	public function change_comp_status()
	{
        $comp_structure_id                      =	$this->input->post('pkey');
	 	$status                  			    =	$this->input->post('status');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        if($status                              ==  1)
        {
            $comp_status                      =   2;
        }
        else
        {
            $comp_status                      =   1;
        }
		$upArray								=	array();   		
		$upArray['comp_status']			        =	$comp_status;
        $this->db->where("comp_structure_id=".$comp_structure_id);			
        $this->db->update($this->tableName,$upArray);
	}
	 public function get_office_list()
	{
		$where			=	"comp_status=1";
		$this->db->select('comp_structure_id as id,title as name');
		$this->db->where($where);
		$this->db->order_by('title', 'ASC'); 
		$result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
    public function get_old_vendor_id($id)
	{
		$this->db->select('old_vendor_id');
		$this->db->where("comp_structure_id=".$id); 	
        $result                 =   $this->db->get($this->tableName)->row();
        if($result             !=    null)
        {
            if($result->old_vendor_id)
            {
                return $result->old_vendor_id;
            }
        }
        else
        {
            return '';
        }
	}
    public function get_company_list_parent_exclude()
	{
		$this->db->where('comp_status=1 and comp_structure_id not in (1,2)');
		$result             =   $this->db->get($this->tableName);
        return $result->result();
	}
    public function get_emp_company_list()
	{
		$this->db->where('comp_status=1 and comp_structure_id not in (1,2) AND pay_roll =1');
		$result             =   $this->db->get($this->tableName);
        return $result->result();
	}
    public function get_partner_company_list()
	{
		$this->db->where('comp_status=1 and comp_structure_id not in (1,2) AND pay_roll =2');
		$result             =   $this->db->get($this->tableName);
        return $result->result();
	}
    public function get_office_pay_roll($id)
	{
        $this->db->select('pay_roll');
		$this->db->where("comp_structure_id=".$id); 	
        $result                 =   $this->db->get($this->tableName)->row();
        if($result             !=    null)
        {
            if($result->pay_roll)
            {
                return $result->pay_roll;
            }
        }
        else
        {
            return '';
        }
	}	
}