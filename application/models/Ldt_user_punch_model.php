<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_user_punch_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_user_punch';
	}
    public function check_emp_punch($user_id)
	{
		$this->db->select('count(punch_id) as cnt');
		$this->db->where('user_id',$user_id);
		$this->db->where('punch_date',date('Y-m-d'));
		$result                         =	$this->db->get($this->tableName)->row();
        return $result;
	}
    public function insert_punch($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function getCount($cond)
	{
		$query	                        =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                          =   $query->result();
        //echo $this->db->last_query();
		return count($count);
	}
	function checkPunch($cond)
	{
		$query 					        =	$this->db->select("punch_id")
												->from($this->tableName)
												->where($cond)
												->get();
        return $query->result_array();
	}    
	public function get_last_punch_date($user_id)
	{
		$this->db->select('max(punch_date) as punch_date');
		$this->db->where('user_id',$user_id);
		$this->db->where('punch_date != ',date('Y-m-d'),TRUE);
		$result                         =	$this->db->get($this->tableName)->row();        
        return $result->punch_date;
	}
	public function get_punch_ip_by_date($sel_date="")
	{
		$where                          =   "  punch_date= '".date('Y-m-d')."'";
        if($sel_date)
        {
            $where                      =  "  punch_date= '".$sel_date."'";
        }
		$this->db->select('distinct(in_ip)');
		$this->db->where($where);
		$result        					=   $this->db->get($this->tableName);
        return $result->result();
    }    
    public function get_emp_punch_det($user_id,$date)
	{
		$this->db->select('in_time,out_time,machine_in,machine_out');
		$this->db->where('user_id',$user_id);
		$this->db->where('punch_date',$date);
		$result        					=	$this->db->get($this->tableName)->row();
		return $result;
	}  
    public function get_all_emp_punch_det($user_id,$date)
	{
		$this->db->select('in_time,out_time,machine_in,machine_out,in_desc,out_desc,in_location,out_location');
		$this->db->where('user_id',$user_id);
		$this->db->where('punch_date',$date);
		$result        					=	$this->db->get($this->tableName)->row();    
		return $result;
	}  
    public function get_attendance()
	{
        $emp_id                         =   $this->input->post('emp_id', TRUE);     // Admin View
        $user_id                        =   '';
        if($emp_id)
        {
            $this->load->model('mdt_users_model');  
            $user_id                    =   $this->mdt_users_model->get_userid_by_empid($emp_id);            
        }
        $ps_month                       =   $this->input->post('ps_month', TRUE);
        $ps_year                        =   $this->input->post('ps_year', TRUE);
        $emp_name                       =   $this->input->post('emp_name', TRUE);
		$this->load->model('mdt_employees_model');
		if(!$emp_name)
		{
			$emp_name                   =   $this->mdt_employees_model->get_emp_name($emp_id);
		}
        if($user_id && $ps_month && $ps_year)
        {
            $this->load->model('tdt_user_roles_model');            
            $this->load->model('cdt_leave_calendar_model');            
            $this->load->model('time_model');  
            $this->load->model('sdt_roles_model');
            $this->load->model('tdt_employee_salary_model');
            $office_id                  =   $this->mdt_employees_model->get_employee_office($emp_id);
            $working_days               =   $this->tdt_employee_salary_model->get_total_working_days($ps_year,$ps_month,$user_id); 
            $ps_pre_det                 =   $this->tdt_employee_salary_model->calculate_total_worked_days($user_id,$ps_year,$ps_month);
            
            $primary_role               =   $this->tdt_user_roles_model->get_emp_primary_role($user_id); 
            $salary_amount              =   "Not Updated";
            $sal_det                    =   $this->tdt_employee_salary_model->get_emp_salary_details($emp_id);
            if($sal_det                !=   NULL)
            {
                $salary_amount          =   $sal_det->salary_amount;
            }
            $sty                        =   'style="font-weight:bold;"';            
            $acc_div                    =   $ps_pre_det['acc_div'];
            $tot_machine_days           =   $ps_pre_det['tot_machine_days'];
            $e_days                     =   '';
            $extra_days                 =   '';
            
            if($ps_pre_det['extra_days'])
            {
                if($primary_role        ==  1)
                {  
                    $extra_days         =   '<tr '.$sty.'> <td>'.$extra_days.'Extra Worked Days</td> <td>'.$ps_pre_det['first_extra'].'</td></tr>'; 
                }
                else
                {
                    $extra_days             =   '<tr '.$sty.'> <td>'.$extra_days.'Extra Worked Days</td> <td>'.$ps_pre_det['extra_days'].'</td></tr>'; 
                     
                }                
            }            
            $sty                        =   'style="font-weight:bold;"';            
            $apps_present_days          =   $ps_pre_det['apps'];
            $paid_days                  =   (($ps_pre_det['machine']+$ps_pre_det['extra_days'])-$ps_pre_det['payment_leave']);
            '<tr '.$sty.'> <td>Fixed CTC</td> <td>'.$salary_amount.'</td></tr>';
            echo '<table border="0" cellspacing="0" cellpadding="0" class="table ">
                <thead>
                    <tr class="bg-info">
                        <th>'.$emp_name.'</th>
                        <th>'.date("F", mktime(0, 0, 0, $ps_month, 15)).' '.$ps_year.'</th>
                        <th title="close" id="'.$emp_id.'" class="closeAt">X</th>
                    </tr>
                </thead>
                    <tbody>
                    <tr '.$sty.'> <td>Working Days </td> <td>'.$working_days.'</td></tr>   
                    <tr '.$sty.'> <td>App Present On Working Days </td> <td>'.$apps_present_days.'</td></tr>   
                </tbody>
            </table>            
            <table border="0" cellspacing="0" cellpadding="0" class="table">
            <thead>
            <tr class="bg-info">
                <th>    Date            </th>                
                <th>    Apps-In         </th>
                <th>    Apps-Out        </th>
                <th>    Apps-Total      </th>
                <th>    Day             </th>
            </tr>
            </thead><tbody>';              
            if($primary_role)
            {
                $to_be_present_days     =   $this->sdt_roles_model->get_to_be_present_days($primary_role);
            }
            else
            {
                $to_be_present_days     =   '1,2,3,4,5,6';
            }
            $daysInMonth 				= 	cal_days_in_month(CAL_GREGORIAN,$ps_month,$ps_year);
            for($i	=	1;	$i	<=	$daysInMonth;	$i++)
            {
                if($i%2					==	0)
                {
                    $sty                =   'style="background:#FFF;"';
                }
                else
                {
                    $sty                =   'style="background:#FFF;"';
                }
                $date		     		=	$ps_year."-".$ps_month."-".$i;                                  
                $in_time                =   '-- -- --';
                $out_time               =   '-- -- --';
                $machine_in             =   '-- -- --';
                $machine_out            =   '-- -- --';
                $in_desc                =   '';
                $out_desc               =   '';
                $total_time             =   '-- -- --';
                $machine_total          =   '-- -- --';
                $rule_msg_m				=	'';	
                $rule_msg_a				=	'';
                $apps_diff              =   '';
                $machine_diff           =   '';
                $day_type               =	''; 
                $in_location            =   '';
                $out_location           =   '';
                $daily_details			=	$this->get_all_emp_punch_det($user_id, $date);
                $day_type				=	$this->cdt_leave_calendar_model->get_cal_leave_desc($date);
                
                if($day_type)
                {
                    $day_type           =   '<span style="color:red">'.$day_type.'</span>';
                } 
                else                
                {
                    $day_type           =   $this->get_holidays_based_on_role($date,$to_be_present_days);
                    if($day_type)
                    {
                        $day_type       =   '<span style="color:red">'.$day_type.'</span>';
                    }
                    else
                    {    
                        if($daily_details)
                        {
                            $day_type   =   '<span style="color:green">Working</span>';
                        }
                        else if(date('Y-m-d',strtotime($date))   <   date('Y-m-d'))
                        {
                            $day_type   =   '<span style="color:blue">Leave</span>';
                        }  
                        else
                        {
                             $day_type   =   '<span style="color:green">Working</span>';
                        }
                    }
                }
                if($daily_details)
                {
                    $in_time            =   $daily_details->in_time;
                    $out_time           =   $daily_details->out_time;
                    $machine_in         =   $daily_details->machine_in;
                    $machine_out        =   $daily_details->machine_out;
                    $in_desc            =   $daily_details->in_desc;
                    $out_desc           =   $daily_details->out_desc;
                                        
                    $in_location        =   $daily_details->in_location;                    
                    $out_location       =   $daily_details->out_location; 
                                       
                    $total_time         =   $this->time_model->timeDiference($out_time,$in_time);
                    //$daily_details->total_time;
                    $apps_diff          =	$this->sdt_roles_model->calculate_deduction_factor($in_time,$out_time,$primary_role);
                }    
                //$showInDesc 	       	=	($in_desc)?'<i class="fa fa-commenting" title="'.$in_desc.'" aria-hidden="true"></i>':'';
                //$showOutDesc	       	=	($out_desc)?'<i class="fa fa-commenting" title="'.$out_desc.'" aria-hidden="true"></i>':'';
                
                $showInDesc 	       	=	($in_location)?'<i class="fa fa-commenting" title="'.$in_location.'" aria-hidden="true"></i>':'';
                $showOutDesc	       	=	($out_location)?'<i class="fa fa-commenting" title="'.$out_location.'" aria-hidden="true"></i>':'';
                
                $rule_msg_a				=	($apps_diff)?"title='Deducted ".$apps_diff." Days as per Rule'":'';
                $rule_msg_m				=	($apps_diff)?"title='Deducted ".$apps_diff." Days as per Rule'":'';
                $total_time				=	($rule_msg_a)?'<span style="">'.$total_time.'</span>':$total_time;
                ?>
                <tr <?php echo $sty;?>>
                    <td	>	<?=$i?>								</td>                    
                    <td >	<?=$in_time.' '.$showInDesc?>		</td>
                    <td >	<?=$out_time.' '.$showOutDesc?>		</td>
                    <td >	<?=$total_time?></td>
                    <td >	<?=$day_type?></td>
                </tr>    
                <?php
            }
            echo '</tbody>  </table>  ';
        }
        else
        {
            echo 'something went wrong!!!';
        }
	}
    public function get_holidays_based_on_role($date,$to_be_present_days)
	{                     
        $dayofweek                      =   date('w', strtotime($date));
        if(strpos($to_be_present_days, $dayofweek) !== false) 
        {
            return '';
        }
        else
        {
            if($dayofweek               ==  1)
            {
                return 'Monday Holiday';
            }
            else if($dayofweek          ==  2)
            {
                return 'Tuesday Holiday';
            }
            else if($dayofweek          ==  3)
            {
                return 'Wednsday Holiday';
            }
            else if($dayofweek          ==  4)
            {
                return 'Thursday Holiday';
            }
            else if($dayofweek          ==  5)
            {
                return 'Friday Holiday';
            }
            else if($dayofweek          ==  6)
            {
                return 'Saturday Holiday';
            }
        }
	}
    public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);        
    }
    public function get_user_punch_details($userid,$start,$end)
	{
		$this->db->where("user_id=".$userid);
		$this->db->where("punch_date >=", $start);
		$this->db->where("punch_date <=", $end);
		$result                         =	$this->db->get($this->tableName)->result();
		return $result;      
    }
    public function check_emp_punch_today($user_id)
	{
		$this->db->where('user_id',$user_id);
		$this->db->where('punch_date',date('Y-m-d'));
		$result                         =	$this->db->get($this->tableName)->row();
		return $result;
	}
    public function user_today_list()
    {          
        $emp_days_search                =   $this->input->input_stream('emp_days_search', TRUE);
        if($emp_days_search)
        {
            $punch_date                    =   date("Y-m-d", strtotime($emp_days_search));
        }
        else
        {
             $punch_date                     =   date('Y-m-d');
        } 
        $where 							=	'user_id != 0 AND punch_date= "'.$punch_date.'"';
        $emp_name_search                =   $this->input->input_stream('emp_name_search',TRUE);
        $department                     =   $this->input->input_stream('department', TRUE);         
        $emp_days_search                =   $this->input->input_stream('emp_days_search', TRUE);
        $reporting_to                   =   $this->input->input_stream('reporting_to', TRUE);
        $office                         =   $this->input->input_stream('office', TRUE);

       
        if(is_numeric($emp_name_search))
        {
             $where                          =  $where. " AND emp_id =".$emp_name_search;
        }
        elseif($emp_name_search)
        {   
            $where                          =   $where. " AND employee_name LIKE '%".$emp_name_search."%'";
        }

        if($department)
        {
            $where                          =  $where. " AND department ='".$department."'";
        }
        
        if($reporting_to)
        {
            $where                          =  $where. " AND reporting_to ='".$reporting_to."'";
        }
        if($office)
        {
            $where                          =  $where. " AND office ='".$office."'";
        }
        

        ////////////////////////////////////////////////////////////////////////
        ///  Note Using Core PHP Query for fetching user_today_list         ////
        ////////////////////////////////////////////////////////////////////////
        $query                              =   'select p.* from ldt_user_punch p, mdt_employees hrm where p.user_id=hrm.emp_id AND '.$where;
        $get_empList                        =   $this->pagination_model->get_pagination_sql($query,25);
        return $get_empList;        
    }//end of function user_today_List
    public function user_absent_today_list()
    {
        $sql                            =   '';
        $punch_date                     =   date('Y-m-d');
        $where                          =   'e.employee_status=1 AND p.user_id is null';
        $emp_name_search                =   $this->input->input_stream('emp_name_search',TRUE);
        $department                     =   $this->input->input_stream('department', TRUE);         
        $emp_days_search                =   $this->input->input_stream('emp_days_search', TRUE);
        $reporting_to                   =   $this->input->input_stream('reporting_to', TRUE);
        $office                         =   $this->input->input_stream('office', TRUE);
        //<!--START--><!--User Absent from 2/3/4 days-->
        $absent_from                    =   $this->input->input_stream('absent_from', TRUE);
        
       
        if(is_numeric($emp_name_search))
        {
             $where                          =  $where. " AND emp_id =".$emp_name_search;
        }
        elseif($emp_name_search)
        {   
            $where                          =   $where. " AND employee_name LIKE '%".$emp_name_search."%'";
        }

        if($department)
        {
            $where                          =  $where. " AND department ='".$department."'";
        }
        
        if($reporting_to)
        {
            $where                          =  $where. " AND reporting_to ='".$reporting_to."'";
        }
        if($office)
        {
            $where                          =  $where. " AND office ='".$office."'";
        }
        if($emp_days_search)
        {
             $punch_date                    =   date("Y-m-d", strtotime($emp_days_search));
        }
        if($absent_from)
        {
            
            $today                      =   date('Y-m-d');
            if($absent_from == 'two_days')
            {
                $timestamp              =   strtotime($today);
                if(date('D', $timestamp)=== 'Mon') 
                {
                    $lastDate               =   date('Y-m-d',strtotime("-2 days"));
                }
                else
                {
                    $lastDate               =   date('Y-m-d',strtotime("-1 days"));
                }
                $piQry                      =   "SELECT user_id FROM ldt_user_punch WHERE punch_date BETWEEN  '".$lastDate."' AND   '".$today."'";   
                $sql                        = "select distinct e.emp_id, e.employee_name,e.private_email,e.mobile_phone,e.alternate_phone, e.department, e.designation, e.reporting_to,e.office from  mdt_employees as e left join ( ".$piQry.") as p on e.emp_id = p.user_id where ".$where;
            } 
            else if($absent_from == 'three_days')
            {
                $timestamp                  =   strtotime($today);
                if(date('D', $timestamp)    === 'Mon') 
                {
                    $lastDate               =   date('Y-m-d',strtotime("-3 days"));
                }
                else if(date('D', $timestamp)=== 'Tue') 
                {
                    $lastDate               =   date('Y-m-d',strtotime("-3 days"));
                }
                else
                {
                    $lastDate               =   date('Y-m-d',strtotime("-2 days"));
                }

                $piQry                      =   "SELECT user_id FROM ldt_user_punch WHERE punch_date BETWEEN  '".$lastDate."' AND   '".$today."'";   
                $sql                        = "select distinct e.emp_id, e.employee_name,e.private_email,e.mobile_phone,e.alternate_phone, e.department, e.designation, e.reporting_to,e.office from  mdt_employees as e left join ( ".$piQry.") as p on e.emp_id = p.user_id where ".$where;
            }
            else if($absent_from == 'four_days')
            {
                $timestamp                  =   strtotime($today);
                if(date('D', $timestamp)    === 'Mon') 
                {
                    $lastDate               =   date('Y-m-d',strtotime("-4 days"));
                }
                else if(date('D', $timestamp)=== 'Tue') 
                {
                    $lastDate               =   date('Y-m-d',strtotime("-5 days"));
                }
                else if(date('D', $timestamp)=== 'Wed') 
                {
                    $lastDate               =   date('Y-m-d',strtotime("-4 days"));
                }
                else
                {
                    $lastDate               =   date('Y-m-d',strtotime("-3 days"));
                }

                $piQry                      =   "SELECT user_id FROM ldt_user_punch WHERE punch_date BETWEEN  '".$lastDate."' AND   '".$today."'";   
                $sql                        = "select distinct e.emp_id, e.employee_name,e.private_email,e.mobile_phone,e.alternate_phone, e.department, e.designation, e.reporting_to,e.office from  mdt_employees as e left join ( ".$piQry.") as p on e.emp_id = p.user_id where ".$where;
            }
        }

        ///////////////////////////////////////////////////////////////////////
        ///  Note Using Core PHP Query for fetching user_absent_today_list ////
        ///////////////////////////////////////////////////////////////////////
        if($sql)
        {
            $query = $sql;
        }
        else
        {
            $query = "select distinct e.emp_id, e.employee_name, e.private_email,e.mobile_phone,e.alternate_phone, e.department, e.designation, e.reporting_to,e.office from  mdt_employees as e left join (SELECT user_id FROM ldt_user_punch WHERE punch_date = '".$punch_date."') as p on e.emp_id = p.user_id where ".$where;
        }
       // echo $query;
        $get_empList                    =   $this->pagination_model->get_pagination_sql($query,25);
		return $get_empList; 

    }
    public function user_absent_today_list_formanager($userid)
    {
        $punch_date                     =   date('Y-m-d');
        $where                          =   "e.reporting_to='".$userid."'AND e.employee_status=1 AND p.user_id is null";
        $emp_name_search                =   $this->input->input_stream('emp_name_search',TRUE);
        $department                     =   $this->input->input_stream('department', TRUE); 
        $emp_id                         =   $this->input->input_stream('emp_id_search', TRUE);
        $emp_days_search                =   $this->input->input_stream('emp_days_search', TRUE);        
        $office                         =   $this->input->input_stream('office', TRUE);

        if(is_numeric($emp_name_search))
        {
             $where                          =  $where. " AND emp_id =".$emp_name_search;
        }
        elseif($emp_name_search)
        {   
            $where                          =   $where. " AND employee_name LIKE '%".$emp_name_search."%'";
        }
        if($department)
        {
            $where                          =  $where. " AND department ='".$department."'";
        }         
        if($office)
        {
            $where                          =  $where. " AND office ='".$office."'";
        }      
        if($emp_days_search)
        {
             $punch_date                    =   date("Y-m-d", strtotime($emp_days_search));
        }
        ///////////////////////////////////////////////////////////////////////
        ///  Note Using Core PHP Query for fetching user_absent_today_list ////
        ///////////////////////////////////////////////////////////////////////
        
        $query = "select distinct e.emp_id, e.employee_name, e.private_email,e.mobile_phone,e.alternate_phone, e.department, e.designation, e.reporting_to from  mdt_employees as e left join (SELECT user_id FROM ldt_user_punch WHERE punch_date = '".$punch_date."') as p on e.emp_id = p.user_id where ".$where;        
        $get_empList                    =   $this->pagination_model->get_pagination_sql($query,25);        
        return $get_empList;
    }
}