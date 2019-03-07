<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Planning extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->module   =   'planning';
        $this->load->model('pagination_model');

        $this->load->model('Mdt_customer_master_model');
        $this->load->model('Mdt_customer_site_model');
        
        $this->load->model('Sdt_schemes_model');

        $this->load->model('Mdt_p_tracksheet');
        $this->load->model('Mdt_assigned_tracksheet_users');

        $this->load->model('Ldt_app_rev_form');
        $this->load->model('Ldt_app_rev_anzsic_codes_model');
        $this->load->model('Ldt_app_rev_audit_team_plan');

        $this->load->model('Ldt_audit_certificate_model');
        $this->load->model('Ldt_audit_program_form_model');

        $this->load->model('Ldt_audit_plan_team_plan_model');
        $this->load->model('Ldt_audit_plan_process_model');
        
        $this->load->model('Ldt_audit_on_site1_model');

        $this->load->model('Ldt_client_feedback_model');
        $this->load->model('Mdt_client_feedback_qstns_model');
        $this->load->model('Ldt_client_feedback_ans_model');                    

        $this->load->model('Ldt_notify_reviewer_about_audit_report_model');

        $this->load->model('Ldt_intimation_of_changes_model');
        $this->load->model('Ldt_intimation_of_changes_sites_model');
        
        $this->load->model('Ldt_scope_of_cert_model');
        
        $this->load->model('Mdt_questionnaire_forms_model');
        $this->load->model('Mdt_questionnaire_qstns_model');
        $this->load->model('Ldt_questionnaire_ans_model');

        $this->load->model('Ldt_audit_report_summary_model');
        $this->load->model('Ldt_tracksheet_status_model');        

        $this->load->model('Mdt_users_model', '', true);      
    }    

//function to list all the customers to the planning department coming from the marketing team
    public function list_customer()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_customer_master_model->customer_list();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Customers';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_customer';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view details of a particular customer
    public function view_customer_info()
    {
        #############################################################################
        $data_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;

        $database_record                   =   $this->Mdt_customer_master_model->view_customer_info($data_id);
       
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['database_record']    =   $database_record[0];
        } 

        $this->load->model('ldt_application_form_model');
        $this->load->model('ldt_application_quotation_model');
        $this->load->model('ltd_application_documents_model');
        $this->load->model('sdt_schemes_model');
        $schemes_list                   =   $this->sdt_schemes_model->get_schemes_list();
        $serilised_scheem               =   array();
        foreach($schemes_list           as $val)
        {
            $serilised_scheem[$val->scheme_id]  =   $val->scheme_name;
        }
        $data['schemes_list']           =   $serilised_scheem;
        $data['application_form']       =   $this->ldt_application_form_model->get_application_form_by_data_id($database_record[0]->data_id);
        $data['quotation_details']      =   $this->ldt_application_quotation_model->get_quotation_by_dataid($database_record[0]->data_id);
        $data['document_details']       =   $this->ltd_application_documents_model->get_document_by_owner($database_record[0]->data_id);
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Customer Details';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_customer_info';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the create tracksheet page
    public function create_tracksheet_page()
    {
        $data['data_id']             =   $this->input->input_stream('data_id', TRUE);
        $data_id = $data['data_id'];

    //for case of surveillance updating the old tracksheet
        $tracksheet_id             =   $this->input->input_stream('tracksheet_id', TRUE);
        if($tracksheet_id !="")
        {
            $query= "UPDATE mdt_p_tracksheet SET new_ts_started = 1 WHERE tracksheet_id = $tracksheet_id";
            $query_run = $this->db->query($query);
        }

    //rendering the view 
        $database_record                   =   $this->Mdt_p_tracksheet->create_tracksheet_page($data_id);
        $data['database_record']    =   $database_record[0];
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Create Tracksheet';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/create_tracksheet';
        $this->load->view('main',$data);
    }

//function to create (or start) a new tracksheet
    public function create_new_tracksheet()
    {
        $send['cm_id']             =   $this->input->input_stream('cm_id', TRUE);
        $send['cb_type']             =   $this->input->input_stream('cb_type', TRUE);

        $send['track_month']             =   $this->input->input_stream('track_month', TRUE);
        $send['track_year']             =   $this->input->input_stream('track_year', TRUE);
        $send['track_date']             =   $this->input->input_stream('track_date', TRUE);

        $send['scheme_id']             =   $this->input->input_stream('scheme_id', TRUE);
        $send['certification_type']             =   $this->input->input_stream('cert_type', TRUE);
        $send['scope']             =   $this->input->input_stream('scope', TRUE);

        $result                   =   $this->Mdt_p_tracksheet->create_new_tracksheet($send);

        echo $result;
    }

//function to update the tracksheet status in the customer master table
    public function update_customer_tracksheet_status()
    {
        $cm_id             =   $this->input->input_stream('cm_id', TRUE);

        $result                   =   $this->Mdt_customer_master_model->update_customer_tracksheet_status($cm_id);
        echo $result;
    }

//function to list all the tracksheet in the database
    public function list_tracksheet()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_tracksheet();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Tracksheets';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_tracksheet';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view tracksheet complete info
    public function view_tracksheet_info()
    {
        #############################################################################
        $data_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;

        $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($data_id);
        
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['database_record']    =   $database_record[0];
        } 

    //to get assigned user details of that tracksheet    
        $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($data_id);
        $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Tracksheet Details';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_tracksheet_info';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to open the assign technical guys for a tracksheet
    public function assign_tech_to_tracksheet_page()
    {
        #############################################################################
        $data_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;

        $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($data_id);
        
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['database_record']    =   $database_record[0];
            $data['users']          =   $this->Mdt_users_model->getRoleUsers(ROLE_TECHNICAL_EMPLOYEE);
        } 
        
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Assign Technical Guys to Tracksheet';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/assign_tech_to_tracksheet_page';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the tracksheets that requires surveillance
    public function list_surveillance_tracksheets()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        
        $get_pageList                   =   $this->Mdt_p_tracksheet->list_surveillance_tracksheets();
        $data['page_details']    =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Surveillance Tracksheets';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_surveillance_tracksheets';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to send notification to the surveillance required clients
    public function send_notification_page()
    {
        $tracksheet_id             =   $this->input->input_stream('tracksheet_id', TRUE);
        $notify_no                 =   $this->input->input_stream('notify_no', TRUE);

        $type                      =   $this->input->input_stream('type', TRUE);
        
    #############################################################################
        $data['tracksheet_id']      = $tracksheet_id;
        $data['notify_no']          = $notify_no;
        $data['type']               = $type;

        $database_record = $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
        $data['database_record'] = $database_record[0];

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Send Notification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/send_notification_page';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to send mail for notifying for surveillance or re-certification
    public function send_mail()
    {
    //sending mail    
        $mail_email = htmlentities($this->input->input_stream('mail_email', TRUE));        
        $mail_subject= htmlentities($this->input->input_stream('mail_subject', TRUE));        
        $mail_body= htmlentities($this->input->input_stream('mail_body', TRUE));
        $mail_header= "From: technical@iascertification.com";

        @mail($mail_email, $mail_subject, $mail_body, $mail_header);

    //updating new_ts_started status and surveillance_notify status of that tracksheet
        $tracksheet_id = $this->input->input_stream('tracksheet_id', TRUE);
        $notify_no = $this->input->input_stream('notify_no', TRUE);

        $type = $this->input->input_stream('type', TRUE);

        $result1 = $this->Mdt_p_tracksheet->update_surveillance_status($tracksheet_id, $notify_no);

    //for case of first notification a new tracksheet should be started automatically with pending status
        if($result1 == 1)
        {
            if($notify_no == 1)
            {
            //starting new tracksheet for surveillance audit with status pending
                $result2 = $this->Mdt_p_tracksheet->create_surv_tracksheet($tracksheet_id, $notify_no);
            }

            if($type == 1)
                redirect(base_url('planning/list_surveillance_tracksheets'));  
            else if($type ==2)
                redirect(base_url('planning/list_re_certification_tracksheet'));  
        }
        else
        {
            if($type == 1)
                redirect(base_url('planning/list_surveillance_tracksheets'));  
            else if($type ==2)
                redirect(base_url('planning/list_re_certification_tracksheet'));  
        }
    }

//function to list the filled application review form
    public function list_filled_app_rev_form()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_app_rev_form();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Application Review Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_app_rev_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the filled applicaiton review form
    public function view_filled_app_rev_form()
    {
        #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;

    //tracksheet info and customer info    
        $database_record                   =   $this->Ldt_app_rev_form->application_review_form($tracksheet_id);
        
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['database_record']    =   $database_record[0];
            $scheme_system = $database_record[0]->scheme_system;    

            $cm_id                      =  $database_record[0]->cm_id;

        //to get all the site addresses of that client
            $data['get_site_records'] = $this->Mdt_customer_site_model->get_site_records($cm_id);        
        
        //general records of the form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);
            
            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];
            
            //anzsic codes records of the form               
                $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                $data['app_rev_anzsic_codes_record']    =   $app_rev_anzsic_codes_record;
            
             //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //specific specific records of the form
                if($scheme_system == "EnMS")
                {
                   $table_name = "ldt_app_rev_enms_qstn";
                }
                else if($scheme_system == "FSMS")
                {
                    $table_name = "ldt_app_rev_fsms_qstn";
                }
                else if($scheme_system == "OHSAS")
                {
                    $table_name = "ldt_app_rev_ohsas_qstn";
                }
                else if($scheme_system == "EMS")
                {
                    $table_name = "ldt_app_rev_ems_qstn";
                }
                else if($scheme_system == "ISMS")
                {
                    $table_name = "ldt_app_rev_isms_qstn";
                }

                if($scheme_system == "EnMS" || $scheme_system == "FSMS" || $scheme_system == "OHSAS" || $scheme_system == "EMS" || $scheme_system == "ISMS")
                {           
                    $app_rev_scheme_secific_query = "SELECT * FROM " . $table_name . " WHERE tracksheet_id = $tracksheet_id";

                    $app_rev_scheme_secific_query_run = $this->db->query($app_rev_scheme_secific_query);
                    $app_rev_scheme_secific_query_record = $app_rev_scheme_secific_query_run->result_array();

                    $data['app_rev_scheme_secific_query_record']    =   $app_rev_scheme_secific_query_record[0]; 
                }   
            }
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Application Review Form View';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_filled_app_rev_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list all the tracksheets for which filling of audit program form is pending
    public function fill_audit_program()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->fill_audit_program();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Fill Audit Program';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/fill_audit_program';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit program form by planning department
    public function planning_audit_program_form()
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

        //general records of the form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);
            
            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];

            //anzsic codes records of the form
                $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);

                $scheme_system = $app_rev_anzsic_codes_record[0]['scheme_system'];
                $anzsic_codes = [];
                foreach ($app_rev_anzsic_codes_record as $key => $value) 
                {
                    array_push($anzsic_codes, "'" . $value['anzsic_code'] . "'");
                }

            //getting the technical expert on the basis of selected anzsic codes
                $get_tech_exp                   =   $this->Ldt_audit_certificate_model->get_tech_exp($anzsic_codes);
                $data['get_tech_exp']    =   $get_tech_exp;        

            //getting the auditors on the basis of selected scheme
                $get_auditors                   =   $this->Ldt_audit_certificate_model->get_auditors($scheme_system);
                $data['get_auditors']    =   $get_auditors;      

            //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                   
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
            }
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Planning Audit Program Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/planning_audit_program_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view filled audit program formby planning department
    public function view_planning_audit_program_form()
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

        //general records of the form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);
            
            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];

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

                //anzsic codes records of the form
                    $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                  
                    $scheme_system = $app_rev_anzsic_codes_record[0]['scheme_system'];
                    $anzsic_codes = [];
                    foreach ($app_rev_anzsic_codes_record as $key => $value) 
                    {
                        array_push($anzsic_codes, "'" . $value['anzsic_code'] . "'");
                    }

                //getting the technical expert on the basis of selected anzsic codes
                    $get_tech_exp                   =   $this->Ldt_audit_certificate_model->get_tech_exp($anzsic_codes);
                    $data['get_tech_exp']    =   $get_tech_exp;        

                //getting the auditors on the basis of selected scheme
                    $get_auditors                   =   $this->Ldt_audit_certificate_model->get_auditors($scheme_system);
                    $data['get_auditors']    =   $get_auditors;      

                //audit team plan records of the form
                    $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                    $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
                }
            }
        }
        
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Planning View Audit Program Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_planning_audit_program_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to send intimation letter to the client
    public function send_intim()
    {
        $stage = intval($this->uri->segment(3));
        $tracksheet_id = $this->uri->segment(4);
        $redirect_stage = $this->uri->segment(5);

    //some defined variables
        $mail_from = "tcsupport@eascertification.com";
        $mail_header = "From: " . $mail_from;

        $client_login_link = "www.act.iasiso.com/customer";      

    //getting general records of tracksheet
        $database_record                   =   $this->Mdt_p_tracksheet->view_tracksheet_info($tracksheet_id);
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $records    =   $database_record[0];
            $scheme_name = $database_record[0]->scheme_name;
            $cb_type = $database_record[0]->cb_type;

            if($cb_type == 1)//EAS
            {
                $sign = "Empowering Assurance Systems Pvt Ltd";
            }
            else
            {
                $sign = "Integrated Assessment Services Pvt Ltd";
            }

            $mail_email = $records->contact_email;            
            
        //etting audit porgram form details of that tracksheet_id
            $get_audit_program_form_records = $this->Ldt_audit_program_form_model->get_audit_program_form_records($tracksheet_id);    

        //getting intimation letter details according to different stages  
            $audit_stage = "Stage 2";
            $audit_date = "0000-00-00";
            $mail_body = "";

            if($stage == 1)//stage 1 audit
            {                                        
                $audit_stage = "Stage 1";
                $audit_date = $get_audit_program_form_records[0]['stage1_date_from'];

                $mail_body = "This is to inform you that we are in receipt of your duly signed contract and will initiate " . $audit_stage . " Audit for " . $scheme_name . " . \nIn this regard, We have planned your " . $audit_stage . " audit on " . $audit_date . " at your premises. The detailed audit plan can be viewed on our client login at " . $client_login_link . ". Please, go-through the same and revert back if any changes is required. \nFor further queries and assistance you can contact office co-ordinator.\n\nThanks and regards.\n" . $sign;
            }
            else if($stage == 2)//stage 2 audit
            {                        
                if($redirect_stage == 2) //RC
                {
                    $audit_stage = "Re-Certification";
                    $audit_date = $get_audit_program_form_records[0]['stage2_date_from'];

                    $mail_body = "This is to inform you that we are in receipt of your duly signed contract and will initiate " . $audit_stage . " Audit for " . $scheme_name . " . \nIn this regard, We have planned your " . $audit_stage . " audit on " . $audit_date . " at your premises. The detailed audit plan can be viewed on our client login at " . $client_login_link . ". Please, go-through the same and revert back if any changes is required. \nFor further queries and assistance you can contact office co-ordinator.\n\nThanks and regards.\n" . $sign;
                }   
                else
                {             
                    $audit_stage = "Stage 2";
                    $audit_date = $get_audit_program_form_records[0]['stage2_date_from'];

                    $mail_body = "Further to the successful completion of your Stage 1 Audit for the for " . $scheme_name . " . \nAs a subsequent activity, we have planned your " . $audit_stage . " audit on " . $audit_date . " at your premises. The detailed audit plan can be viewed on our client login at " . $client_login_link . ". Please, go-through the same and revert back if any changes is required. \nFor further queries and assistance you can contact office co-ordinator.\n\nThanks and regards.\n" . $sign;
                }               
            }
            else if($stage == 3)//Surv 1 audit
            {                                        
                $audit_stage = "Surveillance 1";
                $audit_date = $get_audit_program_form_records[0]['surv1_date_from'];

                $mail_body = "This is to inform you that we are in receipt of your duly signed contract and will initiate " . $audit_stage . " Audit for " . $scheme_name . " . \nIn this regard, We have planned your " . $audit_stage . " audit on " . $audit_date . " at your premises. The detailed audit plan can be viewed on our client login at " . $client_login_link . ". Please, go-through the same and revert back if any changes is required. \nFor further queries and assistance you can contact office co-ordinator.\n\nThanks and regards.\n" . $sign;
            }
            else if($stage == 4)//Surv 2 audit
            {                                        
                $audit_stage = "Surveillance 2";
                $audit_date = $get_audit_program_form_records[0]['surv2_date_from'];

                $mail_body = "This is to inform you that we are in receipt of your duly signed contract and will initiate " . $audit_stage . " Audit for " . $scheme_name . " . \nIn this regard, We have planned your " . $audit_stage . " audit on " . $audit_date . " at your premises. The detailed audit plan can be viewed on our client login at " . $client_login_link . ". Please, go-through the same and revert back if any changes is required. \nFor further queries and assistance you can contact office co-ordinator.\n\nThanks and regards.\n" . $sign;
            }
            else
            {
                $audit_stage = "Stage 1";
                $audit_date = $get_audit_program_form_records[0]['stage1_date_from'];

                $mail_body = "This is to inform you that we are in receipt of your duly signed contract and will initiate " . $audit_stage . " Audit for " . $scheme_name . " . \nIn this regard, We have planned your " . $audit_stage . " audit on " . $audit_date . " at your premises. The detailed audit plan can be viewed on our client login at " . $client_login_link . ". Please, go-through the same and revert back if any changes is required. \nFor further queries and assistance you can contact office co-ordinator.\n\nThanks and regards.\n" . $sign;
            }

        //sending mail
            $mail_subject = "Intimation for " . $audit_stage . " Audit";

            $to_name            =   "Client";                  
            $fromName           =   "Technical";  

            $this->load->helper('send_mail_helper');                        

            // echo "mail_email: " . $mail_email . "<br>";
            // echo "mail_subject: " . $mail_subject . "<br>";
            // echo "mail_body: " . $mail_body . "<br>";
            // echo "mail_header: " . $mail_header . "<br>";
            
            // if(simple_mail($mail_from, $fromName, $mail_email, $to_name, $mail_subject, $mail_body))  
            //     echo "gud";
            // else
            //     echo "bad";        

            //@mail($mail_email, $mail_subject, $mail_body, $mail_header);    
        }

    //updating the intimation status
        $result                   =   $this->Ldt_audit_program_form_model->update_intimation_status($tracksheet_id, $stage);

        if($result == 1 && $stage ==1)
           redirect(base_url('planning/fill_audit_program'));       
        else if($result == 1 && $stage ==2)
        {
            if($redirect_stage == 2)
                redirect(base_url('planning/fill_audit_program_re_cert'));
            else
                redirect(base_url('planning/list_stage1_audit_done_tracksheet'));
        }
        else if($result == 1 && $stage == 3)
            redirect(base_url('planning/list_filled_audit_plan_surv_form'));
        else if($result == 1 && $stage == 4)
            redirect(base_url('planning/list_filled_audit_plan_surv_form'));
    }

//function to list the filled audit plan forms to the planning department
    public function list_filled_audit_plan1_form()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_audit1_plan_form();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Audit Plan 1 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_audit_plan1_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the tracksheets for which audit report 1 has been submitted
    public function list_submitted_audit_report_1()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_submitted_audit_report_1();
        $data['page_details']           =   $get_pageList;

    //to get notify status of reviewer for a level  
        $level = 1;
        $get_notify_status_level_wise =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);
        $data['get_notify_status_level_wise']    =   $get_notify_status_level_wise; 

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Submitted Audit Report 1';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_submitted_audit_report_1';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view page for assigning reviewer to a tracksheet for reviewing their audit report
    public function assign_reviewer_page()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

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
            $scheme_system =   strtolower($database_record[0]->scheme_system);
            $cert_type = $database_record[0]->certification_type;
            $old_tracksheet_id = $database_record[0]->old_tracksheet_id;

            if($level == 3 || $level == 4)
            {                
            //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($old_tracksheet_id);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
            }
            else if($level == 11)
            {
                if($cert_type == 1 OR $cert_type == 4)
                {
                //audit team plan records of the form
                    $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                    $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
                }
                else if($cert_type == 2 OR $cert_type == 3)
                {
                //audit team plan records of the form
                    $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($old_tracksheet_id);
                    $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
                }
            } 
            else
            {
            //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
            }
     
        //getting the auditors on the basis of selected scheme
            $get_auditors                   =   $this->Ldt_audit_certificate_model->get_auditors($scheme_system);
            $data['get_auditors']    =   $get_auditors; 

        //to get notify status of reviewer for a tracksheet  
            $get_notify_status =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status($tracksheet_id);
            $data['get_notify_status']    =   $get_notify_status; 
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Assign Reviewer';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/assign_reviewer_page';
        $this->load->view('main',$data);
        #############################################################################
    }    

//function to load the page to view the audit report 1 by planning department
    public function view_audit_report()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;
        $data["qstn_not_avail"] = 0;

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
            }            
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Audit Report';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_audit_report';
        $this->load->view('main',$data);
        #############################################################################       
    }

//function to render the page to list all the tracksheet for which stage 1 audit has been done
    public function list_stage1_audit_done_tracksheet()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_stage1_audit_done_tracksheet();
        $data['page_details']           =   $get_pageList;

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Stage 1 Audit Done';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_stage1_audit_done_tracksheet';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the filled audit plan forms to the planning department
    public function list_filled_audit_plan2_form()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_audit2_plan_form();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Audit Plan 2 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_audit_plan2_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the tracksheets for which audit report 2 has been submitted
    public function list_submitted_audit_report_2()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_submitted_audit_report_2();
        $data['page_details']           =   $get_pageList;

    //to get notify status of reviewer for a level         
        $level = 2;
        $get_notify_status_level_wise =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);
        $data['get_notify_status_level_wise']    =   $get_notify_status_level_wise; 

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Submitted Audit Report 2';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_submitted_audit_report_2';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list tracksheet for which customer feedback is pending
    public function list_client_feedback()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_client_feedback();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Customer Feedback';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_client_feedback';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to view the client feedback for a tracksheet id and level
    public function view_client_feedback()
    {
        $tracksheet_id = $this->uri->segment(3);
        $userid = $_SESSION['userid'];
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;

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

        //get the client feedback details
            $get_client_feedback = $this->Ldt_client_feedback_model->get_client_feedback($tracksheet_id, $level);
            $data['get_client_feedback']    =   $get_client_feedback; 

        //get the client feedback questions
            $feedback_qstn_records = $this->Mdt_client_feedback_qstns_model->feedback_qstn_records();
            $data['feedback_qstn_records']    =   $feedback_qstn_records;   

        //get the client feedback answers
            $get_client_feedback_anss = $this->Ldt_client_feedback_ans_model->get_client_feedback_anss($tracksheet_id, $level);
            $data['get_client_feedback_anss']    =   $get_client_feedback_anss;    
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Customer Feedback';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'auditor/view_client_feedback';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list all the tracksheets for which re-audit is required
    public function list_re_audit()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_report_summary_model->list_re_audit();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_re_audit';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to view the audit summary of the audit report
    public function view_audit_report_summary()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

        $data['out_of_index'] = 0;        

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
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Audit Report Summary';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_audit_report_summary';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list all the filled re-audit plans
    public function list_filled_re_audit_plans()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_re_audit_plans();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Re-Audit Plans';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_re_audit_plans';
        $this->load->view('main',$data);
    #############################################################################   
    }

//function to view the re-audit plan form
    public function view_re_audit_plan_form()
    {
        #############################################################################
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
                $re_audit_level = 11; //stage1
                $audit_plan_team_plan_records = $this->Ldt_audit_plan_team_plan_model->get_audit_team_plan_records($tracksheet_id, $re_audit_level);
                $data['audit_plan_team_plan_records']    =   $audit_plan_team_plan_records;

            //getting the audit plan process list of that tracksheet
                $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $re_audit_level);
                $data['audit_plan_process_list']    =   $audit_plan_process_list;

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }        
        }            

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Re-Audit Plan Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_re_audit_plan_form';
        $this->load->view('main',$data);
    #############################################################################      
    } 

//function to view the audit plan2 form for a tracksheet
    public function view_audit_plan2_form()
    {
    #############################################################################
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
        }              

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
                $level = 2; //stage 2
                $audit_plan_team_plan_records = $this->Ldt_audit_plan_team_plan_model->get_audit_team_plan_records($tracksheet_id, $level);
                $data['audit_plan_team_plan_records']    =   $audit_plan_team_plan_records;

            //getting the audit plan process list of that tracksheet
                $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                $data['audit_plan_process_list']    =   $audit_plan_process_list;
            }
        }
        
    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Audit Plan 2 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_audit_plan2_form';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to view the audit plan1 form for a tracksheet
    public function view_audit_plan1_form()
    {
    #############################################################################
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
        }              

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

            //getting the audit plan process list of that tracksheet
                $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                $data['audit_plan_process_list']    =   $audit_plan_process_list;
            }
        }
        
    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Audit Plan 1 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_audit_plan1_form';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to list the tracksheets started automatically for surveillance clients
    public function list_s_tracksheet()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_s_tracksheet();
        $data['page_details']           =   $get_pageList;
        
    //function to get the tracksheets for which md approval for withdrawn status is pending
        $list_tracksheets_with_pending_withdrwan_status = $this->Ldt_tracksheet_status_model->list_tracksheets_with_pending_withdrwan_status();
        $data['list_tracksheets_with_pending_withdrwan_status'] = $list_tracksheets_with_pending_withdrwan_status;

    //checking if that tracksheet is eligible for automatically suspended or withdrawn status
        $today = date('Y-m-d');

        $mail_header = "FROM: technical@iascertification.com";

        $results_count = count($get_pageList['results']);
        if($results_count !=0)
        {
            foreach ($get_pageList['results'] as $key => $get_page)
            {
                $tracksheet_id = $get_page->tracksheet_id;
                $status = $get_page->status;
                $cert_date_to = $get_page->cert_date_to;
                $initial_certification_date = $get_page->initial_certification_date;
                
            //getting some basic details for mailing to the client    
                $contact_email = $get_page->contact_email;
                $mail_email = $contact_email;

                $cb_type = $get_page->cb_type;
                if($cb_type == 1)//EAS
                {
                    $sign = "Empowering Assurance Systems Pvt Ltd";
                }
                else
                {
                    $sign = "Integrated Assessment Services Pvt Ltd";
                }               
                
            //performing automatic sactions
                if($status == 2) //if that tracksheet is listed as pending status then adding it in that table and changing its status to suspended
                {                
                    if($today >= $cert_date_to) //that tracksheet is eligible for automatically suspension status
                    {
                        $new_status = 4; //status = suspended

                        $this->Mdt_p_tracksheet->update_tracksheet_status($tracksheet_id, $new_status); //changing its status to suspended  

                    //mailing sunspension letter to the client
                        $this_time = strtotime($today);
                        $this_month = date("F", $this_time);
                        $this_year = date("Y", $this_time);

                        $last_time = $this_time - 2592000;
                        $last_month = date("F", $last_time);
                        $last_year = date("Y", $last_time);
                        
                        $mail_subject = "Suspension Of ISO Certification Status";
                        $mail_body = "This is reference to your previous Surveillance Audit reminder emails on the month of " . $this_month . "' " . $this_year . " & " . $last_month . "' " . $last_year . " to your organization. Since you have not complied with the requirement of the surveillance audit even after repeated request, your ISO 9001 Certification Status stands “suspended” with immediate effect.\nIf you do not complete the surveillance audit within months of this communication, your Certification status will be Withdrawn.\nIf your certification is withdrawn, your Organization name with reference to your certification will be removed from IAS Website (www.iascertification.com), UQAS Accreditation Website (www.uqas.org). You will be advised to stop declaring/advertising your certification status and not to use our logos in any form.\nYour communication to us is highly appreciated to resolve this issue. Please contact this number for further details (9789900430 / 044-26162670).\n\n" . $sign;

                        @mail($mail_email, $mail_subject, $mail_body, $mail_header);                     
                    }
                }
                else if($status == 4)//if that tracksheet is listed as suspended status then changing its status to withdrawn
                {
                    $end_date = strtotime($cert_date_to) + 15897600; // Date, 6 months after certificate expiry date
                    $end_date = date('Y-m-d', $end_date);

                    if($today >= $end_date) //that tracksheet is eligible for automatically withdrawn status
                    {
                        $new_status = 3; //status = withdrawn

                        $this->Mdt_p_tracksheet->update_tracksheet_status($tracksheet_id, $new_status); //changing its status to withdrawn     

                    //mailing sunspension letter to the client
                        $tenth_time = strtotime($today) - 15984000;
                        $tenth_month = date("F", $tenth_time);
                        $tenth_year = date("Y", $tenth_time);

                        $ele_time = $tenth_time - 2592000;
                        $ele_month = date("F", $ele_time);
                        $ele_year = date("Y", $ele_time);

                        $mail_subject = "Cancellation/Withdrawal of ISO 9001: 2015 Certification Status";
                        $mail_body = "This is reference to your ISO certification(S) issued to your organization as per the agreement; you have signed with us, dated " . $initial_certification_date . ". As per the agreement and the Accreditation requirement, you should have completed your surveillance audit before " . $cert_date_to . ".\nWe have sent you two reminder letters in this regard, on 10th Month(" . $ele_month . "' " . $ele_year . ") and 11th month(" . $tenth_month . "' " . $tenth_year . "), requesting you to complete the audit before the stipulated time. Further, we contacted from our office to help you in completing this procedure.\nBut unfortunately, we did not hear any positive reply from your side. Since, you have not complied with the requirement of the Certification & the agreement for very long time, your ISO Certification Status stands cancelled/Withdrawn with immediate effect.\nFurther, you are hereby advised to stop declaring/advertising your certification status, stop using our logos in any form in website, letterhead, visiting cards, brochure, Id cards, Advertisements etc, if you are into and do not claim your organization as “ISO certified”, which may not be appropriate as your organization does not have a valid certification.\nIf you continue to use or misuse or falsely claim IAS and/or accreditation logo, means, you have violated the terms of contract that you have signed with us. IAS will take serious note of such violation may be forced to initiate Legal action against your organization\n\n". $sign;

                        @mail($mail_email, $mail_subject, $mail_body, $mail_header);     
                    }
                }
            }
        }        

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Tracksheet';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_s_tracksheet';
        $this->load->view('main',$data);
    #############################################################################
    }

//funciton to show the edit tracksheet page
    public function edit_tracksheet()
    {
    #############################################################################
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

            $cm_id = $database_record[0]->cm_id;
            $cert_type = $database_record[0]->certification_type;           

            $old_tracksheet_id =  $database_record[0]->old_tracksheet_id;
            
        //to check intimation of changes form is filled or not
            $data['get_changes_records'] = $this->Ldt_intimation_of_changes_model->get_changes_records($tracksheet_id);

        //to get all the site addresses of that client
            $data['get_site_records'] = $this->Mdt_customer_site_model->get_site_records($cm_id);

        //to get the scope details
            $data['old_scope'] = $this->Ldt_scope_of_cert_model->get_scope_of_cert($old_tracksheet_id);
           
        //function to check if this tracksheet has withdrawn MD approval request 
            $withdrawn_md_approval_req = $this->Ldt_tracksheet_status_model->withdrawn_md_approval_req($tracksheet_id);
            $data['withdrawn_md_approval_req'] = $withdrawn_md_approval_req;
        }   

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Edit Tracksheet';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/edit_tracksheet';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to fill the intimation of changes form
    public function fill_initmation_of_changes()
    {
    #############################################################################
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

            $cm_id = $database_record[0]->cm_id;
            $old_tracksheet_id =  $database_record[0]->old_tracksheet_id;
            
        //to get the scope details
            $data['old_scope'] = $this->Ldt_scope_of_cert_model->get_scope_of_cert($old_tracksheet_id);
        }
    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Fill intimation of Changes';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/fill_initmation_of_changes';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to view the intimation of changes form
    public function view_initmation_of_changes()
    {
    #############################################################################
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

            $cm_id = $database_record[0]->cm_id;
            $old_tracksheet_id =  $database_record[0]->old_tracksheet_id;

        //to get intimation of changes form records for a tracksheet_id
            $get_changes_records = $this->Ldt_intimation_of_changes_model->get_changes_records($tracksheet_id);
            
            $get_changes_records_count = count($get_changes_records);
            if($get_changes_records_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['get_changes_records'] = $get_changes_records[0];

                $intimation_of_changes_id = $get_changes_records[0]['id'];

            //to get intimation of changes form sites records for a intimation_of_changes_id
                $data['get_site_changes_records'] = $this->Ldt_intimation_of_changes_sites_model->get_site_changes_records($intimation_of_changes_id);
                
            //to get the scope details
                $data['old_scope'] = $this->Ldt_scope_of_cert_model->get_scope_of_cert($old_tracksheet_id);
            }
           
        }

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View intimation of Changes';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_initmation_of_changes';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to list the filled audit plan surveillance form
    public function list_filled_audit_plan_surv_form()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_audit_plan_surv_form();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Audit Plan Surveillance Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_audit_plan_surv_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the audit plan2 form for a tracksheet
    public function view_audit_plan_surv_form()
    {
    #############################################################################
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
            }
        }
        
    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Audit Plan Surveillance Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_audit_plan_surv_form';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to list the tracksheets for which audit report for surveillance has been submitted
    public function list_submitted_audit_report_surv()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_submitted_audit_report_surv();
        $data['page_details']           =   $get_pageList;

    //to get notify status of reviewer for a level  
        $level = 3;
        $get_notify_status_level_wise1 =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);

        $level = 4;
        $get_notify_status_level_wise2 =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);

        $data['get_notify_status_level_wise']    =  array_merge($get_notify_status_level_wise1, $get_notify_status_level_wise2);

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Submitted Audit Report Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_submitted_audit_report_surv';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list tracksheet for which customer feedback is pending
    public function list_client_feedback_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_on_site1_model->list_client_feedback_surv();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Customer Feedback Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_client_feedback_surv';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list all the tracksheets for which re-audit is required
    public function list_re_audit_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_report_summary_model->list_re_audit_surv();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_re_audit_surv';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list all the filled re-audit plans for surveillance
    public function list_filled_re_audit_plans_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_re_audit_plans_surv();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Re-Audit Plan Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_re_audit_plans_surv';
        $this->load->view('main',$data);
    #############################################################################   
    }

//function to list the tracksheets for which re-certification is required
    public function list_re_certification_tracksheet()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        
        $get_pageList                   =   $this->Mdt_p_tracksheet->list_re_certification_tracksheet();
        $data['page_details']    =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Certification Tracksheets';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_re_certification_tracksheet';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the tracksheets started automatically for surveillance clients
    public function list_re_tracksheet()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_re_tracksheet();
        $data['page_details']           =   $get_pageList;
        
    //function to get the tracksheets for which md approval for withdrawn status is pending
        $list_tracksheets_with_pending_withdrwan_status = $this->Ldt_tracksheet_status_model->list_tracksheets_with_pending_withdrwan_status();
        $data['list_tracksheets_with_pending_withdrwan_status'] = $list_tracksheets_with_pending_withdrwan_status;

    //checking if that tracksheet is eligible for automatically suspended or withdrawn status
        $today = date('Y-m-d');

        echo $results_count = count($get_pageList['results']);
        if($results_count !=0)
        {
            foreach ($get_pageList['results'] as $key => $get_page)
            {
                $tracksheet_id = $get_page->tracksheet_id;
                $status = $get_page->status;
                $cert_date_to = $get_page->cert_date_to;

                if($status == 2) //if that tracksheet is listed as pending status then adding it in that table and changing its status to suspended
                {                
                    if($today >= $cert_date_to) //that tracksheet is eligible for automatically suspension status
                    {
                        $new_status = 4; //status = suspended

                        $this->Mdt_p_tracksheet->update_tracksheet_status($tracksheet_id, $new_status); //changing its status to suspended                    

                        // $result = $this->Ldt_tracksheet_status_model->insert_tracksheet_in_db_automatically($tracksheet_id, $new_status);
                    }
                }
                else if($status == 4)//if that tracksheet is listed as suspended status then changing its status to withdrawn
                {
                    $end_date = strtotime($cert_date_to) + 15897600; // Date, 6 months after certificate expiry date
                    $end_date = date('Y-m-d', $end_date);

                    if($today >= $end_date) //that tracksheet is eligible for automatically withdrawn status
                    {
                        $new_status = 3; //status = withdrawn

                        $this->Mdt_p_tracksheet->update_tracksheet_status($tracksheet_id, $new_status); //changing its status to withdrawn                    

                        // $result = $this->Ldt_tracksheet_status_model->insert_tracksheet_in_db_automatically($tracksheet_id, $new_status);
                    }
                }
            }
        } 

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Tracksheet';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_re_tracksheet';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to list the filled application review form for re-certification tracksheet
    public function list_filled_app_rev_re_cert()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_app_rev_re_cert();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Application Review Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_app_rev_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the filled applicaiton review form
    public function view_filled_app_rev_form_re_cert()
    {
        #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;

    //tracksheet info and customer info    
        $database_record                   =   $this->Ldt_app_rev_form->application_review_form($tracksheet_id);
        
        $database_record_count = count($database_record);
        if($database_record_count == 0)
        {
            $data["out_of_index"] = 1;
        }
        else
        {
            $data['database_record']    =   $database_record[0];
            $scheme_system = $database_record[0]->scheme_system;    

            $cm_id                      =  $database_record[0]->cm_id;

        //to get all the site addresses of that client
            $data['get_site_records'] = $this->Mdt_customer_site_model->get_site_records($cm_id);        
        
        //general records of the form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);
            
            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];
            
            //anzsic codes records of the form               
                $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                $data['app_rev_anzsic_codes_record']    =   $app_rev_anzsic_codes_record;
            
             //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //specific specific records of the form
                if($scheme_system == "EnMS")
                {
                   $table_name = "ldt_app_rev_enms_qstn";
                }
                else if($scheme_system == "FSMS")
                {
                    $table_name = "ldt_app_rev_fsms_qstn";
                }
                else if($scheme_system == "OHSAS")
                {
                    $table_name = "ldt_app_rev_ohsas_qstn";
                }
                else if($scheme_system == "EMS")
                {
                    $table_name = "ldt_app_rev_ems_qstn";
                }
                else if($scheme_system == "ISMS")
                {
                    $table_name = "ldt_app_rev_isms_qstn";
                }

                if($scheme_system == "EnMS" || $scheme_system == "FSMS" || $scheme_system == "OHSAS" || $scheme_system == "EMS" || $scheme_system == "ISMS")
                {           
                    $app_rev_scheme_secific_query = "SELECT * FROM " . $table_name . " WHERE tracksheet_id = $tracksheet_id";

                    $app_rev_scheme_secific_query_run = $this->db->query($app_rev_scheme_secific_query);
                    $app_rev_scheme_secific_query_record = $app_rev_scheme_secific_query_run->result_array();

                    $data['app_rev_scheme_secific_query_record']    =   $app_rev_scheme_secific_query_record[0]; 
                }   
            }
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Filled Application Review Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_filled_app_rev_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list all the tracksheets for which filling of audit program form is pending for re-certification tracksheet
    public function fill_audit_program_re_cert()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->fill_audit_program_re_cert();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Fill Audit Program Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/fill_audit_program_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit program form by planning department
    public function planning_audit_program_form_re_cert()
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

        //general records of the form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);
            
            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];

            //anzsic codes records of the form
                $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);

                $scheme_system = $app_rev_anzsic_codes_record[0]['scheme_system'];
                $anzsic_codes = [];
                foreach ($app_rev_anzsic_codes_record as $key => $value) 
                {
                    array_push($anzsic_codes, "'" . $value['anzsic_code'] . "'");
                }

            //getting the technical expert on the basis of selected anzsic codes
                $get_tech_exp                   =   $this->Ldt_audit_certificate_model->get_tech_exp($anzsic_codes);
                $data['get_tech_exp']    =   $get_tech_exp;        

            //getting the auditors on the basis of selected scheme
                $get_auditors                   =   $this->Ldt_audit_certificate_model->get_auditors($scheme_system);
                $data['get_auditors']    =   $get_auditors;      

            //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                   
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
            }
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Planning Audit Program Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/planning_audit_program_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view filled audit program formby planning department
    public function view_planning_audit_program_form_re_cert()
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

        //general records of the form
            $app_rev_form_record                   =   $this->Ldt_app_rev_form->app_rev_form_record($tracksheet_id);
            
            $app_rev_form_record_count = count($app_rev_form_record);
            if($app_rev_form_record_count == 0)
            {
                $data["out_of_index"] = 1;
            }
            else
            {
                $data['app_rev_form_record']    =   $app_rev_form_record[0];

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

                //anzsic codes records of the form
                    $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
                  
                    $scheme_system = $app_rev_anzsic_codes_record[0]['scheme_system'];
                    $anzsic_codes = [];
                    foreach ($app_rev_anzsic_codes_record as $key => $value) 
                    {
                        array_push($anzsic_codes, "'" . $value['anzsic_code'] . "'");
                    }

                //getting the technical expert on the basis of selected anzsic codes
                    $get_tech_exp                   =   $this->Ldt_audit_certificate_model->get_tech_exp($anzsic_codes);
                    $data['get_tech_exp']    =   $get_tech_exp;        

                //getting the auditors on the basis of selected scheme
                    $get_auditors                   =   $this->Ldt_audit_certificate_model->get_auditors($scheme_system);
                    $data['get_auditors']    =   $get_auditors;      

                //audit team plan records of the form
                    $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                    $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;
                }
            }
        }
        
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Planning View Audit Program Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/view_planning_audit_program_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the filled audit plan form for re-certification tracksheets
    public function list_filled_audit_plan_form_re_cert()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_audit_plan_form_re_cert();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Audit Plan Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_audit_plan_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the audit plan2 form for a tracksheet
    public function view_audit_plan_form_re_cert()
    {
    #############################################################################
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

            $level = 2;
        }              

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
                $audit_plan_team_plan_records = $this->Ldt_audit_plan_team_plan_model->get_audit_team_plan_records($tracksheet_id, $level);
                $data['audit_plan_team_plan_records']    =   $audit_plan_team_plan_records;

            //getting the audit plan process list of that tracksheet
                $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                $data['audit_plan_process_list']    =   $audit_plan_process_list;
            }
        }
        
    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Audit Plan Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_audit_plan_form_re_cert';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to list the tracksheets for which audit report for surveillance has been submitted
    public function list_submitted_audit_report_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_submitted_audit_report_re_cert();
        $data['page_details']           =   $get_pageList;

    //to get notify status of reviewer for a level  
        $level = 2;

        $get_notify_status_level_wise =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);
        $data['get_notify_status_level_wise']    =  $get_notify_status_level_wise;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Submitted Audit Report Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_submitted_audit_report_re_cert';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to list all the re-certification tracksheets for which re-audit is required
    public function list_re_audit_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_report_summary_model->list_re_audit_re_cert();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_re_audit_re_cert';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list all the filled re-audit plans for surveillance
    public function list_filled_re_audit_plan_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_filled_re_audit_plan_re_cert();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Filled Re-Audit Plan Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_filled_re_audit_plan_re_cert';
        $this->load->view('main',$data);
    #############################################################################   
    }

//function to list tracksheets for which re-audit reports has been submitted
    public function list_submitted_re_audit_report()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_submitted_re_audit_report();
        $data['page_details']           =   $get_pageList;

    //to get notify status of reviewer for a level         
        $level = 11;
        $get_notify_status_level_wise =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);
        $data['get_notify_status_level_wise']    =   $get_notify_status_level_wise; 

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Submitted Re-Audit Report';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_submitted_re_audit_report';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list tracksheets for which re-audit reports has been submitted
    public function list_submitted_re_audit_report_re_cert()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_submitted_re_audit_report_re_cert();
        $data['page_details']           =   $get_pageList;

    //to get notify status of reviewer for a level         
        $level = 11;
        $get_notify_status_level_wise =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);

        $data['get_notify_status_level_wise']    = $get_notify_status_level_wise;

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Submitted Re-Audit Report Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_submitted_re_audit_report_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list tracksheets for which re-audit reports has been submitted
    public function list_submitted_re_audit_report_surv()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_submitted_re_audit_report_surv();
        $data['page_details']           =   $get_pageList;

    //to get notify status of reviewer for a level         
        $level = 11;
        $get_notify_status_level_wise =    $this->Ldt_notify_reviewer_about_audit_report_model->get_notify_status_level_wise($level);

        $data['get_notify_status_level_wise']    = $get_notify_status_level_wise;

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Submitted Re-Audit Report Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'planning/list_submitted_re_audit_report_surv';
        $this->load->view('main',$data);
        #############################################################################
    }

}