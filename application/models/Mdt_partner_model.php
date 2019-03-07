<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdt_partner_model extends CI_Model 
{
	function __construct() 
	{
		$this->tableName = 'mdt_partners';
	}
    public function get_partner_details($id)
	{
		$this->db->where('fellow_id',$id);
		$result = $this->db->get($this->tableName)->row();
		return $result;
	}
    public function get_fellow_name($fellow_id)
	{
        $this->db->select('fellow_name');
		$this->db->where('fellow_id',$fellow_id);
		$result       =   $this->db->get($this->tableName)->row();   		
        if($result   !=    null)
        {
            return $result->fellow_name;
        }
		else
        {
            return '';
        }
	}
    public function get_fellow_req_det($fellow_id)
	{
        $this->db->select('joined_date,gender,fellow_code');
		$this->db->where('fellow_id',$fellow_id);
		$result       =   $this->db->get($this->tableName)->row();    
        if($result   !=    null)
        {
            return $result;
        }
		else
        {
            return true;
        }
	}
	public function create_partner($fellow_details)
	{
		$insert_array			=	array();
		$insert_array['fellow_id']		    =	$fellow_details['fellow_id'];
		$insert_array['fellow_name']		=	$fellow_details['pd_name'];
		$insert_array['private_email']		=	$fellow_details['pd_email'];
		$insert_array['mobile_phone']       =	$fellow_details['mobile_phone'];
        if($fellow_details['pd_anumber'])
        {
            $insert_array['alternate_phone']=	$fellow_details['pd_anumber'];
        }
		$insert_array['gender']				=	$fellow_details['pd_gender'];
		$insert_array['blood_group']		=	$fellow_details['pd_bgroup'];
		$insert_array['birthday']			=	date('Y-m-d',strtotime($fellow_details['pd_dob']));        
		$insert_array['marital_status']		=	$fellow_details['pd_marital_status'];
		$insert_array['aadhaar_num']		=	str_replace(' ', '',$fellow_details['pd_aadhaar']);
		$insert_array['pan_num']			=	strtoupper($fellow_details['pd_pan']);
		$insert_array['permanent_state']	=	$fellow_details['emp_permanant_address_state'];
		$insert_array['permanent_city']		=	$fellow_details['emp_permanant_address_city'];
		$insert_array['permanent_address']	=	$fellow_details['emp_permanant_address'];
		$insert_array['permanent_zip']		=	$fellow_details['emp_permanant_address_zip'];
		$insert_array['contact_state']		=	$fellow_details['emp_state'];
		$insert_array['contact_city']		=	$fellow_details['emp_city'];
		$insert_array['contact_address']	=	$fellow_details['emp_address'];
		$insert_array['contact_zip']		=	$fellow_details['emp_zip'];
		$insert_array['fellow_code']		=	$fellow_details['fellow_code'];
        
        $insert_array['reporting_to']		=	$this->input->input_stream('reporting_mngr', TRUE);
		$insert_array['department']			=	$this->input->input_stream('department', TRUE);
		$joined_date		                =	$this->input->input_stream('date_of_joining', TRUE);
		$insert_array['joined_date']		=	date('Y-m-d',strtotime($joined_date));
		$insert_array['designation']		=	$this->input->input_stream('designation', TRUE);
		$insert_array['office']				=	$this->input->input_stream('company', TRUE);
		$this->db->insert($this->tableName, $insert_array);
        return $this->db->insert_id();
	}
	public function update_partner_code($fellow_id)
	{
		$last_idzero			= 	sprintf("%05s",$fellow_id);
		$fellow_code				=	"EMP".$last_idzero;
		$this->db->set("fellow_code",$fellow_code); 
        $this->db->where("fellow_id",$fellow_id); 
		$this->db->update($this->tableName);
	}
    public function get_user_id_under_office_dep($comp_structure_id="",$dept_id="")
    {
        if($comp_structure_id           || $dept_id)
        {
            $this->db->select('user_id');
            $this->db->from('mdt_users');
            $this->db->where('user_status',1);
            $this->db->where('user_type',1);
            $this->db->join($this->tableName, $this->tableName.'.fellow_id = mdt_users.partner');
            $this->db->where('fellow_status',1);
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
	public function get_user_id_rep_to_me($fellow_id)
    {
        $reportToMe				         =	array();
        $this->db->select('user_id');
        $this->db->from('mdt_users');
        $this->db->where('user_status',1);
        $this->db->where('user_type',1);
        $this->db->join($this->tableName, $this->tableName.'.fellow_id = mdt_users.partner');
        $this->db->where('fellow_status',1);
        $this->db->where('reporting_to',$fellow_id);
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
	public function get_partner_list()
	{
		$key                            =   $this->input->input_stream('key', TRUE); 
		$fellow_status			    	=	$this->input->input_stream('fellow_status', TRUE); 
		$gender					        =	$this->input->input_stream('gender', TRUE); 
		$department			            =	$this->input->input_stream('department', TRUE); 
		$office			                =	$this->input->input_stream('office', TRUE); 
		$reporting_to			        =	$this->input->input_stream('reporting_to', TRUE); 
		$designation			        =	$this->input->input_stream('designation', TRUE); 
		$part_mob_search		        =	$this->input->input_stream('part_mob_search', TRUE); 
		$joined_From_date		        =	$this->input->input_stream('joined_From_date', TRUE); 
		$joined_To_date   		        =	$this->input->input_stream('joined_To_date', TRUE); 
		$designation     		        =	$this->input->input_stream('designation', TRUE); 
		$where 							=	'fellow_id != 0';
		if(is_numeric($key))
		{
			$where                          =  $where. " AND fellow_id =".$key."";
		}
		elseif($key)
		{	
			$where	                        =   $where. " AND fellow_name LIKE '%".$key."%'";
		}
        if($fellow_status)
		{
			$where						=	$where." AND fellow_status   =".$fellow_status;
		}	
        if($gender)
		{
			$where						=	$where." AND gender  =".$gender;
		}	
		if($office)
		{
			$where						=	$where." AND office =".$office;
		}
		if($reporting_to)
		{
			$where						=	$where." AND reporting_to  =".$reporting_to;
		}
		if($designation)
		{
			$where						=	$where." AND designation  =".$designation;
		}        
		if($department)
		{
			$where						=	$where." AND department  =".$department;
		}
		if($part_mob_search)
		{
			$where						=	$where." AND mobile_phone  =".$part_mob_search;
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
		$get_partner_emp  				=   $this->pagination_model->get_pagination("mdt_partners",$where,"fellow_status asc,fellow_id asc",25);	
        return $get_partner_emp;
	}
	public function get_managers_list($department="")
	{	
		$cond	=	"";
		if($department)
		{
			$cond = ' and department='.$department;
		}
		$query					=   $this->db->query("SELECT fellow_name AS name,fellow_id as id FROM $this->tableName t1 JOIN(SELECT distinct reporting_to FROM $this->tableName WHERE reporting_to!=0 ".$cond.") t2 ON t1.fellow_id = t2.reporting_to ".$cond);
		$res					=	$query->result();
		return $res;
	}
    public function get_count($cond)
	{
		$query	                        =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                          =	$query->result();
		return count($count);
	}
    public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
	function getEmpIDs($cond)
	{ 
		 $this->db->select('fellow_id');
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
				
				array_push($arr1,$det1->fellow_id);
			}
			if(count($arr1))
			{
				   $emp_list1           =   implode(',',$arr1);
			}
		}
		return $emp_list1;
	}
	public function attrition_report_partner()
	{
		$this->load->model('sdt_department_model');
        $this->load->model('mdt_company_structures_model');
        $this->load->model('sdt_roles_model');
		$yrMnth						    =	date('Y-m');
		$begin_month                    =   $yrMnth.'-01';
		$end_month                      =   date("Y-m-t", strtotime($begin_month));
		$department                     =   $this->input->input_stream('department', TRUE);
		$office                         =   $this->input->input_stream('office', TRUE); 
		$ps_month                       =   $this->input->input_stream('ps_month', TRUE); 
		$ps_year                        =   $this->input->input_stream('ps_year', TRUE); 
		$user_roles                     =   $this->input->input_stream('user_roles', TRUE); 
		$reporting_to                   =   $this->input->input_stream('reporting_to', TRUE); 
		$where 							=	'';
		if($office)
		{
		$where                          =   $where.' AND office = '.$office;
		}
		if($ps_month)
		{
		$where			     	        =	$where.' AND  month(joined_date)  =' .$ps_month;
		}
		if($ps_year)
		{
		$where			     	        =	$where.' AND  year(joined_date)=' .$ps_year;
		}
		if($department)
		{
		$where                          =    $where.' AND department = '.$department;
		}
		if($reporting_to)
		{
		$where                          =    $where.' AND reporting_to = '.$reporting_to;
		}
		if($user_roles)
		{
		$where                          =    $where.' AND designation = '.$user_roles;
		}
		$data['department_list']        =   $this->sdt_department_model->get_department_list();
		$data['officearr']              =   $this->mdt_company_structures_model->get_office_list();
		$data['roles_list']				=   $this->sdt_roles_model->get_all_roles();
		########################### Total no of employees at the beginning of month ###########################
	    $data['res_one1']				=   $this->get_count( 'joined_date <= "'.$begin_month.'" AND exit_date IS NULL AND fellow_status = 1' .$where);
		$data['res_one2']				=   $this->get_count('joined_date <= "'.$begin_month.'" AND exit_date > "'.$begin_month.'"' .$where);
		$data['res_one']                =   $data['res_one1']+$data['res_one2'];
		########################### Total no of employees at the end of month ###########################
		$data['res_two1']              =   $this->get_count('joined_date <= "'.$end_month.'" AND exit_date IS NULL AND fellow_status = 1' .$where);
		$data['$res_two2']             =   $this->get_count('joined_date <= "'.$end_month.'" AND exit_date > "'.$end_month.'"' .$where); 
		$data['res_two']              =   $data['res_two1'] +$data['$res_two2'];
		########################### No of employees added during month ###########################
		$data['res_three']             =   $this->get_count('joined_date BETWEEN "'.$begin_month.'" AND "'.$end_month.'"' .$where);
		########################### No of employees left in month ###########################
		$data['res_four']              =   $this->get_count('exit_date BETWEEN "'.$begin_month.'" AND "'.$end_month.'"'.$where);
		########################### Average no of employees in  month ###########################
		if($data['res_one']                         &&  $data['res_two'])
		{
		$data['res_five']               =   ($data['res_one'] +$data['res_two'])/2;
		}
		else
		{
		$data['res_five']               =   0;
		}
		########################### Attrition rate in month ###########################
		if($data['res_four']                        &&  $data['res_five'])
		{
		$data['res_six']               =   ($data['res_four']/$data['res_five'])*100;
		$data['result_six']            =   (round($data['res_six'],2));
		}
		else
		{
		$data['result_six']         = '';
		}
		$data['office']  		        = 	$office?$office:0;
		$data['ps_month']  		        = 	$ps_month?$ps_month:0;
		$data['ps_year']  		        = 	$ps_year?$ps_year:0;
		$data['department']  		    = 	$department?$department:0;
		$data['reporting_to']  		    = 	$reporting_to?$reporting_to:0;
		$data['user_roles']  		    = 	$user_roles?$user_roles:0;
		$data['url']                    =   'hrm/attrition_report_partner_list/1'.'/'.$begin_month.'/'.$end_month;
		$data['selected']               =   'selected="selected"';
		$data['title']                 	=   '';
		$data['subtitle']              	=   ''; 
		$data['module']               	=   'hrm';
		$data['view']                 	=   'hrm/attritionReportPartner';
		//echo $this->db->last_query();		
		$this->load->view('main',$data);
		}
		
		public function attrition_report_partner_list()
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
		$key 		 		            = 	$this->input->input_stream('key', TRUE); 
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
		$common_cond                    =   $common_cond.' AND designation = '.$user_roles;
		}
		if($ps_year)
		{
		$common_cond			        =	$common_cond.' AND  year(joined_date)=' .$ps_year;
		}
		if($ps_month)
		{
		$common_cond			     	=	$common_cond.' AND  month(joined_date)=' .$ps_month;
		}
		if($office)
		{
		$common_cond                    =    $common_cond.' AND office = '.$office;
		}
		$where 							=	'fellow_id!=0';
		if(is_numeric($key))
		{
		$where                          =   $where. " AND fellow_id =".$key."";
		}
		elseif($key)
		{	
		$where	                        =   $where. " AND fellow_name LIKE '%".$key."%'";
		}
		if($type                        ==  1)
		{
		########################### Total no of employees at the beginning of month ###########################
		$data['one_cond1']				 =   'joined_date <= "'.$begin_month.'"AND exit_date IS NULL AND fellow_status = 1'.$common_cond;
		$data['res_one2']				 =   'joined_date <= "'.$begin_month.'" AND exit_date > "'.$begin_month.'"'.$common_cond;
		$fellow_ids_list1                =   $this->getEmpIDs($data['one_cond1']); 
		$fellow_ids_list2                =   $this->getEmpIDs($data['res_one2']);
		if($fellow_ids_list1                   &&  $fellow_ids_list2)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list1.') OR fellow_id IN ('.$fellow_ids_list2.')';
		}
		else if($fellow_ids_list1)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list1.')' ;
		}
		else if($fellow_ids_list2)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list2.')' ;
		}
		}
				else if($type                           ==  2)
		{
		########################### Total no of employees at the end of month ###########################
		$data['two_cond1']               =   'joined_date <= "'.$end_month.'" AND exit_date IS NULL AND fellow_status = 1 '.$common_cond; 
		$data['two_cond2']               =   'joined_date <= "'.$end_month.'" AND exit_date> "'.$end_month.'" '.$common_cond;  
		$fellow_ids_list1                =   $this->getEmpIDs($data['two_cond1']);
		$fellow_ids_list2                =   $this->getEmpIDs($data['two_cond2']);
		if($fellow_ids_list1                   &&  $fellow_ids_list2)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list1.') OR fellow_id IN ('.$fellow_ids_list2.')';
		}
		else if($fellow_ids_list1)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list1.')';
		}
		else if($fellow_ids_list2)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list2.')';
		}
		}
		else if($type                    ==  3)
		{    
		########################### No of employees added during month ###########################
		$data['three_cond']              =   'joined_date BETWEEN "'.$begin_month.'" AND "'.$end_month.'"'.$common_cond; 
		$fellow_ids_list                 =   $this->getEmpIDs($data['three_cond']);
		if($fellow_ids_list)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list.')';
		}
		}
		else if($type                    ==  4)
		{
		########################### No of employees left in month ###########################
		$data['four_cond']               =   'exit_date BETWEEN "'.$begin_month.'" AND "'.$end_month.'"'.$common_cond;
		$fellow_ids_list                 =   $this->getEmpIDs($data['four_cond']);
		if($fellow_ids_list)
		{
			$where                       =   $where.' AND fellow_id IN ('.$fellow_ids_list.')';
		}
		}
	//echo $where;
	$get_empList  					     =   $this->pagination_model->get_pagination("mdt_partners",$where,"fellow_status asc,fellow_id asc",25);
	//echo $this->db->last_query();
	return $get_empList;
}
	public function partnerIdslist($cond)
	{ 
	$query	=	$this->db->where($cond)
	->get($this->tableName);
	$result= $query->result(); 
	return $result;
	}
	
	public function get_all_partners()
	{
		$key  		                    = 	$this->input->input_stream('key', TRUE);
		$department  		            = 	$this->input->input_stream('department', TRUE); 
		$reporting_to  		            = 	$this->input->input_stream('reporting_to', TRUE); 
		$office  		                = 	$this->input->input_stream('office', TRUE); 
		$designation        		    = 	$this->input->input_stream('designation', TRUE); 
		$where 							=	'fellow_status = 1';
		if(is_numeric($key))
		{
		$where                          =  $where. " AND fellow_id =".$key."";
		}
		elseif($key)
		{	
		$where	                        =   $where. " AND fellow_name LIKE '%".$key."%'";
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
		$get_partnerList  			    =   $this->pagination_model->get_pagination("mdt_partners",$where,"fellow_id asc",25);
        return $get_partnerList;
	}
	
     public function allEmployeeEmailPhList($key,$designation,$reporting_to,$office,$gender,$joined_From_date,$joined_To_date,$fellow_status,$mobile_phone,$department)
	{
		$this->db->select('fellow_id,fellow_name,designation,department,office,reporting_to,private_email,mobile_phone,joined_date,gender,fellow_status');
		//$this->db->where('fellow_status=1');
		if(is_numeric($key) && $key != 'NA')
		{
		  $this->db->where('fellow_id ='.$key);
		}
		elseif($key && $key != 'NA')
		{	
		  $this->db->where('fellow_name LIKE "%'.$key.'%"');
		}
		if($designation && $designation != 'NA')
		{
		  $this->db->where('designation ='.$designation);
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
		if($fellow_status && $fellow_status != 'NA')
		{
		  $this->db->where('fellow_status = "'.$fellow_status.'"');
		}
		if($mobile_phone && $mobile_phone != 'NA')
		{
		  $this->db->where('mobile_phone = "'.$mobile_phone.'"');
		}
		if($department && $department != 'NA')
		{
		  $this->db->where('department = "'.$department.'"');
		}
		$result             =   $this->db->get($this->tableName);
		//echo $this->db->last_query();
		return $result->result();
	}
	
	public function download_Email_phnum($key,$department,$reporting_to,$office)
	{
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Email&PhoneNumber".date("Y_m_d_H_i_s").".xls");
		
		$partner_details        =   $this->allEmployeeEmailPhList($key,$department,$reporting_to,$office);
      //print_r($employee_details);		 
			?>
            <table width="100%" height="226" align="center" cellpadding="0" cellspacing="0" border="1" style="padding-left:20px; padding-top:20px; border:thick"> 
            <tr>
                <td align="center" width="100%" bgcolor="#99CCFF" style="font-weight:bold; font-size:16px"><b>Partner Card  </b> </td>
            </tr>
            <tr>
                <td valign="top">
                    <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0">
                        <tr class="tableHead" bgcolor="#99FFFF" style="font-weight:bold">
                            <td width="20px">	SL No						</td>
							<td	width="20px">	Partner ID 				    </td>
							<td	width="20px">	Partner Name 		        </td>
							<td	width="20px">	Email 		        	    </td>
							<td	width="20px">	Mobile Number 				</td>
                        </tr>
		     <?php
			if($partner_details)
			{
				$cnt                                 = 1;
				foreach($partner_details as  $resval)
				{	
				?>
				<tr>
					<td><?php echo $cnt++; ?></td>
					<td><?=$resval->fellow_id?></td>
					<td><?=$resval->fellow_name?></td>
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
    public function get_partner_suggestion()
    {      
        $nm                 =   $this->input->post('keyword', TRUE);
        $this->db->select('fellow_id as id, fellow_name as name, department');        
        $this->db->where('fellow_name LIKE "%'.$nm.'%" AND fellow_status=1');
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
    public function check_partner_mobile_exists($mobile)
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
}
?>