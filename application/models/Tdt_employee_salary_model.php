<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tdt_employee_salary_model extends CI_Model {
	function __construct() {
		$this->tableName                =   'tdt_employee_salary'; 
		$this->primaryKey               =   'salary_id';
	}
    public function get_emp_salary_details($emp_id)
	{
		$this->db->where('emp_id',$emp_id);
		$this->db->where('salary_status','1');
		$result                         =   $this->db->get($this->tableName)->row();
		return $result;
	}
    public function get_emp_variable($emp_id)
	{
		$this->db->select('variables');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('salary_status','1');
		$result                         =   $this->db->get($this->tableName)->row();
        if($result)
        {
            return $result->variables;
        }
		else
        {
            return 0;
        }
	} 
    public function get_emp_salary_count($emp_id)
	{
		$this->db->where('emp_id',$emp_id);
		$this->db->where('salary_status','1');
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();       
		return $retval;      
	}  
    public function arrears_calculation($emp_id='',$days='',$month='',$year='')
    {
        $emp_id                         =   ($this->input->post('emp_id'))?($this->input->post('emp_id')):$emp_id;
        $days                           =   ($this->input->post('extra_days'))?($this->input->post('extra_days')):$days;
        $month                          =   ($this->input->post('ps_month'))?($this->input->post('ps_month')):$month;
        $year                           =   ($this->input->post('ps_year'))?($this->input->post('ps_year')):$year;
        $sal_det                        =   $this->get_emp_salary_details($emp_id);
        $salary_amount                  =   $sal_det->salary_amount;  
        $total_working_days             =   $this->get_total_working_days($year,$month,$emp_id);
        if($days && $salary_amount && $total_working_days)
        {    
            $result                     =   ($days * $salary_amount)/$total_working_days;
            return round($result);             
        }
        else
        {
            return 0;
        }
    }  
    public function get_total_working_days($year,$month,$emp_id)
	{         
        $this->load->model('tdt_user_roles_model');
        $this->load->model('sdt_roles_model');
        $this->load->model('cdt_leave_calendar_model');
        $this->load->model('mdt_users_model');
        $this->load->model('mdt_employees_model');
        $office_id                      =   $this->mdt_employees_model->get_employee_office($emp_id);        
        $user_id                        =   $this->mdt_users_model->get_userid_by_empid($emp_id);
        if($user_id)
        $primary_role                   =   $this->tdt_user_roles_model->get_emp_primary_role($user_id); 
        if($primary_role)
        {
            $to_be_present_days         =   $this->sdt_roles_model->get_to_be_present_days($primary_role);
            if($to_be_present_days)
            {
                $to_be_present_days_arr =   str_replace("1","Mon",$to_be_present_days);
                $to_be_present_days_arr =   str_replace("2","Tue",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("3","Wed",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("4","Thu",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("5","Fri",$to_be_present_days_arr);
                $to_be_present_days_arr =   str_replace("6","Sat",$to_be_present_days_arr);
                $to_be_present_days_arr =   explode(',',$to_be_present_days_arr); 
            }
            $dayArr                     =   $to_be_present_days_arr;
            $total_leaves_det           =   $this->cdt_leave_calendar_model->get_cal_leave($year,$month);
            $leaveDateArr               =   array();
            if($total_leaves_det       !=   null)
            {
                foreach($total_leaves_det	as  $row)
                {
                    $leaveDateArr[]		=	$row->cal_leave_full_date;
                }
            }     
            $number                     =   cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $cnt 						=	0;
            if($month                   <   10)
            {
                $month                  =   '0'.$month;
            }
            for($i = 1; $i <=$number; $i++)
            {
                $date                 	=   $year."-".$month."-".str_pad($i, 2, '0', STR_PAD_LEFT);
                $day             		=   date('D', strtotime($date));
                if(in_array($day, $dayArr))
                {
                	if(!(in_array($date, $leaveDateArr)))
                	{
                		$cnt 			=	$cnt+1;
                	}
                }                   
            }
            return $cnt;            
        }
        else
        {
            return 0;
        }        
	}
    public function salary_generation()
    {        
        $emp_id                         =   $this->input->post('emp_id');
        $variable                       =   $this->input->post('variable');
        $extra_days                     =   $this->input->post('extra_days');
        $arrears                        =   $this->input->post('arrears');
        $month                          =   $this->input->post('ps_month');
        $year                           =   $this->input->post('ps_year');
        $paid_days                      =   $this->input->post('paid_days');
        $total_working_days             =   $this->get_total_working_days($year,$month,$emp_id);     
        $this->load->model('ldt_user_punch_model');
        $this->load->model('ldt_leave_wallet_model');
        $this->load->model('tdt_employee_bank_account_model');
        $this->load->model('mdt_employees_model');
        $this->load->model('ldt_pay_slip_details_model');
        $this->load->model('ldt_advance_salary_model'); 
        $this->load->model('mdt_users_model');
        $this->load->model('ldt_arrears_model');
        $this->load->model('ldt_loan_model');
        $user_id                        =   $this->mdt_users_model->get_userid_by_empid($emp_id);
        $total_days                     =   $this->ldt_user_punch_model->getCount("user_id = ".$user_id." and punch_date like '".$year."-".$month."%' GROUP BY punch_date");        
		$leaveBalance					=	$this->ldt_leave_wallet_model->get_earned_leave($emp_id);
        $year_month                     =   $year."-".$month;
		$empSalaryDetails				=	$this->get_emp_salary_details($emp_id); 
		$grossFixedCTC					= 	$empSalaryDetails->salary_amount;
		$existing_var					= 	$empSalaryDetails->variables;
		$TDS						    = 	$empSalaryDetails->tds;
		$grossSalary      			    = 	$empSalaryDetails->grossSalary;
		$empBankDetails					=	$this->tdt_employee_bank_account_model->get_emp_bank_details($emp_id);
		$bankAccount					= 	$empBankDetails->acc_bank_account_number;
		$pfAccount						= 	$empBankDetails->pf_account_number;
        $embdoj                         =   $this->mdt_employees_model->getEmployeeJoiningDate($emp_id);
        $empName                        =   $this->mdt_employees_model->get_emp_name($emp_id);
        $ps_present_days                =   '';
        $Advance                        =   $this->ldt_advance_salary_model->get_emp_advance_salary($emp_id,$year,$month);
        $Advance                        =   ($Advance)?$Advance:0;
        $loan_emi                       =   $this->ldt_loan_model->get_loan_by_emp_id($emp_id,$month,$year);
        $loan_emi                       =   ($loan_emi)?$loan_emi:0;        
        /*$ps_present_det                 =   $this->calculate_total_worked_days($emp_id,$year,$month);
        $ps_present_days                =   $ps_present_det['machine'];
        $ps_present_det['apps'];  */  
        $ps_present_days                =   $paid_days;
        if($ps_present_days             >   $total_working_days)
        {
            $ps_present_days            =   $total_working_days;
        }        
		$totalPD						= 	 $ps_present_days + $leaveBalance->temp_leave_bal;        
		if ($totalPD >= $total_working_days) 
		{
			$leaveBal	 				= 	$totalPD - $total_working_days;
			$totalPD 					= 	$total_working_days;
			$usedLeave					=   $leaveBalance->used_leave + ($leaveBalance->temp_leave_bal -  $leaveBal);
		}
		else
		{
			$leaveBal	 				= 	0;
			$usedLeave					=   $leaveBalance->used_leave + $leaveBalance->temp_leave_bal;
		}
		$leaveDeducted					= 	$leaveBalance->temp_leave_bal - $leaveBal;
		$earnedLeaveArray				= 	array('used_leave'=>$usedLeave,'leave_balance'=>$leaveBal);
		$updateLeaveBal					=	$this->ldt_leave_wallet_model->update($earnedLeaveArray,'emp_id='.$emp_id);
        
        ##########  Gross Fixed CTC (From Table) ##############     	   
        $grossFixedCTC; 
        ##########  Gross Variable CTC Calculation ############
        $vf_sum                         =   $variable + $grossFixedCTC;
        if($vf_sum                      >   21000   && ($variable > 0))
        {
            $grossVariableCTC           =   $variable;
        }
        else if($grossFixedCTC          <   21000   && ($variable > 0))
        {
            $grossVariableCTC           =   22000 - $grossFixedCTC;  
        }   
        else
        {
            $grossVariableCTC           =   0;
        }   
        ########## Gross Total CTC ############################
        if($grossVariableCTC)
        { 
            $grossTotalCTC              =   $grossVariableCTC + $grossFixedCTC;
            if($grossSalary            >=   $grossTotalCTC)
            {
                $grossTotalCTC          =   $grossSalary;
            }
            else
            {
                $grossTotalCTC          =   $grossTotalCTC;
            }
        }
        else
        {
            $grossTotalCTC              =   $existing_var + $grossFixedCTC;  
        }   
        ########## Net Fixed CTC   ############################
        $monthNetCTC					=	$grossFixedCTC*($totalPD/$total_working_days);
		$monthNetCTC					=	round($monthNetCTC, 2);		
        ####################  Basic  ##########################
		$basic						    =	($monthNetCTC*50)/100; 
        ####################  LTA    ##########################
        if($monthNetCTC                 <   25000)
        {
			$LTA						=	0;	
		}
		else
		{
			$LTA						=	1250;	
		}
        ###################  Medical reimbursement  ###########
        if($monthNetCTC                 <   25000)
        {
            $Medical                    =   0;
        }
        else
        {
            $Medical                    =   1250;
        }        
        ####################  Conveyance    ##########################
        if($monthNetCTC                 <   15000) 
        {
            $Conveyance                 =   0;
        }
        else
        {
            $Conveyance                 =   800;
        }
        ####################  Employee  PF    ##############      
		$con							=	($basic*12)/100;
        if($con                         <   1800)
        {
            $empPF                      =   $con;
        }
        else
        {
            $empPF                      =   1800;
        }
        ####################  Employer PF  ####################
		$emprPF							=	($empPF*13.61)/12;	
        ####################  Professional tax ################
        if($grossFixedCTC               >=  15000)
        {
            $PT                         =   200;
        }
        else
        {
            $PT                         =   0;
        }		
        ####################  GMC #############################
        $gmc_gpa						=	250;
        ####################  Gratuity ########################
        if($grossFixedCTC              <= 20000)
		{
			$gratuity					=	50;	
		}
		else 
		{
			$gratuity					=	100;
		}         
        ####################  HRA    ##########################        
        $fa_diff                        =   $monthNetCTC - ($basic + $LTA + $Medical + $Conveyance + $empPF + $emprPF + $PT + $gmc_gpa + $gratuity + ((50*$basic)/100));
        $ot_diff                        =   $monthNetCTC - ($basic + $LTA + $Medical + $Conveyance + $empPF + $emprPF + $PT + $gmc_gpa + $gratuity);
        $sal_25                         =   (25*$monthNetCTC)/100;        
        if($fa_diff                     <   0)
        {
            $HRA                        =   0;
        }
        if($ot_diff                     >   $sal_25)
        {
            $HRA                        =   $sal_25; 
        }
        else if($ot_diff                <   0)
        {
            $HRA                        =   0; 
        }
        else
        {
           $HRA                         =   $ot_diff;    
        }
        ####################  Special allowance ################		  
		$specialAllowance				=	$monthNetCTC - ($basic + $HRA + $LTA + $Medical + $Conveyance + $empPF + $emprPF + $PT + $gmc_gpa + $gratuity);
        ####################  Deductions   #####################
        $total_deductions				=	$empPF + $emprPF + $PT + $gmc_gpa + $gratuity + $TDS + $Advance + $loan_emi;
        ####################  Net Payable #######################
		$NetPayable						= 	$monthNetCTC + $arrears + $variable - $total_deductions;         
		$ps_file_code					=	"EMPPS_".$emp_id."_".$month."_".$year;
		$add_arr						=	array('ps_emp_id'=>$emp_id,'ps_month'=>$month,'ps_year'=>$year,'ps_file_code'=>$ps_file_code,'ps_added_by'=>$this->session->userdata('userid'),'ps_present_days'=>$ps_present_days,'ps_basic'=>$basic,'ps_hra'=>$HRA,'ps_lta'=>$LTA, 'ps_med_reim'=>$Medical,'ps_conveyance'=>$Conveyance,'ps_arrears'=>$arrears,'ps_tds'=>$TDS,'ps_adv_sal'=>$Advance,'ps_pt'=>$PT,'ps_gmc_gpa'=>$gmc_gpa,'ps_gratuity'=>$gratuity,'ps_empl_contri_pf'=>$empPF,'ps_empyr_contri_pf'=>$emprPF,'ps_special_allow'=>$specialAllowance,'ps_net_pay'=>$NetPayable,'ps_net_sal'=>$monthNetCTC,'ps_tot_ctc'=>$grossTotalCTC,'ps_variable'=>$variable,'ps_gross_variable'=>$grossVariableCTC,'ps_loan_amt'=>$loan_emi,'ps_deducted_leave'=>$leaveDeducted,'ps_extra_days'=>$extra_days);	
        $ps_generated                   =   $this->ldt_pay_slip_details_model->check_emp_pay_slip_generated($emp_id,$month,$year);	
		if($ps_generated)
		{
            $updatePayslip              =   $this->ldt_pay_slip_details_model->update($add_arr,"ps_emp_id=".$emp_id." AND ps_month=".$month." AND ps_year=".$year);
            $la_date                    =   '';            
            $la_date                    =   $year.'-'.$month;
            $la_date                    =   date('Y-m',strtotime($la_date));
            $la_id                      =   $this->ldt_arrears_model->get_laid_by_emp_id($emp_id,$la_date);
            if($la_id)
            {
                $this->ldt_arrears_model->update(array('paid_incentive'=>$variable),'la_id='.$la_id);
            }
            else
            {
                $this->ldt_arrears_model->insert(array('emp_id'=>$emp_id,'paid_incentive'=>$variable,'la_date'=>date('Y-m-d',strtotime($la_date)),'la_added_by'=>$this->session->userdata('userid')));
            } 
            $msg                        =   $empName.' Pay Slip Updated Successfully.';
		}
		else
		{
			$insertPayslip				=	$this->ldt_pay_slip_details_model->insert($add_arr);
            $la_date                    =   '';            
            $la_date                    =   $year.'-'.$month;
            $la_date                    =   date('Y-m',strtotime($la_date));
            $la_id                      =   $this->ldt_arrears_model->get_laid_by_emp_id($emp_id,$la_date);
            if($la_id)
            {
                $this->ldt_arrears_model->update(array('paid_incentive'=>$variable),'la_id='.$la_id);
            }
            else
            {
                $this->ldt_arrears_model->insert(array('emp_id'=>$emp_id,'paid_incentive'=>$variable,'la_date'=>date('Y-m-d',strtotime($la_date)),'la_added_by'=>$this->session->userdata('userid')));
            } 
            $msg                        =   'Pay Slip Generated Successfully.';
		}	
        return $msg;
    }
    public function calculate_total_worked_days($emp_id,$year,$month)
    {
        $this->load->model('ldt_user_punch_model');
        $this->load->model('tdt_user_roles_model');
        $this->load->model('sdt_roles_model');        
        $this->load->model('mdt_users_model');
        $this->load->model('cdt_leave_calendar_model');
        $this->load->model('mdt_employees_model');
        $office_id                      =   $this->mdt_employees_model->get_employee_office($emp_id);    
        $user_id                        =   $this->mdt_users_model->get_userid_by_empid($emp_id);        
        $primary_role                   =   $this->tdt_user_roles_model->get_emp_primary_role($user_id); 
        $to_be_present_days             =   $this->sdt_roles_model->get_to_be_present_days($primary_role);
        $to_be_present_days             =   ($to_be_present_days)?($to_be_present_days):'1,2,3,4,5,6';
        $daysInMonth 					= 	cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $working_days                   =   $this->get_total_working_days($year,$month,$emp_id); 
        $mDed	                        =	0;
        $aDed	                        =	0;
        $extra                          =   0;
        for($i	=	1;	$i	<=	$daysInMonth;	$i++)
        {             
            $date					    =	$year."-".$month."-".$i;
            $daily_details				=	$this->ldt_user_punch_model->get_emp_punch_det($user_id,$date);
            $day_type				    =	$this->cdt_leave_calendar_model->get_cal_leave_desc($date);
            
            if($day_type)
            {
                $day_type               =   '<span style="color:red">'.$day_type.'</span>'; // non working
            } 
            else                
            {
                $day_type               =   $this->ldt_user_punch_model->get_holidays_based_on_role($date,$to_be_present_days);
                if($day_type)
                {
                    $day_type           =   '<span style="color:red">'.$day_type.'</span>'; // non working
                }
                else
                {    
                    $day_type           =   ''; //working day
                }
            }            
            $in                         =   '';
            $out                        =   '';
            $m_in                       =   '';
            $m_out                      =   '';
            if($daily_details)
            {
                $in                     =   $daily_details->in_time;
                $out                    =   $daily_details->out_time;                
                $m_in                   =   $daily_details->machine_in;                
                $m_out                  =   $daily_details->machine_out;                
            }               
            $apps_diff                  =   $this->sdt_roles_model->calculate_deduction_factor($in,$out,$primary_role);                
            $machine_diff			    =	$this->sdt_roles_model->calculate_deduction_factor($m_in,$m_out,$primary_role);            
            $aDed					    =	$aDed + $apps_diff;
            if($day_type)           
            {
               if($daily_details)
               {
                   $extra               =   $extra + (1-$machine_diff); // NON WORKING DAY
               }                
            }
            else
            {
                $mDed                   =	$mDed + $machine_diff;    //  WORKING DAY
            }            
        }        
        $worked_days_machine			=	$working_days - $mDed;
        $worked_days_apps				=	$daysInMonth - $aDed;
        $ret_arr                        =   array('machine'=>$worked_days_machine,'apps'=>$worked_days_apps,'acc_div'=>'','tot_machine_days'=>'','extra_days'=>$extra,'first_extra'=>'', 'payment_leave'=>'');
        $sty                            =   'style="font-weight:bold;"';
        if($primary_role                ==  39)
        {                   
            $acc_div                    =   '';
            $this->load->model('old_app_model');
            //$this->load->model('mdt_employees_model');
            $doj                        =   $this->mdt_employees_model->getEmployeeJoiningDate($emp_id);
            $tt_det                     =   $this->old_app_model->get_ttt_achieved($emp_id,$month,$year,$doj,$primary_role,$office_id,$to_be_present_days); 
            $ttd                        =   $tt_det[0]+$tt_det[3]; // TT and mcd for new joinees
            $tt_hrs                     =   $tt_det[1];       
            $ten_less30                 =   $tt_det[2]; 
            $ten_less30_extra           =   ($tt_det[4])?(' (<span title="Machine days Extra Worked">'.$tt_det[4].'</span>)'):'';
            $for_newj                   =   ($tt_det[3])?(' (<span title="Machine days added">'.$tt_det[3].'</span>)'):'';
            $nw_extra_tt                =   $tt_det[5];
            $nw_extra_hrs               =   $tt_det[6];
            if(!$ten_less30)
            {
                $tot_machine_days       =   $ret_arr['machine'];
                if($tot_machine_days    <   $ttd)
                {
                    $ret_arr['machine'] =   $tot_machine_days;
                }
                else if($ttd            <   $tot_machine_days)
                {
                    $ret_arr['machine'] =   $ttd;
                } 
                
                $nwd_ext_mc             =   $ret_arr['extra_days'];
                if($nwd_ext_mc          <   $nw_extra_tt)
                {
                    $ret_arr['extra_days'] =   $nwd_ext_mc;
                }
                else if($nw_extra_tt    <   $nwd_ext_mc)
                {
                    $ret_arr['extra_days'] =   $nw_extra_tt;
                } 
                $acc_div                 =   '<tr '.$sty.'> <td>Talktime && Achieved Days</ </td> <td>'.$tt_hrs.' => '.$tt_det[0].$for_newj.' '.$ten_less30_extra.'</td></tr>'; 
                if($nw_extra_tt || $nw_extra_hrs)
                {
                    $acc_div            =   $acc_div.'<tr '.$sty.'> <td>Talktime && Achieved Days (Extra Worked)</ </td> <td>'.$nw_extra_hrs.' => '.$nw_extra_tt.'</td></tr>'; 
                } 
            }   
            $ret_arr['acc_div']         =   $acc_div;
            $ret_arr['tot_machine_days']=   $tot_machine_days;
            $ret_arr['first_extra']     =   $extra;
        }  
        else if($primary_role           ==  40  ||  $primary_role   ==  75  ||  $primary_role   ==  63)
        {
            $acc_div                    =   '';
            $this->load->model('Old_assoprfollowups_attendance_log_model');
            $leaveDeductionSum          =   $this->Old_assoprfollowups_attendance_log_model->get_sum_of_leave_deducted($emp_id, $month, $year);  
            $acc_div                    =   $acc_div.'<tr '.$sty.'> <td>Payment FU/PP Leave Deduction</ </td> <td>'.($leaveDeductionSum?$leaveDeductionSum:0).'</td></tr>'; 
            $ret_arr['payment_leave']   =   ($leaveDeductionSum?$leaveDeductionSum:0);
            $ret_arr['acc_div']         =   $acc_div;
        }
        return $ret_arr;
    }
    public function emp_pay_slip($e_id='',$e_mo='',$e_ye='')
    {      
        $this->load->model('ldt_pay_slip_details_model');
        $this->load->model('mdt_employees_model');
        $this->load->model('sdt_department_model');
        $this->load->model('sdt_designations_model');
        $this->load->model('tdt_employee_bank_account_model');
        $this->load->model('ldt_leave_wallet_model');
        $this->load->model('mdt_users_model');
        $emp_id						    =	$this->input->input_stream('emp_id', TRUE);
        $emp_id                         =   ($emp_id)?($emp_id):$e_id;  // send mail
        $emp_id                         =   ($emp_id)?($emp_id):$this->session->userdata('employee'); 
        $ps_month					    =	$this->input->input_stream('ps_month', TRUE);
        $ps_month                       =   ($ps_month)?($ps_month):$e_mo;    // send mail
        $ps_month                       =   ($ps_month)?($ps_month):(date('m') - 1);    
        $ps_year   					    =	$this->input->input_stream('ps_year', TRUE);        
        $ps_year    				    =	($ps_year)?$ps_year:$e_ye;        // send mail
        $ps_year    				    =	($ps_year)?$ps_year:date('Y');
        $res                            =   $this->ldt_pay_slip_details_model->get_emp_pay_slip_details($emp_id,$ps_month,$ps_year);
        if($res)
        {
            $data['result']             =   $res;    
            $path                       =   $data['result']->ps_file_path;
            if($path                   != null)
            {                
                $data['det_pre']        =   2;
                $data['ps_file_path']   =   $this->config->item('uploaded_img_display_url').'EmployeePaySlips/'.$path;
                $data['ps_file_path']   =   './uploads/EmployeePaySlips/'.$path;
                $emp_det                =   $this->mdt_employees_model->get_emp_req_details('private_email','emp_id='.$emp_id);  
                $data['emp_email']      =   $this->mdt_users_model->get_email($emp_id);
                $data['ps_email']       =   $emp_det->private_email;
            }
            else
            {
                $emp_det                =   $this->mdt_employees_model->get_emp_req_details('employee_name,designation,department,employee_code,private_email','emp_id='.$emp_id);  
                $data['emp_level']      =   '-';//$emp_det->emp_level;        
                $data['emp_code']       =   $emp_det->employee_code;  
                $data['empName']        =   $emp_det->employee_name;
                $data['emp_email']      =   $this->mdt_users_model->get_email($emp_id);
                $data['ps_email']       =   $emp_det->private_email;
                if($emp_det->department)
                {
                    $data['deptName']   =   $this->sdt_department_model->get_department_name($emp_det->department); 
                }    
                if($emp_det->designation)
                {
                    $data['desg_name']  =   $this->sdt_designations_model->get_designation_name($emp_det->designation); 
                }             
                $acc_det                =   $this->tdt_employee_bank_account_model->get_emp_bank_details($emp_id); 
                $data['bankAccount']	= 	$acc_det->acc_bank_account_number;
                $data['pfAccount']		= 	$acc_det->pf_account_number;
                $data['embdoj']         =   $this->mdt_employees_model->getEmployeeJoiningDate($emp_id);
                $data['total_working_days']=   $this->get_total_working_days($ps_year,$ps_month,$emp_id); 
                
                $salary_det             =   $this->get_emp_salary_details($emp_id);   
                $data['salary']         =   $salary_det->salary_amount;
                $data['variable']       =   $salary_det->variables;                 
                $data['total_deductions']=	$data['result']->ps_empl_contri_pf + $data['result']->ps_empyr_contri_pf + $data['result']->ps_pt + $data['result']->ps_gmc_gpa + $data['result']->ps_gratuity + $data['result']->ps_tds + $data['result']->ps_adv_sal + $data['result']->ps_loan_amt;                 
                $data['det_pre']        =   1;
            }
        }
        else
        {
            $data['det_pre']            =   0;
        }
        $data['ps_month']               =   $ps_month;
        $data['ps_year']                =   $ps_year;        
        return $data;
    } 
    public function get_extra_worked_days($year,$month,$emp_id)
    {        
       /* $total_working_days             =   $this->get_total_working_days($year,$month,$emp_id); 
        $ps_present_det                 =   $this->calculate_total_worked_days($emp_id,$year,$month);
        if($ps_present_det['machine']   >   $total_working_days)
        {
            $extra_days                 =   $ps_present_det['machine'] - $total_working_days;
            return $extra_days;
        }
        else
        {
            return '';
        }*/
        $ps_present_det                 =   $this->calculate_total_worked_days($emp_id,$year,$month);
        return $ps_present_det['extra_days'];
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
	public function get_emp_salary_history($emp_id)
	{
		$this->db->select('salary_amount,grossSalary,variables,tds,salary_added_on,salary_status');
		$this->db->where('emp_id',$emp_id);
		$this->db->order_by('salary_added_on','DESC');
		$result                         =   $this->db->get($this->tableName)->result();
		return $result;
	}
	function insert_employee_salary($emp_id)
	{
		$insArr['emp_id']			=	$emp_id;		
		$insArr['salary_amount']	=	$this->input->input_stream('fixed_ctc', TRUE);
		$insArr['grossSalary']		=	$this->input->input_stream('gross_ctc', TRUE);
		$insArr['variables']		=	$this->input->input_stream('variable_ctc', TRUE);
		$insArr['tds']				=	$this->input->input_stream('tax_amount', TRUE);
		$insArr['salary_added_by']  =	$this->session->userdata('userid');
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}	
    public function get_salary_approval()
    {    
        $this->load->model('pagination_model');
        $this->load->model('mdt_employees_model');
        $selEmpId   				=	$this->input->input_stream('selEmpId', TRUE);        
        $cond                       =   'salary_status=3';
        if($selEmpId)
        {
            $cond                   =   $cond.' AND emp_id ='.$selEmpId;
        }
        $get_pageList  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'salary_added_on',25);		
        $data['page_details'] 		= 	$get_pageList;        
        $result_array				=	$data['page_details']['results'];
        $data['page_details']['results']=   array();
        if($result_array            !=  null)
        {
            foreach($result_array   as  $resval)
            {	   
                if($resval->emp_id)
                {
                    $emp_name       =   $this->mdt_employees_model->get_emp_name($resval->emp_id);
                }
                else
                {
                    $emp_name       =   'NA';
                }
                if($resval->salary_added_by)
                {
                    $add_emp_name   =   $this->mdt_employees_model->get_emp_name($resval->salary_added_by);
                }
                else
                {
                    $add_emp_name   =   'NA';
                }
                $temp               =   array("emp_id"=>$resval->emp_id,"salary_id"=>$resval->salary_id,"salary_amount" =>$resval->salary_amount,"grossSalary"=>$resval->grossSalary,"variables"=>$resval->variables,"tds"=>$resval->tds,"salary_added_on"=>$resval->salary_added_on ,"salary_added_by"=>$resval->salary_added_by,"salary_status"=>$resval->salary_status,"emp_name"=>$emp_name,"add_emp_name"=>$add_emp_name);
                $data['page_details']['results'] = (array) $data['page_details']['results'];
                array_push($data['page_details']['results'], $temp);
            }		  
        }	   
        $data['selEmpId']               =   $selEmpId;
        $data['title']                  =   'Salary Approval Pending';
        $data['subtitle']               =   '';
        $data['view']                   =   'hrm/salary_approval';		
        return $data;
    }
    public function approve_salary()
    {        
		$salary_status                 	=	$this->input->input_stream('salary_status', TRUE);
		$salary_id                		=	$this->input->input_stream('salary_id', TRUE);
		$emp_id                   		=	$this->input->input_stream('emp_id', TRUE);
        $sal_st                         =   $this->get_salary_id_status($salary_id);
        if($sal_st                      ==  1 || $sal_st==4)
        {
            // nothing
        }
        else
        {
            if($salary_status               ==  1 && $salary_id)
            {
                $this->update(array('salary_status'=>1),'salary_id='.$salary_id);
            }
            else if($salary_status          ==  4 && $salary_id)
            {
                if($emp_id)
                {
                    $this->get_salary_id_for_revert($emp_id);
                }
                $this->update(array('salary_status'=>4),'salary_id='.$salary_id);
            } 
        }      
    } 
    public function get_salary_id_for_revert($emp_id)
    {        
        $this->db->select('salary_id');
        $this->db->where('emp_id',$emp_id);
        $this->db->where('salary_status','2');
		$this->db->order_by('salary_added_on','DESC');
        $this->db->limit('1,0');
		$result                         =   $this->db->get($this->tableName)->row();
        if($result)
        {
            $act                        =   $this->get_emp_salary_details($emp_id);
            if($act)
            {
                // nothing
            }
            else
            {
                if($result->salary_id)
                {
                    $this->update(array('salary_status'=>1),'salary_id='.$result->salary_id);
                }
            }            
        }
    } 
    public function get_salary_id_status($sal_id)
    {        
        $this->db->select('salary_status');
        $this->db->where('salary_id',$sal_id);
		$result                         =   $this->db->get($this->tableName)->row();
        if($result)
        {
            return $result->salary_status;
        }
		else
        {
            return 0;
        }
    }
    public function get_total_working_days_custom($arr_days,$office_id,$to_be_present_days,$month,$year)
	{         
        $this->load->model('cdt_leave_calendar_model');       
        if($to_be_present_days)
        {
            $to_be_present_days_arr =   str_replace("1","Mon",$to_be_present_days);
            $to_be_present_days_arr =   str_replace("2","Tue",$to_be_present_days_arr);
            $to_be_present_days_arr =   str_replace("3","Wed",$to_be_present_days_arr);
            $to_be_present_days_arr =   str_replace("4","Thu",$to_be_present_days_arr);
            $to_be_present_days_arr =   str_replace("5","Fri",$to_be_present_days_arr);
            $to_be_present_days_arr =   str_replace("6","Sat",$to_be_present_days_arr);
            $to_be_present_days_arr =   explode(',',$to_be_present_days_arr); 
        }
        $dayArr                     =   $to_be_present_days_arr;
        $total_leaves_det           =   $this->cdt_leave_calendar_model->get_cal_leave($year,$month);
        $leaveDateArr               =   array();
        if($total_leaves_det       !=   null)
        {
            foreach($total_leaves_det	as  $row)
            {
                
                    $leaveDateArr[]	=	$row->cal_leave_full_date;
            }
        }     
        $cnt 						=	0;
        foreach($arr_days           as $dkey=>$dval)
        {
            $date                 	=   date('Y-m-d',strtotime($dval));
            $day             		=   date('D', strtotime($dval));
            if(in_array($day, $dayArr))
            {
                if(!(in_array($date, $leaveDateArr)))
                {
                    $cnt 			=	$cnt+1;
                }
            }                   
        }
        return $cnt;   
	}
}