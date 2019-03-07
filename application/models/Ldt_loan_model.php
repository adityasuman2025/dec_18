<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_loan_model extends CI_Model {
	function __construct() {
        $this->tableName        =   'ldt_loan';
        $this->primaryKey       =   'll_id';
	}  
    public function get_count($cond)
    {
        $query	                =	$this->db->where($cond)->get($this->tableName);
		$count                  =	$query->result();
		return count($count);
    }

    public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
    public function get_llid_by_emp_id($emp_id,$acd)
	{
        $this->db->select('ll_id');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('ll_status','1');
		$this->db->where('ll_date LIKE "'.$acd.'%"');
		$result                 =   $this->db->get($this->tableName)->row();
        if($result             !=   null)
        {
            return $result->ll_id;
        }
        else
        {
            return '';
        }        
	} 
    public function get_loan_by_emp_id($emp_id,$month,$year)
	{
        $acd                    =   $year.'-'.$month;
        $acd                    =   date('Y-m',strtotime($acd));
        $this->db->select('loan_amount');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('ll_status','1');
		$this->db->where('ll_date LIKE "'.$acd.'%"');
		$result                 =   $this->db->get($this->tableName)->row();
        if($result             !=   null)
        {
            return $result->loan_amount;
        }
        else
        {
            return '';
        }        
	} 
	public function insert($insArr)
	{	
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	} 
	public function upload_loan()
	{	
        $month                        	=   $this->input->post('month', TRUE);
        $year                        	=   $this->input->post('year', TRUE);
        $month                          =   ($month)?($month):round(date('m'));
        $year                           =   ($year)?($year):date('Y');
        $date                           =   $year.'-'.$month;
        $acd                            =   date('Y-m',strtotime($date));
        $ll_date                        =   date('Y-m-d',strtotime($date));
        if ($_FILES['csv']['size']		> 	0)
        {
            $file		 				= 	$_FILES[csv][tmp_name];
            $handle	 					= 	fopen($file,"r");
            $insCnt						=	0;
            $wrongCnt					=	0;
            $updateCnt					=	0;
            do
            {
                if($data[1]) 
                {
                    $emp_id             =	$data[0];
                    $arrears            =	$data[1];
                    $ll_id              =   $this->get_llid_by_emp_id($data[0],$acd);
                    if($ll_id           && $data[1])
                    {   
                        $this->update(array('loan_amount'=>$data[1]),'ll_id='.$ll_id ); 
                                             
                    }
                    else
                    {   
                        $this->insert(array('emp_id'=>$data[0],'loan_amount'=>$data[1],'ll_date'=>$ll_date,'ll_added_by'=>$this->session->userdata('userid')));
                    }                    
                    $insCnt				=	$insCnt + 1;
                }
            }while ($data 				= 	fgetcsv($handle,1000,",","'"));
        }
	} 
}