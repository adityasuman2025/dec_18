<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_pay_slip_details_model extends CI_Model {
	function __construct() {
		$this->tableName  = 'ldt_pay_slip_details'; 
		$this->primaryKey = 'ps_id';
	}
    public function get_emp_pay_slip_details($emp_id,$ps_month,$ps_year)
	{		
		$this->db->where('ps_emp_id',$emp_id);
		$this->db->where('ps_month',$ps_month);
		$this->db->where('ps_year',$ps_year);
		$result           =   $this->db->get($this->tableName)->row();
		return $result;
	}
    public function check_emp_pay_slip_generated($emp_id,$ps_month,$ps_year)
	{		
		$this->db->where('ps_emp_id',$emp_id);
		$this->db->where('ps_month',$ps_month);
		$this->db->where('ps_year',$ps_year);
		$result           =   $this->db->get($this->tableName)->num_rows();
		return $result;
	}
    public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    public function update($arr,$cond)
	{
		$this->db->update($this->tableName, $arr,$cond);
        return 1;
	}
	public function allEmployeePayslipList($ps_month,$ps_year)
	{
	   $this->db->where('ps_year',$ps_year);
       $this->db->where('ps_month',$ps_month);
	   $result             =   $this->db->get($this->tableName);
	   //echo $this->db->last_query();
       return $result->result();
	}
	public function downloadallEmployeePayslipList($ps_month,$ps_year)
	{ 
	    header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=payslipReport".date("Y_m_d_H_i_s").".xls");
		 $page_details                      =   $this->allEmployeePayslipList($ps_month,$ps_year);
			?>
            <table width="100%" height="226" align="center" cellpadding="0" cellspacing="0" border="1" style="padding-left:20px; padding-top:20px; border:thick"> 
            <tr>
                <td align="center" width="100%" bgcolor="#99CCFF" style="font-weight:bold; font-size:16px"><b>Attendance Report : <?php echo $ps_year." - ".date('F', mktime(0, 0, 0, $ps_month, 10));?></b></td>
            </tr>
            <tr>
                <td valign="top">
                    <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0">
                        <tr class="tableHead" bgcolor="#99FFFF" style="font-weight:bold">
                            <td width="20px">	SL No						</td>
                            <td	width="20px">	Employee ID 				</td>
                            <td	width="20px">	Employee Name 		        </td>
                            <td	width="20px">	Department 		        	</td>
                            <td	width="20px">	Designation 				</td>
                            <td	width="20px">	Office     					</td>
                            <!--<td	width="20px">	Employee Level 				</td>-->
                            <td	width="20px">	DOJ							</td>
                            <td	width="20px">	Bank Account 				</td>
                            <td	width="20px">	PF Account Number			</td>
                            <td	width="20px">	IFSC     					</td>
                            <td	width="20px">	Number of Working Days 		</td>
                            <td	width="20px">	Number of Days Paid 		</td>
                            <td	width="20px">	Number of Earned Leaves Deducted </td>
                            <td	width="20px">	Gross Total CTC for the Month </td>
                            <td	width="20px">	Gross Fixed CTC for the Month </td> 
                            <td	width="20px">	Gross Variable CTC for the Month </td>
                            <td	width="20px">	Net Fixed CTC for the Month </td>
                            <td	width="20px">	Basic						</td>
                            <td	width="20px">	HRA							</td>
                            <td	width="20px">	LTA							</td>
                            <td	width="20px">	Medical Reimbursements		</td>
                            <td	width="20px">	Conveyance					</td>
                            <td	width="20px">	Special Allowance			</td>                            
                            <td	width="20px">	Total Arrears				</td>
                            <td	width="20px">	Non working days/Extra working days salary</td>
                            <td	width="20px">	10 Days deduction salary    </td>
                            <td	width="20px">	Variable 					</td>
                            <td	width="20px">	PT							</td> 
                            <td	width="20px">	GMC / GPA					</td>
                            <td	width="20px">	Gratuity					</td>
                            <td	width="20px">	Employee Contribution to PF	</td>
                            <td	width="20px">	Employer Contribution to PF	</td>
                            <td	width="20px">	TDS							</td>
                            <td	width="20px">	Advance Salary				</td> 
                            <td	width="20px">	Loan           				</td> 
                            <td	width="20px">	Total Deductions 			</td> 
                            <td	width="20px">	Net Salary Payable 			</td>
                        </tr>                        
		     <?php
			if($page_details)
			{
				$cnt                           = 1;
                $this->load->model('ldt_arrears_model');
				foreach($page_details as  $resval)
				{
                    $ps_present_days           =   '';
                    $nwds                      =   '';
					$ps_id                     =   $resval->ps_id;	
					$ps_emp_id                 =   $resval->ps_emp_id?$resval->ps_emp_id:0;
					$empdeta                   =   $this->mdt_employees_model->get_employee_details($ps_emp_id);
					$salDet                    =   $this->tdt_employee_salary_model->get_emp_salary_details($ps_emp_id);                    
					$bankAccDet                =   $this->tdt_employee_bank_account_model->get_emp_bank_details($ps_emp_id);  
                    if($resval->ps_gross_variable   ==  '0.00')
                    {
                        $gvCTC                  =   ($salDet->variables)?($salDet->variables):"NA";
                    }
                    else if($resval->ps_gross_variable <=   $salDet->variables)
                    {
                        $gvCTC                  =   $salDet->variables;
                    }
                    else 
                    {
                        $gvCTC                  =   $resval->ps_gross_variable;
                    }  
                    $ps_month                   =   $resval->ps_month;
                    $ps_year                    =   $resval->ps_year;
                    $userId                     =	$this->session->userdata('userid');
                    $working_days               =   $this->tdt_employee_salary_model->get_total_working_days($ps_year,$ps_month,$ps_emp_id);
                    $total_deductions           =	$resval->ps_empl_contri_pf  + $resval->ps_empyr_contri_pf  + $resval->ps_pt  + $resval->ps_gmc_gpa  + $resval->ps_gratuity  + $resval->ps_tds  + $resval->ps_adv_sal + $resval->ps_loan_amt;	
                    $ps_present_days            =   $resval->ps_present_days+$resval->ps_deducted_leave;
                    $ps_present_days            =   ($ps_present_days)?($ps_present_days):'NA';
                    $nwds                       =   $resval->ps_arrears; //Non working day salary
                    $upd_arrears                =   $this->ldt_arrears_model->get_arrears_by_emp_id($ps_emp_id,$ps_month,$ps_year); 
                    $resval->ps_arrears         =   $resval->ps_arrears + $upd_arrears;                    
                    $resval->ps_net_pay         =   $resval->ps_net_pay + $upd_arrears;
				?>
				<tr>
					<td><?php echo $cnt++; ?></td>
					<td><?=$resval->ps_emp_id?></td>
					<td><?=get_employee_name($resval->ps_emp_id)?></td>
					<td><?=get_department_name($empdeta->department)?></td>
					<td><?=get_designation_name($empdeta->designation)?></td>
					<td><?=get_company_name($empdeta->office)?></td>
					<!--<td><?php echo "-";?>    </td>-->
					<td><?=($empdeta->joined_date)?($empdeta->joined_date):"NA"?></td>
					<td><?=($bankAccDet->acc_bank_account_number)?($bankAccDet->acc_bank_account_number):"NA"?></td>
					<td><?=($bankAccDet->pf_account_number)?($bankAccDet->pf_account_number):"NA"?></td>
					<td><?=($bankAccDet->ifsc_code)?($bankAccDet->ifsc_code):"NA"?></td>
					<td><?=($working_days)?($working_days):"NA"?></td>
					<td><?=$ps_present_days?></td>
					<td><?=$resval->ps_deducted_leave?$resval->ps_deducted_leave:"NA"?></td>
					<td><?=$resval->ps_tot_ctc?$resval->ps_tot_ctc:"NA"?></td>
					<td><?=$salDet->salary_amount?$salDet->salary_amount:"NA" ?></td>
					<td><?=$gvCTC?$gvCTC:"NA"?></td>
					<td><?=$resval->ps_net_sal?$resval->ps_net_sal:"NA"?></td>
					<td><?=$resval->ps_basic?$resval->ps_basic:"NA"?></td>  
					<td><?=number_format($resval->ps_hra,2)?number_format($resval->ps_hra,2):"NA"?></td> 
					<td><?=number_format($resval->ps_lta,2)?number_format($resval->ps_lta,2):"NA"?></td> 
					<td><?=$resval->ps_med_reim?$resval->ps_med_reim:"NA"?></td>
					<td><?=$resval->ps_conveyance?$resval->ps_conveyance:"NA"?></td>
					<td><?=number_format($resval->ps_special_allow)?number_format($resval->ps_special_allow):"NA"?></td>
					<td><?=number_format($resval->ps_arrears)?number_format($resval->ps_arrears):"NA"?></td>
                    <td><?=number_format($nwds)?number_format($nwds):"NA"?></td>
					<td><?=number_format($upd_arrears)?number_format($upd_arrears):"NA"?></td> 
					<td><?=number_format($resval->ps_variable)?number_format($resval->ps_variable):"NA"?></td>
					<td><?=number_format($resval->ps_pt)?number_format($resval->ps_pt):"NA"?></td>
					<td><?=number_format($resval->ps_gmc_gpa,2)?number_format($resval->ps_gmc_gpa,2):"NA"?></td> 
					<td><?=number_format($resval->ps_gratuity)?($resval->ps_gratuity):"NA"?></td> 
					<td><?=number_format($resval->ps_empl_contri_pf)?number_format($resval->ps_empl_contri_pf):"NA"?></td>
					<td><?=number_format($resval->ps_empyr_contri_pf)?number_format($resval->ps_empyr_contri_pf):"NA"?></td>
					<td><?=number_format($resval->ps_tds)?number_format($resval->ps_tds):"NA"?></td>
					<td><?=number_format($resval->ps_adv_sal)?number_format($resval->ps_adv_sal):"NA"?></td>
					<td><?=number_format($resval->ps_loan_amt)?number_format($resval->ps_loan_amt):"NA"?></td>
					<td><?=number_format($total_deductions)?number_format($total_deductions):"NA" ?></td>
					<td><?=number_format($resval->ps_net_pay)?number_format($resval->ps_net_pay):"NA" ?></td>
				</tr>
					<?php				
				}
			}			
		  ?>
		</table>
     </td></tr></table>	
	<?php				
	}
    
    function pay_slip_list_details()
    {
        $this->load->model('pagination_model');
        $name_id                     =    $this->input->input_stream('name_id', TRUE);       
        $emp_status                  =    $this->input->input_stream('emp_status', TRUE); 
        $selStatus  	           	 = 	 ($emp_status)?($emp_status):1;
        $report_to                   =    $this->input->input_stream('report_to', TRUE); 
        $depart                      =    $this->input->input_stream('depart', TRUE); 
        $seloffc                     =    $this->input->input_stream('seloffc', TRUE);
        $from_joined_date            =    $this->input->input_stream('from_joined_date', TRUE); 
        $To_joined_date              =    $this->input->input_stream('To_joined_date', TRUE);
        $ps_month                    =    $this->input->input_stream('ps_month', TRUE);
        $ps_year                     =    $this->input->input_stream('ps_year', TRUE);      
        $ps_month					 =	  ($ps_month)?$ps_month:date('m')-1;
        $yyyy                        =     date('Y');
        if(date('m')                 ==  1)
        {
            $yyyy                   =   date('Y')-1;
        }
        $ps_year	       			=	($ps_year)?$ps_year:$yyyy; 
        $where 						=	' WHERE mdt_employees.emp_id != 0';
       	if(is_numeric($name_id))
		{
		  $where                    =  $where. " AND mdt_employees.emp_id =".$name_id;
		}
		elseif($name_id)
		{	
            $where	                =   $where. " AND mdt_employees.employee_name LIKE '%".$name_id."%'";
		}
        if($from_joined_date          &&    $To_joined_date)
		{
			$where				    =	$where." AND mdt_employees.joined_date between '".date("Y-m-d", strtotime($from_joined_date))."' AND '".date("Y-m-d", strtotime($To_joined_date))."' ";
		}
        elseif($from_joined_date)
		{
			$where					=	$where." AND mdt_employees.joined_date >= '".date("Y-m-d", strtotime($from_joined_date))."' ";
		}
        elseif($To_joined_date)
		{
			$where					=	$where." AND mdt_employees.joined_date <= '".date("Y-m-d", strtotime($To_joined_date))."' ";
		}
        if($emp_status)
		{
            $where	                =   $where. " AND mdt_employees.employee_status LIKE '%".$emp_status."%'";
		}
        if($depart)
		{
            $where					=	$where." AND mdt_employees.department  = ".$depart;
		}
        if($report_to)
		{
            $where				    =	$where." AND mdt_employees.reporting_to  =".$report_to;
		}
         if($seloffc)
		{
             $where					=	$where." AND mdt_employees.office =".$seloffc;
		}
        if($ps_month)
		{
			$where 					=	$where." AND ldt_pay_slip_details.ps_month=".$ps_month;
		}
		if($ps_year)
		{
			$where 					=	$where." AND ldt_pay_slip_details.ps_year=".$ps_year;
		}        
        if($this->session->userdata('office') != 1)
        {
            //$where                  =  $where. " AND mdt_employees.office =".$this->session->userdata('office');
        } 
        $sql   =   ' SELECT mdt_employees.emp_id, mdt_employees.employee_name , mdt_employees.joined_date, mdt_employees.employee_status, mdt_employees.department, mdt_employees.reporting_to, mdt_employees.office, ldt_pay_slip_details.ps_emp_id, ldt_pay_slip_details.ps_month, ldt_pay_slip_details.ps_year FROM mdt_employees INNER JOIN ldt_pay_slip_details ON mdt_employees.emp_id = ldt_pay_slip_details.ps_emp_id'.$where;   
        $get_pageList               =    $this->pagination_model->get_pagination_sql($sql,25);
        $get_pageList['name_id']    = 	$name_id;
        $get_pageList['emp_status'] = 	$emp_status;
        $get_pageList['report_to']  = 	$report_to;
        $get_pageList['depart']     = 	$depart;
        $get_pageList['seloffc']    = 	$seloffc;
        $get_pageList['from_joined_date']= 	$from_joined_date;
        $get_pageList['To_joined_date']= 	$To_joined_date;
        $get_pageList['ps_month']   = 	$ps_month;
        $get_pageList['ps_year']    = 	$ps_year;
        return $get_pageList;
    }
}
?>