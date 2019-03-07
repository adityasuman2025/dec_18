<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_user_name'))
{
	function get_user_name($user_id)
	{
		if($user_id)
		{
			$ci =& get_instance();  
			$ci->load->model('mdt_users_model');
			$user_name	=  $ci->mdt_users_model->get_employee_name($user_id);
			return $user_name;			
		}	   
	}
}
if ( ! function_exists('get_employees_reporting_me'))
{
	function get_employees_reporting_me($emp_id)
	{
		if($emp_id)
		{
			$ci =& get_instance();  
			$ci->load->model('mdt_employees_model');
			$emp_ids	=  $ci->mdt_employees_model->get_employees_reporting_me($emp_id);
			$reporteeslist					=	implode(",",$emp_ids);
			return $reporteeslist;				
		}   
	}
}
if ( ! function_exists('get_city_list_by_stateid'))
{
	function get_city_list_by_stateid($state_id="")
	{
		if($state_id)
		{
			$ci =& get_instance();  
			$ci->load->model('sdt_city_model');
			$city_list	=  $ci->sdt_city_model->get_city_list($state_id);
			return $city_list;	
		}  
	}
}
if ( ! function_exists('get_emp_leave_status'))
{
	function get_emp_leave_status($cond)
	{
		$ci =& get_instance();  
		$query = $ci->db->get_where('ldt_leave',$cond);
	   if($query->num_rows() > 0)
	   {
		   $result = $query->row();
		   return $result;
	   }
	   else
	   {
		   return false;
	   }
	}
}
function get_share_value()
{
	return 2000;
}
if ( ! function_exists('get_language_name'))
{
	function get_language_name($lang_id="")
	{
		if($lang_id)
		{
			$ci =& get_instance();  
			$query = $ci->db->get_where('sdt_languages',array('languages_id'=>$lang_id));
		   if($query->num_rows() > 0)
		   {
			   $result = $query->row();
			   return $result->languages_name;
		   }
		   else
		   {
			   return false;
		   }	
		}

	}
}
if ( ! function_exists('getAllPermitedPagesIds'))
{
	function getAllPermitedPagesIds($RoleID="")
	{
		if($RoleID)
		{
			$ci =& get_instance(); 
			$ci->load->model('cdt_permission_role_page_model');
			$pagelistArrayob                =   $ci->cdt_permission_role_page_model->getRoleActivity($RoleID);
			$pagelistArray                  =   array();
			foreach($pagelistArrayob        as $key)
			{
				$pagelistArray[]            =   $key->page_id;
			}
			return $pagelistArray;	
		}   
	}
}
if ( ! function_exists('getPageIdfromCTRLmdl'))
{
	function getPageIdfromCTRLmdl($ctrl,$mdl)
	{
		$ci =& get_instance(); 
        $ci->load->model('cdt_pages_model');
        return    $ci->cdt_pages_model->getPageIdfromCTRLmdl($ctrl,$mdl);
        	   
	}
}
if ( ! function_exists('get_state_name'))
{
	function get_state_name($state_id="")
	{
		if($state_id)
		{
			$ci =& get_instance();  
			$query = $ci->db->get_where('sdt_state',array('state_id'=>$state_id));
		   if($query->num_rows() > 0)
		   {
			   $result = $query->row();
			   return $result->state_name;
		   }
		   else
		   {
			   return false;
		   }	
		}

	}
}
if ( ! function_exists('get_city_name'))
{
	function get_city_name($city_id="")
	{
		if($city_id)
		{
			$ci =& get_instance();  
			$query = $ci->db->get_where('sdt_city',array('city_id'=>$city_id));
		   if($query->num_rows() > 0)
		   {
			   $result = $query->row();
			   return $result->city_name;
		   }
		   else
		   {
			   return false;
		   }
		}
	}
}
if ( ! function_exists('get_department_name'))
{
	function get_department_name($dept_id="")
	{
		if($dept_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('sdt_department_model');
			return    $ci->sdt_department_model->get_department_name($dept_id);
		}
	}
}
if ( ! function_exists('get_employee_name'))
{
	function get_employee_name($emp_id="")
	{
		if($emp_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('mdt_employees_model');
			return    $ci->mdt_employees_model->get_emp_name($emp_id);			
		}

	}
}
if ( ! function_exists('get_designation_name'))
{
	function get_designation_name($desg_id="")
	{
		if($desg_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('sdt_designations_model');
			return    $ci->sdt_designations_model->get_designation_name($desg_id);			
		}
	}
}
if ( ! function_exists('get_company_name'))
{
	function get_company_name($comp_id="")
	{
		if($comp_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('mdt_company_structures_model');
			return    $ci->mdt_company_structures_model->get_company_name($comp_id);			
		}

	}
}
if ( ! function_exists('get_userid_by_empid'))
{
	function get_userid_by_empid($emp_id="")
	{
		if($emp_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('mdt_users_model');
			return    $ci->mdt_users_model->get_userid_by_empid($emp_id);			
		}

	}
}
if ( ! function_exists('get_role_name'))
{
	function get_role_name($role_id="")
	{
		if($role_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('sdt_roles_model');
			return    $ci->sdt_roles_model->getRoleName($role_id);			
		}
	}
}
if ( ! function_exists('get_fellow_name'))
{
	function get_fellow_name($fellow_id="")
	{
		if($fellow_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('mdt_partner_model');
			return    $ci->mdt_partner_model->get_fellow_name($fellow_id);			
		}

	}
}
if ( ! function_exists('get_userid_by_fellowid'))
{
	function get_userid_by_fellowid($fellow_id="")
	{
		if($emp_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('mdt_users_model');
			return    $ci->mdt_users_model->get_userid_by_fellowid($fellow_id);			
		}

	}
}
if ( ! function_exists('get_language_details'))
{
	function get_language_details($language_id="")
	{
		if($language_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('sdt_wda_languages_model');
			return    $ci->sdt_wda_languages_model->get_language_details($language_id);			
		}

	}
}
if ( ! function_exists('get_wda_language_name'))
{
	function get_wda_language_name($language_id="")
	{
		if($language_id)
		{
			$ci =& get_instance(); 
			$ci->load->model('sdt_wda_languages_model');
			$result	=	$ci->sdt_wda_languages_model->get_language_details($language_id);				
			if($result)
			{
				return	$result->language_name;
			}
			else
			{
				return	"";
			}
		}

	}
}

if ( ! function_exists('get_employee_details'))
{
	function get_employee_details($emp_id)
	{
		if($emp_id)
		{
			$ci =& get_instance();  
			$ci->load->model('mdt_employees_model');
			$result	=  $ci->mdt_employees_model->get_employee_details($emp_id);
			if($result)
			{
				return	$result;
			}
			else
			{
				return	"";
			}		
		}   
	}
}
if ( ! function_exists('get_eseparation_details'))
{
	function get_eseparation_details($emp_id)
	{
		if($emp_id)
		{
			$ci =& get_instance();  
			$ci->load->model('ldt_emp_separation_model');
			$result	=  $ci->ldt_emp_separation_model->get_eseparation_details($emp_id);
			if($result)
			{
				return	$result;
			}
			else
			{
				return	"";
			}		
		}   
	}
}
?>