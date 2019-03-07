<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_actions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->module   =   'manage_actions';

        $this->load->model('pagination_model');
        $this->load->model('Mdt_customer_master_model');
        $this->load->model('Sdt_schemes_model');

        $this->load->model('Mdt_p_tracksheet');
        $this->load->model('Mdt_assigned_tracksheet_users');
        
        $this->load->model('Mdt_questionnaire_forms_model');
        $this->load->model('Mdt_questionnaire_qstns_model');

        $this->load->model('Mdt_client_feedback_qstns_model');

        $this->load->model('Ldt_certi_issue_checklist_model');

        $this->load->model('Ldt_tracksheet_status_model');            
        
        $this->load->model('Mdt_users_model', '', true);
    }

//function to add a new questionnaire into the database
    public function add_new_questionnaire_db()
    {
        $scheme_id              =   $this->input->input_stream('scheme_id', TRUE);
        $level                  =   $this->input->input_stream('level', TRUE);
        $page_title             =   $this->input->input_stream('page_title', TRUE);
        $page_intro             =   $this->input->input_stream('page_intro', TRUE);

        $insert_id = $this->Mdt_questionnaire_forms_model->add_new_questionnaire_db($scheme_id, $level, $page_title, $page_intro);
        echo $insert_id;
    }

//function to add text sample questions in db
    public function add_text_sample_qstns_in_db()
    {
        $page_id             =   $this->input->input_stream('page_id', TRUE);
        $records             =   $this->input->input_stream('records', TRUE);

        $result = $this->Mdt_questionnaire_qstns_model->add_text_sample_qstns_in_db($page_id, $records);
        echo $result;
    }

//function to delete a qstn from database
    public function delete_qstn_from_db()
    {
        $qstn_id             =   $this->input->input_stream('qstn_id', TRUE);

        $result = $this->Mdt_questionnaire_qstns_model->delete_qstn_from_db($qstn_id);
        echo $result;
    }    

//function to update a qstn from database
    public function update_qstn_from_db()
    {
        $qstn_id                =   $this->input->input_stream('qstn_id', TRUE);
        $qstn_title             =   $this->input->input_stream('qstn_title', TRUE);
        $qstn_data              =   $this->input->input_stream('qstn_data', TRUE);
        $qstn_type              =   $this->input->input_stream('qstn_type', TRUE);
        $qstn_help              =   $this->input->input_stream('qstn_help', TRUE);

        $result = $this->Mdt_questionnaire_qstns_model->update_qstn_from_db($qstn_id, $qstn_title, $qstn_data, $qstn_type, $qstn_help);
        echo $result;
    } 

//function to add sample question in db
    public function add_sample_qstn_in_db()
    {
        $page_id                =   $this->input->input_stream('page_id', TRUE);
        
        $qstn_title             =   $this->input->input_stream('qstn_title', TRUE);
        $qstn_data              =   $this->input->input_stream('qstn_data', TRUE);
        $qstn_type              =   $this->input->input_stream('qstn_type', TRUE);
        $qstn_parent            =   $this->input->input_stream('qstn_parent', TRUE);
        $qstn_help              =   $this->input->input_stream('qstn_help', TRUE);

        $result = $this->Mdt_questionnaire_qstns_model->add_sample_qstn_in_db($page_id, $qstn_title, $qstn_data, $qstn_type, $qstn_parent, $qstn_help);
        echo $result;
    }

//function to update the page details in db
    public function update_page_details_db()
    {
        $page_id                =   $this->input->input_stream('page_id', TRUE);

        $page_title             =   $this->input->input_stream('page_title', TRUE);
        $page_intro             =   $this->input->input_stream('page_intro', TRUE);

        $result = $this->Mdt_questionnaire_forms_model->update_page_details_db($page_id, $page_title, $page_intro);
        echo $result;
    }

//function to add feedback question in db
    public function add_feedback_qstn_in_db()
    {
        $qstn                =   $this->input->input_stream('qstn', TRUE);
        $qstn_type             =   $this->input->input_stream('qstn_type', TRUE);

        $result = $this->Mdt_client_feedback_qstns_model->add_feedback_qstn_in_db($qstn, $qstn_type);
        echo $result;
    }

//function to delete feedback question in db
    public function delt_feedback_qstn_in_db()
    {
        $qstn_id                =   $this->input->input_stream('qstn_id', TRUE);

        $result = $this->Mdt_client_feedback_qstns_model->delt_feedback_qstn_in_db($qstn_id);
        echo $result;
    }

//function to edit feedback question in db
    public function edit_feedback_qstn_in_db()
    {
        $qstn_id                =   $this->input->input_stream('qstn_id', TRUE);

        $qstn                =   $this->input->input_stream('qstn', TRUE);
        $qstn_type             =   $this->input->input_stream('qstn_type', TRUE);

        $result = $this->Mdt_client_feedback_qstns_model->edit_feedback_qstn_in_db($qstn_id, $qstn, $qstn_type);
        echo $result;
    }

//update approval_by_md status in ldt_certi_issue_checklist table
    public function update_md_approval_status_of_certi_checklist()
    {
        $id                =   $this->input->input_stream('id', TRUE);

        $result = $this->Ldt_certi_issue_checklist_model->update_md_approval_status_of_certi_checklist($id);
        return $result;
    }    

//function to update certifciation dates for a tracksheet
    public function update_certification_dates_for_tracsheet()
    {
        $tracksheet_id                =   $this->input->input_stream('tracksheet_id', TRUE);
        $level                =   $this->input->input_stream('level', TRUE);

        $result = $this->Mdt_p_tracksheet->update_certification_dates_for_tracsheet($tracksheet_id, $level);
        return $result;
    }

//function to upate the md_approved status of a tracksheet in ldt_tracksheet_status table
    public function update_md_approved_status_in_tracksheet_status_table()
    {
        $row_id                =   $this->input->input_stream('row_id', TRUE);
        $md_approved                =   $this->input->input_stream('md_approved', TRUE);

        $result = $this->Ldt_tracksheet_status_model->update_md_approved_status_in_tracksheet_status_table($row_id, $md_approved);
        echo $result;
    }

//function to edit status for a tracksheet
    public function update_tracksheet_status()
    {
        $tracksheet_id                          =    $this->input->input_stream('tracksheet_id', TRUE);
        $status                          =    $this->input->input_stream('status', TRUE);
        
        $result = $this->Mdt_p_tracksheet->update_tracksheet_status($tracksheet_id, $status);
        echo $result;
    }
}
?>