<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ps_mail_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        return true;
    }    
	function get_ps_mail($data)
	{        
        $this->load->model('ldt_arrears_model');          
        $ps_emp_id						=	$data['result']->ps_emp_id;
        $ps_month						=	$data['result']->ps_month;    
        $num_month                      =   $ps_month;
        $ps_year						=	$data['result']->ps_year;
        $ps_present_days				=	$data['result']->ps_present_days;
        $ps_basic						=	$data['result']->ps_basic;
        $ps_hra							=	$data['result']->ps_hra;
        $ps_lta							=	$data['result']->ps_lta;
        $ps_med_reim					=	$data['result']->ps_med_reim;
        $ps_conveyance					=	$data['result']->ps_conveyance;        
        $upd_arrears                    =   $this->ldt_arrears_model->get_arrears_by_emp_id($ps_emp_id,$ps_month,$ps_year);
        $ps_arrears						=	$data['result']->ps_arrears + $upd_arrears;
        $ps_tds							=	$data['result']->ps_tds;
        $ps_adv_sal						=	$data['result']->ps_adv_sal;
        $ps_pt							=	$data['result']->ps_pt;
        $ps_gmc_gpa						=	$data['result']->ps_gmc_gpa;
        $ps_gratuity					=	$data['result']->ps_gratuity;
        $ps_empl_contri_pf				=	$data['result']->ps_empl_contri_pf;
        $ps_empyr_contri_pf				=	$data['result']->ps_empyr_contri_pf;
        $ps_special_allow				=	$data['result']->ps_special_allow;
        $ps_net_pay						=	$data['result']->ps_net_pay + $upd_arrears;
        $ps_net_sal						=	$data['result']->ps_net_sal;    
        $ps_tot_ctc						=	$data['result']->ps_tot_ctc;    
        $ps_variable    				=	$data['result']->ps_variable;    
        $ps_gross_variable 				=	$data['result']->ps_gross_variable;    
        $ps_loan_amt    				=	$data['result']->ps_loan_amt;    
        $ps_deducted_leave				=	$data['result']->ps_deducted_leave; 
        $ps_present_days                =   $ps_present_days + $ps_deducted_leave;
        if($ps_gross_variable           ==  '0.00')
        {
            $gvCTC                      =   $variable;
        }
        else if($ps_gross_variable     <=   $variable)
        {
            $gvCTC                      =   $variable;
        }
        else 
        {
            $gvCTC                      =   $ps_gross_variable;
        } 
        #########################################################################
        $header                         =   $this->config->item('img_url').'header.png';    
        $footer                         =   $this->config->item('img_url').'footer.png';
        $im_logo                        =   $this->config->item('img_url').'iml.png';
        $tele                           =   $this->config->item('img_url').'tele.png';
        $ps_month						=	date('F',mktime(0,0,0, $ps_month,10));  
        if($data['embdoj']              !=   '0000-00-00')
        {
            $time                       =   strtotime($data['embdoj'] ); 
            $embdoj                     =   $final=date("d",$time)." ".substr(date("F",$time),0,3)." ".date("Y",$time);
        }
        '<tr >
              <td colspan="2"> Employee Level </td>
              <td colspan="2" align="right"> '.$data['emp_level'].'</td>
        </tr>';        
        $ps                             =   '<div class="aaa" id="aaa"> 
        <div style="width: 800px;float: left;margin-left: 5%;">
            <img src="'.$im_logo.'" height="75" width="300" />
            <img src="'.$tele.'" height="75" width="300" style="float:right;" />
        </div>
        <table width="800" border="1" cellspacing="1" cellpadding="5" style="float: left;margin-left: 5%;margin-top:1%;">       
           <tr > 
              <td colspan="4" align="center" height="50"> <b>Salary Slip for the Month of '.$ps_month.' - '.$ps_year.'</b> </td>
           </tr> 
           <tr > 
              <td colspan="2"> Employee ID </td>
              <td colspan="2" align="right"> '.$data['emp_code'].' </td>
           </tr>
           <tr >
              <td colspan="2"> Employee Name </td>
              <td colspan="2" align="right"> '.$data['empName'].' </td>
           </tr>
           <tr >
              <td colspan="2"> Department </td>
              <td colspan="2" align="right"> '.$data['deptName'].'</td>
           </tr>
           <tr >
              <td colspan="2"> Designation </td>
              <td colspan="2" align="right"> '.$data['desg_name'].'</td>
           </tr>           
           <tr >
              <td colspan="2"> Date of Joining </td>
              <td colspan="2" align="right"> '.$embdoj.'</td>
           </tr>
           <tr >
              <td colspan="2"> Bank Account Number </td>
              <td colspan="2" align="right"> '.$data['bankAccount'].'</td>
           </tr>
           <tr >
              <td colspan="2"> PF Account Number </td>
              <td colspan="2" align="right"> '.$data['pfAccount'].'</td>
           </tr>
           <tr >
              <td colspan="2"> Number of Working Days </td>
              <td colspan="2" align="right"> '.$data['total_working_days'].'</td>
           </tr>
           <tr >
              <td colspan="2"> Number of Days Paid </td>
              <td colspan="2" align="right"> '.$ps_present_days.'</td>
           </tr>
           <tr >
              <td colspan="2"> Number of Earned Leaves Deducted </td>
              <td colspan="2" align="right"> '.$ps_deducted_leave.'</td>
           </tr>
           <tr >
              <td colspan="2"> Gross Total CTC for the Month </td>
              <td colspan="2" align="right"> '.$ps_tot_ctc.'</td>
           </tr>
           <tr >
              <td colspan="2"> Gross Fixed CTC for the Month </td>
              <td colspan="2" align="right"> '.$data['salary'].'</td>
           </tr>
           <tr >
              <td colspan="2"> Gross Variable CTC for the Month</td>
              <td colspan="2" align="right"> '.$gvCTC.'</td>
           </tr>
           <tr >
              <td colspan="2"> Net Fixed CTC for the Month </td>
              <td colspan="2" align="right"> '.$ps_net_sal.'</td>
           </tr>
           <tr >
              <td colspan="2" align="center"><b>Earnings</b>  </td>
              <td colspan="2" align="center"><b>Deductions</b> </td>
           </tr>
           <tr >
              <td ><b>Particulars</b> </td>
              <td align="right"><b>Net</b> </td>
              <td ><b>Particulars</b> </td>
              <td align="right"><b>Amount</b> </td>
           </tr>
           <tr >
              <td >Basic  </td>
              <td >'.$ps_basic.' </td>
              <td >PT  </td>
              <td >'.$ps_pt.' </td>
           </tr>
           <tr >
              <td >HRA  </td>
              <td >'.number_format($ps_hra,2).' </td>  
              <td >GMC  </td>
              <td >'.number_format($ps_gmc_gpa,2).' </td>
           </tr>
           <tr >
              <td >LTA  </td>
              <td >'.number_format($ps_lta,2).' </td>   
              <td >Gratuity  </td>
              <td >'.$ps_gratuity.' </td>  
           </tr>
           <tr >
              <td >Medical Reimbursements  </td>
              <td >'.$ps_med_reim.' </td>   
              <td > Employeer PF Contributions</td>
              <td > '.number_format($ps_empyr_contri_pf,2).'</td>   
           </tr>
           <tr >
              <td >Conveyance  </td>
              <td >'.number_format($ps_conveyance,2).' </td>   
              <td > Employee PF Contributions</td>
              <td > '.number_format($ps_empl_contri_pf,2).'</td>   
           </tr>
           <tr >
              <td >Special Allowance  </td>
              <td >'.number_format($ps_special_allow,2).' </td>              
              <td >TDS</td>
              <td >'.number_format($ps_tds,2).'</td>
           </tr>
           <tr >
              <td >Arrears</td>
              <td >'.number_format($ps_arrears,2).'</td>    
              <td >Advance Salary</td>
              <td >'.number_format($ps_adv_sal,2).'</td> 
           </tr>
           <tr >
              <td >Variable  </td>
              <td >'.number_format($ps_variable,2).' </td>   
              <td >Loan</td>
              <td >'.$ps_loan_amt.'</td>
           </tr>
           <tr >          
              <td ><b>Net Salary Payable <b> </td>
              <td ><b>'.number_format($ps_net_pay,2).'<b></td>   
              <td ><b>Total Deductions </b></td>
              <td >'.$data['total_deductions'].'</td>
           </tr> 
           <tr >
              <td colspan="4"> Notes  </td>
           </tr>
           <tr >
              <td colspan="4"> Gross Variable CTC will be paid seperately as per the company incentive policy communicated to you and this is completely based on your performance  </td>
           </tr>
           <tr >
              <td colspan="4"> 1. Professional Tax will be applicable on salary > or =Rs 15,000 .  </td>
           </tr>
           <tr >
              <td colspan="4"> 2. As per Suvision Policy LTA and Medical Reimbursement will be deducted from monthly salary and employees  are eligible to claim after 3 months by producing supporting. </td>
           </tr>
           <tr >
              <td colspan="4"> 3. TDS will be deducted as applicable.  </td>
           </tr>
           <tr >
              <td colspan="4"> 4. Deduction due to Attendance Policy will be calculated on the basis on absent and Office reporting time. </td>
           </tr>
        </table><p style="text-align:center;font-weight:bold;float:right;margin-right:5%;">Manager - HR&Admin</p><img src="'.$footer.'" height="100" width="900" /> </div>
        <button type="button" class="btn btn-primary pull-right saveFile" id="IMPS_'.$ps_emp_id.'_'.$num_month.'_'.$ps_year.'">Save</button>
    <script src="'.$this->config->item('plugins').'jQuery/jquery-2.2.3.min.js"></script>
    <style> td, th { padding: 2px; } </style> 
    <script>
    function PrintElem(elem,title)
    {
        var mywindow = window.open("", "PRINT", "height=600,width=600");    
        mywindow.document.write("<title>"+title+"</title>");
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); 
        mywindow.print();
        mywindow.close();
        return true;
    }  
    $(document).ready(function(){   
        $(document).on("click",".saveFile",function(){  
            var title                       =   $(this).attr("id");
            PrintElem("aaa",title);
        });        
    });
    </script>';
    return $ps;
	}
}
?>