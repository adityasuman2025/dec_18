<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tds_calculation_model extends CI_Model {
	function __construct() {
		$this->tableName                =   'ldt_employee_tds_calculator'; 
	}
    public function get_details_for_tds_calculation($emp_id)
    {    
        $data['sal_not_added']                 =   '';
        $data['financial_yr']                  =   $this->get_financial_year();
        $calculated_tds                        =   $this->get_calculated_tds_val($emp_id,$data['financial_yr']); 
        $data['avg_mnth_tds']                  =   '';
        $data['tds_id']                        =   '';
        $data['rent_paid']                     =   '';
        $data['income_hous_prop']              =   '';
        $data['tds_hous_loan_principle']       =   '';
        $data['tds_insurance']                 =   '';
        $data['tds_schl_fees']                 =   '';
        $data['tds_nsc']                       =   '';
        $data['med_self_spouse_child']         =   '';
        $data['med_parents_less_sixty']        =   '';
        $data['med_parents_more_sixty']        =   '';
        $data['med_self_more_sixty']           =   '';
        if($calculated_tds)
        {            
            $data['tds_id']             =   $calculated_tds->tds_id;
            if($calculated_tds->status  ==  1)
            {
                $data['avg_mnth_tds']  =   $calculated_tds->avg_mnth_tds; 
            }
            else if($calculated_tds->status  ==  2)
            {
                $data['avg_mnth_tds']                =   '';
                $data['rent_paid']                   =   $calculated_tds->rent_paid;
                $data['income_hous_prop']            =   $calculated_tds->income_hous_prop;
                $data['tds_hous_loan_principle']     =   $calculated_tds->tds_hous_loan_principle;
                $data['tds_insurance']               =   $calculated_tds->tds_insurance;
                $data['tds_schl_fees']               =   $calculated_tds->tds_schl_fees;
                $data['tds_nsc']                     =   $calculated_tds->tds_nsc;
                $data['med_self_spouse_child']       =   $calculated_tds->med_self_spouse_child;
                $data['med_parents_less_sixty']      =   $calculated_tds->med_parents_less_sixty;
                $data['med_parents_more_sixty']      =   $calculated_tds->med_parents_more_sixty;
                $data['med_self_more_sixty']         =   $calculated_tds->med_self_more_sixty;
            }
        }        
        if($data['avg_mnth_tds'])
        {
            // display calculated tds
        }
        else
        {
            $this->load->model('Tdt_employee_salary_model');
            $this->load->model('Mdt_employees_model');
            $empSalaryDetails              =    $this->Tdt_employee_salary_model->get_emp_salary_details($emp_id);
            if($empSalaryDetails)
            {
            $grossFixedCTC				   = 	$empSalaryDetails->salary_amount;
            $existing_var				   = 	$empSalaryDetails->variables;
            $TDS						   = 	$empSalaryDetails->tds;
            $grossSalary      			   = 	$empSalaryDetails->grossSalary;  
            ##################################################################################################    
            ##################################################################################################    
            ##################################################################################################  
            ##########  Gross Fixed CTC (From Table) ##############     	   
            $grossFixedCTC; 
            $variable                       =   '';
            $Advance                        =   '';
            $loan_emi                       =   '';
            $arrears                        =   '';
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
            $monthNetCTC					=	$grossFixedCTC;
            $monthNetCTC					=	round($monthNetCTC, 2);	
            $total_gross_salary				=	$monthNetCTC;	
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
            $total_deductions_for_calc  	=	$empPF + $emprPF + $PT + $gmc_gpa + $gratuity + $Advance + $loan_emi;
            ####################  Net Payable #######################
            $NetPayable						= 	$monthNetCTC + $arrears + $variable - $total_deductions;              
            ##################################################################################################    
            ##################################################################################################    
            ##################################################################################################    
            $emp_det                        =    $this->Mdt_employees_model->get_emp_req_det($emp_id); 
            $data['joined_date']            =    $emp_det->joined_date;
            $data['employee_code']          =    $emp_det->employee_code;
            $data['tds']                    =    $TDS;
            $data['emp_name']               =    $this->Mdt_employees_model->get_emp_name($emp_id);  
            $pf_details                     =    $this->get_pay_slip_details($emp_id); 
            $months_to_multiply             =    $this->get_no_of_months_till_end_fy($emp_det->joined_date);
            $data['basic']                  =    $basic * $months_to_multiply; 
            $data['HRA']                    =    $HRA * $months_to_multiply;  
            $data['LTA']                    =    $LTA * $months_to_multiply;   
            $data['Medical']                =    $Medical * $months_to_multiply;                               
            $data['specialAllowance']       =    $specialAllowance * $months_to_multiply; 
            $data['Conveyance']             =    $Conveyance * $months_to_multiply;   
            $data['total_gross_salary']     =    $total_gross_salary * $months_to_multiply; 
            $tdsempdetails                  =    $this->get_employee_details($emp_id);
            $data['birthday']               =    $tdsempdetails['birthday'];
            $data['contact_city']           =    $tdsempdetails['contact_city'];
            $data['ps_empl_contri_pf']      =    $pf_details['ps_empl_contri_pf'] * $months_to_multiply;
            $data['ps_empyr_contri_pf']     =    $pf_details['ps_empyr_contri_pf'] * $months_to_multiply;
            $data['ps_pt']                  =    $pf_details['ps_pt'];
            $data['total_deductions']       =    $total_deductions_for_calc * $months_to_multiply;
            $data['tds_gmc']                =    $gmc_gpa * $months_to_multiply;
            $data['months_to_multiply']     =    $months_to_multiply;
            }
            else
            {
                $data['sal_not_added']      =   1;
            }
            $data['emp_id']                 =   $emp_id;
        }
        return $data;
    }      
    public function get_employee_details($emp_id)
	{
        $this->db->select('birthday,contact_city');
        $this->db->from('mdt_employees');
		$this->db->where('emp_id',$emp_id);
		$result                     =   $this->db->get()->row();    
        if($result                 !=    null)
        {
            $data['birthday']      =    $result->birthday;
            $data['contact_city']  =    $result->contact_city;
        }
		else
        {
           $data['birthday']      =    '';
            $data['contact_city']  =    '';
        }
            return $data;
	} 
    public function get_pay_slip_details($emp_id)
	{    
        $this->db->select('ps_pt,ps_empl_contri_pf,ps_empyr_contri_pf');
        $this->db->where('ps_emp_id',$emp_id);
        $this->db->from('ldt_pay_slip_details');
        $this->db->order_by('ps_added_on DESC');		
        $this->db->limit('1','0');	        
        $result                     =   $this->db->get()->row();  
        if($result                 !=    null)
        {
            $data['ps_pt']         =    $result->ps_pt;
            $data['ps_empl_contri_pf']  =    $result->ps_empl_contri_pf;
            $data['ps_empyr_contri_pf']  =    $result->ps_empyr_contri_pf;
        }
		else
        {
            $data['ps_pt']      =    '';
            $data['ps_empl_contri_pf']  =    '';
            $data['ps_empyr_contri_pf']  =    '';
        }
        return $data;
	}
    public function update_tds_calculation()
    {   
		 $emp_id              =      $this->input->post('emp_id');
         //$r                   =      $this->get_details_for_tds_calculation($emp_id);
         //$tdspayslipdetails   =      $this->get_pay_slip_details($emp_id); 
         $insArr		      =	     array();
         $tds_id              =      $this->input->post('tds_id');
         $financial_yr        =      $this->input->post('financial_yr');
         $basic               =      $this->input->post('basic');
         $joined_date         =      $this->input->post('joined_date');
         $finanyr             =      $this->input->post('finanyr');
         $hra                 =      $this->input->post('HRA');
         $tds                 =      $this->input->post('tds');
         $total_gross_salary  =      $this->input->post('total_gross_salary');
         $tds_res             =	     $this->input->post('tds_res');
         $tds_yr              =	     $this->input->post('tds_yr');
         $tds_cat             =	     $this->input->post('tds_cat');
         $rentpaid            =	     $this->input->post('rentpaid');
         $incomeHouse         =	     $this->input->post('incomeHouse');
         $othrsrcs         =	     $this->input->post('othrsrcs');
         $tds_others          =	     $this->input->post('tds_others');
         $tds_ifhp            =	     $this->input->post('tds_ifhp');
         $tds_ifos            =	     $this->input->post('tds_ifos');
         $tds_si              =	     $this->input->post('tds_si');
         $othded              =	     $this->input->post('othded');
         $pf                 =	     $this->input->post('pf');
         $hlp                 =	     $this->input->post('hlp');
         $insurance           =	     $this->input->post('insurance');
         $schlfee             =	     $this->input->post('schlfee');
         $nsc                 =	     $this->input->post('nsc');
         $gmc                 =	     $this->input->post('tds_gmc');
         $sum_total_deduction =      $pf + $hlp + $insurance + $schlfee + $nsc +$gmc;
         $medSelf             =	     $this->input->post('medSelf');
         $medpar              =	     $this->input->post('medpar');
         $medparmore60        =	     $this->input->post('medparmore60');
         $medselfmore60       =	     $this->input->post('medselfmore60');
         $months_to_multiply  =	     $this->input->post('months_to_multiply');
         $pt                  =	     $this->input->post('pt');            
         $st_end_date_arr     =      $this->get_fy_start_end_date();
         $StartFy             =      $st_end_date_arr[0];
         $EndFy               =      $st_end_date_arr[1];
         #########################HRA Exemption#####################
         if($rentpaid)
         {
            $emprentpaid      =    $rentpaid-((10/100) * $basic);   
         }
         else
         {
            $emprentpaid      =    0;  
         }
         if($emprentpaid      <    0)
         {
            $emprentpaid      =   0;
         }        
         $actualHra           =    $hra;
         if($tds_res          == '1')
         {
             $residence_val      =    ((50/100) * $basic);
         }else{
             $residence_val      =    ((40/100) * $basic);
         }
         $minval            =    min($emprentpaid,$actualHra,$residence_val);
         if($minval         <    0)
         {
             $minval        =   0;
         }
         ################Others(pls disclose if thereis any other exemption)##########
         $salbfrdeduction  =    $total_gross_salary-$minval;
       ##################Deduction u/s 16#########################
         $sal161           =    min(40000,$salbfrdeduction);
       #################u/s 16(iii) Tax on Employment(Rs. 200 pm from the DOJ) PT####
        $totval            =    $pt * $months_to_multiply;

        $sum_16        = $sal161 + $totval;
        $incmfromSl    = $salbfrdeduction-$sum_16;
        ########################Income form house property####################
        $max_val       = '200000';
        if($incomeHouse < $max_val)
        {
            $housval   =   -$incomeHouse;
        }else{
            $housval   =   -$max_val;
        }
        $finhousval    =   $housval;
        ########################Income form other sources####################
        $grosstotincm  =   $othrsrcs + $incmfromSl + $finhousval;
        ########################Deduction u/s 80C/80CCC####################
        $max_ded_val   =   '150000';
        if($sum_total_deduction < $max_ded_val)
        {
            $deductionval   =   $sum_total_deduction;
        }else{
            $deductionval   =   $max_ded_val;
        }
        ########################Deduction u/s 80D####################
        $max_medSelf_val   =   '25000';
        if($medSelf < $max_medSelf_val)
        {
            $medSelf_val   =   $medSelf;
        }else{
            $medSelf_val   =   $max_medSelf_val;
        }
        $max_medpar_val   =   '25000';
        if($medpar < $max_medpar_val)
        {
            $medpar_val   =   $medpar;
        }else{
            $medpar_val   =   $max_medpar_val;
        }
        $max_medparmore60   =   '50000';
        if($medparmore60 < $max_medparmore60)
        {
            $medparmore60_val   =   $medparmore60;
        }else{
            $medparmore60_val   =   $max_medparmore60;
        }
        $max_medselfmore60_val   =   '50000';
        if($medselfmore60 < $max_medselfmore60_val)
        {
            $medselfmore60_val   =   $medselfmore60;
        }else{
            $medselfmore60_val   =   $max_medselfmore60_val;
        }
        $sum_total_med_deduction =      $medSelf_val + $medpar_val + $medparmore60_val + $medselfmore60_val;
        $total_deduction   =  $deductionval + $sum_total_med_deduction;
        $net_income        =  $grosstotincm - $total_deduction;
        if($tds_cat == '1')
        {
           if($net_income < 250000)
           {
              $tax_payable  =  0;
           }else if($net_income > 250000 && $net_income < 500000)
           {
            $tax_payable  =  (($net_income-250000) * (5/100)) + 0;
           }else if($net_income > 500000 && $net_income < 1000000)
           {
            $tax_payable  =  (($net_income-500000) * (20/100)) + (250000 * (5/100)) + 0;
           }else if($net_income > 1000000)
           {
               $tax_payable  =  (($net_income-1000000) * (30/100))+(500000 * (20/100))+(250000 * (5/100)) + 0;
           }
        }else if($tds_cat == '2'){
          if($net_income < 300000)
           {
              $tax_payable  =  0;
           }else if($net_income > 300000 && $net_income < 500000)
           {
            $tax_payable  =  (($net_income-300000) * (5/100));
           }else if($net_income > 500000 && $net_income < 1000000)
           {
            $tax_payable  =  (($net_income-500000) * (20/100)) + (300000 * (5/100)) + 0;
           }else if($net_income > 1000000)
           {
               $tax_payable  =  (($net_income-1000000)*(30/100))+(500000 * (20/100))+(300000 * (5/100));
           } 
        }
        ########################Rebate U/S 87A ,If the Tax payable below Rs. 2500/-####################  
        // Rebate Calculation        
        if($tax_payable <=  2500)
        {
            $rebate     = $tax_payable;
        }
        else
        {
            $rebate     = 0;
        }
        // Surcharge  Calculation
        if($net_income >= 5000000 && $net_income < 10000000)
        {
            $surcharge  =  ((10/100) * $tax_payable);
         
        }
        else if($net_income >= 10000000)
        {
            $surcharge  =  ((15/100) * $tax_payable);
            
        }
        else
        {
            $surcharge = '';
        }  
        // Cess  Calculation
        $cess       =  (($tax_payable - $rebate + $surcharge) * (4/100));
        // total tax payable  Calculation
        $total_tax_payable =  $tax_payable-$rebate+$surcharge+$cess;
        // Calculating months from start of financial year till today  
        $e_months                               =   0;
        $monthscnt                              =   0;
        $avg_mnth_tds                           =   0;
        $currentDateTime                        =   date('Y-m-d');
        $tds_deducted                           =   $this->get_tds_decucted_fy($emp_id);
        $balance_tax_payable                    =   $total_tax_payable - $tds_deducted;
        if($balance_tax_payable                 <   0)
        {
            $balance_tax_payable                =   0;
        }      
        else
        {
            $remaingmnths                       =        date_diff(date_create($EndFy), date_create($currentDateTime));
            if(date('d')                        <=  10)
            {
                $e_months                       =   2;
            }
            else
            {
                $e_months                       =   1;
            }
            $monthscnt                          =        $remaingmnths->format('%m')+$e_months;
            $avg_mnth_tds                       =        $balance_tax_payable/$monthscnt;
        }
        $insArr['emp_id']                       =	     $emp_id;
        $insArr['residence']                    =	     $tds_res;
        $insArr['category']                     =	     $tds_cat;
        $insArr['rent_paid']                    =	     $rentpaid;
        $insArr['tds_ppf']                      =	     $pf;
        $insArr['tds_gmc']                      =	     $gmc;
        $insArr['tds_hous_loan_principle']      =	     $hlp;
        $insArr['tds_insurance']                =	     $insurance;
        $insArr['tds_schl_fees']                =	     $schlfee;
        $insArr['tds_nsc']                      =	     $nsc;
        $insArr['med_self_spouse_child']        =	     $medSelf_val;
        $insArr['med_parents_less_sixty']       =	     $medpar_val;
        $insArr['med_parents_more_sixty']       =	     $medparmore60_val;
        $insArr['med_self_more_sixty']          =	     $medselfmore60_val;
        $insArr['income_hous_prop']             =	     $incomeHouse;
        $insArr['dedction_80c']                 =	     $deductionval;
        $insArr['dedctn_medical']               =	     $sum_total_med_deduction;
        $insArr['rent_paid_basic_pay']          =	     $emprentpaid;
        $insArr['actual_hra']                   =	     $actualHra;
        $insArr['residence_val']                =	     $residence_val;
        $insArr['min_val']                      =	     $minval;
        $insArr['salary_bfr_deduction']         =	     $salbfrdeduction;
        $insArr['ded_us_16ia']                  =	     $sal161;
        $insArr['ded_us_16iii']                 =	     $totval;
        $insArr['sum_16ia_16iii']               =	     $sum_16;
        $insArr['incm_from_salary']             =	     $incmfromSl;
        $insArr['incm_from_hous_prop']          =	     $finhousval;
        $insArr['gross_total_incm']             =	     $grosstotincm;
        $insArr['total_deduction']              =	     $total_deduction;
        $insArr['net_income']                   =	     $net_income;
        $insArr['tax_payable']                  =	     $tax_payable;
        $insArr['rebate']                       =	     $rebate;
        $insArr['surcharge']                    =	     $surcharge;
        $insArr['Cess']                         =	     $cess;
        $insArr['tot_tax_payable']              =	     $total_tax_payable;
        $insArr['tds_deducted']                 =	     $tds_deducted;
        $insArr['balance_tax_payable']          =	     $balance_tax_payable;
        $insArr['no_month']                     =	     $monthscnt;
        $insArr['financial_yr']                 =	     $financial_yr;
        $insArr['avg_mnth_tds']                 =	     $avg_mnth_tds;
        if($tds_id)
        {
            $insArr['status']                   =        1;
            $update                             =        $this->update($insArr,'tds_id='.$tds_id);
        }
        else
        {
            $insert						        =        $this->insert($insArr);
        }
        ////////////////////// TDS Updation to salary //////////////////////
        $this->load->model('Tdt_employee_salary_model');
        $salDet                                 =   $this->Tdt_employee_salary_model->get_emp_salary_details($emp_id);
        if($salDet						        != NULL)
        {
            $avg_mnth_tds                       =   round($avg_mnth_tds,2);
            if($salDet->tds		    	        != 	$avg_mnth_tds) // difference found
            {
                if($avg_mnth_tds                <   0)
                {
                    $avg_mnth_tds               =   0;
                }  
                $upSalArray['salary_amount']    =	$salDet->salary_amount;
                $upSalArray['grossSalary']      =	$salDet->grossSalary;
                $upSalArray['variables']        =	$salDet->variables;
                $upSalArray['tds']              =	$avg_mnth_tds;
                $upSalArray['emp_id']           =	$emp_id;
                $upSalArray['salary_added_by']  =	$this->session->userdata('userid');
                $updateaccountdet			    =	$this->Tdt_employee_salary_model->update(array('salary_status'=>2),"emp_id=".$emp_id." AND salary_status=1");				
                $updateaccountdet			    =	$this->Tdt_employee_salary_model->insert($upSalArray);
            }
            else
            {
                // no change
            }
        }        
        /*if($avg_mnth_tds                        >   0)
        {
            $this->load->model('Tdt_employee_salary_model');
            $this->Tdt_employee_salary_model->update(array('tds'=>$avg_mnth_tds),'emp_id='.$emp_id.' AND salary_status=1');            
        }*/
        return $emp_id;
    }
    ///////////////////////////////////////////////////////////
    ////////////////        insert           //////////////////
    ///////////////////////////////////////////////////////////
    function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    ///////////////////////////////////////////////////////////
    ////////////////         update          //////////////////
    ///////////////////////////////////////////////////////////
	function update($arr,$cond)
	{
		$this->db->update($this->tableName,$arr,$cond);
        return true;
	}
    ///////////////////////////////////////////////////////////
    //////////////// /get_calculated_tds_val //////////////////
    ///////////////////////////////////////////////////////////
    function get_calculated_tds_val($emp_id,$fy)
    {     
        $this->db->select('avg_mnth_tds,status,tds_id,rent_paid,income_hous_prop,tds_hous_loan_principle,tds_insurance,tds_schl_fees,tds_nsc,med_self_spouse_child,med_parents_less_sixty,med_parents_more_sixty,med_self_more_sixty');
        $this->db->from('ldt_employee_tds_calculator');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('financial_yr',$fy);
		$result                     =   $this->db->get()->row();    
        if($result                 !=    null)
        {
            return $result;
        }
        else
        {
            return '';
        }
    } 
    ///////////////////////////////////////////////////////////
    //////////////// get_financial_year      //////////////////
    ///////////////////////////////////////////////////////////
    function get_financial_year()
    {       
        $month              = date("M");
        $curr_month         = date('m', strtotime($month));
        $cur_year           = date("Y");
        if($curr_month      >= 04)
        {
            $financial_yr       = ($cur_year)."-".($cur_year+1);
        }
        else  
        {
            $financial_yr = ($cur_year-1)."-".($cur_year);
        }
        return $financial_yr;
    }
    ///////////////////////////////////////////////////////////
    //////////////// get_tds_decucted_fy      //////////////////
    ///////////////////////////////////////////////////////////
    function get_tds_decucted_fy($emp_id)
    {     
        $tot_tds            =   '';
        $month              = date("M");
        $curr_month         = date('m', strtotime($month));        
        $cur_year           = date("Y");
        if($curr_month      >= 04)
        {
            $financial_yr   = ($cur_year);
        }
        else  
        {
            $financial_yr   = ($cur_year-1);
        }
        $from_month         =   '04';
        $from_year          =   $financial_yr;
        $to_year            =   $cur_year;
        $to_month           =   $curr_month;
        ///////////////////////////////////////
        $month_arr          =   array();
        $month_arr2         =   array();
        $ps_year            =   '';
        $ps_year2           =   '';
        if($to_month        > $from_month)
        {
            $month_arr      =   range($from_month,$to_month);
            $ps_year        =   $cur_year;
        }
        else if($to_month   <   $from_month)
        {
            $month_arr      =   range($from_month,12);   
            $ps_year        =   $cur_year-1;            
            $month_arr2     =   range(1,$to_month);
            $ps_year2       =   $cur_year;
        }
        else
        {
            $ps_year        = $cur_year;
            $month_arr      =  array('0'=>$from_month); 
        }        
        $final_arr[$ps_year] =   $month_arr;
        $final_arr[$ps_year2] =   $month_arr2;
        if($final_arr)
        {
            foreach($final_arr      as $key=>$val)
            {
                foreach($val as $inkey=>$inval)
                {
                    $tot_tds         =  $tot_tds + $this->get_tds_for_emp($emp_id,$inval,$key);
                }
            }
        }
        return $tot_tds;
    }
    ///////////////////////////////////////////////////////////
    //////////////// get_tds_for_emp         //////////////////
    ///////////////////////////////////////////////////////////
    function get_tds_for_emp($emp_id,$month,$year)
    {
        $this->db->select('ps_tds');
        $this->db->from('ldt_pay_slip_details');
		$this->db->where('ps_emp_id',$emp_id);
		$this->db->where('ps_month',$month);
		$this->db->where('ps_year',$year);
		$result                     =   $this->db->get()->row();
        if($result                 !=    null)
        {
            return $result->ps_tds;
        }
        else
        {
            return '';
        }
    }  
    /////////////////////////////////////////////////////
    ///////////    Richa   -   08/03/2018   /////////////
    ////////////////////////////////////////////////////  
    function getEmployeeTdsCalculationList($down_fun='')
    {
        $this->load->model('pagination_model');
        $ps_financial_year          =   $this->input->input_stream('ps_financial_year', TRUE);       
        $selName                    =   $this->input->input_stream('selName', TRUE); 
        $selReportingTo             =   $this->input->input_stream('selReportingTo', TRUE); 
        $selDepart                  =   $this->input->input_stream('selDepart', TRUE);
        $joined_From_date           =   $this->input->input_stream('joined_From_date', TRUE); 
        $joined_To_date             =   $this->input->input_stream('joined_To_date', TRUE); 
        $selStat                    =   $this->input->input_stream('selStat', TRUE);
        $selStatus                  =   $this->input->input_stream('selStatus', TRUE); 
        $selStatus  	           	= 	($selStatus)?($selStatus):1;
        $selStat  	           	    = 	($selStat)?($selStat):1;
        $selEmployId                =   $this->input->input_stream('selEmployId', TRUE); 
        $selofc                     =   $this->input->input_stream('selofc', TRUE); 
        /*if($doj                     >   $limit_doj)
        {
            $doj                    =   $limit_doj;
        }*/
    
         $cond                       =   ($selStat == '3')?'mdt_employees.emp_id != 0':'ldt_employee_tds_calculator.emp_id != 0';
        
        if($selName)
		{
			$cond					=	$cond. " AND ( mdt_employees.employee_name like '%".$selName."%' )";
		}
        if($selEmployId)
		{
			$cond					=	$cond. " AND mdt_employees.emp_id =".$selEmployId;
		}
        if($joined_From_date && $joined_To_date)
		{
            $cond						=	$cond." AND mdt_employees.joined_date between '".date("Y-m-d", strtotime($joined_From_date))."' AND '".date("Y-m-d", strtotime($joined_To_date))."' ";
		}
        elseif($joined_From_date)
		{
			$cond						=	$cond." AND mdt_employees.joined_date >= '".date("Y-m-d", strtotime($joined_From_date))."' ";
		}
        elseif($joined_To_date)
		{
			$cond						=	$cond." AND mdt_employees.joined_date <= '".date("Y-m-d", strtotime($joined_To_date))."' ";
		}
        if($selStat == '1' || $selStat == '2')
		{
			$cond					=	$cond. " AND ldt_employee_tds_calculator.status =".$selStat;
		}
        if($selStatus)
		{
			$cond					=	$cond." AND mdt_employees.employee_status = ".$selStatus;
		}
        if($selDepart)
		{
			$cond					=	$cond. " AND mdt_employees.department = ".$selDepart;
		}
        if($selReportingTo)
		{
			$cond					=	$cond. " AND mdt_employees.reporting_to = ".$selReportingTo;
		}
         if($selofc)
		{ 
			$cond					=	$cond. " AND mdt_employees.office = ".$selofc;
		}
        if($ps_financial_year && $selStat != '3')
		{
			$cond					=	$cond. " AND ldt_employee_tds_calculator.financial_yr = '".$ps_financial_year."'";
		}
        if($selStat == '3')
        {
            $sql = 'SELECT ldt_employee_tds_calculator.*,mdt_employees.emp_id,mdt_employees.employee_name,mdt_employees.reporting_to,mdt_employees.office,mdt_employees.employee_status,mdt_employees.joined_date,mdt_employees.department FROM mdt_employees LEFT JOIN ldt_employee_tds_calculator ON mdt_employees.emp_id=ldt_employee_tds_calculator.emp_id WHERE ldt_employee_tds_calculator.emp_id IS NULL AND '.$cond;
        }else
        { 
            $sql   =   'SELECT ldt_employee_tds_calculator.*, mdt_employees.emp_id,mdt_employees.employee_name,mdt_employees.reporting_to,mdt_employees.office,mdt_employees.employee_status,mdt_employees.joined_date,mdt_employees.department FROM mdt_employees INNER JOIN ldt_employee_tds_calculator ON mdt_employees.emp_id=ldt_employee_tds_calculator.emp_id AND '.$cond;   
        }        
        if($down_fun                                   ==   '1')
        {
            $get_pageList = '';
            $query	    			 =   $this->db->query($sql);
            $res					 =	 $query->result();
            return $res;
        }
        else
        {
            $get_pageList                                  =    $this->pagination_model->get_pagination_sql($sql,25);
            $get_pageList['selStat']  	                   = 	$selStat;
            $get_pageList['selName']  	                   = 	$selName;
            $get_pageList['selEmployId']  	               = 	$selEmployId;
            $get_pageList['joined_From_date']  	           = 	$joined_From_date;
            $get_pageList['joined_To_date']  	           = 	$joined_To_date;
            $get_pageList['selDepart']                     = 	$selDepart;
            $get_pageList['ps_financial_year']             = 	$ps_financial_year;
            $get_pageList['selReportingTo']                = 	$selReportingTo;
            $get_pageList['selofc']                        = 	$selofc;
            $get_pageList['ps_financial_year']             = 	$ps_financial_year;
            $get_pageList['joined_From_date']              = 	$joined_From_date;
            $get_pageList['joined_To_date']                = 	$joined_To_date;
            $get_pageList['selStatus']                     =   	$selStatus;
            return $get_pageList;
        }
    }
    public function downloadEmployeeTdsSheet()
	{ 
	    /*header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Employee_tds_calculation_list".date("Y_m_d_H_i_s").".xls");
        $page_details                      =    $this->getEmployeeTdsCalculationList(1);
           			?>
            <table width="100%" height="226" align="center" id="dgg" cellpadding="0" cellspacing="0" border="1" style="padding-left:20px; padding-top:20px; border:thick"> 
            <tr>
                <td align="center" width="100%" bgcolor="#99CCFF" style="font-weight:bold; font-size:16px"><b>TDS Sheet</b></td>
            </tr>
            <tr>
                <td valign="top">
                    <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0">
                        <tr class="tableHead" bgcolor="#99FFFF" style="font-weight:bold">
                            <td>	Sl No						                                       </td>
                            <td>	Employee Name 		                                               </td>
                            <td>	Employee ID 				                                       </td>
                            <td>	DOJ							                                       </td>
                            <td>	Status 				                                               </td>
                            <td>	Metro -1/ Non Metro 2  		                                       </td>
                            <td>	Age Category (1 - less than 60)  				                   </td>
                            <td>	Rent Paid    				                                       </td>
                            <td>	PF 				                                                   </td>
                            <td>	GMC 				                                               </td>
                            <td>	Housing loan principle   				                           </td>
                            <td>	Insurance   				                                       </td>
                            <td>	School fees 				                                       </td>
                            <td>	NSC and any fixed deposit with 5 years maturity 				   </td>
                            <td>	Medical Insurance for self,spouse and dependent children   	       </td>
                            <td>	Medical Insurance for parents if the parents are less than 60 yrs  </td>
                            <td>	Medical Insurance for parents if the parents are more than 60 yrs  </td>
                            <td>	Medical insurance for self if they are more than 60yrs   		   </td>
                            <td>	Housing Loan Interest for Self-Occupied Prop 				       </td>
                            <td>	Deduction u/s 80C   				                               </td>
                            <td>	Deduction u/s 80D   				                               </td>
                            <td>	Financial Year   				                                   </td>
                            <td>	Added On Date   				                                   </td>
                            <td>	rent paid in Excess over 10% of Basic pay   				       </td>
                            <td>	Actual HRA Received   				                               </td>
                            <td>	Tds Residence Value   				                               </td>
                            <td>	minval of rent_paid_basic_pay,actual_hra,residence_val   		   </td>
                            <td>	Salary Income before deduction   				                   </td>
                            <td>	Deduction u/s 16(ia) 				                               </td>
                            <td>	Deduction u/s 16(iii)     				                           </td>
                            <td>	Sum of Deduction u/s 16(ia) and u/s 16(iii) 				       </td>
                            <td>	Income From Salary 				                                   </td>
                            <td>	Income  from House Property 				                       </td>
                            <td>	Gross Total Income 				                                   </td>
                            <td>	Total Deduction 				                                   </td>
                            <td>	Net Income 				                                           </td>
                            <td>	Tax Payable 				                                       </td>
                            <td>	Rebate 				                                               </td>
                            <td>	Surcharge 				                                           </td>
                            <td>	Cess 				                                               </td>
                            <td>	Total Tax Payable 				                                   </td>
                            <td>	TDS Deducted 				                                       </td>
                            <td>	Balance Tax Payable 				                               </td>
                            <td>	No of Month Remain in this FY  				                       </td>
                            <td>	Average Monthly Tds  				                               </td>
                        </tr>                        
		     <?php
			if($page_details)
			{
				$cnt                           = 1;
				foreach($page_details as  $tds)
				{
                    $emp_id                             =	     $tds->emp_id;
                    $status                             =	     $tds->status;	
                    $employee_name                      =        $tds->employee_name;  
                    $joined_date                        =        $tds->joined_date; 
                    $residence                          =        $tds->residence; 
                    $category                           =        $tds->category; 
                    $rent_paid                          =        $tds->rent_paid; 
                    $tds_ppf                            =        $tds->tds_ppf;
                    $tds_gmc                            =        $tds->tds_gmc; 
                    $tds_hous_loan_principle            =        $tds->tds_hous_loan_principle; 
                    $tds_insurance                      =        $tds->tds_insurance; 
                    $tds_schl_fees                      =        $tds->tds_schl_fees; 
                    $tds_nsc                            =        $tds->tds_nsc; 
                    $med_self_spouse_child              =        $tds->med_self_spouse_child; 
                    $med_parents_less_sixty             =        $tds->med_parents_less_sixty; 
                    $med_parents_more_sixty             =        $tds->med_parents_more_sixty; 
                    $med_self_more_sixty                =        $tds->med_self_more_sixty; 
                    $income_hous_prop                   =        $tds->income_hous_prop; 
                    $dedction_80c                       =        $tds->dedction_80c; 
                    $dedctn_medical                     =        $tds->dedctn_medical; 
                    $financial_yr                       =        $tds->financial_yr; 
                    $added_on                           =        $tds->added_on; 
                    $rent_paid_basic_pay                =        $tds->rent_paid_basic_pay; 
                    $actual_hra                         =        $tds->actual_hra; 
                    $residence_val                      =        $tds->residence_val; 
                    $min_val                            =        $tds->min_val; 
                    $salary_bfr_deduction               =        $tds->salary_bfr_deduction; 
                    $ded_us_16ia                        =        $tds->ded_us_16ia; 
                    $ded_us_16iii                       =        $tds->ded_us_16iii; 
                    $sum_16ia_16iii                     =        $tds->sum_16ia_16iii; 
                    $incm_from_salary                   =        $tds->incm_from_salary; 
                    $incm_from_hous_prop                =        $tds->incm_from_hous_prop; 
                    $gross_total_incm                   =        $tds->gross_total_incm; 
                    $total_deduction                    =        $tds->total_deduction; 
                    $net_income                         =        $tds->net_income; 
                    $tax_payable                        =        $tds->tax_payable; 
                    $rebate                             =        $tds->rebate; 
                    $surcharge                          =        $tds->surcharge; 
                    $cess                               =        $tds->cess; 
                    $tot_tax_payable                    =        $tds->tot_tax_payable; 
                    $tds_deducted                       =        $tds->tds_deducted; 
                    $balance_tax_payable                =        $tds->balance_tax_payable; 
                    $no_month                           =        $tds->no_month; 
                    $avg_mnth_tds                       =        $tds->avg_mnth_tds; 
                    
				?>
				<tr>
					<td><?php echo $cnt++; ?></td>
					<td><?=$tds->employee_name?></td>
					<td><?=$tds->emp_id?></td>
					<td><?=$tds->joined_date?></td>
                    <?php
                        if($status == '1')
                        {
                        ?>
                          <td>Calculated</td>  
                        <?php
                        }elseif($status == '2'){
                        ?>
                          <td>Recalculate</td>  
                        <?php
                        }else{
                            ?>
                        <td>Not Calculated</td>  
                        <?php
                            
                        }
                    ?>
                    <td><?=$tds->residence?></td>
                    <td><?=$tds->category?></td>
                    <td><?=$tds->rent_paid?></td>
                    <td><?=$tds->tds_ppf?></td>
                    <td><?=$tds->tds_gmc?></td>
                    <td><?=$tds->tds_hous_loan_principle?></td>
                    <td><?=$tds->tds_insurance?></td>
                    <td><?=$tds->tds_schl_fees?></td>
                    <td><?=$tds->tds_nsc?></td>
                    <td><?=$tds->med_self_spouse_child?></td>
                    <td><?=$tds->med_parents_less_sixty?></td>
                    <td><?=$tds->med_parents_more_sixty?></td>
                    <td><?=$tds->med_self_more_sixty?></td>
                    <td><?=$tds->income_hous_prop?></td>
                    <td><?=$tds->dedction_80c?></td>
                    <td><?=$tds->dedctn_medical?></td>
                    <td><?=$tds->financial_yr?></td>
                    <td><?=$tds->added_on?></td>
                    <td><?=$tds->rent_paid_basic_pay?></td>
                    <td><?=$tds->actual_hra?></td>
                    <td><?=$tds->residence_val?></td>
                    <td><?=$tds->min_val?></td>
                    <td><?=$tds->salary_bfr_deduction?></td>
                    <td><?=$tds->ded_us_16ia?></td>
                    <td><?=$tds->ded_us_16iii?></td>
                    <td><?=$tds->sum_16ia_16iii?></td>
                    <td><?=$tds->incm_from_salary?></td>
                    <td><?=$tds->incm_from_hous_prop?></td>
                    <td><?=$tds->gross_total_incm?></td>
                    <td><?=$tds->total_deduction?></td>
                    <td><?=$tds->net_income?></td>
                    <td><?=$tds->tax_payable?></td>
                    <td><?=$tds->rebate?></td>
                    <td><?=$tds->surcharge?></td>
                    <td><?=$tds->cess?></td>
                    <td><?=$tds->tot_tax_payable?></td>
                    <td><?=$tds->tds_deducted?></td>
                    <td><?=$tds->balance_tax_payable?></td>
                    <td><?=$tds->no_month?></td>
                    <td><?=$tds->avg_mnth_tds?></td>
                    
				</tr>
					<?php
                }
			}			
		  ?>
		</table>
     </td></tr></table>	
	<?php	*/			
	}
    ///////////////////////////////////////////////////////////
    //////////////// get_no_of_months_till_end_fy//////////////
    ///////////////////////////////////////////////////////////
    function get_no_of_months_till_end_fy($doj)
    {
        $st_end_date_arr     =      $this->get_fy_start_end_date();
        $StartFy             =      $st_end_date_arr[0];
        $EndFy               =      $st_end_date_arr[1];
        if($doj              >      $StartFy)
        { 
            $diff_dat        =      date_diff(date_create($EndFy), date_create($doj));
            $mnthcnt         =      $diff_dat->format('%m');
            $mnthcnt         =      $mnthcnt+1;
        }
        else
        {
            $mnthcnt         =      12;
        }
        return $mnthcnt;    
    } 
    ///////////////////////////////////////////////////////////
    //////////////// get_fy_start_end_date       //////////////
    ///////////////////////////////////////////////////////////
    function get_fy_start_end_date()
    {
        $fin_year            =      $this->get_financial_year();
        $fin_year_arr        =      explode('-',$fin_year);
        $f_st_year           =      $fin_year_arr[0];
        $f_end_year          =      $fin_year_arr[1];
        $fy_start_date       =      '04/01/'.$f_st_year;
        $fy_end_date         =      '03/31/'.$f_end_year;          
        $x                   =      new DateTime($fy_start_date);
        $StartFy             =      date_format($x, 'Y-m-d H:i:s');
        $y                   =      new DateTime($fy_end_date);
        $EndFy               =      date_format($y, 'Y-m-d H:i:s');        
        return array('0'=>$StartFy,'1'=>$EndFy);
    }
}
?>
