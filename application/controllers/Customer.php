<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Customer extends MY_Controller
{
	function __construct()
	{ 
		parent::__construct();
        $this->module   =   'customer';
        $this->load->model('pagination_model');
        $this->load->model('Customer_model');
        $this->load->model('Mdt_p_tracksheet');
        $this->load->model('Mdt_customer_site_model');
        $this->load->model('Ldt_audit_plan_notify_to_client_model');
        $this->load->model('Ldt_scope_of_cert_model');
        
        $this->load->model('Mdt_client_feedback_qstns_model');
        $this->load->model('Ldt_client_feedback_model');
        $this->load->model('Ldt_client_feedback_ans_model');
        $this->load->model('Ldt_intimation_of_changes_model');
        $this->load->model('Ldt_intimation_of_changes_sites_model');
        
	}	
	public function Index()
	{   		 
		$this->load->view("customer/login"); 
	}
    //function to login the customer    
	public function verify_login()
	{
        $result                     =   $this->Customer_model->check_user_exists();
        if($result                  ==  'Login Successfull')
        {             
            redirect(base_url('customer/dashboard'));
        }
        else
        {
            redirect(base_url('customer'));
        }
	}
    //function for logging out the logined customer    
    public function logout()
    {
        $this->session->set_flashdata('success_message', 'Sucessfully Logged out.');
        $user_data = $this->session->all_userdata();        
        foreach ($user_data as $key => $value) 
        {
            $this->session->unset_userdata($key);
        }
        //session_destroy();        
        //$this->session->sess_destroy();       
        redirect(base_url('customer'));
    }
    //function to view the dashboard/index page of a customer
    public function dashboard()
    {
        $cm_id = $this->session->userdata('cm_id');
        //print_r($this->session->all_userdata());
        ###########################################################
        $data['title']                  =   'Dashboard';
        $data['subtitle']               =   '';
        $data['view']                   =   'dashboard';
        $this->load->view('customer/home',$data);
        ###########################################################
    }
    //function to list all the audit plans of a client
    public function list_audit_plans()
    {
    #############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        $get_pageList                   =   $this->Customer_model->list_audit_plans();
        $data['page_details']           =   $get_pageList;
    #############################################################################  
        $data['title']                  =   'List Audit Plans';
        $data['subtitle']               =   '';
        $data['view']                   =   'list_audit_plans';
        $this->load->view('customer/home',$data);
    #############################################################################
    }
//function to view the audit plan form by client
    public function view_my_audit_plan()
    {
        $this->load->model('Ldt_audit_program_form_model');
        $this->load->model('Ldt_audit_plan_team_plan_model');
        $this->load->model('Ldt_audit_plan_process_model');
    #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);
        $cm_id = $this->session->userdata('cm_id');
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
       //getting records of the audit program form
            if($level == 3 || $level == 4) //surv 1/2
            {
                $audit_program_form_records                   =   $this->Ldt_audit_program_form_model->get_audit_program_form_records($old_tracksheet_id);
            }
            else
            {
                $audit_program_form_records                   =   $this->Ldt_audit_program_form_model->get_audit_program_form_records($tracksheet_id);
            }               
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
            //to check if that tracksheet belongs to that client or not for a level
                $tracksheet_to_client_relation_for_a_level = $this->Ldt_audit_plan_notify_to_client_model->tracksheet_to_client_relation_for_a_level($tracksheet_id, $cm_id, $level);
                if($tracksheet_to_client_relation_for_a_level == 0)
                    $data["out_of_index"] = 1;
            }
        }
    ####################### Comon page data return #############################
        $data['title']                  =   'View My Audit Plan';
        $data['subtitle']               =   '';
        $data['view']                    = 'view_my_audit_plan';
        $this->load->view('customer/home',$data);
    #############################################################################
    }   
//function to view the re-audit plan form by auditor
    public function view_my_re_audit_plan()
    {
        $this->load->model('Ldt_audit_plan_team_plan_model');
        $this->load->model('Ldt_audit_plan_process_model');
        $this->load->model('Ldt_audit_report_summary_model');
    #############################################################################
        $tracksheet_id = $this->uri->segment(3);
        $cm_id = $this->session->userdata('cm_id');
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
            //getting the audit plan process list of that tracksheet
                $audit_plan_process_list = $this->Ldt_audit_plan_process_model->get_audit_plan_process_list($tracksheet_id, $level);
                $data['audit_plan_process_list']    =   $audit_plan_process_list;
            //to check if that tracksheet belongs to that client or not for a level
                $tracksheet_to_client_relation_for_a_level = $this->Ldt_audit_plan_notify_to_client_model->tracksheet_to_client_relation_for_a_level($tracksheet_id, $cm_id, $level);
                if($tracksheet_to_client_relation_for_a_level == 0)
                    $data["out_of_index"] = 1;
            }
        }
    ####################### Comon page data return #############################
        $data['title']                  =   'View My Re-Audit Plan';
        $data['subtitle']               =   '';
        $data['view']                   = 'view_my_re_audit_plan';
        $this->load->view('customer/home',$data);
    #############################################################################
    }   
//function to list all the tracksheet with nc to a client
    public function list_nc()
    {
    ############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        $get_pageList                   =   $this->Customer_model->list_nc();
        $data['page_details']           =   $get_pageList;
    #############################################################################  
        $data['title']                  =   'List NC';
        $data['subtitle']               =   '';
        $data['view']                   =   'list_nc';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function to view the audit nc of a tracksheet and level
    public function view_audit_nc()
    {
        $this->load->model('Ldt_app_rev_form');
        $this->load->model('Ldt_app_rev_anzsic_codes_model');
        $this->load->model('Ldt_audit_report_nc_model');
        $this->load->model('Ldt_audit_report_nc_comments_model');
        $this->load->model('Ldt_audit_plan_notify_to_auditor_model');
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);
        $cm_id = $this->session->userdata('cm_id');
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
        //getting the questions, answers and other other infos about that question for which NC has been marked for a tracksheet and a level          
            $get_all_infos_of_nc = $this->Ldt_audit_report_nc_model->get_all_infos_of_nc($tracksheet_id, $level);
            $data["get_all_infos_of_nc"] = $get_all_infos_of_nc;
        //getting all comments for that tracksheet_id and level
            $get_audit_nc_comments = $this->Ldt_audit_report_nc_comments_model->get_audit_nc_comments($tracksheet_id, $level);
            $data['get_audit_nc_comments'] = $get_audit_nc_comments;
        //getting all the auditors of a tracksheet and level
            $get_all_auditors = $this->Ldt_audit_plan_notify_to_auditor_model->get_all_auditors($tracksheet_id, $level);
            $data['get_all_auditors'] = $get_all_auditors;
        //to check if that tracksheet belongs to that client or not for a level
            $tracksheet_to_client_relation_for_a_level = $this->Ldt_audit_plan_notify_to_client_model->tracksheet_to_client_relation_for_a_level($tracksheet_id, $cm_id, $level);
            if($tracksheet_to_client_relation_for_a_level == 0)
                $data["out_of_index"] = 1;
        }
    #############################################################################  
        $data['title']                  =   'View Audit NC';
        $data['subtitle']               =   '';
        $data['view']                   =   'view_audit_nc';
        $this->load->view('customer/home',$data);
    ############################################################################# 
    }
//function to add audit report nc comment in databse
    public function add_audit_report_nc_comments_in_db()
    {
        $this->load->model('Ldt_audit_report_nc_comments_model');
        $cm_id = $this->session->userdata('cm_id');
        $data['tracksheet_id'] = $this->input->input_stream('tracksheet_id', TRUE);
        $data['level'] = $this->input->input_stream('level', TRUE);
        $data['nc_id'] = $this->input->input_stream('nc_id', TRUE);
        $data['comment'] = $this->input->input_stream('comment', TRUE);
        $data['commented_by'] = $cm_id;
        $data['comment_type'] = $this->input->input_stream('comment_type', TRUE);
        $data['status'] = 1;
        $result = $this->Ldt_audit_report_nc_comments_model->add_audit_report_nc_comments_in_db($data);
        return $result;
    }
//function to edit some details for a nc_ic
    public function edit_nc_details()
    {
        $this->load->model('Ldt_audit_report_nc_model');
        $id = $this->input->input_stream('id', TRUE);
        $data = $this->input->input_stream('data', TRUE);
        $result = $this->Ldt_audit_report_nc_model->edit_nc_details($id, $data);
        echo $result;
    }
//function to upload corrective actions documents for a nc
    public function upload_corrective_action()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);
        $nc_id = $this->uri->segment(5);
        if($_FILES["file"]["name"] != '')
        {
        //for getting extension of uploaded file
            $test = explode(".", $_FILES["file"]["name"]);
            $image_extension = end($test);
        //setting new name to the pic
            $file_new_name = md5("corr_acc_" . $tracksheet_id . "_" . $level . "_" . $nc_id) . "." . $image_extension;
        //uploading the pic at temp location
            $file_location = 'uploads/audit_report_nc_docs/' . $file_new_name;
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $file_location))
            {
                echo $file_new_name;
            }
            else
            {
                echo "Something went wrong";
            }
        } 
    }
//function to list all the tracksheet with nc to a client
    public function list_scope_of_certi()
    {
    ############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        $get_pageList                   =   $this->Customer_model->list_scope_of_certi();
        $data['page_details']           =   $get_pageList;
    #############################################################################  
        $data['title']                  =   'List Scope of Certification';
        $data['subtitle']               =   '';
        $data['view']                   =   'list_scope_of_certi';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function for viewing scope of certificate to client
    public function view_scope_of_cert()
    {
        $tracksheet_id = $this->uri->segment(3);
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
        //get scope of certification records for that tracksheet
            $get_scope_of_cert = $this->Ldt_scope_of_cert_model->get_scope_of_cert($tracksheet_id);
            $data['get_scope_of_cert']    =   $get_scope_of_cert;    
        }
    #############################################################################  
        $data['title']                  =   'View Scope of Certification';
        $data['subtitle']               =   '';
        $data['view']                   =   'view_scope_of_cert';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function to list all the tracksheet with nc to a client
    public function list_feedback()
    {
    ############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        $get_pageList                   =   $this->Customer_model->list_feedback();
        $data['page_details']           =   $get_pageList;

        $list_filled_feedback_tracksheets = $this->Ldt_client_feedback_model->list_filled_feedback_tracksheets();
        $data['list_filled_feedback_tracksheets'] = $list_filled_feedback_tracksheets;
    #############################################################################  
        $data['title']                  =   'List Feedback';
        $data['subtitle']               =   '';
        $data['view']                   =   'list_feedback';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function to fill the feedback form for a tracksheet_id
    public function fill_feedback()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);
        $cm_id = $this->session->userdata('cm_id');
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
        //get the client feedback questions
            $feedback_qstn_records = $this->Mdt_client_feedback_qstns_model->feedback_qstn_records();
            $data['feedback_qstn_records']    =   $feedback_qstn_records;   
                    
        //to check if that tracksheet belongs to that client or not for a level
            $tracksheet_to_client_relation_for_a_level = $this->Ldt_audit_plan_notify_to_client_model->tracksheet_to_client_relation_for_a_level($tracksheet_id, $cm_id, $level);
            if($tracksheet_to_client_relation_for_a_level == 0)
                $data["out_of_index"] = 1;
        }
    #############################################################################  
        $data['title']                  =   'Fill Feedback';
        $data['subtitle']               =   '';
        $data['view']                   =   'fill_feedback';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }    
//function to insert feedback answers for a tracksheet and id in database
    public function insert_feedback_in_db()
    {
        $tracksheet_id                  = $this->input->input_stream('tracksheet_id', TRUE);
        $level                          = $this->input->input_stream('level', TRUE);
        $feedback_records               = $this->input->input_stream('feedback_records', TRUE);
        $result = $this->Ldt_client_feedback_ans_model->insert_feedback_in_db($tracksheet_id, $level, $feedback_records);
        echo $result;
    }
//function to insert feedback answers for a tracksheet and id in database
    public function insert_tracksheet_feedback()
    {
        $tracksheet_id                  = $this->input->input_stream('tracksheet_id', TRUE);
        $level                          = $this->input->input_stream('level', TRUE);
        $suggestion                     = $this->input->input_stream('suggestion', TRUE);
        $date                           = $this->input->input_stream('date', TRUE);
        $result = $this->Ldt_client_feedback_model->insert_tracksheet_feedback($tracksheet_id, $level, $suggestion, $date);
        echo $result;
    }
//function to view the client feedback for a tracksheet id and level
    public function view_feedback()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);
        $cm_id = $this->session->userdata('cm_id');
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
        //to check if that tracksheet belongs to that client or not for a level
            $tracksheet_to_client_relation_for_a_level = $this->Ldt_audit_plan_notify_to_client_model->tracksheet_to_client_relation_for_a_level($tracksheet_id, $cm_id, $level);
            if($tracksheet_to_client_relation_for_a_level == 0)
                $data["out_of_index"] = 1;
        }
    #############################################################################  
        $data['title']                  =   'View Feedback';
        $data['subtitle']               =   '';
        $data['view']                   =   'view_feedback';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function to list all the tracksheet with nc to a client
    public function list_audit_report_summary()
    {
        
    ############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        $get_pageList                   =   $this->Customer_model->list_audit_report_summary();
        $data['page_details']           =   $get_pageList;
    #############################################################################  
        $data['title']                  =   'List Audit Report Summary';
        $data['subtitle']               =   '';
        $data['view']                   =   'list_audit_report_summary';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function to view the audit report summary for a tracksheet_id and level
    public function view_audit_report_summary()
    {
        $this->load->model('Ldt_audit_report_summary_model');
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);
        $cm_id = $this->session->userdata('cm_id');
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
     //to check if that tracksheet belongs to that client or not for a level
        $tracksheet_to_client_relation_for_a_level = $this->Ldt_audit_plan_notify_to_client_model->tracksheet_to_client_relation_for_a_level($tracksheet_id, $cm_id, $level);
        if($tracksheet_to_client_relation_for_a_level == 0)
            $data["out_of_index"] = 1;
    ######################## Comon page data return #############################
        $data['title']                  =   'View Audit Report Summary';
        $data['subtitle']               =   '';
        $data['view']                   =   'view_audit_report_summary';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function to list the tracksheets for which intimation of changes are required
    public function fill_intimation_of_changes()
    {
    ############################################################################
        $data['searchText']             =   $this->input->input_stream('searchText', TRUE);
        $get_pageList                   =   $this->Customer_model->fill_intimation_of_changes();
        $data['page_details']           =   $get_pageList;
    //to list all the tracksheet for which intimation of changes form has been filled
        $list_filled_inti_filled_tracksheets = $this->Ldt_intimation_of_changes_model->list_filled_inti_filled_tracksheets();
        $data['list_filled_inti_filled_tracksheets'] = $list_filled_inti_filled_tracksheets;
    #############################################################################  
        $data['title']                  =   'Fill Intimation Of Changes';
        $data['subtitle']               =   '';
        $data['view']                   =   'fill_intimation_of_changes';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
//function to fill the intimation of changes form for a tracksheet_id
    public function fill_intimation_of_changes_form()
    {
    #############################################################################
        $tracksheet_id = $this->input->input_stream('tracksheet_id', TRUE);
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
    #############################################################################  
        $data['title']                  =   'Fill Intimation of Changes Form';
        $data['subtitle']               =   '';
        $data['view']                   =   'fill_intimation_of_changes_form';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }    
//function to insert intimation of changes records in the databse
    public function insert_inti_of_changes_records()
    {
        $userid = $this->session->userdata('cm_id');
        $data['tracksheet_id']                          =    $this->input->input_stream('tracksheet_id', TRUE);
        $data['level']                                  =    $this->input->input_stream('level', TRUE);
        $data['new_client_name']                        =    $this->input->input_stream('new_client_name', TRUE);
        $data['new_contact_address']                    =    $this->input->input_stream('new_contact_address', TRUE);
        $data['no_of_emp']                                  =    $this->input->input_stream('no_of_emp', TRUE);
        $data['perma_emp']                                  =    $this->input->input_stream('perma_emp', TRUE);
        $data['temp_sites']                                  =    $this->input->input_stream('temp_sites', TRUE);
        $data['new_scope']                                  =    $this->input->input_stream('new_scope', TRUE);
        $data['any_process_change']                         =    $this->input->input_stream('any_process_change', TRUE);
        $data['remarks']                                  =    $this->input->input_stream('remarks', TRUE);
        $data['added_by']                                  =    $userid;
        $data['added_on']                                  =    date('Y-m-d');
        $data['status']                                  =    1;
        $result = $this->Ldt_intimation_of_changes_model->insert_inti_of_changes_records($data);
        echo $result;
    }
//function to insert intimation of changes sites records in the databse
    public function insert_inti_of_changes_site_records()
    {
        $intimation_of_changes_id                         =    $this->input->input_stream('intimation_of_changes_id', TRUE);
        $site_address_records                             =    $this->input->input_stream('site_address_records', TRUE);
        $result = $this->Ldt_intimation_of_changes_sites_model->insert_inti_of_changes_site_records($intimation_of_changes_id, $site_address_records);
        echo $result;
    }
//function to view the intimation of changes form
    public function view_intimation_of_changes_form()
    {
    #############################################################################
        $tracksheet_id = $this->input->input_stream('tracksheet_id', TRUE);
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
    #############################################################################  
        $data['title']                  =   'View Intimation of Changes Form';
        $data['subtitle']               =   '';
        $data['view']                   =   'view_intimation_of_changes_form';
        $this->load->view('customer/home',$data);
    #############################################################################  
    }
}