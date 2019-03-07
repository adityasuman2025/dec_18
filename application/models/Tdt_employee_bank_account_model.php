<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tdt_employee_bank_account_model extends CI_Model {
	function __construct() {
		$this->tableName = 'tdt_employee_bank_account';
		$this->primaryKey = 'acc_id';
	}
    public function get_emp_bank_details($emp_id)
	{
		$this->db->where('emp_id='.$emp_id.' and acc_status = 1');
		$result = $this->db->get($this->tableName)->row();
		return $result;
	}  
    public function get_count($cond)
	{
		$this->db->where($cond);
		$result                         =   $this->db->get($this->tableName);
        $retval                         =   $result->num_rows();       
		return $retval;  
	}
	public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
	public function insert($insArr)
	{
		$this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    public function get_emp_bank_acc_history($emp_id)
	{
		$this->db->where('emp_id',$emp_id);
		$this->db->order_by('added_on','DESC');
		$result                         =   $this->db->get($this->tableName)->result();
		return $result;
	}
	
	public function upload_bank_details()
	{
		$added_on						=	date('Y-m-d H:i:s');
		if ($_FILES['csv']['size']		 	> 	0) 
		{
			$file		 				= 	$_FILES['csv']['tmp_name'];
			$handle	 					= 	fopen($file,"r");
			$insCnt						=	0;
			while ($data 				= 	fgetcsv($handle,1000,",","'"))
			{
				if ($data[1]) 
				{
                    $sln                =	$data[0];
                    $emp_id             =	$data[1];
                    $acc_beneficiary_name=	$data[2];
                    $acc_bank_name      =	$data[3];
                    $acc_bank_branch    =	$data[4];
                    $acc_bank_account_number =	$data[5];
                    $pf_account_number  =	$data[6];   
                    $ifsc               =	$data[7];  
					$this->db->where('emp_id ='.$data[1].' AND acc_status=1');
					$acc_det = $this->db->get($this->tableName)->row();		
                    if($acc_det)
                    {
						$set=array();
                        $set['acc_edited_on']            =   date('Y-m-d H:i:s');
						$set['acc_edited_by']			 =	 $_SESSION['AppsLogID'];
                        if($data[2]) // $acc_beneficiary_name
                        {
                            $set['acc_beneficiary_name']	=	$data[2];
                        }
                        if($data[3]) // $acc_bank_name
                        {
							$set['acc_bank_name']			=	$data[3];
                        }
                        if($data[4]) // $acc_bank_branch
                        {
							$set['acc_bank_branch']			=	$data[4];
                        }
                        if($data[5]) // $acc_bank_account_number
                        {
							$set['acc_bank_account_number']	=	$data[5];
                        }
                        if($data[6]) // $pf_account_number
                        {
							$set['pf_account_number']		=	$data[6];
                        }
                        if($data[7]) // ifsc
                        {
							$set['ifsc_code']				=	$data[7];
                        }
                        $this->update($set,"acc_id=".$acc_det->acc_id); 
                        #####################INSERT LOG hrm_account_log################################
                        $log_ins['acc_id']		            =	$acc_det->acc_id;
                        $log_ins['emp_id']		            =	$acc_det->emp_id;
                        $log_ins['acc_beneficiary_name']    =	$acc_det->acc_beneficiary_name;
                        $log_ins['acc_bank_name']		    =	$acc_det->acc_bank_name;
                        $log_ins['acc_bank_name']		    =	$acc_det->acc_bank_name;
                        $log_ins['acc_bank_account_number']	=	$acc_det->acc_bank_account_number;
                        $log_ins['pf_account_number']		=	$acc_det->pf_account_number;
                        $log_ins['ifsc_code']		        =	$acc_det->ifsc_code;
                        $log_ins['acc_added_on']		    =	date('Y-m-d H:i:s');                       
                        $log_ins['acc_added_by']            =	$_SESSION['AppsLogID'];
						$this->load->model('tdt_employee_bank_account_log_model');
						$update_lang_details	=	 $this->tdt_employee_bank_account_log_model->insert($log_ins);  
                    }
                    else
                    {   
                        $INS['emp_id']		            =	$data[1];
                        $INS['acc_beneficiary_name']    =	$data[2];
                        $INS['acc_bank_name']		    =	$data[3];
                        $INS['acc_bank_branch']		    =	$data[4];
                        $INS['acc_bank_account_number']	=	$data[5];
                        $INS['pf_account_number']		=	$data[6];
                        $INS['ifsc_code']		        =	$data[7];
                        $INS['added_on']		    	=	date('Y-m-d H:i:s');                       
                        //$INS['acc_added_by']            =	$_SESSION['AppsLogID'];
                        $INS['acc_status']              =	1;
                        $ins                            =   $this->insert($INS);
                    }                    
					$insCnt				=	$insCnt + 1;
				}
			}
		}
	}
}