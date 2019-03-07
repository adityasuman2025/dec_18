<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_leave_wallet_model extends CI_Model {
	function __construct() {
		$this->tableName  = 'ldt_leave_wallet';
		$this->primaryKey = 'emp_id';
	} 
    public function get_earned_leave($emp_id)
	{ 
		$this->db->select('temp_leave_bal,used_leave');
		$this->db->where('emp_id',$emp_id);
		$result = $this->db->get($this->tableName)->row();        
        return $result;
	}     
    public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();        
    }
    public function update($arr,$cond) 				
	{
		$this->db->set($arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $arr);
	}
	public function get_remaining_leaves($emp_id)
	{ 
		$this->db->select('leave_balance');
		$this->db->where('emp_id',$emp_id);
		$result = $this->db->get($this->tableName)->row();  
		if($result)
		{
			return $result->leave_balance;
		}	
		else
		{
			return 0;
		}
	} 
	public function get_earned_leaves()
	{ 
		$sql                            =   "select e.emp_id, w.emp_id as w_emp_id, w.leave_balance, w.earned_leave, joined_date,date_updated from mdt_employees as e left join ldt_leave_wallet as w on e.emp_id= w.emp_id WHERE e.employee_status = 1";
        $updcnt					        = 	0;
        $inscnt					        = 	0;
        $query                          =   $this->db->query($sql);
        if ($query->num_rows()          >   0) 
        {
            $data                       =   $query->result_array();
            foreach($data               as  $val)
            {
                $date_updated           =   date('Y-m',strtotime($val['date_updated']));
                if($val['w_emp_id']     &&  ($date_updated   !=   date('Y-m')))
                {
                    $doj				=	$val['joined_date'];
                    $datetime1          =   date('Y-m-d',strtotime($doj));
                    $datetime2          =   date('Y-m-d'); 
                    $diff               =   abs(strtotime($datetime2) - strtotime($datetime1));
                    $days               =   $diff / 86400;
                    if($days            <   60)
                    {
                        $totleave		=	0;
                        $usedleave		=	0;
                        $leaveBalance	=	0;
                    }
                    else
                    {
                        $leaveBalance	=	$val['leave_balance'] + 1.5;
                        $totleave		=	$val['earned_leave'] + 1.5;
                    }
                    $updresult          =   $this->update(array("earned_leave"=>$totleave,"leave_balance"=>$leaveBalance,"temp_leave_bal "=>$leaveBalance,'date_updated'=>date('Y-m-d H:i:s'))," emp_id=".$val['emp_id']);
                    $updcnt				= 	$updcnt + 1;
                }
                else if(!$val['w_emp_id'])
                {
                    $doj				=	$val['joined_date'];
                    $datetime1          =   date('Y-m-d',strtotime($doj));
                    $datetime2          =   date('Y-m-d');
                    $diff               =   abs(strtotime($datetime2) - strtotime($datetime1));
                    $days               =   $diff / 86400;
                    if($days            >   60)
                    {
                        $totleave		=	1.5;
                        $usedleave		=	0;
                        $leaveBalance	=	1.5;
                    }
                    else
                    {
                        $totleave		=	0;
                        $usedleave		=	0;
                        $leaveBalance	=	0;
                    }
                    $insResult          =   $this->insert(array('emp_id'=>$val['emp_id'],'earned_leave'=>$totleave,'used_leave'=>$usedleave,'leave_balance'=>$leaveBalance,'temp_leave_bal'=>$leaveBalance,'doj'=>$doj,'date_updated'=>date('Y-m-d H:i:s')));
                    $inscnt				= 	$inscnt + 1;
                }
            }
        }
        return "<br>update count: ".$updcnt."<br> inscnt count: ".$inscnt;
	} 
}
?>