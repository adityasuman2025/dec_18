<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_hr_policy_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	     $this->tableName = 'sdt_hr_policy';
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
    
    public function add_policy()
	{
		$insArray								=	array();
		$upArray								=	array();
        $c_id								    =	$this->input->post('c_id');
       	$policy_name						    =	$this->input->post('pol_name');
        $policy_name                            =   ucwords(strtolower($policy_name));
        $policy_details						    =	$this->input->post('pol_det');
        $category						        =	$this->input->post('category');
        $subcategory						    =	$this->input->post('subcategory');
        
        
		
		if($c_id)
		{
			$upArray['policy_name']			       =	$policy_name;
			$upArray['policy_details']		       =	$policy_details;
            $upArray['policy_category']		       =	$category;
            $upArray['policy_subcategory']		   =	$subcategory;
            
            $updatedepartment					   =	$this->update($upArray,"policy_id=".$c_id);	            
			$this->session->set_flashdata('success_message', 'Policy Updated Successfully');
		}
		else
		{
			$insArray['policy_name']			    =	$policy_name;
			$insArray['policy_details']		        =	$policy_details;
            $insArray['policy_category']		    =	$category;
            $insArray['policy_subcategory']		    =	$subcategory;
		    $last_id								=	$this->insert($insArray);	            
			$this->session->set_flashdata('success_message', 'Policy Added Successfully');
			}
			redirect(base_url('hrm/hr_policy_list_hr'),'refresh');
		
	    }
    
    
   	public function get_policy_details($h_id)
	{
		$this->db->where('policy_id='.$h_id);
		$result =   $this->db->get($this->tableName)->row(0);        
		return $result;
	}
    
   
    public function categorylist()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
     	$where 							=	"category_id!=0";
		if($key)
		{
			$where						=	$where." And category_name LIKE '%".$key."%'";
		}
         $sql                             =  "select circle_name,fa_circle_id,allocation_status from fa_circle_details where ".$where." group by circle_name order by allocation_status,circle_name";
        $circle_list                        = $this->Pagination_model->get_pagination($this->tableName,$where,"	category_status",25);
        return $circle_list;
	}
    
 
 
 	public function change_policy_status()
	{
	
        $status                                 =  $_POST['status'];
        $policy_id                              =  $_POST['policy_id'];
        
        
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        if($status                              ==  1)
        {
            $d_status                      		=   2;
        }
        else
        {
            $d_status                      		=   1;
        }
		$upArray								=	array();   
				
		$upArray['policy_status']				=	$d_status;			
        $update_desg							=	$this->update($upArray,"policy_id=".$policy_id);
        $this->session->set_flashdata('success_message', 'status changed successfully');
		redirect(base_url('hrm/categorylist'),'refresh');
	}
    
    public function activeCategoryList()
	{
		$this->db->select('category_name,category_id');
		$where 							=	'category_status=1 and parent_category=0';
		$this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
    
    public function getPolicyList_e($category)
	{   
	   
		$this->db->select('policy_name,policy_id,policy_status');
         $where 	               	=	' policy_subcategory="'.$category.'" and policy_status=1';  
        
		$this->db->where($where);
		$result                     =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
    public function getPolicyList_hr($category)
	{   
	   
		$this->db->select('policy_name,policy_id,policy_status');
        $where 						=	' policy_subcategory='.$category;
        $this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
    public function getPolicyDetailsbyId($policy_id)
	{
	   
		$this->db->select('policy_details,policy_name,policy_status');
        $where 							=	' policy_id='.$policy_id;
        $this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
    
    
     public function checkPolicyName($policy_name)
	{
		$this->db->select('policy_id');
		$where 							=	' policy_name like"'.$policy_name.'" and policy_status=1';
        $this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
        return $result;
	}
    
    
    
    
   
}
?>