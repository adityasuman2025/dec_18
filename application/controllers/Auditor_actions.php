<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auditor_actions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->module   =   'auditor_actions';
        
        $this->load->model('pagination_model');
        $this->load->model('Mdt_p_tracksheet');

        $this->load->model('Ldt_notify_reviewer_about_audit_report_model');
        
        $this->load->model('Ldt_questionnaire_ans_model');

        $this->load->model('Ldt_audit_report_comments_model');
        $this->load->model('Ldt_audit_report_nc_model');
        $this->load->model('Ldt_audit_report_nc_comments_model');

        $this->load->model('Ldt_audit_report_summary_model');

        $this->load->model('Ldt_scope_of_cert_model');
    }

//function to update answers in to the database according to their ans_id
	public function update_ans_in_db()
	{
		$records            =   $this->input->input_stream('records', TRUE);

		$result = $this->Ldt_questionnaire_ans_model->update_ans_in_db($records);
		echo $result;
	}	

//function to insert new answers in the database
	public function ins_ans_in_db()
	{
		$tracksheet_id          =   $this->input->input_stream('tracksheet_id', TRUE);
		$page_id           	 	=   $this->input->input_stream('page_id', TRUE);
		$qstn_id            	=   $this->input->input_stream('qstn_id', TRUE);

		$records            	=   $this->input->input_stream('records', TRUE);

		$result = $this->Ldt_questionnaire_ans_model->ins_ans_in_db($tracksheet_id, $page_id, $qstn_id, $records);
		echo $result;
	}	

//function to upload attendence sheet
	public function upload_attendence_sheet()
	{
		$tracksheet_id = $this->uri->segment(3);
		$level = $this->uri->segment(4);

		if($_FILES["file"]["name"] != '')
		{
		//for getting extension of uploaded file
			$test = explode(".", $_FILES["file"]["name"]);
			$image_extension = end($test);

		//setting new name to the pic
			$image_name_new = "pic_" . $tracksheet_id . "_" . $level . "." . $image_extension;

		//uploading the pic at temp location
			$image_location = 'uploads/attendence_sheet/' . $image_name_new;

			if(move_uploaded_file($_FILES["file"]["tmp_name"], $image_location))
			{
				echo "<img src=\"" . base_url($image_location) . "\" /><br>";
			}
			else
			{
				echo "Something went wrong";
			}
		}
	}	

//function to notify planning team about a submitted audit on-site report
	public function notify_planning_about_audit_report()
	{
		$tracksheet_id = $this->uri->segment(3);
		$level = $this->uri->segment(4);
		$redirect_level = $this->uri->segment(5);
		
        $result = $this->Mdt_p_tracksheet->incr_flow_status_of_tracksheet($tracksheet_id);
        
        if($level == 1)
        	redirect(base_url('auditor/audit_on_site1'), 'refresh');
        else if($level == 2)
        {
        	if($redirect_level == 2)
        		redirect(base_url('auditor/audit_on_site_re_cert'), 'refresh');
        	else	
        		redirect(base_url('auditor/audit_on_site2'), 'refresh');
        }
        else if($level == 3 || $level == 4)
        	redirect(base_url('auditor/audit_on_site_surv'), 'refresh');
	}

//function to add audit report comment in databse
	public function add_audit_report_comments_in_db()
	{
		$userid = $_SESSION['userid'];

		$data['tracksheet_id'] = $this->input->input_stream('tracksheet_id', TRUE);
		$data['level'] = $this->input->input_stream('level', TRUE);
		$data['qstn_id'] = $this->input->input_stream('qstn_id', TRUE);
		$data['comment'] = $this->input->input_stream('comment', TRUE);
		$data['commented_by'] = $userid;
		$data['comment_type'] = $this->input->input_stream('comment_type', TRUE);
		$data['status'] = 1;

		$result = $this->Ldt_audit_report_comments_model->add_audit_report_comments_in_db($data);

		return $result;
	}

//function to add a new nc in the database
	public function add_audit_report_nc_in_db()
	{
		$records          =   $this->input->input_stream('records', TRUE);

		$result = $this->Ldt_audit_report_nc_model->add_audit_report_nc_in_db($records);
		echo $result;
	}

//function to update nc_statement of a nc_id
	public function update_nc_statement_in_db()
	{
		$nc_id          =   $this->input->input_stream('nc_id', TRUE);
		$nc_statement          =   $this->input->input_stream('nc_statement', TRUE);

		$result = $this->Ldt_audit_report_nc_model->update_nc_statement_in_db($nc_id, $nc_statement);
		echo $result;
	}	

//function to add audit report nc comment in databse
	public function add_audit_report_nc_comments_in_db()
	{
		$userid = $_SESSION['userid'];

		$data['tracksheet_id'] = $this->input->input_stream('tracksheet_id', TRUE);
		$data['level'] = $this->input->input_stream('level', TRUE);
		$data['nc_id'] = $this->input->input_stream('nc_id', TRUE);
		$data['comment'] = $this->input->input_stream('comment', TRUE);
		$data['commented_by'] = $userid;
		$data['comment_type'] = $this->input->input_stream('comment_type', TRUE);
		$data['status'] = 1;

		$result = $this->Ldt_audit_report_nc_comments_model->add_audit_report_nc_comments_in_db($data);

		return $result;
	}

//function to clear NC for a nc_id
	public function clear_audit_report_nc()
	{
		$nc_id = $this->input->input_stream('nc_id', TRUE);

		$result = $this->Ldt_audit_report_nc_model->clear_audit_report_nc($nc_id);
		return $result;
	}

//function to add scope of certification in db
	public function add_scope_of_cert_in_db()
	{
		$userid = $_SESSION['userid'];

		$level 							= $this->input->input_stream('level', TRUE);
		$cert_type 						= $this->input->input_stream('cert_type', TRUE);

		$data['tracksheet_id'] 			= $this->input->input_stream('tracksheet_id', TRUE);
		$data['cm_id'] 					= $this->input->input_stream('cm_id', TRUE);
		$data['scope'] 					= $this->input->input_stream('scope', TRUE);
		$data['comment'] 				= $this->input->input_stream('comment', TRUE);
		$data['added_by'] 				= $userid;
		$data['added_on'] 				= date('Y-m-d');
		$data['status'] 				= 1;

		$this->Ldt_scope_of_cert_model->add_scope_of_cert_in_db($data);
        
        if($level == 2)
        {
        	if($cert_type == 4)
       			redirect(base_url('auditor/list_fill_scope_of_cert_re_cert'), 'refresh');
       		else
       			redirect(base_url('auditor/list_fill_scope_of_cert'), 'refresh');
        }
       	else if($level == 3 || $level == 4)
       		redirect(base_url('auditor/list_fill_scope_of_cert_surv'), 'refresh');
	}	

//function to update scope of certification in db
	public function update_scope_of_cert_in_db()
	{
		$userid = $_SESSION['userid'];

		$id 							= $this->input->input_stream('id', TRUE);
		$level 							= $this->input->input_stream('level', TRUE);
		$cert_type 						= $this->input->input_stream('cert_type', TRUE);

		$data['scope'] 					= $this->input->input_stream('scope', TRUE);
		$data['comment'] 				= $this->input->input_stream('comment', TRUE);
		$data['edited_by'] 				= $userid;
		$data['edited_on'] 				= date('Y-m-d');

		$result                   =   $this->Ldt_scope_of_cert_model->update_scope_of_cert_in_db($id, $data);     

       	if($level == 2)
        {
        	if($cert_type == 4)
       			redirect(base_url('auditor/list_fill_scope_of_cert_re_cert'), 'refresh');
       		else
       			redirect(base_url('auditor/list_fill_scope_of_cert'), 'refresh');
        }
       	else if($level == 3 || $level == 4)
       		redirect(base_url('auditor/list_fill_scope_of_cert_surv'), 'refresh');
	}	

//function to update done for a tracskheet id and level
	public function update_done_status()
	{
		$tracksheet_id 					= $this->input->input_stream('tracksheet_id', TRUE);
		$level							= $this->input->input_stream('level', TRUE);

		$result                   =   $this->Ldt_notify_reviewer_about_audit_report_model->update_done_status($tracksheet_id, $level); 
	}

//function to insert or update report summary data
	public function update_insert_report_summary_data()
	{
		$tracksheet_id 					= $this->input->input_stream('tracksheet_id', TRUE);
		$level							= $this->input->input_stream('level', TRUE);

		$count_minor_nc 					= $this->input->input_stream('count_minor_nc', TRUE);
		$count_major_nc							= $this->input->input_stream('count_major_nc', TRUE);

		$cm_id							= $this->input->input_stream('cm_id', TRUE);

		$stage_of_audit 					= $this->input->input_stream('stage_of_audit', TRUE);
		$recomm 					= $this->input->input_stream('recomm', TRUE);
		$reason							= $this->input->input_stream('reason', TRUE);
		$surv_date 					= $this->input->input_stream('surv_date', TRUE);
		$date							= $this->input->input_stream('date', TRUE);

		$result = $this->Ldt_audit_report_summary_model->update_insert_report_summary_data($tracksheet_id, $level, $count_minor_nc, $count_major_nc, $stage_of_audit, $recomm, $reason, $surv_date, $date, $cm_id);

		return $result;
	}

//function to update the approved_by_reviewer status of report summary for a id
	public function approved_by_reviewer_report_summary()
	{
		$row_id 					= $this->input->input_stream('row_id', TRUE);

		$result = $this->Ldt_audit_report_summary_model->approved_by_reviewer_report_summary($row_id);

		return $result;
	}		

//function to notify technical team of scope of cert
	public function notify_technical_of_scope_of_cert()
	{
		$id 								= $this->uri->segment(3);
		$redirect_level 					= $this->uri->segment(4);

		$result = $this->Ldt_scope_of_cert_model->notify_technical_of_scope_of_cert($id);

		if($redirect_level == 2)
			redirect(base_url('auditor/list_fill_scope_of_cert_re_cert'), 'refresh');
		else if($redirect_level == 3)
			redirect(base_url('auditor/list_fill_scope_of_cert_surv'), 'refresh');
	}
}
?>