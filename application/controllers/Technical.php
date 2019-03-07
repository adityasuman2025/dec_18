<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Technical extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->module   =   'technical';
        $this->load->model('pagination_model');

        $this->load->model('Mdt_customer_master_model');
        $this->load->model('Mdt_customer_site_model');
        
        $this->load->model('Technical_general_model');
        $this->load->model('Sdt_schemes_model');
        
        $this->load->model('Mdt_p_tracksheet');
        $this->load->model('Mdt_assigned_tracksheet_users');

        $this->load->model('Ldt_app_rev_form');
        $this->load->model('Ldt_app_rev_anzsic_codes_model');
        $this->load->model('Ldt_app_rev_audit_team_plan');

        $this->load->model('Ldt_audit_program_form_model');
        $this->load->model('Ldt_audit_prog_process_model');

        $this->load->model('Ldt_audit_plan_form_model');  
        $this->load->model('Ldt_audit_plan_team_plan_model');
        $this->load->model('Ldt_audit_plan_process_model');    

        $this->load->model('Sdt_anzsic_codes');
        $this->load->model('Sdt_audit_process_list_model'); 
        $this->load->model('Sdt_audit_plan_process_list_model'); 

        $this->load->model('Ldt_audit_report_summary_model');

        $this->load->model('Ldt_certi_issue_checklist_model');

        $this->load->model('Ldt_intimation_of_changes_model'); 

        $this->load->model('Ldt_scope_of_cert_model');    

        $this->load->model('Mdt_questionnaire_forms_model');    
        $this->load->model('Mdt_questionnaire_qstns_model');    
        $this->load->model('Ldt_questionnaire_ans_model');    
        

        $this->load->model('Ldt_audit_certificate_model');
        $this->load->model('Mdt_users_model', '', true);
    }

//the dashboard function
    public function dashboard()
    {
        #############################################################################
        
        $data['title']                  =   'Technical Dashboard';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/dashboard';
        $data["hello"] = array("This is dummy Technical Dashboard.", "Chill!!", "Everything gonna be alright.");
        $this->load->view('main',$data);
        #############################################################################
    }  

//function to run any query
    public function query_runner()
    {
        $query            =  $this->input->input_stream('query', TRUE);

        $query_result                   =   $this->Technical_general_model->query_runner($query);
        echo $query_result;     
    }

//function to view the list my tracksheets page
    public function list_my_tracksheet_page()
    {
        $userid = $_SESSION['userid'];

        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_my_tracksheet_page($userid);
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List My Tracksheets';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_my_tracksheet_page';
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
            $data["out_of_index"] = 1;
        else
        {
            $data['database_record']    =   $database_record[0];
        }        

    //get assigned users list for that tracksheet assgigned users
        $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($data_id);
        $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Tracksheet Details';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_tracksheet_info';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the application review required tracksheets of that particular technical employee
    public function application_review()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_app_rev_form->application_review();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Application Review';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/application_review';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the application review for a tracksheet
    public function application_review_form()
    {
        #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;
        
        $database_record                   =   $this->Ldt_app_rev_form->application_review_form($tracksheet_id);
        
        $database_record_count = count($database_record);
        if($database_record_count == 0)
            $data["out_of_index"] = 1;
        else
        {
            $data['database_record']    =   $database_record[0];

            $cm_id                      =  $database_record[0]->cm_id;

        //to get all the site addresses of that client
            $data['get_site_records'] = $this->Mdt_customer_site_model->get_site_records($cm_id);
        }  

    //get assigned users list for that tracksheet assgigned users
        $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
        $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Application Review';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/application_review_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//get anzsic code suggestion
    public function get_anzsic_sugg()
    {
        $scheme_system            =  $this->input->input_stream('scheme_system', TRUE);
        $anzsic_sugg_input            =  $this->input->input_stream('anzsic_sugg_input', TRUE);

        $to_get            =  $this->input->input_stream('to_get', TRUE);

        $result                   =   $this->Sdt_anzsic_codes->get_anzsic_sugg($scheme_system ,$anzsic_sugg_input);
        
        foreach($result as $key1 => $value) 
        {
            $one = $value[$to_get[0]];
            $two = $value[$to_get[1]];
            $three = $value[$to_get[2]];
            $four = $value[$to_get[3]];

            if($scheme_system == "fsms")
            {
                echo "<a class=\"anzsic_sug_val\" val=\"$three\">" . $one . " - " . $two . " - " . $three. " - " . $four . "</a><br/>";
            }
            else if($scheme_system == "enms")
            {
                echo "<a class=\"anzsic_sug_val\" val=\"$one\">" . $one . "</a><br/>";
            }
            else
            {
                $link = $value[$to_get[4]];
                echo "<a link=\"$link\" class=\"anzsic_sug_val\" val=\"$one\">" . $two . " - " . $three. " - " . $four . "</a><br/>";
            }            
        } 
    }

//function to get auditors and technical expert suggestions
    public function get_auditor_sugg()
    {
        $scheme_system            =  $this->input->input_stream('scheme_system', TRUE);
        $anzsic_codes            =  $this->input->input_stream('anzsic_codes', TRUE);
        
        $result                   =   $this->Ldt_audit_certificate_model->get_auditor_sugg($scheme_system ,$anzsic_codes);

        foreach ($result as $key => $value) 
        {
            $auditor_id = $value['ac_id'];
            $anzsic_code = $value['anzsic_code'];
            $auditor_name = $value['username'];

            echo "<option value=\"$auditor_id\">" . $auditor_name . " - " . $anzsic_code . "</option>";
        }
    }    

//function to insert general app rev form record into the database
    public function insert_app_rev_gen_record()
    {
    //variables    
        $tracksheet_id            =  $this->input->input_stream('tracksheet_id', TRUE);
        $cm_id            =  $this->input->input_stream('cm_id', TRUE);
        $application_form_id            =  $this->input->input_stream('application_form_id', TRUE);
        $address_review_date            =  $this->input->input_stream('address_review_date', TRUE);
        $site1_review_date            =  $this->input->input_stream('site1_review_date', TRUE);
        $site2_review_date            =  $this->input->input_stream('site2_review_date', TRUE);
        $site3_review_date            =  $this->input->input_stream('site3_review_date', TRUE);
        $scope            =  $this->input->input_stream('scope', TRUE);
        $assesment_standard            =  $this->input->input_stream('assesment_standard', TRUE); //scheme_system
        $assesment_type            =  $this->input->input_stream('assesment_type', TRUE);
        $scope_clear            =  $this->input->input_stream('scope_clear', TRUE);
       
        $total_emp_as_per            =  $this->input->input_stream('total_emp_as_per', TRUE);
        $perma_emp            =  $this->input->input_stream('perma_emp', TRUE);
        $part_emp            =  $this->input->input_stream('part_emp', TRUE);
        $contract_lab            =  $this->input->input_stream('contract_lab', TRUE);
        $temp_skill_un_worker            =  $this->input->input_stream('temp_skill_un_worker', TRUE);
        $total_eff_emp            =  $this->input->input_stream('total_eff_emp', TRUE);
        $just_for_eff_pers            =  $this->input->input_stream('just_for_eff_pers', TRUE);
       
        $no_of_sites            =  $this->input->input_stream('no_of_sites', TRUE);
        $repetitiveness            =  $this->input->input_stream('repetitiveness', TRUE);
        $complexity_level            =  $this->input->input_stream('complexity_level', TRUE);
        $scope_size            =  $this->input->input_stream('scope_size', TRUE);
        $site_remarks            =  $this->input->input_stream('site_remarks', TRUE);
        $accr_ava_as_req            =  $this->input->input_stream('accr_ava_as_req', TRUE);
        $applicant_lang            =  $this->input->input_stream('applicant_lang', TRUE);
        
        $statuary_applicable            =  $this->input->input_stream('statuary_applicable', TRUE);
        $safety_req            =  $this->input->input_stream('safety_req', TRUE);
        $threat_impart            =  $this->input->input_stream('threat_impart', TRUE);
        $no_surv_audit_plan            =  $this->input->input_stream('no_surv_audit_plan', TRUE);
        $stage1_man_days            =  $this->input->input_stream('stage1_man_days', TRUE);
        $stage2_man_days            =  $this->input->input_stream('stage2_man_days', TRUE);

        $surv1_man_days            =  $this->input->input_stream('surv1_man_days', TRUE);
        $surv2_man_days            =  $this->input->input_stream('surv2_man_days', TRUE);
        $oth_reas_inc_tym            =  $this->input->input_stream('oth_reas_inc_tym', TRUE);
        $oth_reas_desc_tym            =  $this->input->input_stream('oth_reas_desc_tym', TRUE);
        $tym_change_warning            =  $this->input->input_stream('tym_change_warning', TRUE);
        $reviewed_by_name            =  $this->input->input_stream('reviewed_by_name', TRUE);
        $apporved_by_name            =  $this->input->input_stream('apporved_by_name', TRUE);
        $reviewed_by_date            =  $this->input->input_stream('reviewed_by_date', TRUE);
        $apporved_by_date            =  $this->input->input_stream('apporved_by_date', TRUE);

    //running process
        $result = $this->Ldt_app_rev_form->insert_app_rev_gen_record($tracksheet_id, $cm_id, $application_form_id, $address_review_date, $site1_review_date, $site2_review_date, $site3_review_date, $scope, $assesment_standard, $assesment_type, $scope_clear, $total_emp_as_per, $perma_emp, $part_emp, $contract_lab, $temp_skill_un_worker, $total_eff_emp, $just_for_eff_pers, $no_of_sites, $repetitiveness, $complexity_level, $scope_size, $site_remarks, $accr_ava_as_req, $applicant_lang, $statuary_applicable, $safety_req, $threat_impart, $no_surv_audit_plan, $stage1_man_days, $stage2_man_days, $surv1_man_days, $surv2_man_days, $oth_reas_inc_tym, $oth_reas_desc_tym, $tym_change_warning, $reviewed_by_name, $apporved_by_name, $reviewed_by_date, $apporved_by_date);

        return $result;       
    }

//function to insert scheme specific app rev form records into the database
    public function insert_app_rev_scheme_specific_record()
    {
        $qstn_query    =  $this->input->input_stream('qstn_query', TRUE);
        $scheme_system    =  $this->input->input_stream('scheme_system', TRUE);
       
        if($scheme_system == "enms")
        {
           $table_name = "ldt_app_rev_enms_qstn";
        }
        else if($scheme_system == "fsms")
        {
            $table_name = "ldt_app_rev_fsms_qstn";
        }
        else if($scheme_system == "ohsas")
        {
            $table_name = "ldt_app_rev_ohsas_qstn";
        }
        else if($scheme_system == "ems")
        {
            $table_name = "ldt_app_rev_ems_qstn";
        }
        else if($scheme_system == "isms")
        {
            $table_name = "ldt_app_rev_isms_qstn";
        }

        if($qstn_query != "")
        {
            $query = "INSERT INTO " . $table_name . " " . $qstn_query;
            $query_result                   =   $this->Technical_general_model->query_runner($query);
            echo $query_result;  
        }
    }

//function to insert anzsic codes records of the application review form into the database
    public function insert_app_rev_anzsic_codes_record()
    {
        $app_rev_form_id    =  $this->input->input_stream('app_rev_form_id', TRUE);
        $tracksheet_id    =  $this->input->input_stream('tracksheet_id', TRUE);
        $scheme_system    =  $this->input->input_stream('scheme_system', TRUE); //scheme_system
        $anzsic_codes    =  $this->input->input_stream('anzsic_codes', TRUE);

        $result = $this->Ldt_app_rev_anzsic_codes_model->insert_app_rev_anzsic_codes_record($app_rev_form_id, $tracksheet_id, $scheme_system, $anzsic_codes);
    }

//function to insert audit team plan records of the application review form into the database
    public function insert_app_rev_audit_team_plan_record()
    {
        $app_rev_form_id    =  $this->input->input_stream('app_rev_form_id', TRUE);
        $tracksheet_id    =  $this->input->input_stream('tracksheet_id', TRUE);
        $level    =  $this->input->input_stream('level', TRUE);
        $auditor_id    =  $this->input->input_stream('auditor_id', TRUE);
        $type    =  $this->input->input_stream('auditor_type', TRUE);

        $date_from = "";
        $date_to = "";
        $sector = 1;

        $result = $this->Ldt_app_rev_audit_team_plan->insert_in_app_rev_audit_team_plan($tracksheet_id, $app_rev_form_id, $level, $auditor_id, $type, $date_from, $date_to, $sector);
    }

//function to view the application review form for a tracksheet
    public function view_application_review_form()
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
            $cm_id                      =  $database_record[0]->cm_id;

        //to get all the site addresses of that client
            $data['get_site_records'] = $this->Mdt_customer_site_model->get_site_records($cm_id);
        }        
        
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
            $scheme_system = $app_rev_form_record[0]->assesment_standard;

        //anzsic codes records of the form
            $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
            $data['app_rev_anzsic_codes_record']    =   $app_rev_anzsic_codes_record;
        
        //audit team plan records of the form
            $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
            $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

        //specific specific records of the form
            if($scheme_system == "enms")
            {
               $table_name = "ldt_app_rev_enms_qstn";
            }
            else if($scheme_system == "fsms")
            {
                $table_name = "ldt_app_rev_fsms_qstn";
            }
            else if($scheme_system == "ohsas")
            {
                $table_name = "ldt_app_rev_ohsas_qstn";
            }
            else if($scheme_system == "ems")
            {
                $table_name = "ldt_app_rev_ems_qstn";
            }
            else if($scheme_system == "isms")
            {
                $table_name = "ldt_app_rev_isms_qstn";
            }

            if($scheme_system == "enms" || $scheme_system == "fsms" || $scheme_system == "ohsas" || $scheme_system == "ems" || $scheme_system == "isms")
            {           
                $app_rev_scheme_secific_query = "SELECT * FROM " . $table_name . " WHERE tracksheet_id = $tracksheet_id";

                $app_rev_scheme_secific_query_run = $this->db->query($app_rev_scheme_secific_query);
                $app_rev_scheme_secific_query_record = $app_rev_scheme_secific_query_run->result_array();

                $data['app_rev_scheme_secific_query_record']    =   $app_rev_scheme_secific_query_record[0]; 
            }

        //get assigned users list for that tracksheet assgigned users
            $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
            $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;    
        }            

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Application Review Form View';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_application_review_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the dated audit program form belonging to that technical guy
    public function technical_list_dated_audit_program_form()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_program_form_model->technical_list_dated_audit_program_form();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Dated Audit Program Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/technical_list_dated_audit_program_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit program form by technical guy
    public function technical_audit_program_form()
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
        if($audit_program_form_records){
                    $data['audit_program_form_records']    =   $audit_program_form_records[0];
        }
        else{
            $data['audit_program_form_records']             =array();
        }
        //getting the default audit program process list of that scheme system
            $default_audit_program_process = $this->Sdt_audit_process_list_model->get_default_process_list($scheme_system);
            $data['default_audit_program_process']    =   $default_audit_program_process;

        //audit team plan records of the form
            $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
            $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

        //get assigned users list for that tracksheet assgigned users
            $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
            $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Technical Audit Program Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/technical_audit_program_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the audit program form by the technical guy
    public function technical_view_audit_program_form()
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

            //getting the audit program process list of that tracksheet
                $audit_prog_process_list = $this->Ldt_audit_prog_process_model->get_audit_prog_process_list($tracksheet_id);
                $data['audit_prog_process_list']    =   $audit_prog_process_list;

            //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Technical Audit Program Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/technical_view_audit_program_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the tracksheet for which audit plan 1 is pending
    public function audit_plan1()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_plan_form_model->audit_plan1();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Plan 1';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan1';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit plan1 form for a tracksheet
    public function audit_plan1_form()
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
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan__stage_records($tracksheet_id, $level);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //getting audit plan process default records for that scheme system of that tracksheet
                $default_audit_plan_process = $this->Sdt_audit_plan_process_list_model->get_default_process_list($scheme_system, $level);
                $data['default_audit_plan_process']    =   $default_audit_plan_process;

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }
        }

        ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit Plan 1 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan1_form';
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

//function to list the tracksheet for which audit plan 2 is pending
    public function audit_plan2()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_plan_form_model->audit_plan2();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Plan 2';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan2';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit plan2 form for a tracksheet
    public function audit_plan2_form()
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
                $level = 2; //stage1
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan__stage_records($tracksheet_id, $level);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //getting audit plan process default records for that scheme system of that tracksheet
                $default_audit_plan_process = $this->Sdt_audit_plan_process_list_model->get_default_process_list($scheme_system, $level);
                $data['default_audit_plan_process']    =   $default_audit_plan_process;

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }
        }

        ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit Plan 2 Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan2_form';
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
                $level = 2; //stage1
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

//function to list all the tracksheets for which re-audit is required
    public function list_re_audit()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

    //getting the audit report summary
        $get_pageList                   =   $this->Ldt_audit_report_summary_model->tech_list_re_audit();
        $data['page_details']           =   $get_pageList;

    //listing all the audit plans having level of re-audit(level =11)
        $level = 11;
        $listing_audit_plan_for_level                   =   $this->Ldt_audit_plan_form_model->listing_audit_plan_for_level($level);
        $data['listing_audit_plan_for_level']           =   $listing_audit_plan_for_level;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_re_audit';
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
        $data['view']  = 'technical/view_audit_report_summary';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to fill the re-audit plan form
    public function re_audit_plan_form()
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
                if($level == 3 || $level == 4)
                {
                    $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan__stage_records($old_tracksheet_id, $level);
                }
                else if($level = 2)
                {
                    $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan__stage_records($tracksheet_id, $level);
                }
                                  
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //getting audit plan process default records for that scheme system of that tracksheet
                $re_audit_level = 11;
                $re_scheme_system = 'any';
                $default_audit_plan_process = $this->Sdt_audit_plan_process_list_model->get_default_process_list($re_scheme_system, $re_audit_level);
                $data['default_audit_plan_process']    =   $default_audit_plan_process;

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }        
        }            

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Re-Audit Plan Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/re_audit_plan_form';
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

//function to list the tracksheet for which certificate issue checklist is pending
    public function list_certi_issue_checklist()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_certi_issue_checklist();
        $data['page_details']           =   $get_pageList;

    //getting the certificate issue checklist data 
        $level = 2;
        $data['certi_issue_records'] = $this->Ldt_certi_issue_checklist_model->certi_issue_records($level);

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Certificate Issue Checklist';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_certi_issue_checklist';
        $this->load->view('main',$data);
    #############################################################################   
    }

//function to fill the certificate issue checklist for a tracksheet
    public function fill_certi_issue_checklist()
    {
        $tracksheet_id = $this->uri->segment(3);
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

            //for getting the list of technical cords
                $data['tech_cords']          =   $this->Mdt_users_model->getRoleUsers(ROLE_TECHNICAL_EMPLOYEE);

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Fill Certificate Issue Checklist';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/fill_certi_issue_checklist';
        $this->load->view('main',$data);
    #############################################################################  
    }    

//function to view the filled certificate issue checklist for a tracksheet 
    public function view_certi_issue_checklist()
    {
        $tracksheet_id = $this->uri->segment(3);
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
                $data['get_certi_issue_checklist_records']    =   $get_certi_issue_checklist_records[0];

            //for getting the list of technical cords
                $tech_cord = $get_certi_issue_checklist_records[0]['tech_cord'];
                $data['tech_cords']          =   $this->Mdt_users_model->get_user_details_by_id((int)$tech_cord);

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }       
        }

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Certificate Issue Checklist';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_certi_issue_checklist';
        $this->load->view('main',$data);
    #############################################################################  
    }      

//function to list tracksheets for which printing and notifiying to account section is pending
    public function list_certificate()
    {
     #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_certificate();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Certificate';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_certificate';
        $this->load->view('main',$data);
    #############################################################################  
    }

//function to list the tracksheet for which audit plan surveillance is pending
    public function audit_plan_surv()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_plan_form_model->audit_plan_surv();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Plan Surveillance';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan_surv';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit plan surveillance form for a tracksheet
    public function audit_plan_surv_form()
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
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan__stage_records($old_tracksheet_id, $level);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //getting audit plan process default records for that scheme system of that tracksheet
                $default_audit_plan_process = $this->Sdt_audit_plan_process_list_model->get_default_process_list($scheme_system, 2); //2 becuz in surveillance audit same audit as stage 2 is followed
                $data['default_audit_plan_process']    =   $default_audit_plan_process;

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($old_tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }
        }

        ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit Plan Surveillance Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan_surv_form';
        $this->load->view('main',$data);
        #############################################################################
    }

//page to list the tracksheets for which change of scope is proposed by client
    public function list_change_of_scope_req()
    {
     #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Mdt_p_tracksheet->list_change_of_scope_req();
        $data['page_details']           =   $get_pageList;

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Change Of Scope Request';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_change_of_scope_req';
        $this->load->view('main',$data);
    #############################################################################
    }    

//page to list the tracksheets for which change of scope is proposed by client
    public function change_of_scope_req()
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
        }        

    //to check intimation of changes form is filled or not
        $get_changes_records = $this->Ldt_intimation_of_changes_model->get_changes_records($tracksheet_id);
        $data['get_changes_records'] = $get_changes_records[0];

    //to get the scope details
        $data['old_scope'] = $this->Ldt_scope_of_cert_model->get_scope_of_cert($old_tracksheet_id);

    //get assigned users list for that tracksheet assgigned users
        $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
        $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Change Of Scope Request';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/change_of_scope_req';
        $this->load->view('main',$data);
    #############################################################################
    }  

//function to list all the tracksheets for which re-audit is required
    public function list_re_audit_surv()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

    //getting the audit report summary
        $get_pageList                   =   $this->Ldt_audit_report_summary_model->tech_list_re_audit_surv();
        $data['page_details']           =   $get_pageList;

    //listing all the audit plans having level of re-audit(level =11)
        $level = 11;
        $listing_audit_plan_for_level                   =   $this->Ldt_audit_plan_form_model->listing_audit_plan_for_level($level);
        $data['listing_audit_plan_for_level']           =   $listing_audit_plan_for_level;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_re_audit_surv';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list the re-certification tracksheet for which applicartion review is pending
    public function app_rev_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

    //getting the audit report summary
        $get_pageList                   =   $this->Ldt_app_rev_form->app_rev_re_cert();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Application Review Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/app_rev_re_cert';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to fill the application review form for a recertification tracksheet
    public function app_rev_form_re_cert()
    {
    #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $data['out_of_index'] = 0;
        
        $database_record                   =   $this->Ldt_app_rev_form->application_review_form($tracksheet_id);
        
        $database_record_count = count($database_record);
        if($database_record_count == 0)
            $data["out_of_index"] = 1;
        else
        {
            $data['database_record']    =   $database_record[0];

            $cm_id                      =  $database_record[0]->cm_id;
            $old_tracksheet_id          =  $database_record[0]->old_tracksheet_id;

        //to get all the site addresses of that client
            $data['get_site_records'] = $this->Mdt_customer_site_model->get_site_records($cm_id);

        //general records of the form
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

            //anzsic codes records of the form
                $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($old_tracksheet_id);
                $data['app_rev_anzsic_codes_record']    =   $app_rev_anzsic_codes_record;
            
            //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($old_tracksheet_id);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //specific specific records of the form
                if($scheme_system == "enms")
                {
                   $table_name = "ldt_app_rev_enms_qstn";
                }
                else if($scheme_system == "fsms")
                {
                    $table_name = "ldt_app_rev_fsms_qstn";
                }
                else if($scheme_system == "ohsas")
                {
                    $table_name = "ldt_app_rev_ohsas_qstn";
                }
                else if($scheme_system == "ems")
                {
                    $table_name = "ldt_app_rev_ems_qstn";
                }
                else if($scheme_system == "isms")
                {
                    $table_name = "ldt_app_rev_isms_qstn";
                }

                if($scheme_system == "enms" || $scheme_system == "fsms" || $scheme_system == "ohsas" || $scheme_system == "ems" || $scheme_system == "isms")
                {           
                    $app_rev_scheme_secific_query = "SELECT * FROM " . $table_name . " WHERE tracksheet_id = $tracksheet_id";

                    $app_rev_scheme_secific_query_run = $this->db->query($app_rev_scheme_secific_query);
                    $app_rev_scheme_secific_query_record = $app_rev_scheme_secific_query_run->result_array();

                    $data['app_rev_scheme_secific_query_record']    =   $app_rev_scheme_secific_query_record[0]; 
                }
            }
        }  

    //get assigned users list for that tracksheet assgigned users
        $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
        $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Application Review Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/app_rev_form_re_cert';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to view the application review form for a re-certification tracaksheet
    public function view_app_rev_form_re_cert()
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
            $cm_id                      =  $database_record[0]->cm_id;

        //to get all the site addresses of that client
            $data['get_site_records'] = $this->Mdt_customer_site_model->get_site_records($cm_id);
        }        
        
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
            $scheme_system = $app_rev_form_record[0]->assesment_standard;

        //anzsic codes records of the form
            $app_rev_anzsic_codes_record = $this->Ldt_app_rev_anzsic_codes_model->app_rev_anzsic_code_record($tracksheet_id);
            $data['app_rev_anzsic_codes_record']    =   $app_rev_anzsic_codes_record;
        
        //audit team plan records of the form
            $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
            $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

        //specific specific records of the form
            if($scheme_system == "enms")
            {
               $table_name = "ldt_app_rev_enms_qstn";
            }
            else if($scheme_system == "fsms")
            {
                $table_name = "ldt_app_rev_fsms_qstn";
            }
            else if($scheme_system == "ohsas")
            {
                $table_name = "ldt_app_rev_ohsas_qstn";
            }
            else if($scheme_system == "ems")
            {
                $table_name = "ldt_app_rev_ems_qstn";
            }
            else if($scheme_system == "isms")
            {
                $table_name = "ldt_app_rev_isms_qstn";
            }

            if($scheme_system == "enms" || $scheme_system == "fsms" || $scheme_system == "ohsas" || $scheme_system == "ems" || $scheme_system == "isms")
            {           
                $app_rev_scheme_secific_query = "SELECT * FROM " . $table_name . " WHERE tracksheet_id = $tracksheet_id";

                $app_rev_scheme_secific_query_run = $this->db->query($app_rev_scheme_secific_query);
                $app_rev_scheme_secific_query_record = $app_rev_scheme_secific_query_run->result_array();

                $data['app_rev_scheme_secific_query_record']    =   $app_rev_scheme_secific_query_record[0]; 
            }

        //get assigned users list for that tracksheet assgigned users
            $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
            $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;    
        }   

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Application Review Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_app_rev_form_re_cert';
        $this->load->view('main',$data);
    #############################################################################
    }

//function to list the dated audit program form of re-certification tracksheet belonging to that technical guy
    public function technical_list_dated_audit_program_form_re_cert()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_program_form_model->technical_list_dated_audit_program_form_re_cert();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Dated Audit Program Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/technical_list_dated_audit_program_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit program form by technical guy
    public function technical_audit_program_form_re_cert()
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

            $data['audit_program_form_records']    =   $audit_program_form_records[0];

        //getting the default audit program process list of that scheme system
            $default_audit_program_process = $this->Sdt_audit_process_list_model->get_default_process_list($scheme_system);
            $data['default_audit_program_process']    =   $default_audit_program_process;

        //audit team plan records of the form
            $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
            $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

        //get assigned users list for that tracksheet assgigned users
            $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
            $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Technical Audit Program Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/technical_audit_program_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to view the audit program form by the technical guy
    public function technical_view_audit_program_form_re_cert()
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

            //getting the audit program process list of that tracksheet
                $audit_prog_process_list = $this->Ldt_audit_prog_process_model->get_audit_prog_process_list($tracksheet_id);
                $data['audit_prog_process_list']    =   $audit_prog_process_list;

            //audit team plan records of the form
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan_records($tracksheet_id);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //get assigned users list for that tracksheet assgigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }
        }

        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Technical View Audit Program Form';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/technical_view_audit_program_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list the tracksheet for which audit plan surveillance is pending
    public function audit_plan_re_cert()
    {
        #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

        $get_pageList                   =   $this->Ldt_audit_plan_form_model->audit_plan_re_cert();
        $data['page_details']           =   $get_pageList;
        ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit Plan Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to fill the audit plan form for a re-certification tracksheet 
    public function audit_plan_form_re_cert()
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
                $app_rev_audit_team_record = $this->Ldt_app_rev_audit_team_plan->get_audit_team_plan__stage_records($tracksheet_id, $level);
                $data['app_rev_audit_team_record']    =   $app_rev_audit_team_record;

            //getting audit plan process default records for that scheme system of that tracksheet
                $default_audit_plan_process = $this->Sdt_audit_plan_process_list_model->get_default_process_list($scheme_system, $level); //2 becuz in re-certification audit same audit as stage 2 is followed
                $data['default_audit_plan_process']    =   $default_audit_plan_process;

            //get assigned users list for that tracksheet assigned users
                $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
                $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
            }
        }

        ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Audit Plan Form Re-Certification';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/audit_plan_form_re_cert';
        $this->load->view('main',$data);
        #############################################################################
    }

//function to list all the re-certification tracksheets for which re-audit is required
    public function list_re_audit_re_cert()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

    //getting the audit report summary
        $get_pageList                   =   $this->Ldt_audit_report_summary_model->tech_list_re_audit_re_cert();
        $data['page_details']           =   $get_pageList;

    //listing all the audit plans having level of re-audit(level =11)
        $level = 11;
        $listing_audit_plan_for_level                   =   $this->Ldt_audit_plan_form_model->listing_audit_plan_for_level($level);
        $data['listing_audit_plan_for_level']           =   $listing_audit_plan_for_level;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Re-Audit';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_re_audit_re_cert';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list all the re-certification tracksheets for which re-audit is required
    public function list_audit_reports()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

    //getting the audit report summary
        $get_pageList                   =   $this->Mdt_p_tracksheet->list_audit_reports();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Audit Reports';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_audit_reports';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list all the re-certification tracksheets for which re-audit is required
    public function view_audit_report()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

        $userid = $_SESSION['userid'];        

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
        
        //get assigned users list for that tracksheet assigned users
            $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
            $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;
        }
        
    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'View Audit Report';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/view_audit_report';
        $this->load->view('main',$data);
    #############################################################################      
    }

//function to list all the tracksheets for which scope of cert request has been made
    public function list_scope_of_cert_req()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);

    //getting the audit report summary
        $get_pageList                   =   $this->Mdt_p_tracksheet->list_scope_of_cert_req();
        $data['page_details']           =   $get_pageList;

    ######################## Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'List Scope of Certification Request';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/list_scope_of_cert_req';
        $this->load->view('main',$data);
    #############################################################################      
    }

//page to list the tracksheets for which change of scope is proposed by client
    public function scope_of_cert_req()
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
        }        

    //to get the scope details
        $get_scope_of_cert = $this->Ldt_scope_of_cert_model->get_scope_of_cert($tracksheet_id);
        $data['get_scope_of_cert'] = $get_scope_of_cert[0];

    //if no any scope of cert request has been made to the technical team
        $tech_accept_req = $get_scope_of_cert[0]['tech_accept_req'];
        if($tech_accept_req != 1)
        {
            $data["out_of_index"] = 1;
        }

    //get assigned users list for that tracksheet assgigned users
        $get_assigned_users_of_tracksheet                   =   $this->Mdt_assigned_tracksheet_users->get_assigned_users_of_tracksheet($tracksheet_id);
        $data['get_assigned_users_of_tracksheet']    =   $get_assigned_users_of_tracksheet;

    ####################### Comon page data return #############################
        $data['relatedpageLink']        =   array();
        $data['title']                  =   'Scope of Certification Request';
        $data['subtitle']               =   '';
        $data['module']                 =   $this->module;
        $data['view']  = 'technical/scope_of_cert_req';
        $this->load->view('main',$data);
    #############################################################################
    } 
} 	 
?>