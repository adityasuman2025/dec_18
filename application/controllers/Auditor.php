<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auditor extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->module   =   'auditor';
        
        $this->load->model('pagination_model');

        $this->load->model('Mdt_customer_master_model');
        $this->load->model('Mdt_customer_site_model');

        $this->load->model('Sdt_schemes_model');

        $this->load->model('Mdt_p_tracksheet');
        $this->load->model('Mdt_assigned_tracksheet_users');

        $this->load->model('Ldt_app_rev_form');
        $this->load->model('Ldt_app_rev_audit_team_plan');
        $this->load->model('Ldt_app_rev_anzsic_codes_model');
        
        $this->load->model('Ldt_audit_program_form_model');
        $this->load->model('Ldt_audit_prog_process_model');

        $this->load->model('Ldt_audit_plan_form_model');
        $this->load->model('Ldt_audit_plan_team_plan_model');
        $this->load->model('Ldt_audit_plan_process_model');
        $this->load->model('Ldt_audit_plan_notify_to_auditor_model');

        $this->load->model('Sdt_audit_process_list_model'); 
        $this->load->model('Sdt_audit_plan_process_list_model'); 
               
        $this->load->model('Ldt_audit_on_site1_model');

        $this->load->model('Ldt_notify_reviewer_about_audit_report_model');

        $this->load->model('Mdt_questionnaire_forms_model');
        $this->load->model('Mdt_questionnaire_qstns_model');
        $this->load->model('Ldt_questionnaire_ans_model');  

        $this->load->model('Ldt_audit_report_comments_model');   
        $this->load->model('Ldt_audit_report_nc_model');
        $this->load->model('Ldt_audit_report_nc_comments_model');

        $this->load->model('Ldt_scope_of_cert_model');

        $this->load->model('Ldt_client_feedback_model');
        $this->load->model('Mdt_client_feedback_qstns_model');
        $this->load->model('Ldt_client_feedback_ans_model');

        $this->load->model('Ldt_audit_report_summary_model');
        
                
        $this->load->model('Mdt_users_model', '', true);
    }

//function to list audit plans of a particular auditor
    public function list_my_audit_plan()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_plan_form_model->list_my_audit_plan1();
        $data['page_details']           =   $get_pageList;

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List My Audit Plans';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_my_audit_plan';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the audit plan 1 form by auditor
    public function view_my_audit_plan1_form()
    {
    #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
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
     
        //general records of the application review form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);

            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];
                $scheme_system = $app_rev_form_record[0]->assesment_standard;

            //getting records of the audit program form
                $audit_program_form_records                   =   $this->Ldt_audit_program_form_model->get_audit_program_form_records($tracksheet_id);

                $audit_program_form_records_count = count($audit_program_form_records);
                if($audit_program_form_records_count == 0)
                {
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['audit_program_form_records']    =   $audit_program_form_records[0];

                //audit team plan records of the form for a particular level(stage)
                    $level = 1; //stage1
                    $audit_plan_team_plan_records = $this->Ldt_audit_plan_team_plan_model->get_audit_team_plan_records($tracksheet_id, $level);
                    $data['audit_plan_team_plan_records']    =   $audit_plan_team_plan_records;

                //audit plan notify to auditor and confid aggrement status records
                    $audit_plan_notify_to_auditor_records = $this->Ldt_audit_plan_notify_to_auditor_model->audit_plan_notify_to_auditor_records($tracksheet_id, $level, $userid);
                    $data['audit_plan_notify_to_auditor_records']    =   $audit_plan_notify_to_auditor_records;

                //getting the audit plan process list of that tracksheet
                    $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                    $data['audit_plan_process_list']    =   $audit_plan_process_list;
                }
            }
        }

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View My Audit Plan 1 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/view_my_audit_plan1_form';
        $this->load->view('main',$data);
    #############################################################################
    }   

//function to view the audit plan 2 form by auditor
    public function view_my_audit_plan2_form()
    {
    #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
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
     
        //general records of the application review form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);

            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];
                $scheme_system = $app_rev_form_record[0]->assesment_standard;

            //getting records of the audit program form
                $audit_program_form_records                   =   $this->Ldt_audit_program_form_model->get_audit_program_form_records($tracksheet_id);

                $audit_program_form_records_count = count($audit_program_form_records);
                if($audit_program_form_records_count == 0)
                {
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['audit_program_form_records']    =   $audit_program_form_records[0];

                //audit team plan records of the form for a particular level(stage)
                    $level = 2; //stage1
                    $audit_plan_team_plan_records = $this->Ldt_audit_plan_team_plan_model->get_audit_team_plan_records($tracksheet_id, $level);
                    $data['audit_plan_team_plan_records']    =   $audit_plan_team_plan_records;

                //audit plan notify to auditor and confid aggrement status records
                    $audit_plan_notify_to_auditor_records = $this->Ldt_audit_plan_notify_to_auditor_model->audit_plan_notify_to_auditor_records($tracksheet_id, $level, $userid);
                    $data['audit_plan_notify_to_auditor_records']    =   $audit_plan_notify_to_auditor_records;

                //getting the audit plan process list of that tracksheet
                    $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                    $data['audit_plan_process_list']    =   $audit_plan_process_list;
                }
            }
        }

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View My Audit Plan 2 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/view_my_audit_plan2_form';
        $this->load->view('main',$data);
    #############################################################################
    }   

//function to view the re-audit plan form by auditor
    public function view_my_audit_plan11_form()
    {
    #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
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

            $cert_type = $database_record[0]->certification_type;
            $old_tracksheet_id = $database_record[0]->old_tracksheet_id;

            if($cert_type == 1)
                $level = 2;
            else if($cert_type == 2)
                $level = 3;
            else if($cert_type == 3)
                $level = 4;    
            else if($cert_type == 4)
                $level = 2;        
     
        //getting the audit report summary records
            $get_audit_summary_records = $this->Ldt_audit_report_summary_model->get_audit_summary_records($tracksheet_id, $level);
            $get_audit_summary_records_count = count($get_audit_summary_records);

            if($get_audit_summary_records_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['get_audit_summary_records'] = $get_audit_summary_records;

            //audit team plan records of the form for a particular level(stage)
                $level = 11; //re-audit
                $audit_plan_team_plan_records = $this->Ldt_audit_plan_team_plan_model->get_audit_team_plan_records($tracksheet_id, $level);
                $data['audit_plan_team_plan_records']    =   $audit_plan_team_plan_records;

            //audit plan notify to auditor and confid aggrement status records
                $audit_plan_notify_to_auditor_records = $this->Ldt_audit_plan_notify_to_auditor_model->audit_plan_notify_to_auditor_records($tracksheet_id, $level, $userid);
                $data['audit_plan_notify_to_auditor_records']    =   $audit_plan_notify_to_auditor_records;

            //getting the audit plan process list of that tracksheet
                $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                $data['audit_plan_process_list']    =   $audit_plan_process_list;
            }
        }

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View My Re-Audit Plan Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/view_my_audit_plan11_form';
        $this->load->view('main',$data);
    #############################################################################
    }   

//function to view the audit plan surv form by auditor
    public function view_my_audit_plan_surv_form()
    {
    #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
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

            $old_tracksheet_id = $database_record[0]->old_tracksheet_id;
            $certification_type = $database_record[0]->certification_type;

            if($certification_type == 2)
                $level = 3; //surv 1
            else if($certification_type == 3)
                $level = 4; //surv 2
        }              

    //general records of the application review form
        $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($old_tracksheet_id);
       
        $app_rev_form_record_count = count($app_rev_form_record);
        if($app_rev_form_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['app_rev_form_record']    =   $app_rev_form_record[0];
            $scheme_system = $app_rev_form_record[0]->assesment_standard;

        //getting records of the audit program form
            $audit_program_form_records                   =   $this->Ldt_audit_program_form_model->get_audit_program_form_records($old_tracksheet_id);

            $audit_program_form_records_count = count($audit_program_form_records);
            if($audit_program_form_records_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['audit_program_form_records']    =   $audit_program_form_records[0];

            //audit team plan records of the form for a particular level(stage)
                $audit_plan_team_plan_records = $this->Ldt_audit_plan_team_plan_model->get_audit_team_plan_records($tracksheet_id, $level);
                $data['audit_plan_team_plan_records']    =   $audit_plan_team_plan_records;

            //getting the audit plan process list of that tracksheet
                $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                $data['audit_plan_process_list']    =   $audit_plan_process_list;

            //audit plan notify to auditor and confid aggrement status records
                $audit_plan_notify_to_auditor_records = $this->Ldt_audit_plan_notify_to_auditor_model->audit_plan_notify_to_auditor_records($tracksheet_id, $level, $userid);
                $data['audit_plan_notify_to_auditor_records']    =   $audit_plan_notify_to_auditor_records;
            }
        }

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View My Audit Plan Surveillance Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/view_my_audit_plan_surv_form';
        $this->load->view('main',$data);
    #############################################################################
    }   

//function to fill the confidentiality form
    public function fill_confid_agg()
    {
        $id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];

        $data['out_of_index'] = 0;

    //audit plan notify to auditor and confid aggrement status records
        $audit_plan_notify_to_auditor_records_id = $this->Ldt_audit_plan_notify_to_auditor_model->audit_plan_notify_to_auditor_records_id($id, $userid);
        $audit_plan_notify_to_auditor_records_id_count = count($audit_plan_notify_to_auditor_records_id);

        if($audit_plan_notify_to_auditor_records_id_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['audit_plan_notify_to_auditor_records_id']    =   $audit_plan_notify_to_auditor_records_id[0];
            $tracksheet_id =  $audit_plan_notify_to_auditor_records_id[0]['tracksheet_id'];

            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $data['database_record'] = $database_record[0];
        }
                            
    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'The confidentiality and no conflict of interest agreement';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/fill_confid_agg';
        $this->load->view('main',$data);
    #############################################################################
    }     

//function to accept the confidentiality aggrement for a id
    public function accept_cofid_agg()
    {
        $id = $this->uri->segment(3);
        $tracksheet_id = $this->uri->segment(4);
        $level = $this->uri->segment(5);

        if($level == 3 || $level == 4)
            $level = "_surv";

    //to accept the confid agg form of a particular id
        $result = $this->Ldt_audit_plan_notify_to_auditor_model->accept_cofid_agg($id);

        if($result == 1)
        {
            //$result1 = $this->Mdt_p_tracksheet->incr_flow_status_of_tracksheet($tracksheet_id);
            redirect(base_url('auditor/view_my_audit_plan' . $level . '_form/' . $tracksheet_id), 'refresh');           
        }
        else
            redirect(base_url('auditor/list_my_audit_plan'), 'refresh');
    }

//function to list the tracksheet for which audit on-site 1 pending     
    public function audit_on_site1()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->audit_on_site1();
        $data['page_details']           =   $get_pageList;
    
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site 1';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site1';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the questionnaire of a tracksheet for level 1
    public function audit_on_site1_form()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = 1;

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;
        
    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;

             //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id);             

            //general records of the application review form
                $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);

                $app_rev_form_record_count = count($app_rev_form_record);
                if($app_rev_form_record_count == 0)
                {
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['app_rev_form_record']    =   $app_rev_form_record[0];   
                }                 

            //getting the anzsic codes records of that tracksheet
                $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, $level);
                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire questions for a particular page_id
                    $get_questionnaire_form_qstns = $this->Mdt_questionnaire_qstns_model->get_questionnaire_form_qstns($page_id);
                    $get_questionnaire_form_qstns_count = count($get_questionnaire_form_qstns);

                    if($get_questionnaire_form_qstns_count == 0)
                    {
                        $data["qstn_not_avail"] = 1;
                    }
                    else
                    {
                        $data['get_questionnaire_form_qstns'] = $get_questionnaire_form_qstns;
                    }
                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;
                }            
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site Form 1';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site1_form';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list all the tracksheets for which a reviewer has to review their audit report
    public function list_to_review_audit_reports()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_to_review_audit_reports();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Review Audit Reports';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_to_review_audit_reports';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to show the audit report form to the reviewer
    public function reviewer_view_audit_report()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;

    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_notify_reviewer_about_audit_report_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $old_tracksheet_id = $database_record[0]->old_tracksheet_id;

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;

            //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id); 

            //general records of the application review form
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
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['app_rev_form_record']    =   $app_rev_form_record[0];   
                }                 

            //getting the anzsic codes records of that tracksheet
                if($level == 3 || $level == 4)
                {
                     $app_rev_anzsic_code_record            =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                }
                else
                {
                     $app_rev_anzsic_code_record            =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                }  

                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                if($level == 3 || $level == 4)
                {
                    $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, 2);
                }
                else
                {
                    $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, $level);
                }

                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire questions for a particular page_id
                    $get_questionnaire_form_qstns = $this->Mdt_questionnaire_qstns_model->get_questionnaire_form_qstns($page_id);
                    $get_questionnaire_form_qstns_count = count($get_questionnaire_form_qstns);

                    if($get_questionnaire_form_qstns_count == 0)
                    {
                        $data["qstn_not_avail"] = 1;
                    }
                    else
                    {
                        $data['get_questionnaire_form_qstns'] = $get_questionnaire_form_qstns;
                    }

                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;

                //getting all comments for that tracksheet_id and level
                    $get_audit_report_comments = $this->Ldt_audit_report_comments_model->get_audit_report_comments($tracksheet_id, $level);
                    $data['get_audit_report_comments'] = $get_audit_report_comments;

                //getting all the NCs of audit report for that tracksheet_id and level
                    $get_audit_report_ncs = $this->Ldt_audit_report_nc_model->get_audit_report_ncs($tracksheet_id, $level);
                    $data['get_audit_report_ncs'] = $get_audit_report_ncs;

                //getting the audit report summary records
                    $get_audit_summary_records = $this->Ldt_audit_report_summary_model->get_audit_summary_records($tracksheet_id, $level);
                    $data['get_audit_summary_records'] = $get_audit_summary_records;
                }            
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Reviewer View Audit Report';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/reviewer_view_audit_report';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to get options
    function to_get_options($answer_options)
    {
        if($answer_options == 1)
        {
            echo "<option value=\"1\">C</option>";
        }
        else if($answer_options == 2)
        {
            echo "<option value=\"2\">Minor NC</option>";
        }   
        else if($answer_options == 3)
        {
            echo "<option value=\"3\">Major NC</option>";
        }   
        else if($answer_options == 4)
        {
            echo "<option value=\"4\">Observation</option>";        
        }   
        else
        {
            echo "<option value=\"0\">NA</option>";
        }                                           
    }

//function to get history of answers for a qstn
    public function watch_history_of_answers()
    {
        $qstn_id                =   $this->input->input_stream('qstn_id', TRUE);
        $tracksheet_id          =   $this->input->input_stream('tracksheet_id', TRUE);
        
    //getting the questionnaire answers for a tracksheet_id and page_id   
        $get_questionnaire_form_qstn_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_qstn_anss($qstn_id);
        $data['get_questionnaire_form_qstn_anss'] = $get_questionnaire_form_qstn_anss;

        foreach ($get_questionnaire_form_qstn_anss as $key => $get_questionnaire_form_ans)
        {
            $ans_type = $get_questionnaire_form_ans['ans_type'];   
            $ans_tracksheet_id = $get_questionnaire_form_ans['tracksheet_id'];                                                        
            if($ans_type == 2 && $tracksheet_id == $ans_tracksheet_id)
            {
                $answer = $get_questionnaire_form_ans['answer'];
                $ans_id = $get_questionnaire_form_ans['ans_id'];
                $created_on = $get_questionnaire_form_ans['created_on'];

                echo "<div class=\"col-md-12 col-xs-12 col-sl-12\" style=\"padding: 0; margin: 0;\">";
                    echo "<div class=\"checklist_ans_view\" ans_id=\"$ans_id\">
                        $answer
                        <br>
                        <span class=\"pull-right\" style=\"font-size: 90%; background: silver; padding: 2px;\">$created_on</span>
                        </div>";
                    
                echo "</div>";
            }
            elseif ($ans_type == 1 && $tracksheet_id == $ans_tracksheet_id)
            {
                $answer = $get_questionnaire_form_ans['answer'];
                $ans_id = $get_questionnaire_form_ans['ans_id'];
                $created_on = $get_questionnaire_form_ans['created_on'];

                echo "  <div class=\"col-md-12 col-xs-12 col-sl-12\" style=\"padding: 0; margin: 0; margin-bottom: 8px; \">
                            <select class=\"checklist_option\" ans_id=\"<?php echo $ans_id; ?>\" disabled=\"disabled\">";
                                $this->to_get_options($answer);
                echo "      </select>
                        </div>";
            }
        }   
    }

//function to list tracksheets to a auditor for which audit report 1 review is going on
    public function list_audit_report1_review()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_audit_report1_review();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Report 1 Review';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_audit_report1_review';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to show the audit report form to the auditor
    public function auditor_view_audit_report()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;

    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;

                $old_tracksheet_id = $database_record[0]->old_tracksheet_id;

            //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id); 

            //general records of the application review form
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
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['app_rev_form_record']    =   $app_rev_form_record[0];   
                }                 

            //getting the anzsic codes records of that tracksheet
                if($level == 3 || $level == 4)
                {
                     $app_rev_anzsic_code_record            =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                }
                else
                {
                     $app_rev_anzsic_code_record            =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                } 

                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                if($level == 3 || $level == 4)
                {
                    $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, 2);
                }
                else
                {
                    $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, $level);
                }

                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire questions for a particular page_id
                    $get_questionnaire_form_qstns = $this->Mdt_questionnaire_qstns_model->get_questionnaire_form_qstns($page_id);
                    $get_questionnaire_form_qstns_count = count($get_questionnaire_form_qstns);

                    if($get_questionnaire_form_qstns_count == 0)
                    {
                        $data["qstn_not_avail"] = 1;
                    }
                    else
                    {
                        $data['get_questionnaire_form_qstns'] = $get_questionnaire_form_qstns;
                    }

                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;

                //getting all comments for that tracksheet_id and level
                    $get_audit_report_comments = $this->Ldt_audit_report_comments_model->get_audit_report_comments($tracksheet_id, $level);
                    $data['get_audit_report_comments'] = $get_audit_report_comments;

                //getting all the NCs of audit report for that tracksheet_id and level
                    $get_audit_report_ncs = $this->Ldt_audit_report_nc_model->get_audit_report_ncs($tracksheet_id, $level);
                    $data['get_audit_report_ncs'] = $get_audit_report_ncs;
                }            
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Auditor View Audit Report';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/auditor_view_audit_report';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list tracksheets to a auditor for which nc 1 is going on
    public function list_nc1()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_nc1();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List NC 1';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_nc1';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to render the view the audit report for nc for any tracksheet_id and level
    public function auditor_view_audit_report_nc()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;

    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;

                $old_tracksheet_id = $database_record[0]->old_tracksheet_id;

            //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id); 

            //general records of the application review form
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
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['app_rev_form_record']    =   $app_rev_form_record[0];   
                }                 

            //getting the anzsic codes records of that tracksheet
                if($level == 3 || $level == 4)
                {
                    $app_rev_anzsic_code_record            =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                }
                else
                {
                    $app_rev_anzsic_code_record            =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                }  

                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //getting the questions, answers and other other infos about that question for which NC has been marked for a tracksheet and a level
                $get_all_infos_of_nc = $this->Ldt_audit_report_nc_model->get_all_infos_of_nc($tracksheet_id, $level);
                $data["get_all_infos_of_nc"] = $get_all_infos_of_nc;

            //getting all comments for that tracksheet_id and level
                $get_audit_nc_comments = $this->Ldt_audit_report_nc_comments_model->get_audit_nc_comments($tracksheet_id, $level);
                $data['get_audit_nc_comments'] = $get_audit_nc_comments;

            //getting all the auditors of a tracksheet and level
                $get_all_auditors = $this->Ldt_audit_plan_notify_to_auditor_model->get_all_auditors($tracksheet_id, $level);
                $data['get_all_auditors'] = $get_all_auditors;
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Auditor View Audit Report for NC';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/auditor_view_audit_report_nc';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list the tracksheet for which audit on-site 2 pending     
    public function audit_on_site2()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->audit_on_site2();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site 2';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site2';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the questionnaire of a tracksheet for level 2
    public function audit_on_site2_form()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = 2;

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;
        
    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;

             //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id);  

            //general records of the application review form
                $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);

                $app_rev_form_record_count = count($app_rev_form_record);
                if($app_rev_form_record_count == 0)
                {
                    $data["out_of_index"] = 1;
                }
                else
                {
                    $data['app_rev_form_record']    =   $app_rev_form_record[0];   
                }                 

            //getting the anzsic codes records of that tracksheet
                $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, $level);
                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire questions for a particular page_id
                    $get_questionnaire_form_qstns = $this->Mdt_questionnaire_qstns_model->get_questionnaire_form_qstns($page_id);
                    $get_questionnaire_form_qstns_count = count($get_questionnaire_form_qstns);

                    if($get_questionnaire_form_qstns_count == 0)
                    {
                        $data["qstn_not_avail"] = 1;
                    }
                    else
                    {
                        $data['get_questionnaire_form_qstns'] = $get_questionnaire_form_qstns;
                    }

                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;

                //getting the audit report summary records
                    $get_audit_summary_records = $this->Ldt_audit_report_summary_model->get_audit_summary_records($tracksheet_id, $level);
                    $data['get_audit_summary_records'] = $get_audit_summary_records;
                }            
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site Form 2';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site2_form';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheet for which scope of certification has not been filled yet 
    public function list_fill_scope_of_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_fill_scope_of_cert();
        $data['page_details']           =   $get_pageList;

        $get_scope_of_cert_records                   =   $this->Ldt_scope_of_cert_model->get_scope_of_cert_records();
        $data['get_scope_of_cert_records']           =   $get_scope_of_cert_records;
    
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Fill Scope of Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_fill_scope_of_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to render the page to fill scope of cert form
    public function fill_scope_of_cert()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

        $userid = $_SESSION['userid'];
        
        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;

    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];  
            }
        }
        else
        {
            $data["out_of_index"] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Fill Scope of Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/fill_scope_of_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to render the page to view scope of cert form of a tracksheet
    public function view_scope_of_cert()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;

    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0]; 

            //get scope of certification records for that tracksheet
                $get_scope_of_cert = $this->Ldt_scope_of_cert_model->get_scope_of_cert($tracksheet_id);
                $data['get_scope_of_cert']    =   $get_scope_of_cert;    
            }
        }
        else
        {
            $data["out_of_index"] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Scope of Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/view_scope_of_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list tracksheets to a auditor for which audit report 1 review is going on
    public function list_audit_report2_review()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_audit_report2_review();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Report 2 Review';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_audit_report2_review';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list tracksheets to a auditor for which nc 2 is going on
    public function list_nc2()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_nc2();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List NC 2';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_nc2';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to lst tracksheets for doing their re-audit on-site
    public function list_re_audit_on_site()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_re_audit_on_site();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit On-Site';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_re_audit_on_site';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to fill the nc checklist for the re-audit
    public function re_audit_on_site()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;

   //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;
                $old_tracksheet_id = $database_record[0]->old_tracksheet_id;

            //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id); 

            //general records of the application review form
                if($level == 2) //for case of stage 2 audit we get scope from app_rev form
                {
                    $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);

                    $app_rev_form_record_count = count($app_rev_form_record);
                    if($app_rev_form_record_count == 0)
                    {
                        $data["out_of_index"] = 1;
                    }
                    else
                    {
                        $data['app_rev_form_record']    =   $app_rev_form_record[0];   
                    }   
                }
                        
            //getting the anzsic codes records of that tracksheet
                if($level == 2) //for case of stage 2 audit we get scope from app_rev form
                {
                    $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                }
                else
                {
                    $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                }

                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, 2); //for surveillance/stage 2 audit, audit 2 questions will be there
                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;

                //getting the questions, answers and other other infos about that question for which NC has been marked for a tracksheet and a level
                    $get_all_infos_of_nc = $this->Ldt_audit_report_nc_model->get_all_infos_of_nc($tracksheet_id, $level);
                    $data["get_all_infos_of_nc"] = $get_all_infos_of_nc;

                //getting all comments for that tracksheet_id and level
                    $get_audit_nc_comments = $this->Ldt_audit_report_nc_comments_model->get_audit_nc_comments($tracksheet_id, $level);
                    $data['get_audit_nc_comments'] = $get_audit_nc_comments;

                //getting all comments of auditor and reviewer for that tracksheet_id and level
                    $get_audit_report_comments = $this->Ldt_audit_report_comments_model->get_audit_report_comments($tracksheet_id, $level);
                    $data['get_audit_report_comments'] = $get_audit_report_comments;

                //getting the audit report summary records
                    $level = 11;
                    $get_audit_summary_records = $this->Ldt_audit_report_summary_model->get_audit_summary_records($tracksheet_id, $level);
                    $data['get_audit_summary_records'] = $get_audit_summary_records;

                //getting all the auditors of a tracksheet and level
                    $get_all_auditors = $this->Ldt_audit_plan_notify_to_auditor_model->get_all_auditors($tracksheet_id, $level);
                    $data['get_all_auditors'] = $get_all_auditors;
                }
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Re-Audit On-Site';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/re_audit_on_site';
        $this->load->view('main',$data);
    #############################################################################  
    }    

//function to list re-audit report review to the auditor
    public function list_re_audit_report_review()
    {
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit Report Review';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_re_audit_report_review';
        $this->load->view('main',$data);
    #############################################################################  
    }  

//function to show the re-audit report to the reviewer
    public function reviewer_view_re_audit_report()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;

   //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;
                $old_tracksheet_id = $database_record[0]->old_tracksheet_id;

            //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id); 

            //general records of the application review form
                if($level == 2) //for case of stage 2 audit we get scope from app_rev form
                {
                    $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);

                    $app_rev_form_record_count = count($app_rev_form_record);
                    if($app_rev_form_record_count == 0)
                    {
                        $data["out_of_index"] = 1;
                    }
                    else
                    {
                        $data['app_rev_form_record']    =   $app_rev_form_record[0];   
                    }   
                }             

            //getting the anzsic codes records of that tracksheet
               if($level == 2) //for case of stage 2 audit we get scope from app_rev form
                {
                    $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                }
                else
                {
                    $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                }
                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, 2);
                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;

                //getting the questions, answers and other other infos about that question for which NC has been marked for a tracksheet and a level
                    $get_all_infos_of_nc = $this->Ldt_audit_report_nc_model->get_all_infos_of_nc($tracksheet_id, $level);
                    $data["get_all_infos_of_nc"] = $get_all_infos_of_nc;

                //getting all comments for that tracksheet_id and level
                    $get_audit_report_comments = $this->Ldt_audit_report_comments_model->get_audit_report_comments($tracksheet_id, $level);
                    $data['get_audit_report_comments'] = $get_audit_report_comments;

                //getting the audit report summary records
                    $level = 11;
                    $get_audit_summary_records = $this->Ldt_audit_report_summary_model->get_audit_summary_records($tracksheet_id, $level);
                    $data['get_audit_summary_records'] = $get_audit_summary_records;
                }
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Reviewer View Re-Audit Report';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/reviewer_view_re_audit_report';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheets for which audit on-site for surveillance is going on
    public function audit_on_site_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->audit_on_site_surv();
        $data['page_details']           =   $get_pageList;
    
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site_surv';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the questionnaire of a tracksheet for level 3 or 4 (Surveillance)
    public function audit_on_site_surv_form()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;
        
    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $old_tracksheet_id = $database_record[0]->old_tracksheet_id;
                $certification_type = $database_record[0]->certification_type;

                if($certification_type == 2)
                    $level = 3; //surv 1
                else if($certification_type == 3)
                    $level = 4; //surv 2

                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;

            //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id);    
                
            //getting the anzsic codes records of that tracksheet
                $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, 2); //2 becoz for surveillance qstns of audit stage 2 will be there
                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire questions for a particular page_id
                    $get_questionnaire_form_qstns = $this->Mdt_questionnaire_qstns_model->get_questionnaire_form_qstns($page_id);
                    $get_questionnaire_form_qstns_count = count($get_questionnaire_form_qstns);

                    if($get_questionnaire_form_qstns_count == 0)
                    {
                        $data["qstn_not_avail"] = 1;
                    }
                    else
                    {
                        $data['get_questionnaire_form_qstns'] = $get_questionnaire_form_qstns;
                    }

                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;

                //getting the audit report summary records
                    $get_audit_summary_records = $this->Ldt_audit_report_summary_model->get_audit_summary_records($tracksheet_id, $level);
                    $data['get_audit_summary_records'] = $get_audit_summary_records;
                }            
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site Surveillance Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site_surv_form';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list tracksheets to a auditor for which audit report 1 review is going on
    public function list_audit_report_surv_review()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_audit_report_surv_review();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Report Surveillance Review';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_audit_report_surv_review';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list tracksheets to a auditor for which nc for surveillance is going on
    public function list_nc_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_nc_surv();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List NC Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_nc_surv';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheet for which scope of certification has not been filled yet for surveillance audit 
    public function list_fill_scope_of_cert_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_fill_scope_of_cert_surv();
        $data['page_details']           =   $get_pageList;

        $get_scope_of_cert_records                   =   $this->Ldt_scope_of_cert_model->get_scope_of_cert_records();
        $data['get_scope_of_cert_records']           =   $get_scope_of_cert_records;
    
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Fill Scope of Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_fill_scope_of_cert_surv';
        $this->load->view('main',$data);
    #############################################################################  
    }    

//function to list tracksheets of surveillance type for doing their re-audit on-site
    public function list_re_audit_on_site_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_re_audit_on_site_surv();
        $data['page_details']           =   $get_pageList;
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit On-Site Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_re_audit_on_site_surv';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheets for which audit on-site for re-certification is going on
    public function audit_on_site_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->audit_on_site_re_cert();
        $data['page_details']           =   $get_pageList;
    
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site_re_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the questionnaire of a tracksheet for level 2 (re-certification)
    public function audit_on_site_form_re_cert()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;
        
    //checking if that tracksheet is assigned to that auditor or not    
        $allowed_tracksheet_ids = $this->Ldt_audit_plan_notify_to_auditor_model->get_tracksheet_ids_for_user_id($userid, $level);

        $allowed = [];
        foreach ($allowed_tracksheet_ids as $key => $allowed_tracksheet_id)
        {
            array_push($allowed, $allowed_tracksheet_id['tracksheet_id']);
        }

        if(in_array($tracksheet_id, $allowed))
        {
        //general records of the tracksheet    
            $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
            $database_record_count = count($database_record);
            if($database_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['database_record']    =   $database_record[0];    

                $old_tracksheet_id = $database_record[0]->old_tracksheet_id;
                $certification_type = $database_record[0]->certification_type;
               
                $scheme_id = $database_record[0]->scheme_id;
                $cm_id = $database_record[0]->cm_id;

            //getting all the site addresses of that client
                $data['get_site_records']       = $this->Mdt_customer_site_model->get_site_records($cm_id);    
                
            //getting the anzsic codes records of that tracksheet
                $app_rev_anzsic_code_record                   =   $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                $data['app_rev_anzsic_code_record']    =   $app_rev_anzsic_code_record;  

            //getting the auditor name
                $get_auditor_name =  $this->Mdt_users_model->get_auditor_name($userid);
                $data["get_auditor_name"] = $get_auditor_name[0];

            //GETTING THE questionnaire PAGE ID FOR THIS TYPE OF SCHEME AND LEVEL
                $get_questionnaire_form = $this->Mdt_questionnaire_forms_model->get_questionnaire_form($scheme_id, 2); //2 becoz for surveillance qstns of audit stage 2 will be there
                $get_questionnaire_form_count = count($get_questionnaire_form);

                if($get_questionnaire_form_count == 0)
                {
                    $data["qstn_not_avail"] = 1;
                }
                else
                {
                    $page_id = $get_questionnaire_form[0]['page_id'];

                //getting the questionnaire questions for a particular page_id
                    $get_questionnaire_form_qstns = $this->Mdt_questionnaire_qstns_model->get_questionnaire_form_qstns($page_id);
                    $get_questionnaire_form_qstns_count = count($get_questionnaire_form_qstns);

                    if($get_questionnaire_form_qstns_count == 0)
                    {
                        $data["qstn_not_avail"] = 1;
                    }
                    else
                    {
                        $data['get_questionnaire_form_qstns'] = $get_questionnaire_form_qstns;
                    }

                //getting the questionnaire answers for a tracksheet_id and page_id   
                    $get_questionnaire_form_anss = $this->Ldt_questionnaire_ans_model->get_questionnaire_form_anss($tracksheet_id, $page_id);
                    $data['get_questionnaire_form_anss'] = $get_questionnaire_form_anss;

                //getting the audit report summary records
                    $get_audit_summary_records = $this->Ldt_audit_report_summary_model->get_audit_summary_records($tracksheet_id, $level);
                    $data['get_audit_summary_records'] = $get_audit_summary_records;
                }            
            }
        }
        else
        {
            $data['out_of_index'] = 2;
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit On-Site Re-Certification Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/audit_on_site_form_re_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list tracksheets to a auditor for which audit report 1 review is going on
    public function list_audit_report_re_cert_review()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_audit_report_re_cert_review();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Report Re-Certification Review';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_audit_report_re_cert_review';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list tracksheets to a auditor for which nc for re-certification is going on
    public function list_nc_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_nc_re_cert();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List NC Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_nc_re_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheet for which scope of certification has not been filled yet 
    public function list_fill_scope_of_cert_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_fill_scope_of_cert_re_cert();
        $data['page_details']           =   $get_pageList;

        $get_scope_of_cert_records                   =   $this->Ldt_scope_of_cert_model->get_scope_of_cert_records();
        $data['get_scope_of_cert_records']           =   $get_scope_of_cert_records;
    
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Fill Scope of Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_fill_scope_of_cert_re_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list re-certification tracksheet for doing their re-audit on-site
    public function list_re_audit_on_site_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_re_audit_on_site_re_cert();
        $data['page_details']           =   $get_pageList;
    
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit On-Site Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/list_re_audit_on_site_re_cert';
        $this->load->view('main',$data);
    #############################################################################  
    }
}
?>