<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_document_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_user_document';
		$this->primaryKey = 'document_id';
	}
    public function get_emp_photo($emp_id)
	{
        $image_path         =   '';
        $retval             =   '';
		$this->db->select('document_path');
        $this->db->where('user_id',$emp_id);
        $this->db->where('document_category','1');
        $this->db->where('document_type','12');
        $this->db->where('document_status','1');
        $this->db->order_by('document_id','DESC');		
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
            // $image_path		=	$this->config->item('uploaded_img_display_url').'Document/'.$retval; 
            $image_path     =   $_SERVER['DOCUMENT_ROOT'].'/new_apps/uploads/Document/'.$retval;
            if(file_exists($image_path))
            {
                $image_path =   $this->config->item('uploaded_img_display_url').'Document/'.$retval;
            }
            else
            {
                $image_path =   $this->config->item('oldapp_img_display_url').'Document/'.$retval;
            }            
        }    
        return $image_path;
	}
	public function get_all_document($user_id)
	{
		$data               = '';
		$this->db->where("user_id = ".$user_id);
		$this->db->where("document_status =1");
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
    public function get_count($cond)
	{
		$this->db->where($cond);
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();       
		return $retval; 
	}
	public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
	function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function insert_document($custom_name)
	{
		$upCategoryArray						=   array();
		$userId                                 =   $this->input->post('user_id');
		$document_category                      =   $this->input->post('document_category');
		$document_type                       	=   $this->input->post('document_type');
		$upCategoryArray['document_category']   =   $document_category;
		$upCategoryArray['document_type']       =   $document_type;
		$upCategoryArray['document_path']       =   $custom_name;
		$upCategoryArray['user_id']             =   $userId;
		//print_r($upCategoryArray);
		$this->db->insert($this->tableName, $upCategoryArray);
        return $this->db->insert_id();
	}
	public function insert_user_documents($user_document)
	{
		$insert_array								=	array();		
		$insert_array['user_id']					=	$user_document['user_id'];
		$docount									=	count($user_document['document_category']);
		for($docarray=0; $docarray<$docount; $docarray++)
		{ 
			$insert_array['document_category']		=	$user_document['document_category'][$docarray];
			$insert_array['document_type']			=	$user_document['document_type'][$docarray];
			$insert_array['document_path']			=	$user_document['files_name_array'][$docarray];
			//$insert_array['document_code']		=	$user_document['pro_to'][$docarray];
			$this->db->insert($this->tableName, $insert_array);
		}
        return $this->db->insert_id();
	}
	public function get_user_pic_document($userId)
	{
		$this->db->select('document_path,document_category,document_type');
        $this->db->where('document_category','1');
        $this->db->where('document_type','12');
		$this->db->where('user_id',$userId);
		$this->db->where('document_status','1');
		$result                         =   $this->db->get($this->tableName)->row();
		return $result;
	}
	public function insert_profile_pic($filename,$userId)
	{
		$this->load->model('mdt_users_model');
		$uppfArray						        =   array();
		//$user_id                                =   $this->mdt_users_model->get_userid_by_empid($emp_id);
		$document_category                      =   '1';
		$document_type                       	=   '12';
		$uppfArray['document_category']         =   $document_category;
		$uppfArray['document_type']             =   $document_type;
		$uppfArray['document_path']             =   $filename;
		$uppfArray['document_uploaded_on']      =   date('y-m-d');
		$uppfArray['user_id']                   =   $userId;
		$flag                		            =	0;
		$salDet                                 =   $this->get_user_pic_document($userId);
		//print_r($salDet);
		if($salDet						       != NULL)
		{
		 if($salDet->document_path		       != 	$uppfArray['document_path'])
		{           
		$flag                                   =	1;
		}
		 if($salDet->document_category	       != 	$uppfArray['document_category'])
		{
		$flag                                   =	1;
		}
		 if($salDet->document_type		        != 	$uppfArray['document_type'])
		{
		$flag                                  =	1;
		}
		if($flag                                == 1)  
		{
		$updateaccountdet			           =	 $this->update(array('document_status'=>2),"user_id=".$userId);				
		$updateaccountdet                      =  $this->insert($uppfArray);
		}
		}
		else
		{
		$updateaccountdet                      =  $this->insert($uppfArray);
		}
		//print_r($uppfArray);
		//echo $this->db->last_query();
        return $this->db->insert_id();
	}
}