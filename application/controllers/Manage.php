<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->module   =   'manage';

        $this->load->model('pagination_model');
        $this->load->model('Sdt_schemes_model');

        $this->load->model('Mdt_p_tracksheet');

        $this->load->model('Ldt_certi_issue_checklist_model');

        $this->load->model('Ldt_app_rev_form');
        $this->load->model('Ldt_app_rev_anzsic_codes_model');
        $this->load->model('Ldt_app_rev_audit_team_plan');
        $this->load->model('Ldt_audit_program_form_model');

        $this->load->model('Mdt_questionnaire_forms_model');
        $this->load->model('Mdt_questionnaire_qstns_model');

        $this->load->model('Mdt_client_feedback_qstns_model');

        $this->load->model('Ldt_tracksheet_status_model');            

        $this->load->model('Mdt_users_model', '', true);
    }

//function to render the create questionnaire page
    public function create_questionnaire_page()
    {
    //getting the scheme list
        $scheme_list_record1 = $this->Sdt_schemes_model->get_scheme_list(1);
        $scheme_list_record2 = $this->Sdt_schemes_model->get_scheme_list(2);

        $scheme_list_record = array_merge($scheme_list_record1,$scheme_list_record2); 
        $data['scheme_list_record'] = $scheme_list_record;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Create Questionnaire';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/create_questionnaire_page';
        $this->load->view('main',$data);
    #############################################################################  
    }

//page to create the questionnaire
    public function create_questionnaire_form()
    {
        $scheme_id             =   $this->input->input_stream('scheme_id', TRUE);
        $level             =   $this->input->input_stream('level', TRUE);

        if($scheme_id == 0 OR $level == 0)
        {
            redirect(base_url('manage/create_questionnaire_page') , 'refresh');
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Create Questionnaire Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/create_questionnaire_form';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to render the list questionnaire page
    public function list_questionnaire()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_questionnaire_forms_model->list_questionnaire();
        $data['page_details']           =   $get_pageList;
        
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Questionnaire';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/list_questionnaire';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to render the edit questionnaire page
    public function edit_questionnaire_page()
    {
        $page_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;

    //questionnaire form record of a page id
        $questionnaire_form_record = $this->Mdt_questionnaire_forms_model->get_questionnaire_form_records($page_id);

        $questionnaire_form_record_count = count($questionnaire_form_record);
        if($questionnaire_form_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['questionnaire_form_record'] = $questionnaire_form_record[0];

        //getting questions of a questionnaire form of a page_id
            $get_questionnaire_form_qstns = $this->Mdt_questionnaire_qstns_model->get_questionnaire_form_qstns($page_id);
            $data['get_questionnaire_form_qstns'] = $get_questionnaire_form_qstns;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Edit Questionnaire';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/edit_questionnaire_page';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to manage the client feedback page
    public function manage_client_feedback()
    {
    //getting the already added questions in the databse
        $feedback_qstn_records = $this->Mdt_client_feedback_qstns_model->feedback_qstn_records();
        $data['feedback_qstn_records'] = $feedback_qstn_records;
   
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Manage Client Feedback';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/manage_client_feedback';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheet for which certificate issue checklist is pending
    public function list_filled_certi_issue_checklist()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_certi_issue_checklist();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Certificate Issue Checklist';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/list_filled_certi_issue_checklist';
        $this->load->view('main',$data);
    #############################################################################   
    }

//function to view/approve the filled certificate issue checklist form to MD
    public function view_filled_certi_issue_checklist()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;

    //tracksheet and clients details
        $database_record                   =   $this->Mdt_p_tracksheet->fill_certi_issue_checklist($tracksheet_id);
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['database_record']    =   $database_record[0];    

            $old_tracksheet_id = $database_record[0]->old_tracksheet_id; 
            $cert_type = $database_record[0]->certification_type;    

        //for getting level of that tracksheet_id
            if($cert_type == 2)
            {
                $level = 3;
            }
            else if($cert_type == 3)
            {
                $level = 4;
            }
            else
            {
                $level = 2; //only after level 1 (i.e from 2 onwards) can reach to this form
            }

        //general records of the form
            if($level == 3 || $level == 4)
            {
                $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($old_tracksheet_id);
            }
            else
            {
                $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);
            } 
            
            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 2;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];

            //anzsic codes records of the form
                if($level == 3 || $level == 4)
                {
                    $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                }
                else
                {
                    $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                }

                $data['app_rev_anzsic_codes_record']    =   $app_rev_anzsic_codes_record;

            //audit team plan records of the form
                if($level == 3 || $level == 4)
                {
                    $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($old_tracksheet_id);
                }
                else
                {
                   $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                }                
                
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //getting records of the audit program form
                if($level == 3 || $level == 4)
                {
                   $audit_program_form_records                   =   $this->Ldt_audit_program_form_model->get_audit_program_form_records($old_tracksheet_id);
                }
                else
                {
                  $audit_program_form_records                   =   $this->Ldt_audit_program_form_model->get_audit_program_form_records($tracksheet_id);
                }
                
                $data['audit_program_form_records']    =   $audit_program_form_records[0];
                
            //getting certificate issue checklist records for that tracksheet_id and level
                $get_certi_issue_checklist_records                   =   $this->Ldt_certi_issue_checklist_model->get_certi_issue_checklist_records($tracksheet_id, $level);

                $get_certi_issue_checklist_records_count = count($get_certi_issue_checklist_records);
                if($get_certi_issue_checklist_records_count == 0)
                {
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['get_certi_issue_checklist_records']    =   $get_certi_issue_checklist_records[0];
                }
            }
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Approve Filled Certificate Issue Checklist';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/view_filled_certi_issue_checklist';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheets to MD for which tracksheet status change request is done
    public function list_tracksheet_status_change_req()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_tracksheet_status_change_req();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Tracksheet Status Change Request';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/list_tracksheet_status_change_req';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to render the page to tracksheeet status change request
    public function tracksheet_status_change_req()
    {
        $tracksheet_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;

    //tracksheet and clients details
        $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['database_record']    =   $database_record[0];    

        //function to check if this tracksheet has withdrawn MD approval request 
            $withdrawn_md_approval_req = $this->Ldt_tracksheet_status_model->withdrawn_md_approval_req($tracksheet_id);
            
            $withdrawn_md_approval_req_count = count($withdrawn_md_approval_req);
            if($withdrawn_md_approval_req_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['withdrawn_md_approval_req'] = $withdrawn_md_approval_req[0];
            }
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Tracksheet Status Change Request';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'manage/tracksheet_status_change_req';
        $this->load->view('main',$data);
    #############################################################################  
    }
}
?>