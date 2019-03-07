<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_arrears_model extends CI_Model {
	function __construct() {
        $this->tableName        =   'ldt_arrears';
        $this->primaryKey       =   'la_id';
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
    public function get_laid_by_emp_id($emp_id,$acd)
	{
        $this->db->select('la_id');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('la_status','1');
		$this->db->where('la_date LIKE "'.$acd.'%"');
		$result                 =   $this->db->get($this->tableName)->row();
        if($result             !=   null)
        {
            return $result->la_id;
        }
        else
        {
            return '';
        }        
	} 
    public function get_incenctives_by_emp_id($emp_id,$month,$year)
	{
        $acd                    =   $year.'-'.$month;
        $acd                    =   date('Y-m',strtotime($acd));
        $this->db->select('incentives');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('la_status','1');
		$this->db->where('la_date LIKE "'.$acd.'%"');
		$result                 =   $this->db->get($this->tableName)->row();
        if($result             !=   null)
        {
            return $result->incentives;
        }
        else
        {
            return '';
        }        
	} 
    public function get_arrears_by_emp_id($emp_id,$month,$year)
	{
        $acd                    =   $year.'-'.$month;
        $acd                    =   date('Y-m',strtotime($acd));
        $this->db->select('arrears');
		$this->db->where('emp_id',$emp_id);
		$this->db->where('la_status','1');
		$this->db->where('la_date LIKE "'.$acd.'%"');
		$result                 =   $this->db->get($this->tableName)->row();
        if($result             !=   null)
        {
            return $result->arrears;
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
	public function upload_arrears()
	{	
        $month                        	=   $this->input->post('month', TRUE);
        $year                        	=   $this->input->post('year', TRUE);
        $month                          =   ($month)?($month):round(date('m'));
        $year                           =   ($year)?($year):date('Y');
        $date                           =   $year.'-'.$month;
        $acd                            =   date('Y-m',strtotime($date));
        $la_date                        =   date('Y-m-d',strtotime($date));
        if ($_FILES['csv']['size']		> 	0)
        {
            $file		 				= 	$_FILES[csv][tmp_name];
            $handle	 					= 	fopen($file,"r");
            $insCnt						=	0;
            $wrongCnt					=	0;
            $updateCnt					=	0;
            do
            {
                if($data[0]) 
                {
                    $emp_id             =	$data[0];
                    $incentives         =	$data[1];
                    $la_id              =   $this->get_laid_by_emp_id($data[0],$acd);
                    if($la_id)
                    {   
                        if($data[1]     && $data[2])
                        {
                            $this->update(array('incentives'=>$data[1],'arrears'=>$data[2]),'la_id='.$la_id ); 
                        }
                        else if($data[1])
                        {
                            $this->update(array('incentives'=>$data[1]),'la_id='.$la_id ); 
                        }
                        else if($data[2])
                        {
                            $this->update(array('arrears'=>$data[2]),'la_id='.$la_id ); 
                        }                    
                    }
                    else
                    {   
                        $this->insert(array('emp_id'=>$data[0],'incentives'=>$data[1],'arrears'=>$data[2],'la_date'=>$la_date,'la_added_by'=>$this->session->userdata('userid')));
                    }                    
                    $insCnt				=	$insCnt + 1;
                }
            }while ($data 				= 	fgetcsv($handle,1000,",","'"));
        }
	} 
}