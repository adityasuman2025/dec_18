<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_advance_salary_model extends CI_Model {
	function __construct() {
		$this->tableName        = 'ldt_advance_salary'; 
		$this->primaryKey       = 'ads_id';
	}
    public function get_emp_advance_salary($emp_id,$year,$month)
	{
        $date1                   =   date($year.'-'.$month.'-11');
        $date2                   =   date('Y-m-05');        
		$this->db->select('sum(ads_processed_amount) as amt');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('ads_applied_on BETWEEN "'.$date1.'" AND  "'.$date2.'"');        
		$result                 =   $this->db->get($this->tableName)->row();
        if($result              !=  null)
        {
            return $result->amt;
        }
		else
        {
            return 0;
        }
	}
    public function update($arr,$cond)
	{
        $this->db->update($this->tableName,$arr,$cond);
        return true;
	}
    public function insert($arr)
	{
        $this->db->insert($this->tableName,$arr);
        return true;
	}
    public function advance_salary_list()
	{        
        $ps_month                   =   $this->input->input_stream('ps_month', TRUE); 
        $ps_year                    =   $this->input->input_stream('ps_year', TRUE);          
        $ads_status                 =   $this->input->input_stream('ads_status', TRUE);          
        //$ps_month					=	($ps_month)?$ps_month:'';
        //$ps_year                    =   ($ps_year)?$ps_year:'';     
        $ads_status	       			=	($ads_status)?$ads_status:1;     
        $selEmpId                   =   $this->input->input_stream('selEmpId', TRUE);  
        
        $cond                       =   "emp_id != 0";
        if($selEmpId)
		{
			$cond					=	$cond." AND emp_id =".$selEmpId;
		}
        if($ps_year || $ps_month)
		{
            $year_month             =   $ps_year."-".$ps_month;
			$cond					=	$cond." AND ads_applied_on LIKE '".$year_month."%'";
		}
        if($ads_status)
		{
			$cond					=	$cond." AND ads_status =".$ads_status;
		}
        $get_pageList  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'ads_on DESC',25);	
        $data['page_details'] 		= 	$get_pageList;        
        $result_array				=	$data['page_details']['results'];
        $data['page_details']['results']=   array();
        if($result_array            !=  null)
        {
            $this->load->model('mdt_employees_model');
            $this->load->model('tdt_employee_salary_model');
            $this->load->model('tdt_employee_bank_account_model');
            foreach($result_array   as  $resval)
            {                 
                $salary             =   '';                
                $emp_name           =   '';                
                $emp_name           =   $this->mdt_employees_model->get_emp_name($resval->emp_id);   
                $emp_doj            =   $this->mdt_employees_model->getEmployeeJoiningDate($resval->emp_id);
                $emp_doj            =   date('d-m-Y', strtotime($emp_doj));
                $applied_on         =   $resval->ads_applied_on;
                $applied_on         =   date('d-m-Y', strtotime($applied_on));
                $acc_det            =   $this->tdt_employee_bank_account_model->get_emp_bank_details($resval->emp_id);
                $acc_no             =   ($acc_det->acc_bank_account_number)?($acc_det->acc_bank_account_number):'NU';
                $empSalaryDetails	=	$this->tdt_employee_salary_model->get_emp_salary_details($resval->emp_id); 
                if($empSalaryDetails)
                {
                    $salary 		= 	$empSalaryDetails->salary_amount;
                } 
                $temp               =   array("ads_id"=>$resval->ads_id,"emp_id"=>$resval->emp_id,"ads_applied_amount"=>$resval->ads_applied_amount,"ads_processed_amount" =>$resval->ads_processed_amount,"emp_name"=>$emp_name,"ads_applied_on"=>$applied_on,"ads_status"=>$resval->ads_status,"ads_comment"=>$resval->ads_comment,"salary"=>$salary, 'emp_doj'=>$emp_doj, 'acc_no'=>$acc_no);
                $data['page_details']['results'] = (array) $data['page_details']['results'];
                array_push($data['page_details']['results'], $temp);
            }		  
        }
		$data['selEmpId']  		    = 	$selEmpId;		
		$data['ps_month']  		    = 	$ps_month;		
		$data['ps_year']  	       	= 	$ps_year;	
		$data['ads_status']  	    = 	$ads_status;	
        return $data;         
	}
    public function my_advance_salary_list()
	{        
        $ps_month                   =   $this->input->input_stream('ps_month', TRUE); 
        $ps_year                    =   $this->input->input_stream('ps_year', TRUE);          
        $ads_status                 =   $this->input->input_stream('ads_status', TRUE);  
        $year_month                 =   $ps_year."-".$ps_month;
        $cond                       =   "emp_id =".$this->session->userdata('employee');       
        if($year_month              && $ps_year &&  $ps_year)
		{
			$cond					=	$cond." AND ads_applied_on LIKE '".$year_month."%'";
		}
        if($ads_status)
		{
			$cond					=	$cond." AND ads_status =".$ads_status;
		}
        $get_pageList  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'ads_on DESC',25);	
        $data['page_details'] 		= 	$get_pageList;   
		$data['ps_month']  		    = 	$ps_month;		
		$data['ps_year']  	       	= 	$ps_year;	
		$data['ads_status']  	    = 	$ads_status;	
        return $data;         
	}
    public function process_advance_salary()
	{		
        $ads_status                 =   $this->input->input_stream('ads_status', TRUE); 
        $ads_processed_amount       =   $this->input->input_stream('ads_processed_amount', TRUE);          
        $ads_comment                =   $this->input->input_stream('ads_comment', TRUE);          
        $ads_id                     =   $this->input->input_stream('ads_id', TRUE);          
        $update                     =   $this->update(array('ads_status'=>$ads_status,'ads_processed_amount'=>$ads_processed_amount,'ads_updated_by'=>$this->session->userdata('userid'),'ads_updated_on'=>date('Y-m-d H:i:s'),'ads_comment'=>$ads_comment),'ads_id='.$ads_id); 
	}
    public function apply_advance_salary()
	{		
        $this->load->model('tdt_employee_salary_model');
        $empSalaryDetails       	=	$this->tdt_employee_salary_model->get_emp_salary_details($this->session->userdata('userid')); 
        $salary                     =   '';
        $applied_count              =   $this->get_applied_count($this->session->userdata('userid'));
        if($empSalaryDetails)
        {
            $salary         		= 	$empSalaryDetails->salary_amount;
        } 
        return array('0'=>$salary,'1'=>$applied_count); 
	}
    public function apply_for_ads_sal()
	{		
        $ads_applied_amount         =   $this->input->input_stream('ads_applied_amount', TRUE);          
        $ads_id                     =   $this->input->input_stream('ads_id', TRUE);          
        $insert                     =   $this->insert(array('ads_applied_amount'=>$ads_applied_amount,'emp_id'=>$this->session->userdata('employee'),'ads_applied_on'=>date('Y-m-d')));
        redirect(base_url('hrm/my_advance_salary_list'));
	}
    public function get_applied_count($emp_id)
	{		
        $day                        =   date('d');
        $date1                      =   '';
        $date2                      =   '';
        if($day                    >=   11)
        {        
            $m                      =   date('m')+1;
            $date1                  =   date('Y-m-11');
            $date2                  =   date('Y-'.$m.'-05');
        }   
        if($day                    <=   5)
        {        
            $m                       =   date('m')-1;
            $date1                   =   date('Y-'.$m.'-11');
            $date2                   =   date('Y-m-05');
        }
        $this->db->select('count(ads_id) as cnt');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('ads_applied_on BETWEEN "'.$date1.'" AND "'.$date2.'" AND ads_status NOT IN (4,3)');
		$result                 =   $this->db->get($this->tableName)->row();
        if($result              !=  null)
        {
            return $result->cnt;
        }
		else
        {
            return 0;
        }        
	}
}