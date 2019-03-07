<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdt_employees_model extends CI_Model 
{
	function __construct() 
	{
		$this->tableName = 'mdt_employees';
	}
    public function get_employee_details($id)
	{
		$this->db->where('emp_id',$id);
		$result = $this->db->get($this->tableName)->row();
		  if($result                     !=  null)
        {
            return $result;
        }
        else
        {
            return '';
        }	
	}
    public function get_employee_office($id)
	{
		$this->db->select('office');
		$this->db->where('emp_id',$id);
		$result = $this->db->get($this->tableName)->row();
        if($result)
        {
            return $result->office;
        }
		else
        {
            return '';
        }
	}
	public function getEmployeeJoiningDate($emp_id)
	{
		$this->db->select('joined_date');
		$this->db->where("emp_id=".$emp_id);
		$result =   $this->db->get($this->tableName)->row();
        if($result)
        {
            return $result->joined_date;
        }
		else
        {
            return '';
        }
	}    
	public function get_emp_req_details($fields,$cond)
	{
        $this->db->select($fields);
		$this->db->where($cond);
		$result       =   $this->db->get($this->tableName)->row();  
		return $result;
	}    
    public function getCount($cond)
	{
		$query	                        =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                          =	$query->result();
		return count($count);
	}
    public function generate_pay_slip()
    {    
        $this->load->model('pagination_model');
        $this->load->model('tdt_user_roles_model');
        $this->load->model('Ldt_pay_slip_details_model');
        $this->load->model('tdt_employee_salary_model');
        $this->load->model('sdt_department_model');                            
        $this->load->model('mdt_company_structures_model');                            
        $this->load->model('mdt_users_model');
        $this->load->model('ldt_arrears_model');
        $this->load->model('ldt_leave_wallet_model');
        $ps_month                   =   $this->input->input_stream('ps_month', TRUE); 
        $ps_year                    =   $this->input->input_stream('ps_year', TRUE);          
        $ps_month					=	($ps_month)?$ps_month:date('m')-1;
        $yyyy                       =   date('Y');
        if(date('m')                ==  1)
        {
            $yyyy                   =   date('Y')-1;
        }
        $ps_year	       			=	($ps_year)?$ps_year:$yyyy;                
        $selKey                     =   $this->input->input_stream('selKey', TRUE); 
        $selReportTo                =   $this->input->input_stream('selReportTo', TRUE); 
        $selDept                    =   $this->input->input_stream('selDept', TRUE); 
        $selStatus                  =   $this->input->input_stream('selStatus', TRUE); 
        $selStatus  	           	= 	($selStatus)?($selStatus):1;
        $selEmpId                   =   $this->input->input_stream('selEmpId', TRUE); 
        $seloffice                  =   $this->input->input_stream('seloffice', TRUE); 
        $doj                        =   $this->input->input_stream('doj', TRUE); 
        $limit_doj                  =   '21-'.$ps_month.'-'.$ps_year;
        $doj                        =   ($doj)?($doj):$limit_doj;
        
        $cond                       =   "emp_id != 0 and emp_id != 1 ";
        if($selEmpId)
		{
			$cond					=	$cond." AND emp_id =".$selEmpId;
		}
        if($selKey)
		{
			$cond					=	$cond." AND ( employee_name like '%".$selKey."%' )";
		}
        if($selReportTo)
		{
			$cond					=	$cond." AND reporting_to = ".$selReportTo;
		}
        if($selDept)
		{
			$cond					=	$cond." AND department = ".$selDept;
		}
        if($selStatus)
		{
			$cond					=	$cond." AND employee_status = ".$selStatus;
		}
        if($seloffice)
		{
			$cond					=	$cond." AND office = ".$seloffice;
		}
        if($seloffice)
		{
			$cond					=	$cond." AND office = ".$seloffice;
		}
        if($doj)
		{
			$cond					=	$cond." AND joined_date < '".date('Y-m-d',strtotime($doj))."'";
		}
        $get_pageList  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'emp_id',25);
        $data['page_details'] 		= 	$get_pageList;        
        $result_array				=	$data['page_details']['results'];
        $data['page_details']['results']=   array();
        if($result_array            !=  null)
        {
            foreach($result_array   as  $resval)
            {	
                $ps_status          =   'Generate';
                $variable           =   '';
                $arrears            =   '';
                $extra_days         =   '';
                $user_id            =   '';
                $role_id            =   '';
                $pend_leave         =   '';
                $paid_days          =   '';
                if($resval->employee_status ==  1)
                {
                    $user_id        =   $this->mdt_users_model->get_userid_by_empid($resval->emp_id); 
                    $pro_status     =   $this->check_profile_updated($resval->emp_id,$user_id);                
                    $role_id        =   $this->tdt_user_roles_model->get_emp_primary_role($user_id);
                    $emp_doj        =   $this->getEmployeeJoiningDate($user_id);
                    $emp_doj        =   date('d-m-Y', strtotime($emp_doj));
                    $dt             =   $ps_year.'-'.$ps_month;
                    
                    if(!$pro_status)
                    {
                        $psd        =   $this->Ldt_pay_slip_details_model->get_emp_pay_slip_details($resval->emp_id,$ps_month,$ps_year);
                        if($psd)
                        {
                            $ps_status  =   'Re-generate';
                        }
                        else
                        {
                            $ps_status  =   'Generate';
                        }                        
                    }
                    else if($pro_status)
                    {
                        $ps_status  =   $pro_status;
                    }                      
                } 
                else
                {
					$psd        	=   $this->Ldt_pay_slip_details_model->get_emp_pay_slip_details($resval->emp_id,$ps_month,$ps_year);
					if($psd)
					{
						$ps_status  =   'Re-generate';
					}
					else
					{
						$ps_status  =   'Generate';
					}    
				}
                $act_variable       =   '';
                if($ps_status       ==  'Generate')
                {
                    $act_variable   =   $this->tdt_employee_salary_model->get_emp_variable($resval->emp_id); 
                    $sal_not_upd    =   $this->get_emp_salary_count($resval->emp_id);
                    if($sal_not_upd)
                    {
                        $ps_status  =   $sal_not_upd;
                    }
                    else
                    {
                        $extra_days =   $this->tdt_employee_salary_model->get_extra_worked_days($ps_year,$ps_month,$resval->emp_id);             
                        if($extra_days)
                        {
                            $arrears=   $this->tdt_employee_salary_model->arrears_calculation($resval->emp_id,$extra_days,$ps_month,$ps_year);
                        }                              
                    }    
                    $variable       =   $this->ldt_arrears_model->get_incenctives_by_emp_id($resval->emp_id,$ps_month,$ps_year);                    
                    $ps_present_det =   $this->tdt_employee_salary_model->calculate_total_worked_days($resval->emp_id,$ps_year,$ps_month);
                    $paid_days      =   $ps_present_det['machine'];   
					              
                } 
                else if($ps_status  ==  'Re-generate')
                {
                    $act_variable   =   $this->tdt_employee_salary_model->get_emp_variable($resval->emp_id); 
                    $arrears        =   $psd->ps_arrears;
                    $variable       =   $psd->ps_variable; 
                    $extra_days     =   $psd->ps_extra_days; 
                    $paid_days      =   $psd->ps_present_days;
                    if($variable    ==  '0.00')
                    {
                        $variable   =   $this->ldt_arrears_model->get_incenctives_by_emp_id($resval->emp_id,$ps_month,$ps_year);
                    }
                }
                $pend_leave         =   $this->ldt_leave_wallet_model->get_remaining_leaves($resval->emp_id);
                $temp               =   array("emp_id"=>$resval->emp_id,"employee_status"=>$resval->employee_status,"ps_status" =>$ps_status,"employee_name"=>$resval->employee_name,"variable"=>$variable,"act_variable"=>$act_variable,"ps_arrears"=>$arrears ,"ps_extra_days"=>$extra_days,"pend_leave"=>$pend_leave,"paid_days"=>$paid_days, 'emp_doj'=>$emp_doj);
                $data['page_details']['results'] = (array) $data['page_details']['results'];
                array_push($data['page_details']['results'], $temp); 
            }		  
        }	        
        $data['title']              =   'Generate Pay Slip - '.date("F", mktime(0, 0, 0, $ps_month, 15)).' '.$ps_year;
        $data['subtitle']           =   '';
        $data['module']             =   'hrm';
        $data['view']               =   'hrm/generate_pay_slip';
		$data['selKey']  			= 	$selKey;		
		$data['selStatus']  		= 	$selStatus;		
		$data['selEmpId']  		    = 	$selEmpId;		
		$data['ps_month']  		    = 	$ps_month;		
		$data['ps_year']  	       	= 	$ps_year;		
		$data['selDept']  	       	= 	$selDept;		
		$data['seloffice']  	  	= 	$seloffice;		
		$data['selReportTo']  	    = 	$selReportTo;		
		$data['doj']  	            = 	$doj;		
		$data['dep_array']  	    = 	$this->sdt_department_model->get_active_departments();
		$data['off_array']  	    = 	$this->mdt_company_structures_model->get_emp_company_list();
		$data['rep_array']  	    = 	$this->get_managers_list();
        return $data;
    } 
    public function get_emp_name($emp_id)
	{
        $this->db->select('employee_name');
		$this->db->where('emp_id',$emp_id);
		$result                     =   $this->db->get($this->tableName)->row();    
        if($result                 !=    null)
        {
            return $result->employee_name;
        }
		else
        {
            return '';
        }
	}
 
    public function get_emp_req_det($emp_id)
	{
        $this->db->select('joined_date,gender,employee_code,office');
		$this->db->where('emp_id',$emp_id);
		$result                     =   $this->db->get($this->tableName)->row();    
        if($result                 !=    null)
        {
            return $result;
        }
		else
        {
            return true;
        }
	}
    public function check_profile_updated($emp_id,$user_id)
    {
        $pd_cond                    =   " emp_id =".$emp_id." AND (employee_name IS NULL OR gender IS NULL OR private_email IS NULL OR marital_status IS NULL OR mobile_phone IS NULL OR permanent_city IS NULL OR permanent_state IS NULL OR permanent_zip IS NULL OR birthday IS NULL OR department IS NULL OR designation IS NULL) ";
        $cond                       =   "emp_id =".$emp_id;
        $status                     =   '';
        if($emp_id)
        {              
            $u_cond                 =   "user_id =".$user_id;   
            $this->load->model('ldt_user_emergency_contact_model');
            $this->load->model('ldt_user_document_model');            
            $this->load->model('ldt_user_education_model');
            $this->load->model('ldt_user_languages_model');
            $this->load->model('tdt_employee_bank_account_model');
            $this->load->model('tdt_user_roles_model');            
            $per_det                =   $this->getCount($pd_cond);
            $lang_det               =   $this->ldt_user_languages_model->get_count($u_cond);
            $edu_det                =   $this->ldt_user_education_model->get_count($u_cond);            
            $ref_det                =   $this->ldt_user_emergency_contact_model->get_count($u_cond);            
            $doc_det                =   $this->ldt_user_document_model->get_count($u_cond);            
            $primary_role           =   $this->tdt_user_roles_model->get_emp_primary_role($user_id);
            $acc_det                =   $this->tdt_employee_bank_account_model->get_count($cond);
            if(!$per_det && $lang_det && $edu_det && $ref_det && $acc_det && $doc_det && $primary_role)
            {  }
            else
            {
                if($per_det)
                {
                    $status         =   '<a class="emp_pro_cl" id="persnl_div" emp="'.$emp_id.'">Update Personal Details</a>';
                }
                else if(!$lang_det)
                {
                    $status         =   '<a class="emp_pro_cl" id="lang_div" emp="'.$emp_id.'">Update Lanuages</a>';
                }
                else if(!$edu_det)
                {
                    $status         =   '<a class="emp_pro_cl" id="education_div" emp="'.$emp_id.'">Update Education</a>';
                }
                else if(!$ref_det)
                {
                    $status         =   '<a class="emp_pro_cl" id="emrg_contact_div" emp="'.$emp_id.'">Update Contact</a>';
                }
                else if(!$acc_det)
                {
                    $status         =   '<a class="emp_pro_cl" id="account_div" emp="'.$emp_id.'">Update Bank Account Details';
                }
                else if(!$doc_det)
                {
                    $status         =   '<a class="emp_pro_cl" id="document_div" emp="'.$emp_id.'">Update Documents</a>';
                }
                else if(!$primary_role)
                {
                    $status         =   '<a class="emp_pro_cl" id="employment_div" emp="'.$emp_id.'">No Primary Role</a>';
                }
                else
                {
                    $status         =   '<a class="emp_pro_cl" id="pro" emp="'.$emp_id.'">Update Profile</a>';
                }
            }
        }
        return $status;
    }   
    public function get_emp_salary_count($emp_id)
    {        
        $this->load->model('tdt_employee_salary_model');
        $result                     =   $this->tdt_employee_salary_model->get_emp_salary_count($emp_id);
        $status                     =   '';
        if($result)
        {
            // nothing
        }
        else
        {
            $status                 =   '<a class="emp_pro_cl" id="salary_div" emp="'.$emp_id.'">Update Salary</a>';
        }
        return $status;
    }
	function get_employees_reporting_me($emp_id)
	{
		$empReportToMe				=	array();
		$this->db->select('emp_id');
		$this->db->where("reporting_to = ".$emp_id." and employee_status = 1");
		$query       =   $this->db->get($this->tableName);        		
        if($query)
        {
            foreach($query->result_array() as $key)
    		{
    		 	array_push($empReportToMe,$key['emp_id']);
    		}
        }		
		return	$empReportToMe;
	}
    function get_employees_reporting_array($emp_id_array)
	{
		$empReportToMe				=	array();
		$this->db->select('emp_id');
		$this->db->where_in("reporting_to", $emp_id_array);
		$this->db->where("employee_status = 1");
		$query       =   $this->db->get($this->tableName);        		
        if($query)
        {
            foreach($query->result_array() as $key)
    		{
    		 	array_push($empReportToMe,$key['emp_id']);
    		}
        }		
		return	$empReportToMe;
	}
    public function get_multy_level_reporting_me($emp_id)
    {
        $final_employee_array           =   array();
        $current_employee_array         =   array();
        $new_employee_array             =   array();
        $current_employee_array         =   $this->get_employees_reporting_me($emp_id);
        $current_employee_array         =   array_diff($current_employee_array,array($emp_id));
        $final_employee_array           =   $current_employee_array;
        if(count($current_employee_array)>0)
            {
                $new_employee_array     =   $this->get_employees_reporting_array($current_employee_array);
                $new_employee_array     =   array_diff($new_employee_array,$final_employee_array);
                $final_employee_array   =   array_merge($new_employee_array,$final_employee_array);
                //echo $this->db->last_query();
            }
       loopreplica:
        if(count($new_employee_array)>0)
            {
                $new_employee_array     =   $this->get_employees_reporting_array($new_employee_array);
                $new_employee_array     =   array_diff($new_employee_array,$final_employee_array);   
                //echo $this->db->last_query();
            }
        if($new_employee_array)
        {
            $final_employee_array       =   array_merge($new_employee_array,$final_employee_array);
           goto loopreplica;
        }
        /*foreach($current_employee_array as $key)
        {
            array_push($final_employee_array,$key);
            $new_employee_array         =   $this->get_employees_reporting_me($key);
            $current_employee_array     =   array_merge($current_employee_array,$new_employee_array);
        }*/
        return $final_employee_array;
    }
	public function employeelist()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
		$employee_status			    =	$this->input->input_stream('employee_status', TRUE); 
		$gender					        =	$this->input->input_stream('gender', TRUE); 
		$joined_To_date					=	$this->input->input_stream('joined_To_date', TRUE); 
		$joined_From_date			    =	$this->input->input_stream('joined_From_date', TRUE); 
		$department			            =	$this->input->input_stream('department', TRUE); 
		$office			                =	$this->input->input_stream('office', TRUE); 
		$reporting_to			        =	$this->input->input_stream('reporting_to', TRUE); 
		$emp_mob_search			        =	$this->input->input_stream('emp_mob_search', TRUE); 
		$designation			        =	$this->input->input_stream('designation', TRUE);        
		$where 							=	'emp_id != 0 and emp_id != 1 ';
		if(is_numeric($key))
		{
		$where                          =  $where. " AND emp_id =".$key;
		}
		elseif($key)
		{	
		$where	                        =   $where. " AND employee_name LIKE '%".$key."%'";
		}
        if($employee_status)
		{
			$where						=	$where." AND employee_status   = ".$employee_status;
		}	
        if($gender)
		{
			$where						=	$where." AND gender  = ".$gender;
		}	
		if($department)
		{
			$where						=	$where." AND department  = ".$department;
		}
		if($designation)
		{
			$where						=	$where." AND designation  = ".$designation;
		}
		if($office)
		{
			$where						=	$where." AND office =".$office;
		}
		if($reporting_to)
		{
			$where						=	$where." AND reporting_to  =".$reporting_to;
		}
		if($emp_mob_search)
		{
			$where						=	$where." AND mobile_phone  =".$emp_mob_search;
		}
		if($joined_From_date          && $joined_To_date)
		{
			$where						=	$where." AND joined_date between '".date("Y-m-d", strtotime($joined_From_date))."' AND '".date("Y-m-d", strtotime($joined_To_date))."' ";
		}
        elseif($joined_From_date)
		{
			$where						=	$where." AND joined_date = '".date("Y-m-d", strtotime($joined_From_date))."' ";
		}
        elseif($joined_To_date)
		{
			$where						=	$where." AND joined_date = '".date("Y-m-d", strtotime($joined_To_date))."' ";
		}
        if($this->session->userdata('office') != 1)
        {
            //$where                      =  $where. " AND office =".$this->session->userdata('office');
        } 
		$get_empList  					=   $this->pagination_model->get_pagination("mdt_employees",$where,"employee_status asc,emp_id asc",25);
        return $get_empList;
	}
	public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
	public function get_emp_details($emp_id='')
	{
		$this->db->where('emp_id='.$emp_id);
		$result =   $this->db->get($this->tableName)->row(0);        
		if($result                 !=    null)
        {
            return $result;
        }
		else
        {
            return '';
        }
	}    
	public function get_employee_req_det($emp_id,$fields)
	{
		$this->db->select($fields);
		$this->db->where('emp_id',$emp_id);
        $result                         =   $this->db->get($this->tableName)->row();
        //echo $this->db->last_query();
        if($result                     !=  null)
        {
            return $result;
        }
        else
        {
            return false;
        }		
	}
	public function get_managers_list($department="")
	{	
		$cond	=	"";
		if($department)
		{
		//	$cond = ' and department='.$department;
			$cond = ' And department='.$department;
		}
		//$query					=   $this->db->query("SELECT employee_name AS name,emp_id as id FROM $this->tableName t1 JOIN(SELECT distinct reporting_to FROM $this->tableName WHERE reporting_to!=0 ".$cond.") t2 ON t1.emp_id = t2.reporting_to ".$cond);
		$query					=   $this->db->query("SELECT employee_name AS name,emp_id as id FROM $this->tableName Where emp_id != 1  ".$cond);
		$res					=	$query->result();
		return $res;
	}
	public function get_manager_reportees($reporting_to)
	{
		$this->db->select('emp_id as id,employee_name as name');
		$this->db->where('reporting_to',$reporting_to);
		$this->db->where('employee_status',1);
		$this->db->order_by('employee_name', 'ASC'); 
		$result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
	public function create_employee($emp_details)
	{	
		$insert_array			            =	array();
		$insert_array['emp_id']		        =	$emp_details['emp_id'];
		$insert_array['employee_name']		=	$emp_details['pd_name'];
		$insert_array['private_email']		=	$emp_details['pd_email'];
		$insert_array['mobile_phone']       =	$emp_details['mobile_phone'];
        if($emp_details['pd_anumber'])
        {
            $insert_array['alternate_phone']=	$emp_details['pd_anumber'];
        }
		$insert_array['gender']				=	$emp_details['pd_gender'];
		$insert_array['blood_group']		=	$emp_details['pd_bgroup'];
		$insert_array['birthday']			=	date('Y-m-d',strtotime($emp_details['pd_dob']));
		$insert_array['marital_status']		=	$emp_details['pd_marital_status'];
		$insert_array['aadhaar_num']		=	str_replace(' ', '',$emp_details['pd_aadhaar']);
		$insert_array['pan_num']			=	strtoupper($emp_details['pd_pan']);
		$insert_array['permanent_state']	=	$emp_details['emp_permanant_address_state'];
		$insert_array['permanent_city']		=	$emp_details['emp_permanant_address_city'];
		$insert_array['permanent_address']	=	$emp_details['emp_permanant_address'];
		$insert_array['permanent_zip']		=	$emp_details['emp_permanant_address_zip'];
		$insert_array['contact_state']		=	$emp_details['emp_state'];
		$insert_array['contact_city']		=	$emp_details['emp_city'];
		$insert_array['contact_address']	=	$emp_details['emp_address'];
		$insert_array['contact_zip']		=	$emp_details['emp_zip'];
		$insert_array['employee_code']		=	$emp_details['employee_code'];
		
		$insert_array['reporting_to']		=	$this->input->input_stream('reporting_mngr', TRUE);
		$insert_array['department']			=	$this->input->input_stream('department', TRUE);
		$joined_date		                =	$this->input->input_stream('date_of_joining', TRUE);
		$insert_array['joined_date']		=	date('Y-m-d',strtotime($joined_date));
		$insert_array['designation']		=	$this->input->input_stream('designation', TRUE);
		$insert_array['office']				=	$this->input->input_stream('company', TRUE);
        $insert_array['pay_group']      =   1;
        $this->db->insert($this->tableName, $insert_array);
        return $this->db->insert_id();
	}
	public function update_employee_code($emp_id)
	{
		$last_idzero			= 	sprintf("%05s",$emp_id);
		$emp_code				=	"EMP".$last_idzero;
		$this->db->set("employee_code",$emp_code); 
        $this->db->where("emp_id",$emp_id); 
		$this->db->update($this->tableName);
	}
	function get_employees_under_office_dep($comp_structure_id="",$dept_id="")
    {
        if($comp_structure_id           || $dept_id)
        {
            $this->db->select('user_id');
            $this->db->from('mdt_users');
            $this->db->where('user_status',1);
            $this->db->where('user_type',1);
            $this->db->join($this->tableName, $this->tableName.'.emp_id = mdt_users.employee');
            $this->db->where('employee_status',1);
            if($comp_structure_id)
            {
                $this->db->where('office',$comp_structure_id);
            }
            if($dept_id)
            {
                $this->db->where('department',$dept_id);
            }
            $result                         =   $this->db->get()->result();
            return $result;
        }
    }
	function get_user_id_rep_to_me($emp_id)
    {
        $reportToMe				            =	array();
        $this->db->select('user_id');
        $this->db->from('mdt_users');
        $this->db->where('user_status',1);
        $this->db->where('user_type',1);
        $this->db->join($this->tableName, $this->tableName.'.emp_id = mdt_users.employee');
        $this->db->where('employee_status',1);
        $this->db->where('reporting_to',$emp_id);
        $result                         =   $this->db->get()->result();
        if($result                     !=  null)
        {
            foreach($result             as $key)
    		{
    		 	array_push($reportToMe,$key->user_id);
    		}            
        }
        return $reportToMe;
    } 
	function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	 public function get_emp_count($cond)
	{
		$this->db->where($cond);
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();   
		return $retval;
	}
	public function getReportingToList()
	{ 
		$this->db->select('employee_name,emp_id');
        $this->db->where('employee_status',1);
        $result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
	public function getReportingToMe($emp_id)
	{ 	
		
		$this->db->select('emp_id');
        $this->db->where('reporting_to',$emp_id);
        $this->db->where('employee_status',1);
        $result             =   $this->db->get($this->tableName)->result();
		if(count($result))
        {
            $retval                 =   array_filter($result);
        }
		else
		{
			$retval = "";
		}		
        return $retval;
	}
	public function getReportingTo($emp_id)
	{ 
		$this->db->select('reporting_to');
		$this->db->where('emp_id',$emp_id);
		$result = $this->db->get($this->tableName)->row();
		//print_r($result);
		return $result->reporting_to;
	}
	 public function get_count($cond)
	{
		$query	=	$this->db->where($cond)
					->get($this->tableName);
		$count= $query->result(); 
		return count($count);
		
	}
	function getEmpIDs($cond)
	{ 
		 $this->db->select('emp_id');
		$this->db->where($cond);
		$result = $this->db->get($this->tableName);
		$ids= $result->result(); 
		$emp_list1='';
		$fstArr                         =   array_filter($ids);
		if(count($fstArr))
		{
			$arr1                       =   array();
			foreach($fstArr               as  $det1)
			{
				
				array_push($arr1,$det1->emp_id);
			}
			if(count($arr1))
			{
				   $emp_list1           =   implode(',',$arr1);
			}
		}
		return $emp_list1;
	}
	public function attrition_report_emp_list()
	{
		$this->load->model('pagination_model');
		$begin_month                    =   $this->uri->segment(4);
		$end_month                      =   $this->uri->segment(5);
		$type                           =   $this->uri->segment(8);
		$department                     =   $this->uri->segment(6);
		$office                         =   $this->uri->segment(7);
		$reporting_to                   =   $this->uri->segment(9);
		$user_roles                     =   $this->uri->segment(10);
		$ps_month                       =   $this->uri->segment(11);
		$ps_year                        =   $this->uri->segment(12);
		$key  		                    = 	$this->input->input_stream('key', TRUE); 
		if($department &&  $office  &&  $reporting_to && $user_roles && $ps_month && $ps_year == '0')
		{
			$where 							=	'emp_id=0';
		}
        $common_cond                    =   '';
		if($department)
		{
		$common_cond                    =   $common_cond.' AND department = '.$department;
		}
		if($reporting_to)
		{
		$common_cond                    =   $common_cond.' AND reporting_to = '.$reporting_to;
		}
		if($user_roles)
		{
		$common_cond                    =    $common_cond.' AND designation = '.$user_roles;
		}
		if($ps_year)
		{
		$common_cond			        =	 $common_cond.' AND  year(joined_date)=' .$ps_year;
		}
		if($ps_month)
		{
		$common_cond			     	=	 $common_cond.' AND  month(joined_date)=' .$ps_month;
		}
		if($office)
		{
		$common_cond                    =    $common_cond.' AND office = '.$office;
		}
		$where 							=	'emp_id!=0';
		if($key)
		{
		if(is_numeric($key))
		{
		$where                          =   $where.' AND emp_id='.$key;
		}
		else
		{
		$where                          =   $where.' AND employee_name LIKE "%'.$key.'%"';
		}
		}
		if($type                                ==  1)
		{
		########################### Total no of employees at the beginning of month ###########################
		$data['one_cond1']				=   'joined_date <= "'.$begin_month.'"  AND exit_date IS NULL AND employee_status = 1'.$common_cond;
		$data['res_one2']				=   'joined_date <= "'.$begin_month.'" AND exit_date > "'.$begin_month.'"'.$common_cond;
		$emp_ids_list1                  =   $this->getEmpIDs($data['one_cond1']); 
		$emp_ids_list2                  =   $this->getEmpIDs($data['res_one2']);
		if($emp_ids_list1                   &&  $emp_ids_list2)
		{
			$where                       =   $where.' AND emp_id IN ('.$emp_ids_list1.') OR emp_id IN ('.$emp_ids_list2.')';
		}
		elseif($emp_ids_list1)
		{
			$where                       =   $where.' AND emp_id IN ('.$emp_ids_list1.')' ;
		}
		else if($emp_ids_list2)
		{
			$where                       =   $where.' OR emp_id IN ('.$emp_ids_list2.')' ;
		}
		}
				else if($type                           ==  2)
		{
		########################### Total no of employees at the end of month ###########################
		$data['two_cond1']                  =   'joined_date <= "'.$end_month.'"  AND exit_date IS NULL AND employee_status = 1 '.$common_cond; 
		$data['two_cond2']                  =   'joined_date <= "'.$end_month.'" AND exit_date> "'.$end_month.'" '.$common_cond;  
		$emp_ids_list1                      =   $this->getEmpIDs($data['two_cond1']);
		$emp_ids_list2                      =   $this->getEmpIDs($data['two_cond2']);
		if($emp_ids_list1                   &&  $emp_ids_list2)
		{
			$where                          =   $where.' AND emp_id IN ('.$emp_ids_list1.') OR emp_id IN ('.$emp_ids_list2.'))';
		}
		else if($emp_ids_list1)
		{
			$where                          =   $where.' AND emp_id IN ('.$emp_ids_list1.')';
		}
		else if($emp_ids_list2)
		{
			$where                          =   $where.' AND emp_id IN ('.$emp_ids_list2.')';
		}
		}
		else if($type                           ==  3)
		{    
			########################### No of employees added during month ###########################
			$data['three_cond']                 =   'joined_date BETWEEN "'.$begin_month.'" AND "'.$end_month.'"'.$common_cond; 
			$emp_ids_list                       =   $this->getEmpIDs($data['three_cond']);
			if($emp_ids_list)
			{
				$where                          =   $where.' AND emp_id IN ('.$emp_ids_list.')';
			}
		}
		else if($type                           ==  4)
		{
		########################### No of employees left in month ###########################
		$data['four_cond']                      =   'exit_date BETWEEN "'.$begin_month.'" AND "'.$end_month.'"'.$common_cond;
		$emp_ids_list                           =   $this->getEmpIDs($data['four_cond']);
		if($emp_ids_list)
		{
			$where                              =   $where.' AND emp_id IN ('.$emp_ids_list.')';
		}
	}
	$empList  					           =   $this->pagination_model->get_pagination("mdt_employees",$where,"employee_status asc,emp_id asc",25);
	return $empList;
}
   public function empIdslist($cond)
{ 
	$query	=	$this->db->where($cond)
				->get($this->tableName);
	$result= $query->result(); 
	return $result;
}

	
	public function get_all_content($start,$content_per_page)
    {
		$this->db->select('SELECT emp_id,employee_name,designation,department,office,reporting_to,private_email,mobile_phone');
		$this->db->LIMIT('start',$start);
		$this->db->LIMIT('content_per_page',$content_per_page);
		$result =   $this->db->get($this->tableName);
	    return $result;
		
    }
	public function empIdslistttt($cond)
	{ 
	$query	=	$this->db->where($cond)
	->get($this->tableName);
	$result= $query->result(); 
	return $result;
	}
	 public function get_employee_id($name)
	{
		$this->db->select('emp_id');
		$this->db->where('employee_name',$name);
		$result = $this->db->get($this->tableName)->row();
		return $result->emp_id;
	}
	function getempList($start, $where='')
    { 
        $cond                   =   'employee_status=1';
        if($where)
        {
            $cond               =   $cond.$where;
        }
        $query                  =   $this   ->db
                                            ->limit(NEWS_PAGE_LIMIT, $start)
                                            ->where($cond)
                                            ->get($this->table);
        return $query->result();  
    }
	public function allEmployeeEmailPhList($key,$department,$reporting_to,$office,$gender,$joined_From_date,$joined_To_date,$employee_status,$emp_mob_search)
	{ 
		$this->db->select('emp_id,employee_name,designation,department,office,reporting_to,private_email,mobile_phone,gender,joined_date,employee_status');
		//$this->db->where('employee_status=1');
		if(is_numeric($key) && $key != 'NA')
		{
		  $this->db->where('emp_id ='.$key);
		}
		elseif($key && $key != 'NA')
		{	
		  $this->db->where('employee_name LIKE "%'.$key.'%"');
		}
		if($department && $department != 'NA')
		{
		  $this->db->where('department ='.$department);
		}
		if($reporting_to && $reporting_to != 'NA')
		{
		  $this->db->where('reporting_to = '.$reporting_to);
		}
		if($office && $office != 'NA')
		{
		  $this->db->where('office = '.$office);
		}
		if($gender && $gender != 'NA')
		{
		  $this->db->where('gender = '.$gender);
		}
		if($joined_From_date && $joined_From_date != 'NA' && $joined_To_date && $joined_To_date != 'NA')
		{
		  $this->db->where("joined_date between '".date("Y-m-d", strtotime($joined_From_date))."' AND '".date("Y-m-d", strtotime($joined_To_date))."'");
		}
		elseif($joined_From_date && $joined_From_date != 'NA')
		{
		  $this->db->where("joined_date = '".date("Y-m-d", strtotime($joined_From_date))."'");
		}
		elseif($joined_To_date && $joined_To_date != 'NA')
		{
		  $this->db->where("joined_date = '".date("Y-m-d", strtotime($joined_To_date))."'");
		}
		if($employee_status && $employee_status != 'NA')
		{
		  $this->db->where('employee_status = "'.$employee_status.'"');
		}
		if($emp_mob_search && $emp_mob_search != 'NA')
		{
		  $this->db->where('mobile_phone = "'.$emp_mob_search.'"');
		}
		//$this->db->last_query();
		$result             =   $this->db->get($this->tableName);
		return $result->result();
	}
	public function download_Email_phnum($key,$department,$reporting_to,$office)
	{
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Email&PhoneNumber".date("Y_m_d_H_i_s").".xls");
		
		$employee_details        =   $this->allEmployeeEmailPhList($key,$department,$reporting_to,$office);
      //print_r($employee_details);		 
			?>
            <table width="100%" height="226" align="center" cellpadding="0" cellspacing="0" border="1" style="padding-left:20px; padding-top:20px; border:thick"> 
            <tr>
                <td align="center" width="100%" bgcolor="#99CCFF" style="font-weight:bold; font-size:16px"><b>Employee Card  </b> </td>
            </tr>
            <tr>
                <td valign="top">
                    <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0">
                        <tr class="tableHead" bgcolor="#99FFFF" style="font-weight:bold">
                            <td width="20px">	SL No						</td>
							<td	width="20px">	Employee ID 				</td>
							<td	width="20px">	Employee Name 		        </td>
							<td	width="20px">	Email 		        	    </td>
							<td	width="20px">	Mobile Number 				</td>
                        </tr>
		     <?php
			if($employee_details)
			{
				$cnt                                 = 1;
				foreach($employee_details as  $resval)
				{	
				?>
				<tr>
					<td><?php echo $cnt++; ?></td>
					<td><?=$resval->emp_id?></td>
					<td><?=$resval->employee_name?></td>
					<td><?=($resval->private_email)?($resval->private_email):'-'?></td>
					<td><?=($resval->mobile_phone)?($resval->mobile_phone):'-'?></td>
				</tr>
					<?php				
				}
			}			
		  ?>
		</table>
     </td></tr></table>	
	<?php				
	}

	public function get_all_employees()
	{
		$key  		                    = 	$this->input->input_stream('key', TRUE);
		$department  		            = 	$this->input->input_stream('department', TRUE); 
		$reporting_to  		            = 	$this->input->input_stream('reporting_to', TRUE); 
		$office  		                = 	$this->input->input_stream('office', TRUE); 
		$designation        		    = 	$this->input->input_stream('designation', TRUE); 
		$where 							=	'employee_status = 1 AND emp_id > 1 ';
		if(is_numeric($key))
		{
		$where                          =  $where. " AND emp_id =".$key."";
		}
		elseif($key)
		{	
		$where	                        =   $where. " AND employee_name LIKE '%".$key."%'";
		}
		if($department)
		{
			$where						=	$where." AND department =" .$department;
		}
		if($office)
		{
			$where						=	$where." AND office =" .$office;
		}
		if($reporting_to)
		{
			$where						=	$where." AND reporting_to = " .$reporting_to;
		}
		if($designation)
		{
			$where						=	$where." AND designation = " .$designation;
		}
		$get_empList  					=   $this->pagination_model->get_pagination("mdt_employees",$where,"joined_date asc",25);
        return $get_empList;
	}
    public function get_reporting_manager_name($reporting_id)
    {
        $this->db->select('employee_name');        
        $this->db->where('emp_id',$reporting_id);
        $result = $this->db->get($this->tableName)->row();
        return $result->employee_name;
    }
    public function get_emp_suggestion()
    {      
        $nm                 =   $this->input->post('keyword', TRUE);
        $this->db->select('emp_id as id, employee_name as name, department');        
        $this->db->where('employee_name LIKE "%'.$nm.'%" AND employee_status=1');
        $result             =   $this->db->get($this->tableName);
        if($result         !=  NULL)
        {
            return $result  =   array_unique($result->result_array(), SORT_REGULAR);
        }
		else
        {
            return '';
        }
    }
	public function get_pay_group_values()
    {      
        $pay_group                 =	$this->get_employee_req_det($this->session->userdata('employee'),array('pay_group'));
		$this->load->model('sdt_pay_group_model');
		$result		   =	$this->sdt_pay_group_model->get_pg_det($pay_group->pay_group); 
		return $result;
    }
	public function get_employees_reporting_me_list($emp_id)
	{
		$this->db->select('emp_id,employee_name');
		$this->db->where("reporting_to = ".$emp_id." and employee_status = 1");
		$query       =   $this->db->get($this->tableName);        		
        if($query)
        {
			return $result  =   $query->result_array();
        }		
	}
	public function get_active_emp_det_in($emp_list)
	{
		$this->db->select('emp_id,employee_name');
		$this->db->where_in("emp_id",$emp_list,FALSE);
		$this->db->where("employee_status",'1');
		$result             =   $this->db->get($this->tableName);
        return $result->result();
	}
    public function check_emp_mobile_exists($mobile)
    {
        $this->db->select('mobile_phone');
        $this->db->where('mobile_phone',$mobile);
        $result                 =   $this->db->get($this->tableName)->row();
        if($result             !=    null)
        { 
            if($result->mobile_phone)
            {
                return $result->mobile_phone;
            }
        }
        else
        {
            return '';
        } 
    }
    public function payslip_dashboard()
    {    
        $ps_month                   =   $this->input->input_stream('ps_month', TRUE); 
        $ps_year                    =   $this->input->input_stream('ps_year', TRUE);          
        $ps_month					=	($ps_month)?$ps_month:date('m')-1;
        $yyyy                       =   date('Y');
        if(date('m')                ==  1)
        {
            $yyyy                   =   date('Y')-1;
        }
        $ps_year	       			=	($ps_year)?$ps_year:$yyyy;                
        $selKey                     =   $this->input->input_stream('selKey', TRUE); 
        $selReportTo                =   $this->input->input_stream('selReportTo', TRUE); 
        $selDept                    =   $this->input->input_stream('selDept', TRUE); 
        $selStatus                  =   $this->input->input_stream('selStatus', TRUE); 
        $selStatus  	           	= 	($selStatus)?($selStatus):1;
        $selEmpId                   =   $this->input->input_stream('selEmpId', TRUE); 
        $seloffice                  =   $this->input->input_stream('seloffice', TRUE); 
        $cond                       =   "emp_id != 1 AND employee_status=1";  
        $cond_join                  =   '';
        $limit_doj                  =   '21-'.$ps_month.'-'.$ps_year;
        if($selReportTo)
		{
			$cond					=	$cond." AND reporting_to = ".$selReportTo;
		}
        if($selDept)
		{
			$cond					=	$cond." AND department = ".$selDept;
		}
        if($seloffice)
		{
			$cond					=	$cond." AND office = ".$seloffice;
		} 
        if($limit_doj)
		{
			$cond					=	$cond." AND joined_date < '".date('Y-m-d',strtotime($limit_doj))."'";
		} 
        if($ps_month)
		{
			$cond_join              =	$cond_join." AND ps_month = ".$ps_month;
		} 
        if($ps_year)
		{
			$cond_join              =	$cond_join." AND ps_year = ".$ps_year;
		}         
        $result_gen					=	$this->db->select('emp_id, employee_name')
												->from($this->tableName.' t1')
												->join('ldt_pay_slip_details t2', 'emp_id = ps_emp_id', 'inner')
												->where($cond.$cond_join)
												->get();
        $result_not_gen				=	$this->db->select('emp_id, employee_name')
												->from($this->tableName.' t1')
												->join('ldt_pay_slip_details t2', 'emp_id = ps_emp_id '.$cond_join, 'left')
												->where('ps_emp_id IS NULL AND '.$cond)
												->get();
        $data['generated']          =   $result_gen->result();
        $data['not_generated']      =   $result_not_gen->result();
        $data['title']              =   'PaySlip Dashboard - '.date("F", mktime(0, 0, 0, $ps_month, 15)).' '.$ps_year;
        $data['subtitle']           =   '';
        $data['module']             =   'hrm';
        $data['view']               =   'hrm/payslip_dashboard';
		$data['selKey']  			= 	$selKey;		
		$data['selStatus']  		= 	$selStatus;		
		$data['selEmpId']  		    = 	$selEmpId;		
		$data['ps_month']  		    = 	$ps_month;		
		$data['ps_year']  	       	= 	$ps_year;		
		$data['selDept']  	       	= 	$selDept;		
		$data['seloffice']  	  	= 	$seloffice;		
		$data['selReportTo']  	    = 	$selReportTo;	
        $this->load->model('sdt_department_model');
        $this->load->model('mdt_company_structures_model');
		$data['dep_array']  	    = 	$this->sdt_department_model->get_active_departments();
		$data['off_array']  	    = 	$this->mdt_company_structures_model->get_emp_company_list();
		$data['rep_array']  	    = 	$this->get_managers_list();
        return $data;
    } 
    public function get_userDetails($emp_id)
	{
		$this->db->select('designation,reporting_to,office,department');
		$this->db->where('emp_id',$emp_id);
		$result             =   $this->db->get($this->tableName)->result(); 
		$this->db->last_query();
		return $result;
	}
	public function downloadEmpDetails($key,$gender,$reporting_to,$department,$office,$joined_To_date,$joined_From_date,$emp_mob_search,$employee_status,$designation)
	{ 		
	    $cond                     = '';	
	    if(is_numeric($key))
		{
		  $cond				     .=	   " and t14.emp_id =".$key;
		}
		elseif($key)
		{	
		  $cond				      .=	" and t14.username LIKE '%".$key."%'";
		}
		if($department)
		{
			$cond				  .=	" and t14.department =" .$department;
		}
		if($office)
		{
			$cond				  .=	" and t14.office =" .$office;
		}
		if($reporting_to)
		{
			$cond				  .=	" and t14.reporting_to = " .$reporting_to;
		}
		if($designation)
		{
			$cond				  .=	" and t14.designation = " .$designation;
		}
		if($emp_mob_search)
		{
			$cond				  .=	" and t14.mobile_phone = " .$emp_mob_search;
		}
		if($gender)
		{
			$cond				  .=	" and t14.gender = " .$gender;
		}
		if($joined_From_date && $joined_To_date)
		{
		  $cond						.=	" and t14.joined_date between '".date("Y-m-d", strtotime($joined_From_date))."' AND '".date("Y-m-d", strtotime($joined_To_date))."' ";
		}
        elseif($joined_From_date)
		{
		  $cond						.= " and t14.joined_date = '".date("Y-m-d", strtotime($joined_From_date))."' ";
		}
        elseif($joined_To_date)
		{
		  $cond						.= " and t14.joined_date = '".date("Y-m-d", strtotime($joined_To_date))."'";
		}
		if($employee_status)
		{
			$cond				    .=	" and t14.employee_status = " .$employee_status;
		}
		$sql                     = "SELECT t14.*, salary_amount, grossSalary, variables, tds FROM tdt_employee_salary t15 JOIN(SELECT t12.*, t13.title FROM mdt_company_structures t13 JOIN(SELECT t8.*, t9.pg_code FROM sdt_pay_group t9 JOIN(SELECT t7.*, t8.dept_name FROM sdt_department t8 JOIN(SELECT t6.*, t5.username as TLName FROM mdt_users t5 JOIN(SELECT username, email, t4.* FROM mdt_users t3 JOIN(SELECT emp_id, employee_code,birthday, gender,employee_status,  CASE WHEN marital_status = 1 THEN 'Single' WHEN marital_status = 2 THEN 'Married' ELSE 'Other' END as MaritalStatus, aadhaar_num,pan_num,designation, d_name, mobile_phone, alternate_phone, CASE WHEN blood_group = 1 THEN 'A+' WHEN blood_group = 2 THEN 'B+' WHEN blood_group = 3 THEN 'A-' WHEN blood_group = 4 THEN 'B-' WHEN blood_group = 5 THEN 'AB+' WHEN blood_group = 6 THEN 'AB-' WHEN blood_group = 7 THEN 'O+' WHEN blood_group = 8 THEN 'O-' WHEN blood_group = 9 THEN 'Unknown' ELSE 'Unkown' END as BloodGroup, joined_date, reporting_to, department, pay_group, office,rp_emp_referral,rp_emp_referral_detail,rp_consultancy FROM mdt_employees t1 JOIN sdt_designations t2 ON t1.designation = t2.d_id left join ldt_recruitment_process t16 on t1.mobile_phone=t16.rp_emp_mobile WHERE emp_id != 0  and emp_id != 1 ) t4 ON t3.user_id = t4.emp_id AND user_type = 1) t6 ON t6.reporting_to = t5.user_id) t7 ON t7.department = t8.dept_id) t8 ON t9.pg_id = t8.pay_group) t12 ON t13.comp_structure_id = t12.office) t14 ON t14.emp_id = t15.emp_id and t15. salary_status=1 ".$cond."";
		
		$query	    			 =   $this->db->query($sql);
		$res					 =	$query->result();
		return $res;
	}
    public function get_recruiters_list()
	{
		$this->db->select('emp_id,employee_name');
		$this->db->where('department',20);
		$this->db->where('employee_status',1);
		$result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
     public function getBirthdays($date)
	{
		$this->db->select('emp_id,employee_name,email');
		$this->db->like('birthday',$date);
        $this->db->where('user_status',1 ); 
        $this->db->where('user_type',1 );
        
        $this->db->join('mdt_users', 'mdt_users.employee = mdt_employees.emp_id');
        $result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
    
     public function getAnniversary($date)
	{
		$this->db->select('emp_id,employee_name,email,joined_date');
		$this->db->like('joined_date',$date);
        $this->db->where('user_status',1 ); 
        $this->db->where('user_type',1 );
        
        $this->db->join('mdt_users', 'mdt_users.employee = mdt_employees.emp_id');
        $result             =   $this->db->get($this->tableName)->result();
		return $result;
	}
}
?>