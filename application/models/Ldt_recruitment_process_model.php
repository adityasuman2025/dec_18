<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ldt_recruitment_process_model extends CI_Model {
	function __construct() 
    {
		$this->tableName = 'ldt_recruitment_process';
        //$this->load->model('pagination_model');
	}
	public function getCount($cond)
	{
		$query	=	$this->db->where($cond)->get($this->tableName);
		$count= $query->result();
        //echo $this->db->last_query();
		return count($count);
	}
	public function get_data($id)
	{
		$this->db->where('rp_id ='.$id);
		$result =   $this->db->get($this->tableName)->row();
		return $result;
	}
	function insert($insArr)
	{
        $this->db->insert($this->tableName, $insArr);
        return $this->db->insert_id();
	}
    public function update($up_arr,$cond)
	{
        $this->db->set($up_arr); 
        $this->db->where($cond); 
		$this->db->update($this->tableName, $up_arr);
	}
    public function update_comment($Autonote,$status,$cond)
	{
        $this->db->set('rp_recruitment_note', "CONCAT(rp_recruitment_note,',','".$Autonote."')", FALSE); 
        $this->db->set('rp_interview_status', $status);
        $this->db->where($cond); 
		$this->db->update($this->tableName);
	}
    public function selected_candidate()
    {
        $Emp_name                       =   $this->input->input_stream('Emp_name', TRUE); 
		$Emp_email				        =	$this->input->input_stream('Emp_email', TRUE); 
        $Emp_mobile                     =	$this->input->input_stream('Emp_mobile', TRUE); 
		$Emp_interview_date				=	$this->input->input_stream('Emp_interview_date', TRUE); 
		$Emp_interview_date_to			=	$this->input->input_stream('Emp_interview_date_to', TRUE); 
		$Reference				        =	$this->input->input_stream('Reference', TRUE); 
		$emp_experienc			        =	$this->input->input_stream('emp_experienc', TRUE); 
		$emp_office			        	=	$this->input->input_stream('emp_office', TRUE); 
        $rp_application_id              =	$this->input->input_stream('rp_application_id', TRUE); 
        $Emp_interview_joining              =	$this->input->input_stream('Emp_interview_joining', TRUE); 
		$where 							=	'rp_id != 0 AND rp_interview_status = 10';
		if($Emp_name)
		{
			$where						=	$where." AND rp_emp_name LIKE '%".$Emp_name."%'";
		}
        if($emp_experienc)
		{
			$where						=	$where." AND rp_emp_experience =".$emp_experienc;
		}
		if($emp_office)
		{
			$where						=	$where." AND rp_office =".$emp_office;
		}
        if($rp_application_id)
		{
			$where						=	$where." AND rp_application_id = '".$rp_application_id."' ";
		}
		if($Emp_email)
		{
			$where						=	$where." AND rp_emp_email = '".$Emp_email."' ";
		}
        if($Reference)
		{
			$where						=	$where." AND rp_emp_referral = '".$Reference."' ";
		}
        if($Emp_mobile)
		{
			$where						=	$where." AND rp_emp_mobile = '".$Emp_mobile."' ";
		}
        if($Emp_interview_date          && $Emp_interview_date_to)
		{
			$where						=	$where." AND rp_interview_date between '".date("Y-m-d", strtotime($Emp_interview_date))."' AND '".date("Y-m-d", strtotime($Emp_interview_date_to))."' ";
		}
        elseif($Emp_interview_date)
		{
			$where						=	$where." AND rp_interview_date = '".date("Y-m-d", strtotime($Emp_interview_date))."' ";
		}
        elseif($Emp_interview_date_to)
		{
			$where						=	$where." AND rp_interview_date = '".date("Y-m-d", strtotime($Emp_interview_date_to))."' ";
		}        
		elseif($Emp_interview_joining)
		{
			$where						=	$where." AND rp_joining_date = '".date("Y-m-d", strtotime($Emp_interview_joining))."' ";
		}        
        if($this->session->userdata('office') != 1)
        {
            //$where                      =  $where. " AND rp_office =".$this->session->userdata('office');
        } 
        return $Result_data  					=   $this->pagination_model->get_pagination("ldt_recruitment_process",$where,"rp_id DESC",25);
    }
    public function recruitmentprocess()
    {
        $Emp_name                       =   $this->input->input_stream('Emp_name', TRUE); 
		$Emp_email				        =	$this->input->input_stream('Emp_email', TRUE); 
        $Emp_mobile                     =	$this->input->input_stream('Emp_mobile', TRUE); 
		$Emp_interview_date				=	$this->input->input_stream('Emp_interview_date', TRUE); 
		$Emp_interview_date_to			=	$this->input->input_stream('Emp_interview_date_to', TRUE); 
		$Reference				        =	$this->input->input_stream('Reference', TRUE); 
		$emp_office				        =	$this->input->input_stream('emp_office', TRUE); 
		$emp_experienc			        =	$this->input->input_stream('emp_experienc', TRUE); 
        $rp_application_id              =	$this->input->input_stream('rp_application_id', TRUE); 
        $Position                       =	$this->input->input_stream('Position', TRUE); 
        $interview_status_search        =	$this->input->input_stream('interview_status_search', TRUE); 
        $st_to_chk                      =	$this->input->input_stream('st_to_chk', TRUE); 
        $doj                            =	$this->input->input_stream('doj', TRUE); 
        $rec_ref                        =	$this->input->input_stream('rec_ref', TRUE); 
		$where 							=	'rp_id != 0';
		if($interview_status_search)
		{
			$where						=	$where." AND rp_interview_status =".$interview_status_search;
		}
        else if($Emp_name   || $Emp_email || $Emp_mobile || $Emp_interview_date || $Emp_interview_date_to || $doj || $Position || $rp_application_id || $emp_office)
        {
            $where						=	$where." AND rp_interview_status != 0 ";
        }
        else{
            $where						=	$where." AND rp_interview_status = 1 ";
        }
        ////////////// FROM HIRING REQUEST //////////////////////////////////////
        if($st_to_chk)
        {
            $where						=	$where." AND rp_interview_status IN (".$st_to_chk.")";
        }
        ////////////// FROM HIRING REQUEST //////////////////////////////////////
		if($Emp_name)
		{
			$where						=	$where." AND rp_emp_name LIKE '%".$Emp_name."%'";
		} 
        if($Position)
        {
            $where						=	$where." AND rp_emp_applaying_for =".$Position;
        }
        if($emp_experienc)
		{
			$where						=	$where." AND rp_emp_experience =".$emp_experienc;
		}
        if($rp_application_id)
		{
			$where						=	$where." AND rp_application_id = '".$rp_application_id."' ";
		}
		if($Emp_email)
		{
			$where						=	$where." AND rp_emp_email = '".$Emp_email."' ";
		}
        if($Reference)
		{
			$where						=	$where." AND rp_emp_referral = '".$Reference."' ";
		}
        if($rec_ref)
        {
            $where						=	$where." AND rp_emp_referral = 1 AND rp_emp_referral_detail = '".$rec_ref."' ";
        }
		if($emp_office)
		{
			$where						=	$where." AND rp_office  = '".$emp_office."' ";
		}
        if($Emp_mobile)
		{
			$where						=	$where." AND rp_emp_mobile = '".$Emp_mobile."' ";
		}
        if($Emp_interview_date          && $Emp_interview_date_to)
		{
			$where						=	$where." AND rp_interview_date between '".date("Y-m-d", strtotime($Emp_interview_date))."' AND '".date("Y-m-d", strtotime($Emp_interview_date_to))."' ";
		}
        elseif($Emp_interview_date)
		{
			$where						=	$where." AND rp_interview_date = '".date("Y-m-d", strtotime($Emp_interview_date))."' ";
		}
        elseif($Emp_interview_date_to)
		{
			$where						=	$where." AND rp_interview_date = '".date("Y-m-d", strtotime($Emp_interview_date_to))."' ";
		}
        elseif($doj)
		{
			$where						=	$where." AND rp_joining_date = '".date("Y-m-d", strtotime($doj))."' ";
		}
        if($where 						==	'rp_id != 0')
        {
            //$where						=	$where." AND rp_interview_date = '".date("Y-m-d")."' ";
            //$where						=	$where." AND rp_interview_status IN (1,5,6,7,8,13)";
        }	        
        if($this->session->userdata('office') != 1)
        {
            //$where                      =  $where. " AND rp_office =".$this->session->userdata('office');
        }    
        //echo $where;
        return $Result_data             =   $this->pagination_model->get_pagination("ldt_recruitment_process",$where,"rp_id DESC",25);
    }
    public function add_candidate_from_portel($applicationdetails)
    {
        //$applID                             =   $this->input->input_stream('applID', TRUE);
        //$status                             =   $this->input->input_stream('status', TRUE);
        $insertarray                        =   array();
        $insArray['rp_emp_name']            =	$applicationdetails->applName;
        $insArray['rp_emp_email']			=	$applicationdetails->applEmail;
        $insArray['rp_emp_mobile']          =	$applicationdetails->applMobile;
        $insArray['rp_emp_experience']		=	3;// not updated
        $insArray['rp_emp_experience_year']	=	"";
        $insArray['rp_emp_qulification']    =	16;//other / not updated
        $insArray['rp_interview_date']		=	date('Y-m-d', strtotime($this->input->post('rp_interview_date')));
        $insArray['rp_interview_time']      =	date('H:i:s', strtotime($this->input->post('rp_interview_time')));
        $insArray['rp_emp_referral']        =	7;//portal
        $insArray['rp_emp_referral_detail']	=	'job portal';
        $insArray['rp_emp_applaying_for']	=	$applicationdetails->opID;
        $insArray['rp_resume']              =	$applicationdetails->applResume;
        $insArray['rp_recruitment_note']    =	$this->input->input_stream('note', TRUE);//rp_recruitment_note
        $insertid							=	$this->insert($insArray);
        if($insertid)
        {
            // send interview scheduled mail
            $randStr						=	rand_string(4);
            $last_idzero					=	sprintf("%04s",$insertid);
            $upArray['rp_application_id']   =	$randStr.''.$last_idzero;
            $insertpage						=	$this->update($upArray,"rp_id=".$insertid);
            return $upArray['rp_application_id'];	
        }	
		$this->session->set_flashdata('success_message', 'Interview Scheduled Successfully');
    }
    public function add_candidate()
    {
		$insArray								=	array();
		$upArray								=	array();	
		$rp_id								    =	$this->input->post('rp_id');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        $chk_email              			    =	$this->input->post('rp_emp_email');
        $chk_mobile                             =	$this->input->post('rp_emp_mobile');
        $em_exists                              =   $this->check_rpemail_exists($chk_email);
        $mo_exists                              =   $this->check_rpmobile_exists($chk_mobile);
        $this->load->model('mdt_partner_model');
        $this->load->model('mdt_employees_model');
        //$emp_mo_exists                          =   $this->mdt_employees_model->check_emp_mobile_exists($chk_mobile);
        //$part_mo_exists                         =   $this->mdt_partner_model->check_partner_mobile_exists($chk_mobile);
        $rp_resume                              =   "";
        if($_FILES['rp_resume']['name'])
        {
            
            $file_element_name                  =   'rp_resume';
            $full_name                          =	$_FILES['rp_resume']['name'];
            $getext                             =	(explode('.', $full_name));
            $ext                                =	 end($getext);
            $custom_name                        =   "RECUME_".date('ymd_His').".".$ext;
            
			  $config['upload_path']            = $this->config->item('img_upload_url').'resume';
			  $config['allowed_types']          = 'gif|jpg|png|doc|docx|txt|xlsx|zip|pdf';
			  $config['max_size']               = 1024 * 8;
			  $config['encrypt_name']           = FALSE;
			  $config['file_name']              = $custom_name;
			  $this->load->library('upload', $config);
			  if ($this->upload->do_upload($file_element_name))
			  {
				$rp_resume                      =   $custom_name;
			  }
			 @unlink($_FILES[$file_element_name]);
		} 
        if($em_exists   && !$rp_id)
        {
            $this->session->set_flashdata('error_message', 'Email already exists');
            return 'exist';
        }
        else if($mo_exists   && !$rp_id)
        {
            $this->session->set_flashdata('error_message', 'Mobile already exists');
            return 'exist';
        }
        /*else if($emp_mo_exists)
        {
            $this->session->set_flashdata('error_message', 'Mobile already exists');
            return 'exist';
        }
        else if($part_mo_exists)
        {
            $this->session->set_flashdata('error_message', 'Mobile already exists');
            return 'exist';
        }
        */
        else if($rp_id)
		{
            $cur_mob                            =  $this->get_rp_emp_mobile($rp_id);
            $cur_em                             =  $this->get_rp_emp_email($rp_id);
            if(($cur_mob                       !=  $chk_mobile) && $mo_exists)
            {                
                $this->session->set_flashdata('error_message', 'Mobile already exists');
                return 'exist';
            }
            else if(($cur_em                   !=  $chk_email) && $em_exists)
            {                
                $this->session->set_flashdata('error_message', 'Mobile already exists');
                return 'exist';
            }
            else
            {
            $upArray['rp_emp_name']             =   ucwords($this->input->post('emp_name'));
            $upArray['rp_emp_email']			=	$this->input->post('rp_emp_email');
            $upArray['rp_emp_mobile']           =	$this->input->post('rp_emp_mobile');
            $upArray['rp_emp_experience']		=	$this->input->post('rp_emp_experience');
            $upArray['rp_emp_experience_year']	=	$this->input->post('rp_emp_experience_year');
            $upArray['rp_emp_qulification']     =	$this->input->post('rp_emp_qulification');
            $upArray['rp_interview_date']		=	date('Y-m-d', strtotime($this->input->post('rp_interview_date')));
            $upArray['rp_emp_referral']         =	$this->input->post('rp_emp_referral');
            if($rp_resume)
            {
            $upArray['rp_resume']               =	$rp_resume;
            }
            if($upArray['rp_emp_referral']      ==  1)
            {
                $upArray['rp_emp_referral_detail']	=	$this->input->post('ref_recruiter');
            }
            else
            {
                $upArray['rp_emp_referral_detail']	=	$this->input->post('rp_emp_referral_detail');
            } 
            $upArray['rp_consultancy']			=	$this->input->post('rp_consultancy');
            $upArray['rp_emp_applaying_for']	=	$this->input->post('rp_emp_applaying_for');
			$upArray['rp_office']    			=	$this->input->post('rp_comp_applaying');
            $upArray['rp_interview_time']       =	date('H:i:s', strtotime($this->input->post('rp_interview_time')));
			$insertpage							=	$this->update($upArray,"rp_id=".$rp_id);	
            //rp_recruitment_note
            // send interview re scheduled mail			
			$this->session->set_flashdata('success_message', 'Candidate Updated Successfully');
            return 'update';
            }
		}
		else
		{
            $insArray['rp_emp_name']            =   ucwords($this->input->post('emp_name'));
            $insArray['rp_emp_email']			=	$this->input->post('rp_emp_email');
            $insArray['rp_emp_mobile']          =	$this->input->post('rp_emp_mobile');
            $insArray['rp_emp_experience']		=	$this->input->post('rp_emp_experience');
            $insArray['rp_emp_experience_year']	=	$this->input->post('rp_emp_experience_year');
            $insArray['rp_emp_qulification']    =	$this->input->post('rp_emp_qulification');
            $insArray['rp_interview_date']		=	date('Y-m-d', strtotime($this->input->post('rp_interview_date')));
            $insArray['rp_emp_referral']        =	$this->input->post('rp_emp_referral');
            $insArray['rp_resume']              =	$rp_resume;
            $insArray['rp_recruitment_note']    =	date('Y-m-d').'-';
            $insArray['rp_interview_note']      =	'-';

	
			if($this->input->post('rp_emp_referral') == 1)
			//if($this->input->post('ref_recruiter'))
			{
			$insArray['rp_emp_referral_detail']	=	$this->input->post('ref_recruiter');
			}
			else
			{ 
			$insArray['rp_emp_referral_detail']	=	$this->input->post('rp_emp_referral_detail');	
			}
            //$insArray['rp_emp_referral_detail']	=	$this->input->post('rp_emp_referral_detail');
            $insArray['rp_consultancy']			=	$this->input->post('rp_consultancy');
            $insArray['rp_emp_applaying_for']	=	$this->input->post('rp_emp_applaying_for');
			$insArray['rp_office']    			=	$this->input->post('rp_comp_applaying');
            $insArray['rp_interview_time']      =	date('H:i:s', strtotime($this->input->post('rp_interview_time')));
			$insertid							=	$this->insert($insArray);
            if($insertid)
            {
                // send interview scheduled mail
                $randStr						=	rand_string(4);
                $last_idzero					=	sprintf("%04s",$insertid);
                $upArray['rp_application_id']   =	$randStr.''.$last_idzero;
                $insertpage						=	$this->update($upArray,"rp_id=".$insertid);	
            }	
			$this->session->set_flashdata('success_message', 'Candidate Added Successfully');
            return $upArray['rp_application_id'];
		}
       //redirect(base_url('hrm/recruitmentprocess'),'refresh');
    }
    public function change_recruitment_status()
    {
		$upArray								=	array();
        $interview_status_array                 =   interview_status();	
        $rounds_of_interviews_array             =   rounds_of_interviews();
		$rp_id								    =	$this->input->post('rp_id');
		$status								    =	$this->input->post('status');
		$note								    =	$this->input->post('note');
		$rp_emp_applaying_for					=	$this->input->post('rp_emp_applaying_for');
		$rp_interview_date						=	$this->input->post('rp_interview_date');
		$rp_interview_time						=	$this->input->post('rp_interview_time');
		$rp_salary_offered						=	$this->input->post('rp_salary_offered');
		$rp_designation_offered					=	$this->input->post('rp_designation_offered');
		$rp_joining_date						=	$this->input->post('rp_joining_date');
        $rp_joining_date                        =   date('Y-m-d',strtotime($rp_joining_date));
		$department								=	$this->input->post('department');
		$reporting_to							=	$this->input->post('reporting_to');
        $rp_interview_type                      =	$this->input->post('rp_interview_type');
		$timestamp								=	date('Y-m-d H:i:s');
		$emp_id									=	$this->session->userdata('userid');
        $emp_name                               =   get_user_name($emp_id);
        if($rp_id)
		{
            if($status                          ==    5   || $status                            ==    6) //schedule/ re schedule
            {
                $this->db->set('rp_interview_date', date('Y-m-d', strtotime($rp_interview_date)));
                $this->db->set('rp_interview_time', date('H:i:s', strtotime($rp_interview_time)));
                $Autonote                       =   '-- On '.$timestamp.' - '.$interview_status_array[$status].' to '.$rp_interview_date.'-'.$rp_interview_time.'  - '.$emp_name.' note : '.$note;
            }
            else if($status                     ==    7)// send to interviewer
            {
                $Autonote                       =   '-- On '.$timestamp.' - '.$interview_status_array[$status].'  - '.$rounds_of_interviews_array[$rp_interview_type].' - '.$emp_name.' note : '.$note;
                // insert to ldt_recruitment_interview
                $insrtitrvw                     =   array();
                $insrtitrvw['application_id']   =   $rp_id;
                $insrtitrvw['department_id']    =   $department;
                $insrtitrvw['interviewer_id']   =   $reporting_to;
                $insrtitrvw['position_id']      =   $rp_emp_applaying_for;
                $insrtitrvw['interview_type']   =   $rp_interview_type;
                $insrtitrvw['interview_date']   =   date('Y-m-d', strtotime($rp_interview_date));
                $insrtitrvw['interview_time']   =   date('H:i:s', strtotime($rp_interview_time));
                $this->load->model('ldt_recruitment_interview_model');
                $insertid						=	$this->ldt_recruitment_interview_model->insert($insrtitrvw);
                $this->db->set('rp_interview_type', $rp_interview_type);
            }
            else if($status                     ==    10)// selected
            {
                $this->db->set('rp_salary_offered', $rp_salary_offered);
                $this->db->set('rp_designation_offered', $rp_designation_offered);
                $this->db->set('rp_joining_date', $rp_joining_date);
                $Autonote                       =   '-- On '.$timestamp.' - '.$interview_status_array[$status].' - '.$emp_name.' note : '.$note;
            }
            else
            {
                $Autonote                       =   '-- On '.$timestamp.' - '.$interview_status_array[$status].' - '.$emp_name.' note : '.$note;
            }
            $this->db->set('rp_recruitment_note', "CONCAT(rp_recruitment_note,',','".$Autonote."')", FALSE); 
            $this->db->set('rp_interview_status', $status);
            $this->db->where("rp_id=".$rp_id); 	
            $this->db->update($this->tableName);            
            // send interview re scheduled mail			
            echo $this->db->last_query();
            $this->session->set_flashdata('success_message', 'Candidate Updated Successfully');
    	}
    }
	public function get_candidate_details($id)
	{
		$this->db->where("rp_application_id='".$id."'");
		$result =   $this->db->get($this->tableName)->row();
		return $result;
	}
	public function get_rp_details($id)
	{
		$this->db->where('rp_id ='.$id);
		$result =   $this->db->get($this->tableName)->result_array();
		return $result;
	}
	public function submit_candidate_form($url,$rp_id)
	{
        $this->db->set('rp_joining_form', $url); 
        $this->db->set('rp_registration_status', 2); 
        $this->db->where("rp_id=".$rp_id." and rp_registration_status=5"); 
		$this->db->update($this->tableName);
	}
	public function joining_forms_list()
    {
        $rp_application_id              =   $this->input->input_stream('rp_application_id', TRUE); 
		$where 							=	'rp_id != 0 and rp_registration_status=2';
		if($rp_application_id)
		{
			$where						=	$where." AND rp_application_id =".$rp_application_id;
		}
        return $Result_data  			=   $this->pagination_model->get_pagination("ldt_recruitment_process",$where,"rp_id ASC",25);
    }
	public function get_interview_status_count($status)
    {
        $sql                            =   'SELECT count(CASE WHEN rp_interview_status=10 THEN rp_id END) AS selected, count(CASE WHEN rp_interview_status IN(1,3,12) THEN rp_id END) AS on_hold, count(CASE WHEN rp_interview_status=14 THEN rp_id END) AS joined FROM '.$this->tableName.' WHERE rp_emp_applaying_for = '.$status;
        $result                         =   $this->db->query($sql)->row();
        if($result                     !=   null) 
        {
            return $result;
        }
        else
        {
            return '';
        }
    }
	public function approve_candidate()
	{
		$rp_id              =   $this->input->input_stream('rp_id', TRUE); 
		$this->db->set('rp_registration_status', 3);
		$this->db->where("rp_id=".$rp_id); 	
		$this->db->update($this->tableName); 
		redirect(base_url('hrm/selected_candidate'),'refresh');
	}
	public function employee_created()
	{
		$rp_id              =   $this->input->input_stream('rp_id', TRUE); 
		$this->db->set('rp_registration_status', 4);
		$this->db->set('rp_interview_status', 14);
		$this->db->set('rp_recruitment_status', 1);
		$this->db->where("rp_id=".$rp_id); 	
		$this->db->update($this->tableName); 
		redirect(base_url('hrm/selected_candidate'),'refresh');
	}
	public function update_send_mail()
	{
		$rp_id              =   $this->input->input_stream('rp_id', TRUE); 
		$this->db->set('rp_registration_status', 5);
		$this->db->where("rp_id=".$rp_id); 	
		$this->db->update($this->tableName); 
	}
	public function get_rp_emp_mobile($rp_id)
	{
		
		$this->db->select('rp_emp_mobile');
		$this->db->where("rp_id=".$rp_id); 	
        $result                 =   $this->db->get($this->tableName)->row();
        if($result             !=    null)
        { 
            if($result->rp_emp_mobile)
            {
                return $result->rp_emp_mobile;
            }
        }
        else
        {
            return '';
        }
	}
	public function get_rp_emp_email($rp_id)
	{
		
		$this->db->select('rp_emp_email');
		$this->db->where("rp_id=".$rp_id); 	
        $result                 =   $this->db->get($this->tableName)->row();
        if($result             !=    null)
        { 
            if($result->rp_emp_email)
            {
                return $result->rp_emp_email;
            }
        }
        else
        {
            return '';
        }
	}
    public function get_referal($mobile_phone)
    {
        $this->db->select('rp_emp_name,rp_emp_mobile,rp_emp_referral,rp_emp_referral_detail,rp_consultancy');
        $this->db->where('rp_emp_mobile',$mobile_phone);
        $result                 =   $this->db->get($this->tableName)->row();
        if($result)
        {
            return                      $result;
        }
        else
        {
            return  '';
        }  
    }
    public function check_rpemail_exists($email)
    {
        $this->db->select('rp_emp_email');
        $this->db->where('rp_emp_email',$email);
        $result                 =   $this->db->get($this->tableName)->row();
        if($result             !=    null)
        { 
            if($result->rp_emp_email)
            {
                return $result->rp_emp_email;
            }
        }
        else
        {
            return '';
        } 
    }
    public function check_rpmobile_exists($mobile)
    {
        $this->db->select('rp_emp_mobile');
        $this->db->where('rp_emp_mobile',$mobile);
        $result                 =   $this->db->get($this->tableName)->row();
        if($result             !=    null)
        { 
            if($result->rp_emp_mobile)
            {
                return $result->rp_emp_mobile;
            }
        }
        else
        {
            return '';
        } 
    }
    public function get_recruiters_list()
    {
        $this->db->distinct();
        $this->db->select('rp_emp_referral_detail');
        $this->db->where('rp_emp_referral',1);
        $result                 =   $this->db->get($this->tableName);        
        if($result             !=    null)
        { 
            return $result->result();
        }
        else
        {
            return '';
        } 
    }
}
?>