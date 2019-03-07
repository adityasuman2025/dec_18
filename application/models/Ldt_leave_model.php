<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_leave_model extends CI_Model {
	function __construct() {
		$this->tableName = 'ldt_leave';
	}
	public function getCount($cond)
	{
		$query	=	$this->db->where($cond)
					->get($this->tableName);
		$count= $query->result();
		return count($count);
	}
	public function leaveDetails($fields,$cond)
	{
		$this->db->select($fields);
		$this->db->where($cond);
		$this->db->order_by("leave_id","DESC");
		$this->db->limit(1,0);
		$result =   $this->db->get($this->tableName)->result_array();   
        //$retval =   $result->num_rows();
		return $result;
	}
	function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
	public function get_leave_details_by_empid($emp_id="",$leave_type="")
    {
		$EmpNme                            =   $this->input->input_stream('EmpNme', TRUE); 
		$EmpFrm                            =   $this->input->input_stream('EmpFrmDte', TRUE); 
		$EmpTod                            =   $this->input->input_stream('EmpToDte', TRUE); 
		$EmpSts                            =   $this->input->input_stream('Stats', TRUE);
		$this->load->model('pagination_model');
		$where							=	"l.leave_id!=0";
		if($leave_type)
		{
			$where						=	$where." and l.leave_status in(".$leave_type.")";
		}
		if($emp_id)
		{
			$where						=	$where." and l.emp_id in (".$emp_id.")";
		}
		if($EmpNme){
			$where						=	$where." and mdtemp.employee_name LIKE '%".$EmpNme."%'";			
		}
		if($EmpFrm){
			$where						=	$where." and l.leave_from='$EmpFrm'";	 		
		}
		if($EmpTod){
			$where						=	$where." and l.leave_to='$EmpTod'";			
		}
		if($EmpSts){
			if($EmpSts=='Pending' || $EmpSts=='pending'){
				$stats		=	1;
			}else if($EmpSts=='RM Approved'){
				$stats		=	2;
			}else if($EmpSts=='RM Disapproved'){
				$stats		=	3;
			}else if($EmpSts=='RM Excused'){
				$stats		=	4;
			}else if($EmpSts=='HR Approved'){
				$stats		=	5;
			}else if($EmpSts=='HR Disapproved'){
				$stats		=	6;
			}else if($EmpSts=='HR Excused'){
				$stats		=	7;
			}else if($EmpSts=='Excusing leave pending' || $EmpSts=='Excusing Leave Pending'){
				$stats		=	8;
			}else if($EmpSts=='Cancelled By Employee'){
				$stats		=	9;
			}
			$where						=	$where." and l.leave_status='$stats'";			
		}		
		$sql						    =   "select mdtemp.employee_name,l.* from ldt_leave l, mdt_employees mdtemp where l.emp_id=mdtemp.emp_id and ".$where." order by l.leave_createdon desc";
		 return $Result_data  			=   $this->pagination_model->get_pagination_sql($sql,25);
    }
	public function apply_leave()
	{		
        $leave_from        			 =   date('Y-m-d', strtotime($this->input->post('leave_from'))); 
        $leave_to        			 =   date('Y-m-d', strtotime($this->input->post('leave_to')));        
        $leave_days                  =   $this->input->input_stream('total_days', TRUE);          
        $leave_type                  =   $this->input->input_stream('leave_type', TRUE);          
        $leave_reason                =   $this->input->input_stream('leave_reason', TRUE);  
        $leave_days_details         =   "";
        $start    						= 	new DateTime($leave_from);
	    $end      						= 	(new DateTime($leave_to))->modify('+1 day');
	    $interval 						= 	new DateInterval('P1D');
	    $period   						= 	new DatePeriod($start, $interval, $end);   
	    foreach ($period as $dt) 
	    {
        	$leave_days_details			.=  	$dt->format("Y-m-d").',';          
    	}
    	$leave_days_details 			=	rtrim($leave_days_details,',');
        $insert                    	 =   $this->insert(array('leave_from'=>$leave_from,'leave_to'=>$leave_to,'leave_days_details'=>$leave_days_details,'leave_days'=>$leave_days,'leave_type'=>$leave_type,'leave_reason'=>$leave_reason,'emp_id'=>$this->session->userdata('employee')));
        redirect(base_url('hrm/my_leave_details'));
	}
	public function cancel_leave()
	{
        $leave_id                  =   $this->input->input_stream('leave_id', TRUE);  		
        $this->db->set("leave_status",9); 
        $this->db->set("approval_note","Cancelled By Employee"); 
        $this->db->set("leave_action_by",$this->session->userdata('employee')); 
        $this->db->where("leave_id",$leave_id); 
		$this->db->update($this->tableName);
	}
	public function approve_leave_manager()
	{
        $status                  =   $this->input->input_stream('status', TRUE);  		
        $comment                 =   $this->input->input_stream('comment', TRUE);  		
        $leave_id                 =   $this->input->input_stream('leave_id', TRUE);  		
        $this->db->set("leave_status",$status); 
        $this->db->set("approval_note",$comment); 
        $this->db->set("leave_action_by",$this->session->userdata('employee')); 
        $this->db->where("leave_id",$leave_id); 
		$this->db->update($this->tableName);
	}
	public function get_emp_leave_details($emp_id,$start,$end="")
	{
		$this->db->where("emp_id=".$emp_id);
		if($start && $end)
		{
			$this->db->where("leave_from >=", $start);
			$this->db->where("leave_from <=", $end);	
		}
		else
		{
			$this->db->where("leave_from =", $start);
		}
		$result                         =	$this->db->get($this->tableName)->result();
		return $result;      
    }
    public function get_leave_status($emp_id)
    {    	
        $status_by_sts				=	'';
        $status_by_date				=	'';
        $status 					=	'';
        $date_arr 					= 	array();
    	$this->db->select('leave_id');        
        $this->db->where('emp_id',$emp_id);        
        $result1 					= $this->db->get($this->tableName)->result();
        if($result1)
        {
        	foreach($result1 as $row)
	        {
	        	$this->db->select('leave_id,leave_status,leave_from,leave_to,leave_days_details');        
		        $this->db->where('leave_id',$row->leave_id);        
		        $result2 					= 	$this->db->get($this->tableName)->row();
		        $leave_days_details 		=	$result2->leave_days_details;
		        $leave_status 				=	$result2->leave_status;
		        $cond 						=	date('Y-m-d');

		        $date_arr 					=	explode(',', $leave_days_details);
		        if(count($date_arr))
		        {
		        	if (in_array($cond, $date_arr) && ($leave_status == '2' || $leave_status == '5'))
			        {
			        	$status_by_sts 		=	$leave_status;
			        	break;
			        }
			        else
					{
						$status_by_sts 		=	'';
					}				
		        }	        

	        }//end of foreach
	        if(!$status_by_sts)
	        {
	        	foreach($result1 as $row)
	        	{
		        	$this->db->select('leave_id,leave_status,leave_from,leave_to,leave_days_details');        
			        $this->db->where('leave_id',$row->leave_id);        
			        $result3 					= 	$this->db->get($this->tableName)->row();
			        $leave_days_details 		=	$result3->leave_days_details;
			        $leave_status 				=	$result3->leave_status;
			        $cond 						=	date('Y-m-d');

			        $date_arr 					=	explode(',', $leave_days_details);
			        if(count($date_arr))
			        {
			        	if (in_array($cond, $date_arr))
				        {
				        	$status_by_date 	=	$leave_status;
				        	break;
				        }
				        else
						{
							$status_by_date 	=	'';
						}				
			        }
			    }//end of foreach
			    $status 						=	$status_by_date;
	        }//end of if
	        else
	        {
	        	$status 						=	$status_by_sts;
	        }
	        return $status;	

        }//end of if
            
    }//end of function
    public function update_leave_hr()
    {
    	$leave_from        			 =   date('Y-m-d', strtotime($this->input->post('leave_from'))); 
        $leave_to        			 =   date('Y-m-d', strtotime($this->input->post('leave_to')));        
        $leave_days                  =   $this->input->input_stream('total_days', TRUE);          
        $leave_status                =   $this->input->input_stream('leave_comment', TRUE);          
        $leave_reason                =   $this->input->input_stream('leave_reason', TRUE);  
        $emp_id 		             =   $this->input->input_stream('uemp_id', TRUE); 

        $start    						= 	new DateTime($leave_from);
	    $end      						= 	(new DateTime($leave_to))->modify('+1 day');
	    $interval 						= 	new DateInterval('P1D');
	    $period   						= 	new DatePeriod($start, $interval, $end);   
	    foreach ($period as $dt) 
	    {
        	$leave_days_details			.=  	$dt->format("Y-m-d").',';          
    	}
    	$leave_days_details 			=	rtrim($leave_days_details,',');
        $insert                    	 =   $this->insert(array('leave_from'=>$leave_from,'leave_to'=>$leave_to,'leave_days_details'=>$leave_days_details,'leave_days'=>$leave_days,'leave_status'=>$leave_status,'leave_reason'=>$leave_reason,'emp_id'=>$emp_id));
        $this->session->set_flashdata('success_message', 'Reason Updated Successfully');
        redirect(base_url('hrm/user_absent_today'));

    }   
    public function update_leave_rm()
    {
    	$leave_from        			 =   date('Y-m-d', strtotime($this->input->post('leave_from'))); 
        $leave_to        			 =   date('Y-m-d', strtotime($this->input->post('leave_to')));        
        $leave_days                  =   $this->input->input_stream('total_days', TRUE);          
        $leave_status                =   $this->input->input_stream('leave_comment', TRUE);          
        $leave_reason                =   $this->input->input_stream('leave_reason', TRUE); 
        $emp_id 		             =   $this->input->input_stream('uemp_id', TRUE);  

        $start    						= 	new DateTime($leave_from);
	    $end      						= 	(new DateTime($leave_to))->modify('+1 day');
	    $interval 						= 	new DateInterval('P1D');
	    $period   						= 	new DatePeriod($start, $interval, $end);   
	    foreach ($period as $dt) 
	    {
        	$leave_days_details			.=  	$dt->format("Y-m-d").',';          
    	}
    	$leave_days_details 			=	rtrim($leave_days_details,',');
        $insert                    	 	=   $this->insert(array('leave_from'=>$leave_from,'leave_to'=>$leave_to,'leave_days_details'=>$leave_days_details,'leave_days'=>$leave_days,'leave_status'=>$leave_status,'leave_reason'=>$leave_reason,'emp_id'=>$emp_id));
        $this->session->set_flashdata('success_message', 'Reason Updated Successfully');
        redirect(base_url('hrm/user_absent_today_man'));

    }   
    
    
}
?>