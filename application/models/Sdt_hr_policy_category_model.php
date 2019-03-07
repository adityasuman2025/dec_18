<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sdt_hr_policy_category_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	     $this->tableName = 'sdt_hr_policy_category';
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
    
    public function add_policy_category()
	{
		$insArray								=	array();
		$upArray								=	array();
        $c_id								    =	$this->input->post('c_id');
       	
		$category_name						    =	$this->input->post('cat_name');
        $parent_category						=	$this->input->post('category');
        $emp_id									=	$this->session->userdata('userid');
		
			if($c_id)
			{
				$upArray['category_name']			=	$category_name;
				$upArray['parent_category']			=	$parent_category;
                $upArray['category_updated_by']		=	$emp_id;
                $upArray['category_updated_on']		=	date('Y-m-d H:i:s');
				
				$updatedepartment					=	$this->update($upArray,"category_id=".$c_id);	            
				$this->session->set_flashdata('success_message', 'Category Updated Successfully');
			}
			else
			{
				$insArray['category_name']			    =	$category_name;
				$insArray['parent_category']		    =	$parent_category;
                $insArray['category_added_by']		    =	$emp_id;
			
				$last_id								=	$this->insert($insArray);	            
				
				$this->session->set_flashdata('success_message', 'Category Added Successfully');
			}
			redirect(base_url('hrm/hr_policy_category_list'),'refresh');
		
	}
    
    
    	public function get_policy_category_details($h_id)
	{
		$this->db->where('category_id='.$h_id);
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
        $circle_list                        = $this->pagination_model->get_pagination($this->tableName,$where,"	category_status",25);
        return $circle_list;
	}
    
 
 
 	public function change_category_status()
	{
		$c_id					            	=	$this->input->post('pkey');

	 	$status                  			    =	$this->input->post('status');
        
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
				
		$upArray['category_status']				=	$d_status;			
        $update_desg							=	$this->update($upArray,"category_id=".$c_id);
        $this->session->set_flashdata('success_message', 'status changed successfully');
		redirect(base_url('hrm/categorylist'),'refresh');
	}
    
    public function activeCategoryList()
	{
	    $emp_id						    =	$this->session->userdata('userid');
        $role                           =   $this->tdt_user_roles_model->get_users_all_roles($emp_id);
        $role_id                        =   $role[0]->ur_id;
       
		$this->db->select('category_name,category_id');
		
        if( $role_id==6)
        {
		$where 						=	' parent_category=0';
        }
        else{
          $where 						=	' category_status=1 and parent_category=0';  
        }
		$this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
    
    public function activeCategoryListc($category_id)
	{
	    
       
		$this->db->select('category_name,category_id');
		
        if( $category_id)
        {
		$where 						=	' parent_category=0 and category_status=1 and category_id!='.$category_id;
        }
        else{
          $where 						=	' category_status=1 and parent_category=0';  
        }
		$this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
     public function getactiveCategoryList()
	{
	    
       
		$this->db->select('category_name,category_id');
		
        
		$where 						=	' parent_category=0 and category_status=1';
        
        
		$this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
    
    
    public function getSubcategoryList($category)
	{
		$this->db->select('category_name,category_id');
		$where 							=	' parent_category="'.$category.'" and category_status=1';
		$this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
    public function getCategoryName($category_id)
    {
        $this->db->select('category_name');
		$where 							=	' category_id='.$category_id;
		$this->db->where($where);
		$result =   $this->db->get($this->tableName)->result();
		return $result;
        
    }
   
}
?>