<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_languages_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_user_languages';
		$this->primaryKey = 'user_lang_id';
	}
    public function get_count($cond)
	{
		$this->db->where($cond);
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();    
         if($result						 != NULL)
		{
			return $retval; 
		}
		else
		{
			return '';
		}		
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
	public function insert_user_languages($user_languages)
	{
		$insert_array						=	array();
		$languages_read						=	$user_languages['languages_read'];
		$languages_speak					=	$user_languages['languages_speak'];
		$languages_write					=	$user_languages['languages_write'];
		$language_list						=	array_unique(array_merge($languages_read,$languages_speak,$languages_write));
		/*$newarray1							=	array_merge($newarray,$languages_write);
		$language_list 						= 	array_unique($newarray1);	*/
		foreach($language_list as $key=>$val)
		{
            $insert_array                   =   array();
			$insert_array['language_id']	=	$val;
			if(in_array($val,$languages_speak))
			{
				$insert_array['lang_speak']	=	1;
			} 
			if(in_array($val,$languages_read))
			{
				$insert_array['lang_read']	=	1;
			}
			if(in_array($val,$languages_write))
			{
				$insert_array['lang_write']	=	1;
			}            
		    $insert_array['user_id']		=	$user_languages['user_id'];
			$this->db->insert($this->tableName, $insert_array);
		}
        return $this->db->insert_id();
	}
	public function update_language_details()
	{
		$this->load->model('mdt_users_model');
		$languages_read						=	$this->input->post('languages_read');
		$languages_speak					=	$this->input->post('languages_speak');
		$languages_write					=	$this->input->post('languages_write');
		$language_list						=	array_unique(array_merge($languages_read,$languages_speak,$languages_write));	
		$emp_id								=	$this->input->post('emp_id');
		$fellow_id							=	$this->input->post('fellow_id');
		$userId								=	$this->input->post('user_id');
        $emp_id                             =   ($emp_id)?($emp_id):$fellow_id;
		if($emp_id)
		{
            if($userId)
            {
                $del                        =   $this->delete('user_id='.$userId);
            }
			foreach($language_list as $key=>$val)
			{
				$check_where                =   '';
				$insert_array               =	array();
				$insert_array['user_id']    =	$userId;
				$insert_array['language_id']=	$val;
				if(in_array($val,$languages_speak))
				{
					$insert_array['lang_speak']	=	1;
				} 
				if(in_array($val,$languages_read))
				{
					$insert_array['lang_read']	=	1;
				}
				if(in_array($val,$languages_write))
				{
					$insert_array['lang_write']	=	1;
				}
				$this->db->insert($this->tableName,$insert_array);			
			}
			
		}
	}
	public function get_user_language_history($user_id)
	{
		$this->db->distinct('language_id');
		$this->db->select('language_id,lang_read,lang_write,lang_speak,lang_status');
		$this->db->where('user_id',$user_id);
		$this->db->where('lang_status','1');
		$result                         =   $this->db->get($this->tableName)->result();
		return $result;
	}    
	public function delete($cond)
	{
        $this->db->where($cond);
        $this->db->delete($this->tableName);
        return true;
	}	
}