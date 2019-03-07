<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_emp_separation_model extends CI_Model {
	function __construct() {
		$this->tableName            = 'ldt_emp_separation'; 
		$this->primaryKey           = 'es_id';
	}
    public function get_eseparation_details($emp_id)
	{
		$this->db->where('emp_id',$emp_id);
		$this->db->order_by('es_added_on DESC');
		$result                     =   $this->db->get($this->tableName);
        if($result                 !=   null)
        {
            return $result->result_array(); 
        }
        else
        {
            return '';
        }        
	}
    public function get_count($cond)
    {
        $query	                    =	$this->db->where($cond)
					                        ->get($this->tableName);
		$count                      =	$query->result();
		return count($count);
    }
    public function e_separation()
    {
        $emp_id                     =   $this->session->userdata('employee');
        if($emp_id)
        {
            $data['sep_det']        =   $this->get_eseparation_details($emp_id);        
            $data['chk_all_revoked']=   $this->get_count('emp_id ='.$emp_id.' AND es_status IN (1,2,4)');
            $data['chk_not_applied']=   $this->get_count('emp_id ='.$emp_id);
        }
        else
        {
            $data['sep_det']        =   '';
            $data['chk_all_revoked']=   '';
            $data['chk_not_applied']=   '';
        }
        return $data;
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
    public function resignation_form_submit()
	{
        $es_exit_scheme             =    $this->input->input_stream('es_exit_scheme', TRUE);
        $es_lwd                     =    date('Y-m-d',strtotime($this->input->input_stream('es_lwd', TRUE)));
        $emp_id                     =    $this->session->userdata('employee');
        $es_type                    =    $this->input->input_stream('es_type', TRUE);
        $es_description             =    $this->input->input_stream('es_description', TRUE);
        $es_details                 =    implode(' , ',$this->input->input_stream('es_details', TRUE));
        $insert                     =    $this->insert(array('emp_id'=>$emp_id,'es_type'=>$es_type,'es_details'=>$es_details,'es_description'=>$es_description,'es_exit_scheme'=>$es_exit_scheme,'es_lwd'=>$es_lwd));
        //$mail_val					=	$mail_details->insertMailEntry($_POST['emp_id'],52);
        // send Notification      
	}
    public function revoke_esep()
	{
        $arr                        =   array('es_status'=>6,'es_last_updated_on'=>date('Y-m-d H:i:s'));
        $update                     =   $this->update($arr,'es_id ='.$this->input->input_stream('es_id', TRUE));
        // $mail_val				=	 $mail_details->insertMailEntry($_POST['emp_id'],53);
        // send Notification      $this->session->userdata('employee'); 
	}
    public function e_separation_list_hr()
	{
        $sel_es_status              =   $this->input->input_stream('sel_es_status', TRUE);  
        $selEmpId                   =   $this->input->input_stream('selEmpId', TRUE);  
        //$sel_es_status              =   ($sel_es_status)?($sel_es_status):'2';
        $cond                       =   "emp_id != 0";
        if($sel_es_status)
		{
			$cond					=	$cond." AND es_status =".$sel_es_status;
		}
        if($selEmpId)
		{
			$cond					=	$cond." AND emp_id =".$selEmpId;
		}
        $get_pageList  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'es_added_on DESC',25);	
        $data['page_details'] 		= 	$get_pageList;        
        $result_array				=	$data['page_details']['results'];
        $data['page_details']['results']=   array();
        if($result_array            !=  null)
        {
            $this->load->model('mdt_employees_model');
            $this->load->model('sdt_designations_model');
            $this->load->model('ldt_exit_form_answers_model');
            foreach($result_array   as  $resval)
            {     
                $reporting_to       =   '';
                $designation        =   '';
                $emp_details        =   $this->mdt_employees_model->get_employee_req_det($resval->emp_id,'reporting_to,
designation,employee_name,joined_date');
                if($emp_details->reporting_to)
                {
                    $reporting_to   =   $this->mdt_employees_model->get_emp_name($emp_details->reporting_to);
                }  
                if($emp_details->designation)
                {
                    $designation    =   $this->sdt_designations_model->get_designation_name($emp_details->designation);
                }    
                $ans_cnt            =   $this->ldt_exit_form_answers_model->get_count('emp_id='.$resval->emp_id);
                $temp               =   array("es_id"=>$resval->es_id,"emp_id"=>$resval->emp_id,"es_type"=>$resval->es_type,"es_details" =>$resval->es_details,"es_description"=>$resval->es_description,"es_added_on"=>$resval->es_added_on,"es_status"=>$resval->es_status,"es_manager_cmt"=>$resval->es_manager_cmt,"es_man_cmt_on"=>$resval->es_man_cmt_on,"es_hr_cmt"=>$resval->es_hr_cmt,"es_hr_cmt_on"=>$resval->es_hr_cmt_on,"es_exit_scheme"=>$resval->es_exit_scheme,"es_lwd"=>$resval->es_lwd,"emp_name"=>$emp_details->employee_name,"doj"=>$emp_details->joined_date,"reporting_to"=>$reporting_to,"designation"=>$designation,"ans_cnt"=>$ans_cnt);
                $data['page_details']['results'] = (array) $data['page_details']['results'];
                array_push($data['page_details']['results'], $temp);
            }		  
        }
		$data['sel_es_status']      = 	$sel_es_status;	
		$data['selEmpId']           = 	$selEmpId;	
        return $data;        
	}
    public function e_separation_list_rep()
	{
        $sel_es_status              =   $this->input->input_stream('sel_es_status', TRUE);  
        $selEmpId                   =   $this->input->input_stream('selEmpId', TRUE);  
        //$sel_es_status              =   ($sel_es_status)?($sel_es_status):'1';
        $cond                       =   "emp_id != 0";
        if($sel_es_status)
		{
			$cond					=	$cond." AND es_status =".$sel_es_status;
		}
        if($selEmpId)
		{
			$cond					=	$cond." AND emp_id =".$selEmpId;
		}
        $this->load->model('mdt_employees_model');
        $reportees                  =   $this->mdt_employees_model->get_employees_reporting_me($this->session->userdata('employee'));
        $rep_list                   =   implode(',',$reportees);
        $rep_list                   =   ($rep_list)?($rep_list):0;
        $cond					    =	$cond." AND emp_id IN (".$rep_list.")";       
        $get_pageList  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'es_added_on DESC',25);	
        $data['page_details'] 		= 	$get_pageList;        
        $result_array				=	$data['page_details']['results'];
        $data['page_details']['results']=   array();
        if($result_array            !=  null)
        {            
            $this->load->model('sdt_designations_model');
            foreach($result_array   as  $resval)
            {     
                $reporting_to       =   '';
                $designation        =   '';
                $emp_details        =   $this->mdt_employees_model->get_employee_req_det($resval->emp_id,'reporting_to,
designation,employee_name,joined_date');
                if($emp_details->reporting_to)
                {
                    $reporting_to   =   $this->mdt_employees_model->get_emp_name($emp_details->reporting_to);
                }  
                if($emp_details->designation)
                {
                    $designation    =   $this->sdt_designations_model->get_designation_name($emp_details->designation);
                }    
                $temp               =   array("es_id"=>$resval->es_id,"emp_id"=>$resval->emp_id,"es_type"=>$resval->es_type,"es_details" =>$resval->es_details,"es_description"=>$resval->es_description,"es_added_on"=>$resval->es_added_on,"es_status"=>$resval->es_status,"es_manager_cmt"=>$resval->es_manager_cmt,"es_man_cmt_on"=>$resval->es_man_cmt_on,"es_hr_cmt"=>$resval->es_hr_cmt,"es_hr_cmt_on"=>$resval->es_hr_cmt_on,"es_exit_scheme"=>$resval->es_exit_scheme,"es_lwd"=>$resval->es_lwd,"emp_name"=>$emp_details->employee_name,"doj"=>$emp_details->joined_date,"reporting_to"=>$reporting_to,"designation"=>$designation);
                $data['page_details']['results'] = (array) $data['page_details']['results'];
                array_push($data['page_details']['results'], $temp);
            }		  
        }
		$data['sel_es_status']      = 	$sel_es_status;	
		$data['selEmpId']           = 	$selEmpId;	
        return $data;        
	}
    public function approve_reject_res()
    {        
        $subtype                    =   $this->input->input_stream('subtype', TRUE);  
        $type                       =   $this->input->input_stream('type', TRUE);  
        $frp                        =   $this->input->input_stream('frp', TRUE);  
        $es_id                      =   $this->input->input_stream('es_id', TRUE);  
        $today                      =   date('Y-m-d H:i:s');
        if($subtype                 ==  'Accept')
        {            
            if($type                ==  'Man')
            {
                $es_status          =   2;
                $es_man_cmt_by      =   $this->session->userdata('employee');
                $es_man_cmt_on      =   $today;                     
                $upd_arr            =   array('es_status'=>$es_status,'es_man_cmt_by'=>$es_man_cmt_by,'es_man_cmt_on'=>$es_man_cmt_on); 
            }
            else
            {
                $es_status          =   4;
                $es_hr_cmt_by       =   $this->session->userdata('employee');
                $es_hr_cmt_on       =   $today; 
                $es_lwd             =   $this->input->input_stream('es_lwd', TRUE);  
                //$mt_special         =   $_POST['dt'].','.$_POST['es_lwd'];
                //$mail_val			=	$mail_details->insertMailEntry($_POST['emp_id'],55,$mt_special);                
                $upd_arr            =   array('es_status'=>$es_status,'es_hr_cmt_by'=>$es_hr_cmt_by,'es_hr_cmt_on'=>$es_hr_cmt_on,'es_lwd'=>$es_lwd);
            }  
            $update                 =   $this->update($upd_arr,'es_id ='.$es_id);
            if($frp                 ==  'esman')
            {
                redirect(base_url('hrm/e_separation_list_rep'));
            }
            else if($frp            ==  'eshr')
            {
                redirect(base_url('hrm/e_separation_list_hr'));
            }
            else 
            {
                //header('location:'.PATH_SITE.'hrm/viewEmployee.php?id='.$_POST['emp_id'].'#Separation');
            }
        }
        else if($subtype            ==  'Reject')
        {
            if($type                ==  'Man')
            {
                $es_man_cmt_on      =   $today;
                $es_status          =   3;
                $es_man_cmt_by      =   $this->session->userdata('employee');
                $upd_arr            =   array('es_status'=>$es_status,'es_man_cmt_by'=>$es_man_cmt_by,'es_man_cmt_on'=>$es_man_cmt_on); 
            }
            else
            {
                $es_hr_cmt_on       =   $today;
                $es_status          =   5;
                $es_hr_cmt_by       =   $this->session->userdata('employee');
                $upd_arr            =   array('es_status'=>$es_status,'es_hr_cmt_by'=>$es_hr_cmt_by,'es_hr_cmt_on'=>$es_hr_cmt_on);
            }
            $update                 =   $this->update($upd_arr,'es_id ='.$es_id);
            if($frp                 ==  'esman')
            {
                redirect(base_url('hrm/e_separation_list_rep'));
            }
            else if($frp            ==  'eshr')
            {
                redirect(base_url('hrm/e_separation_list_hr'));
            }
            else 
            {
                //header('location:'.PATH_SITE.'hrm/viewEmployee.php?id='.$_POST['emp_id'].'#Separation');
            } 
        }        
    }
    public function exit_form()
    {
        $emp_id                     =   $this->uri->segment(3);
        $type                       =   $this->input->input_stream('type', TRUE);  
        if($type                    ==  null    && $this->session->flashdata())
        {
            $type                   =   $this->session->flashdata('type');
        }
        if(($type ==  'mpf'   && $emp_id  ==  $this->session->userdata('employee')) || ($type ==  'hr'))
        {
            $this->load->model('sdt_exit_form_questions_model');
            $data['form_details']   =   $this->sdt_exit_form_questions_model->get_exit_form_questions();
            $this->load->model('ldt_exit_form_answers_model');
            $data['ans_details']    =   $this->ldt_exit_form_answers_model->get_exit_form_answers_ques($emp_id);
            $this->load->model('mdt_employees_model');            
            $emp_details            =   $this->mdt_employees_model->get_employee_req_det($emp_id,'reporting_to,
designation,employee_name,joined_date');
            $data['reporting_to']   =   '';
            $data['designation']    =   '';
            if($emp_details->reporting_to)
            {
                $data['reporting_to']=   $this->mdt_employees_model->get_emp_name($emp_details->reporting_to);
            }  
            if($emp_details->designation)
            {
                $this->load->model('sdt_designations_model'); 
                $data['designation']=   $this->sdt_designations_model->get_designation_name($emp_details->designation);
            }   
            $data['emp_name']       =   $emp_details->employee_name;
            $data['doj']            =   $emp_details->joined_date;
            $data['lwd']            =   $this->get_last_working_day($emp_id);    
            $data['fb_details']     =   $this->ldt_exit_form_answers_model->get_exit_form_answers_feedback($emp_id);
            $data['fb_ques']        =   $this->sdt_exit_form_questions_model->get_exit_form_feed_back_questions();
            $data['type']           =   $type; 
            return $data;
        }
        else
        {
            return 'no_access';
        }
    }
    public function get_last_working_day($emp_id)
    {
        $this->db->select('es_lwd',$emp_id);
        $this->db->where('emp_id',$emp_id);
        $this->db->where('es_status','4');
		$result                     =   $this->db->get($this->tableName)->row();
        if($result                 !=   null)
        {
            return $result->es_lwd; 
        }
        else
        {
            return '';
        }        
    }
    public function noc_deactivation()
    {   
        $sql                        =   'SELECT es_id, t1.emp_id as emp_id, es_type, es_exit_scheme, es_lwd, es_status, es_noc,t2.efa_added_on,es_added_on FROM ldt_emp_separation t1 JOIN (SELECT emp_id,efa_added_on FROM ldt_exit_form_answers WHERE efa_type=2) t2 on t1.emp_id = t2.emp_id and es_status=4';
        $selEmpId                   =   $this->input->input_stream('selEmpId', TRUE); 
        if($selEmpId)
		{
			$sql					=	$sql." AND t1.emp_id =".$selEmpId;
		}
        $sql                        =   $sql.' ORDER BY t2.efa_added_on DESC';  
        $get_pageList  				=   $this->pagination_model->get_pagination_sql($sql,25);	
        $data['page_details'] 		= 	$get_pageList;        
        $result_array				=	$data['page_details']['results'];
        $data['page_details']['results']=   array();
        if($result_array            !=  null)
        {                                
            $this->load->model('mdt_employees_model');
            $this->load->model('mdt_users_model'); 
            foreach($result_array   as  $resval)
            {    
                $user_status        =   '';
                $user_status        =   $this->mdt_users_model->get_user_status_by_emp_id($resval->emp_id);
                $emp_details        =   $this->mdt_employees_model->get_employee_req_det($resval->emp_id,'employee_name');
                $temp               =   array("es_id"=>$resval->es_id,"emp_id"=>$resval->emp_id,"es_type"=>$resval->es_type,"es_added_on"=>$resval->es_added_on,"es_status"=>$resval->es_status,"es_exit_scheme"=>$resval->es_exit_scheme,"es_lwd"=>$resval->es_lwd,"emp_name"=>$emp_details->employee_name,"es_noc"=>$resval->es_noc,"user_status"=>$user_status);
                $data['page_details']['results'] = (array) $data['page_details']['results'];
                array_push($data['page_details']['results'], $temp);
            }		  
        }
		$data['selEmpId']           = 	$selEmpId;	
        return $data;  
    }
    public function noc_updation()
    {        
        $es_id                      =   $this->input->input_stream('es_id', TRUE);  
        $es_noc                     =   $this->input->input_stream('es_noc', TRUE);  
        $update                     =   $this->update(array('es_noc'=>$es_noc),'es_id='.$es_id);
        return $update;
    }
    public function deactivate_emp()
    {        
        $es_id                      =   $this->input->input_stream('es_id', TRUE); 
        $emp_id                     =   $this->get_emp_id_by_esep($es_id);
        if($emp_id)
        {
            $this->load->model('mdt_users_model');            
            $this->load->model('mdt_employees_model');            
            $user_id                =   $this->mdt_users_model->get_userid_by_empid($emp_id);            
            $upd_user               =   $this->mdt_users_model->update_user_details($user_id,array('user_status'=>2));
            $upd_emp                =   $this->mdt_employees_model->update(array('employee_status'=>2),'emp_id='.$emp_id);
        }
        return $upd_emp;
    }    
    public function get_emp_id_by_esep($es_id)
	{
		$this->db->select('emp_id');
		$this->db->where('es_id',$es_id);
		$result                     =   $this->db->get($this->tableName)->row();
        if($result                 !=   null)
        {
            return $result->emp_id; 
        }
        else
        {
            return '';
        }        
	}
    public function update_manager_cmt($es_id)
	{
        $es_id                      =   $this->input->input_stream('es_id', TRUE);  
        $es_manager_cmt             =   $this->input->input_stream('es_manager_cmt', TRUE);  
        $update                     =   $this->update(array('es_manager_cmt'=>$es_manager_cmt,'es_man_cmt_on'=>date('Y-m-d H:i:s'),'es_man_cmt_by'=>$this->session->userdata('employee')),'es_id='.$es_id);
        return $update;      
	}
	public function get_all_eseparation_details()
	{
		$key                            =   $this->input->input_stream('key', TRUE);
		$es_lwd_From_date				=	$this->input->input_stream('es_lwd_From_date', TRUE); 
		$es_lwd_To_date			    	=	$this->input->input_stream('es_lwd_To_date', TRUE); 
		$full_final_date_from			=	$this->input->input_stream('full_final_date_from', TRUE); 
		$full_final_date_to			    =	$this->input->input_stream('full_final_date_to', TRUE); 
		$department			            =	$this->input->input_stream('department', TRUE); 
		$office			                =	$this->input->input_stream('office', TRUE); 
		$reporting_to			        =	$this->input->input_stream('reporting_to', TRUE); 
		$designation			        =	$this->input->input_stream('designation', TRUE);        
		$Reference				        =	$this->input->input_stream('Source', TRUE); 
		$employee_status				=	$this->input->input_stream('employee_status', TRUE); 
        
		$where 							=	' where mde.employee_status !=1';
		if(is_numeric($key))
		{
		$where                          =  $where. " AND emp_id =".$key;
		}
		elseif($key)
		{	
		$where	                        =   $where. " AND employee_name LIKE '%".$key."%'";
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
		if($Reference)
		{
			$where						=	$where." AND rp_emp_referral = '".$Reference."' ";
		}
		if($employee_status)
		{
			$where						=	$where." AND employee_status = '".$employee_status."' ";
		}
		if($reporting_to)
		{
			$where						=	$where." AND reporting_to  =".$reporting_to;
		}
		if($full_final_date_from && $full_final_date_to)
		{
		  $where						.=	" and mde.full_final_date between '".date("Y-m-d", strtotime($full_final_date_from))."' AND '".date("Y-m-d", strtotime($full_final_date_to))."' ";
		}
        elseif($full_final_date_from)
		{
		  $where						.= " and mde.full_final_date = '".date("Y-m-d", strtotime($full_final_date_from))."' ";
		}
        elseif($full_final_date_to)
		{
		  $where						.= " and mde.full_final_date = '".date("Y-m-d", strtotime($full_final_date_to))."'";
		}
		if($es_lwd_From_date && $es_lwd_To_date)
		{
		  $where						.=	" and mde.exit_date between '".date("Y-m-d", strtotime($es_lwd_From_date))."' AND '".date("Y-m-d", strtotime($es_lwd_To_date))."' ";
		}
        elseif($es_lwd_From_date)
		{
		  $where						.= " and mde.exit_date = '".date("Y-m-d", strtotime($es_lwd_From_date))."' ";
		}
        elseif($es_lwd_To_date)
		{
		  $where						.= " and mde.exit_date = '".date("Y-m-d", strtotime($es_lwd_To_date))."'";
		}
		$where							.= " order by exit_date desc";
		$sql                        	=   'SELECT employee_name,mde.emp_id,joined_date,designation,department,reporting_to,employee_status,notes,exit_date,full_final_date,rp_emp_referral FROM  mdt_employees mde left join ldt_recruitment_process lrp ON mde.mobile_phone = lrp.rp_emp_mobile'. $where;
		$eseparation_details            =   $this->pagination_model->get_pagination_sql($sql,25);

		//echo $sql;
		//$sql                        	=   'SELECT es_id,employee_name,le.emp_id,joined_date,designation,department,reporting_to,es_status,es_description,es_lwd,es_added_on FROM ldt_emp_separation le JOIN mdt_employees mde ON le.emp_id= mde.emp_id'. $where;
		//$sql                        	=   'SELECT es_id,employee_name,le.emp_id,joined_date,designation,department,reporting_to,employee_status,notes,exit_date,full_final_date,es_added_on FROM ldt_emp_separation le JOIN mdt_employees mde ON le.emp_id= mde.emp_id'. $where;
        
		//$sql                        	=   'SELECT es_id,employee_name,le.emp_id,joined_date,designation,department,reporting_to,es_status,es_description,es_lwd,es_added_on,rp_emp_referral FROM ldt_emp_separation le JOIN mdt_employees mde ON le.emp_id= mde.emp_id left join ldt_recruitment_process lrp ON mde.mobile_phone = lrp.rp_emp_mobile'. $where;
        //echo $sql; 
		//$eseparation_details  				=   $this->pagination_model->get_pagination($this->tableName,$cond,'es_added_on DESC',25);	
        return $eseparation_details; 
	}
	
	public function downloadAttritionDetails($key,$reporting_to,$department,$office,$es_lwd_From_date,$es_lwd_To_date,$designation,$Reference,$full_final_date_from,$full_final_date_to,$employee_status)
	{ 
		$where 							=	' where employee_status !=1';
		if(is_numeric($key))
		{
		$where                          =  $where. " AND emp_id =".$key;
		}
		elseif($key)
		{	
		$where	                        =   $where. " AND employee_name LIKE '%".$key."%'";
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
		if($Reference)
		{
			$where						=	$where." AND rp_emp_referral = '".$Reference."' ";
		}
		if($employee_status)
		{
			$where						=	$where." AND employee_status = '".$employee_status."' ";
		}
		if($full_final_date_from && $full_final_date_to)
		{
		  $where						.=	" and mde.full_final_date between '".date("Y-m-d", strtotime($full_final_date_from))."' AND '".date("Y-m-d", strtotime($full_final_date_to))."' ";
		}
        elseif($full_final_date_from)
		{
		  $where						.= " and mde.full_final_date = '".date("Y-m-d", strtotime($full_final_date_from))."' ";
		}
        elseif($full_final_date_to)
		{
		  $where						.= " and mde.full_final_date = '".date("Y-m-d", strtotime($full_final_date_to))."'";
		}
		if($es_lwd_From_date && $es_lwd_To_date)
		{
		  $where						.=	" and mde.exit_date between '".date("Y-m-d", strtotime($es_lwd_From_date))."' AND '".date("Y-m-d", strtotime($es_lwd_To_date))."' ";
		}
        elseif($es_lwd_From_date)
		{
		  $where						.= " and mde.exit_date = '".date("Y-m-d", strtotime($es_lwd_From_date))."' ";
		}
        elseif($es_lwd_To_date)
		{
		  $where						.= " and mde.exit_date = '".date("Y-m-d", strtotime($es_lwd_To_date))."'";
		}
		$where							.= " order by exit_date desc";
		$sql                        	=   'SELECT employee_name,mde.emp_id,joined_date,designation,department,reporting_to,employee_status,notes,exit_date,full_final_date,rp_emp_referral FROM  mdt_employees mde left join ldt_recruitment_process lrp ON mde.mobile_phone = lrp.rp_emp_mobile'. $where;
		//$sql                        	=   'SELECT es_id,employee_name,le.emp_id,joined_date,designation,department,reporting_to,employee_status,notes,exit_date,es_added_on,full_final_date FROM ldt_emp_separation le JOIN mdt_employees mde ON le.emp_id= mde.emp_id'. $where;
		//echo $sql;
		$query	    			 =   $this->db->query($sql);
		$res					 =	$query->result();
		return $res;
	}
}