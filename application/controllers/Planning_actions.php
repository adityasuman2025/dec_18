<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Planning_actions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->module   =   'planning_actions';
        $this->load->model('pagination_model');

        $this->load->model('Mdt_customer_master_model');
        $this->load->model('Mdt_customer_site_model');
        
        $this->load->model('Mdt_p_tracksheet');
        $this->load->model('Mdt_assigned_tracksheet_users');        
        
        $this->load->model('Ldt_app_rev_audit_team_plan');

        $this->load->model('Ldt_audit_program_form_model');

        $this->load->model('Ldt_audit_plan_form_model');  
        $this->load->model('Ldt_audit_plan_team_plan_model');
        $this->load->model('Ldt_audit_plan_process_model');    
        $this->load->model('Ldt_audit_plan_notify_to_auditor_model');    
        $this->load->model('Ldt_audit_plan_notify_to_client_model');    
        $this->load->model('Ldt_notify_reviewer_about_audit_report_model');

        $this->load->model('Ldt_audit_report_summary_model');

        $this->load->model('Ldt_intimation_of_changes_model');
        $this->load->model('Ldt_intimation_of_changes_sites_model'); 

        $this->load->model('Ldt_tracksheet_status_model'); 
                           
        $this->load->model('Mdt_users_model', '', true);
    }

//function to assign the users to the tracksheet and storing data into the database
    public function assign_users_to_tracksheet()
    {
        $tracksheet_id             =   $this->input->input_stream('tracksheet_id', TRUE);
        $type                      =   $this->input->input_stream('type', TRUE);
        $user_ids                  =   $this->input->input_stream('user_ids', TRUE);        

    //assigning users to that tracksheet
        $this->Mdt_assigned_tracksheet_users->assign_users_to_tracksheet($tracksheet_id, $type, $user_ids);  

    //updating the tech_emp_assigned status of that tracksheet     
        $this->Mdt_p_tracksheet->update_tech_emp_assigned_status($tracksheet_id);           
    }

//function to list all the tracksheet flow
    public function get_flow_form_page_view($i, $tracksheet_id)
    {
        if($i == 1)
            return base_url(). "planning/view_filled_app_rev_form/" . $tracksheet_id;
        else if($i == 2)
            return base_url(). "planning/view_planning_audit_program_form/" . $tracksheet_id;
        else if($i == 3)
            return base_url(). "planning/view_audit_plan1_form/" . $tracksheet_id;
        else if($i == 4) //audit on-site 1
            return base_url(). "planning/view_audit_report/" . $tracksheet_id . '/1';
        else if($i == 5)
             return "#";
        else if($i == 6)
             return "#";
        else if($i == 7)
             return "#";
        else if($i == 8)
            return base_url(). "planning/view_audit_plan2_form/" . $tracksheet_id;
        else if($i == 9) //audit on-site 2
            return base_url(). "planning/view_audit_report/" . $tracksheet_id . '/2';
        else if($i == 10)
             return "#";
        else if($i == 11)
             return "#";
        else if($i == 12)
            return "#";
        else if($i == 13)            
            return "#";
        else if($i == 14)
            return "#";
        else
            return "#";
    }

    public function get_flow_form_page($i, $tracksheet_id)
    {
        if($i == 1)
            return "#";
        else if($i == 2)
            return base_url(). "planning/planning_audit_program_form/" . $tracksheet_id;
        else if($i == 3)
             return "#";
        else if($i == 4) //audit on-site 1
            return "#";
        else if($i == 5)
             return "#";
        else if($i == 6)
             return "#";
        else if($i == 7)
             return "#";
        else if($i == 8)
            return "#";
        else if($i == 9) //audit on-site 2
            return "#";
        else if($i == 10)
             return "#";
        else if($i == 11)
             return "#";
        else if($i == 12)
            return "#";       
        else if($i == 13)            
            return "#";
        else if($i == 14)
            return "#";
        else
            return "#";
    }

    public function list_tracksheet_flow()
    {
        $tracksheet_id        =  $this->input->input_stream('tracksheet_id', TRUE);
        $flow_status          =  $this->input->input_stream('flow_status', TRUE);
        $cert_type            =  $this->input->input_stream('cert_type', TRUE);

        $result               =  $this->Mdt_p_tracksheet->list_tracksheet_flow();

        $i = 0;
        $def_color_red = "#ff1a1a";
        $def_color_gr = "#00cc00"; 
        $def_color_grey = "grey"; 

        echo "<div class=\"col-md-3 col-sl-12 col-xs-12\">  
                <ul class=\"nav nav-stacked\" id=\"flow_one\">";
                    echo "<h4 class=\"box-title\">Phase 2</h4>";
                    for($i; $i<2; $i++)
                    {
                        $color = $def_color_red;
                        $link = "#";
                        if($i < $flow_status-1)
                        {
                            $color = $def_color_gr;
                            $link = $this->get_flow_form_page_view($i+1, $tracksheet_id);
                        }
                        else
                        {
                            $color = $def_color_red;
                            $link = $this->get_flow_form_page($i+1, $tracksheet_id);
                        }

                        if($cert_type == 2 OR $cert_type ==3)
                            $color = $def_color_grey;

                        echo "<li style=\"background-color: " . $color . "; \"><a href=\"$link\"><span>" . $result[$i]['tsf_name']. "</span></a></li>";
                    }

        echo "  </ul>
            </div>";

        echo "<div class=\"col-md-3 col-sl-12 col-xs-12\">                        
                <ul class=\"nav nav-stacked\" id=\"flow_one\">";

                    echo "<h4 class=\"box-title\">Phase 2</h4>";
                    for($i; $i<7; $i++)
                    {
                        $color = $def_color_red;
                        $link = "#";
                        if($i < $flow_status-1)
                        {
                            $color = $def_color_gr;
                            $link = $this->get_flow_form_page_view($i+1, $tracksheet_id);
                        }
                        else
                        {
                            $color = $def_color_red;
                            $link = $this->get_flow_form_page($i+1, $tracksheet_id);
                        }

                        if($cert_type == 2 OR $cert_type ==3)
                            $color = $def_color_grey;

                        echo "<li style=\"background-color: " . $color . "; \"><a href=\"$link\"><span>" . $result[$i]['tsf_name']. "</span></a></li>";
                    }

        echo "  </ul>
            </div>";

        echo "<div class=\"col-md-3 col-sl-12 col-xs-12\">                        
                <ul class=\"nav nav-stacked\" id=\"flow_one\">";

                    echo "<h4 class=\"box-title\">Phase 3</h4>";
                    for($i; $i<12; $i++)
                    {
                        $color = $def_color_red;
                        $link = "#";
                        if($i < $flow_status-1)
                        {
                            $color = $def_color_gr;
                            $link = $this->get_flow_form_page_view($i+1, $tracksheet_id);
                        }
                        else
                        {
                            $color = $def_color_red;
                            $link = $this->get_flow_form_page($i+1, $tracksheet_id);
                        }

                        echo "<li style=\"background-color: " . $color . "; \"><a href=\"$link\"><span>" . $result[$i]['tsf_name']. "</span></a></li>";
                    }

        echo "  </ul>
            </div>";

        echo "<div class=\"col-md-3 col-sl-12 col-xs-12\">                        
                <ul class=\"nav nav-stacked\" id=\"flow_one\">";

                    echo "<h4 class=\"box-title\">Phase 4</h4>";
                    for($i; $i<14; $i++)
                    {
                        $color = $def_color_red;
                        $link = "#";
                        if($i < $flow_status-1)
                        {
                            $color = $def_color_gr;
                            $link = $this->get_flow_form_page_view($i+1, $tracksheet_id);
                        }
                        else
                        {
                            $color = $def_color_red;
                            $link = $this->get_flow_form_page($i+1, $tracksheet_id);
                        }

                        echo "<li style=\"background-color: " . $color . "; \"><a href=\"$link\"><span>" . $result[$i]['tsf_name']. "</span></a></li>";
                    }

        echo "  </ul>
            </div>";
    }

//function to update from date and to date in app_rev_audit_team_plan
    public function update_date_in_app_rev_audit_team_plan()
    {
        $row_id             =   $this->input->input_stream('row_id', TRUE);
        $date_from             =   $this->input->input_stream('date_from', TRUE);
        $date_to             =   $this->input->input_stream('date_to', TRUE);

        $result = $this->Ldt_app_rev_audit_team_plan->update_date_in_app_rev_audit_team_plan($row_id, $date_from, $date_to);
        echo $result;
    }

//function to insert into app_rev_audit_team_plan
    public function insert_in_app_rev_audit_team_plan()
    {
        $tracksheet_id             =   $this->input->input_stream('tracksheet_id', TRUE);
        $app_rev_form_id             =   $this->input->input_stream('app_rev_form_id', TRUE);
        $level             =   $this->input->input_stream('level', TRUE);
        $auditor_id             =   $this->input->input_stream('auditor_id', TRUE);
        $type             =   $this->input->input_stream('type', TRUE);
        $sector             =   $this->input->input_stream('sector', TRUE);

        $date_from             =   $this->input->input_stream('date_from', TRUE);
        $date_to             =   $this->input->input_stream('date_to', TRUE);

        $result = $this->Ldt_app_rev_audit_team_plan->insert_in_app_rev_audit_team_plan($tracksheet_id, $app_rev_form_id, $level, $auditor_id, $type, $date_from, $date_to, $sector);
        echo $result;
    }

//function to insert data into dtabase of audit program form by planning department
    public function insert_data_in_audit_program_form_planning()
    {
        $tracksheet_id             =   $this->input->input_stream('tracksheet_id', TRUE);
        $cm_id             =   $this->input->input_stream('cm_id', TRUE);
        $no_of_sites             =   $this->input->input_stream('no_of_sites', TRUE);

        $stage1_auditor_sector             =   $this->input->input_stream('stage1_auditor_sector', TRUE);
        $stage1_date_from             =   $this->input->input_stream('stage1_date_from', TRUE);
        $stage1_date_to             =   $this->input->input_stream('stage1_date_to', TRUE);

        $stage2_auditor_sector             =   $this->input->input_stream('stage2_auditor_sector', TRUE);
        $stage2_date_from             =   $this->input->input_stream('stage2_date_from', TRUE);
        $stage2_date_to             =   $this->input->input_stream('stage2_date_to', TRUE);

        $surv1_auditor_sector             =   $this->input->input_stream('surv1_auditor_sector', TRUE);
        $surv1_date_from             =   $this->input->input_stream('surv1_date_from', TRUE);
        $surv1_date_to             =   $this->input->input_stream('surv1_date_to', TRUE);

        $surv2_auditor_sector             =   $this->input->input_stream('surv2_auditor_sector', TRUE);
        $surv2_date_from             =   $this->input->input_stream('surv2_date_from', TRUE);
        $surv2_date_to             =   $this->input->input_stream('surv2_date_to', TRUE);

        $result = $this->Ldt_audit_program_form_model->insert_data_in_audit_program_form_planning($tracksheet_id, $cm_id, $no_of_sites, $stage1_auditor_sector, $stage1_date_from, $stage1_date_to, $stage2_auditor_sector, $stage2_date_from, $stage2_date_to, $surv1_auditor_sector, $surv1_date_from, $surv1_date_to, $surv2_auditor_sector, $surv2_date_from, $surv2_date_to);
        echo $result;
    }

//function to update data into dtabase of audit program form by planning department
    public function update_data_in_audit_program_form_planning()
    {
        $audit_prog_form_id             =   $this->input->input_stream('audit_prog_form_id', TRUE);

        $stage1_auditor_sector             =   $this->input->input_stream('stage1_auditor_sector', TRUE);
        $stage1_date_from             =   $this->input->input_stream('stage1_date_from', TRUE);
        $stage1_date_to             =   $this->input->input_stream('stage1_date_to', TRUE);

        $stage2_auditor_sector             =   $this->input->input_stream('stage2_auditor_sector', TRUE);
        $stage2_date_from             =   $this->input->input_stream('stage2_date_from', TRUE);
        $stage2_date_to             =   $this->input->input_stream('stage2_date_to', TRUE);

        $surv1_auditor_sector             =   $this->input->input_stream('surv1_auditor_sector', TRUE);
        $surv1_date_from             =   $this->input->input_stream('surv1_date_from', TRUE);
        $surv1_date_to             =   $this->input->input_stream('surv1_date_to', TRUE);

        $surv2_auditor_sector             =   $this->input->input_stream('surv2_auditor_sector', TRUE);
        $surv2_date_from             =   $this->input->input_stream('surv2_date_from', TRUE);
        $surv2_date_to             =   $this->input->input_stream('surv2_date_to', TRUE);

        $result = $this->Ldt_audit_program_form_model->update_data_in_audit_program_form_planning($audit_prog_form_id, $stage1_auditor_sector, $stage1_date_from, $stage1_date_to, $stage2_auditor_sector, $stage2_date_from, $stage2_date_to, $surv1_auditor_sector, $surv1_date_from, $surv1_date_to, $surv2_auditor_sector, $surv2_date_from, $surv2_date_to);
        echo $result;
    }

//function to delete a specified row from ldt_app_rev_audit_team_plan table
    public function delete_row_in_app_rev_audit_team_plan()
    {
        $row_id             =   $this->input->input_stream('row_id', TRUE);

        $result = $this->Ldt_app_rev_audit_team_plan->delete_row_in_app_rev_audit_team_plan($row_id);
        echo $result;
    }

//functon to send notification to auditors about the audit plan
    public function notify_auditors_about_audit_plan()
    {
        $level = $this->uri->segment(3);
        $tracksheet_id = $this->uri->segment(4);

        $redirect_level = $this->uri->segment(5);

    //to get all the distinct auditors related with that audit plan
        $distinct_auditors_result = $this->Ldt_audit_plan_team_plan_model->get_distinct_auditors_of_audit_plan($tracksheet_id, $level);

        $auditor_ids = [];

        foreach ($distinct_auditors_result as $key => $value) 
        {
            array_push($auditor_ids, $value['auditor_id']);
        }
        
    //to notify auditors about the audit plan
        $result = $this->Ldt_audit_plan_notify_to_auditor_model->notify_auditors_about_audit_plan($tracksheet_id, $level, $auditor_ids);

        if($result == 1)
        {
        //updating sent status of the audit plan for a level of a tracksheet
            $to_update = "sent_to_auditor";
            $value = 1;

            $sent_result = $this->Ldt_audit_plan_form_model->update_audit_plan_sent_status($tracksheet_id, $level, $to_update, $value);
        
            if($sent_result == 1 && $level == 1)
                redirect(base_url('planning/list_filled_audit_plan1_form'), 'refresh');
            else if($sent_result == 1 && $level == 2)
            {
                if($redirect_level == 2)
                    redirect(base_url('planning/list_filled_audit_plan_form_re_cert'), 'refresh');
                else
                    redirect(base_url('planning/list_filled_audit_plan2_form'), 'refresh');
            } 
            else if($sent_result == 1 && ($level == 3 || $level == 4))
                redirect(base_url('planning/list_filled_audit_plan_surv_form'), 'refresh');
            else if($sent_result == 1 && $level == 11)
            {
                if($redirect_level == 3)
                    redirect(base_url('planning/list_filled_re_audit_plans_surv'), 'refresh');
                else if($redirect_level == 2)
                    redirect(base_url('planning/list_filled_re_audit_plan_re_cert'), 'refresh');
                else
                    redirect(base_url('planning/list_filled_re_audit_plans'), 'refresh');
            }
            else    
                echo "something went wrong";
        }
        else
            echo "something went wrong";
    }

//functon to send notification to auditors about the audit plan
    public function notify_client_about_audit_plan()
    {
        $level = $this->uri->segment(3);
        $tracksheet_id = $this->uri->segment(4);
        $cm_id = $this->uri->segment(5);

        $redirect_level = $this->uri->segment(6);

    //to notify client about the audit plan
        $result = $this->Ldt_audit_plan_notify_to_client_model->notify_client_about_audit_plan($tracksheet_id, $level, $cm_id);

        if($result == 1)
        {
        //updating sent status of the audit plan for a level of a tracksheet
            $to_update = "sent_to_client";
            $value = 1;

            $sent_result = $this->Ldt_audit_plan_form_model->update_audit_plan_sent_status($tracksheet_id, $level, $to_update, $value);
        
            if($sent_result == 1 && $level == 1)
                redirect(base_url('planning/list_filled_audit_plan1_form'), 'refresh');
            else if($sent_result == 1 && $level == 2)
            {
                if($redirect_level == 2)
                    redirect(base_url('planning/list_filled_audit_plan_form_re_cert'), 'refresh');
                else
                    redirect(base_url('planning/list_filled_audit_plan2_form'), 'refresh');
            }
            else if($sent_result == 1 && ($level == 3 || $level == 4))
                redirect(base_url('planning/list_filled_audit_plan_surv_form'), 'refresh');
            else if($sent_result == 1 && $level == 11)
            {
                if($redirect_level == 3)
                    redirect(base_url('planning/list_filled_re_audit_plans_surv'), 'refresh');
                else if($redirect_level == 2)
                    redirect(base_url('planning/list_filled_re_audit_plan_re_cert'), 'refresh');
                else
                    redirect(base_url('planning/list_filled_re_audit_plans'), 'refresh');
            }
            else    
                echo "something went wrong";
        }
        else
            echo "something went wrong";
    }

//function to notify reviewer team about the submitted audit report
    public function notify_reviewer_about_audit_report()
    {
        $level                          =    $this->input->input_stream('level', TRUE);
        $tracksheet_id                  =    $this->input->input_stream('tracksheet_id', TRUE);
        $auditor_ids                    =    $this->input->input_stream('auditor_ids', TRUE);

        $result = $this->Ldt_notify_reviewer_about_audit_report_model->notify_reviewer_about_audit_report($tracksheet_id, $level, $auditor_ids);

        echo $result;
    }

//function to notify technical about re-audit
    public function notify_to_technical_about_re_audit()
    {
        $id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

        $redirect_level = $this->uri->segment(5);

        $result = $this->Ldt_audit_report_summary_model->notify_to_technical_about_re_audit($id);

        if($level == 2)
        {
            if($redirect_level == 2)
                redirect(base_url('planning/list_re_audit_re_cert'), 'refresh');            
            else
                redirect(base_url('planning/list_re_audit'), 'refresh');   
        }
        else if($level == 3 ||4)
            redirect(base_url('planning/list_re_audit_surv'), 'refresh');            
    }

//function to update re-audit date for that report summary
    public function update_re_audit_date()
    {
        $row_id                          =    $this->input->input_stream('row_id', TRUE);
        $re_audit_date                  =    $this->input->input_stream('re_audit_date', TRUE);

        $result = $this->Ldt_audit_report_summary_model->update_re_audit_date($row_id, $re_audit_date);

        echo $result;
    }    

//function to edit client details
    public function edit_client_details()
    {
        $cm_id                          =    $this->input->input_stream('cm_id', TRUE);

        $client_name                  =    $this->input->input_stream('client_name', TRUE);
        $contact_address                  =    $this->input->input_stream('contact_address', TRUE);
        $scope                  =    $this->input->input_stream('scope', TRUE);

        $result = $this->Mdt_customer_master_model->edit_client_details($cm_id, $client_name, $contact_address, $scope);

        echo $result;
    }  

//function to delete client site
    public function delete_client_site()
    {
        $site_id                          =    $this->input->input_stream('site_id', TRUE);

        $result = $this->Mdt_customer_site_model->delete_client_site($site_id);
        echo $result;
    }

//function to delete client site
    public function edit_client_site()
    {
        $site_id                          =    $this->input->input_stream('site_id', TRUE);
        $site_address                          =    $this->input->input_stream('site_address', TRUE);
        
        $result = $this->Mdt_customer_site_model->edit_client_site($site_id, $site_address);
        echo $result;
    }

//function to add new client site
    public function add_client_site()
    {
        $cm_id                          =    $this->input->input_stream('cm_id', TRUE);
        $site_address                          =    $this->input->input_stream('site_address', TRUE);
        
        $result = $this->Mdt_customer_site_model->add_client_site($cm_id, $site_address);
        echo $result;
    }

//function to edit scope for a tracksheet
    public function edit_tracksheet_scope()
    {
        $tracksheet_id                          =    $this->input->input_stream('tracksheet_id', TRUE);
        $scope                          =    $this->input->input_stream('scope', TRUE);
        
        $result = $this->Mdt_p_tracksheet->edit_tracksheet_scope($tracksheet_id, $scope);
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

//function to insert intimation of changes records in the databse
    public function insert_inti_of_changes_records()
    {
        $userid = $_SESSION['userid'];

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

//function to notify technical of scope of change in intimation of changes
    public function notify_technical_of_scope_change()
    {
        $id                         =    $this->input->input_stream('id', TRUE);
       
        $result = $this->Ldt_intimation_of_changes_model->notify_technical_of_scope_change($id);

        echo $result;
    }

//function to insert a tracksheet into ldt_tracksheet_status table for MD approval
    public function insert_tracksheet_for_md_approval_for_status()
    {
        $tracksheet_id                         =    $this->input->input_stream('tracksheet_id', TRUE);

        $status                                =    $this->input->input_stream('status', TRUE);
        $remarks                               =    $this->input->input_stream('remarks', TRUE);

        $result = $this->Ldt_tracksheet_status_model->insert_tracksheet_for_md_approval_for_status($tracksheet_id, $status, $remarks);

        echo $result;
    }
}
?>