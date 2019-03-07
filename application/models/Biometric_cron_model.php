<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Biometric_cron_model extends CI_Model {
	function __construct() {
        $this->tableName        =   'mdt_employees';
        $this->primaryKey       =   'emp_id';
	}  
    function employee_present_for_finance($download_excel='')
    {
        $this->load->model('pagination_model');
        $get_empList                    =   '';
        $jresval                        =   '';
        $emp_list                       =   '';
        $emp_search                     =   $this->input->input_stream('emp_search', TRUE); 
		$employee_status			    =	$this->input->input_stream('employee_status', TRUE); 
		$gender					        =	$this->input->input_stream('gender', TRUE); 
		$present_date					=	$this->input->input_stream('present_date', TRUE); 
		$from_time    					=	$this->input->input_stream('from_time', TRUE); 
		$to_time    					=	$this->input->input_stream('to_time', TRUE); 
		$department			            =	$this->input->input_stream('department', TRUE); 
		$office			                =	$this->input->input_stream('office', TRUE); 
		$reporting_to			        =	$this->input->input_stream('reporting_to', TRUE); 
		$designation			        =	$this->input->input_stream('designation', TRUE);           
		$where 							=	'emp_id != 0';
		if(is_numeric($emp_search))
		{
            $where                      =  $where. " AND emp_id =".$emp_search;
		}
		elseif($emp_search)
		{	
            $where                      =   $where. " AND employee_name LIKE '%".$emp_search."%'";
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
        if($present_date)
        {
             $present_date              =   date("Y-m-d", strtotime($present_date));
        }
        $present_date                   =   ($present_date)?($present_date):date('Y-m-d');        
        if($from_time)
        {
             $from_time                 =   date('H:i:s', strtotime($from_time));
        }
        if($to_time)
        {
             $to_time                   =   date('H:i:s', strtotime($to_time));
        }
        $file_path                      =   $this->get_bio_file_name($present_date);  
        if(file_exists($file_path))
        {
            $result                     =   file_get_contents($file_path);
            $jresval                    =   json_decode($result, true); 
            foreach($jresval            as  $key=>$json_res)
            { 
                if($from_time           && $to_time)
                {
                    if(($json_res['punch_in']    >= $from_time) && ($json_res['punch_in'] <= $to_time))
                    {
                        $emp_list               =   $emp_list.','.$json_res['emp_id'];
                    }
                }
                else
                {
                    $emp_list               =   $emp_list.','.$json_res['emp_id'];
                }                
            }
            $emp_list                   =   ltrim($emp_list,',');
            if($emp_list)
            {
                $where                  =	$where." AND emp_id  IN (".$emp_list.")";
                if($download_excel)
                {
                    $this->db->where($where);
                    $result             =   $this->db->get($this->tableName);
                    $data['act_res']    =   $result->result();
                }
                else
                {
                    $get_empList        =   $this->pagination_model->get_pagination("mdt_employees",$where,"emp_id asc",25);
                }
            }
        }		
        $data['jresval']                =   $jresval;
        $data['department']             =   $department;
        $data['emp_details']            =   $get_empList;
        $data['reporting_to']           =   $reporting_to;  
		$data['gender']  		        = 	$gender;       
		$data['present_date']  	        = 	$this->input->input_stream('present_date', TRUE);         
		$data['from_time']    			=	$this->input->input_stream('from_time', TRUE); 
		$data['to_time']    			=	$this->input->input_stream('to_time', TRUE); 
        $data['emp_search']  		    = 	$emp_search;
		$data['designation']            =   $designation;
        $data['office']                 =   $office;
        $this->load->model('sdt_department_model');
        $data['dept_list']              =   $this->sdt_department_model->get_active_departments();
        $this->load->model('mdt_employees_model');
        $data['rep_array']              =	$this->mdt_employees_model->get_managers_list();
        $this->load->model('mdt_company_structures_model');
		$data['office_list']            = 	$this->mdt_company_structures_model->get_emp_company_list();
        if($department)
        {            
            $data['designation_list']   =   $this->sdt_designations_model->get_desig_dept_list($department); 
        }
        else
        {
            $data['designation_list']   =   NULL;
        }
        return $data;
    }
    function get_bio_file_name($today)
    {
        $cur_month                      =   round(date('m'));
        $cur_year                       =   date('Y');
        if($today                       !=  date("Y-m-d"))
        {
            $smonth                     =   explode('-',$today);
            $mnth                       =   ltrim($smonth['1'],'0');     
            $table                      =   'DeviceLogs_'.$mnth.'_'.$cur_year;
        }
        else
        {
            $table                      =   'DeviceLogs_'.$cur_month.'_'.$cur_year;
        }
        $dayarr                         =   explode('-',$today);
        $jfile                          =   $table.'_'.$dayarr[2]; 
        $file_path                      =   "/bio_json/".$jfile.".json";
        return $file_path;
    }
}