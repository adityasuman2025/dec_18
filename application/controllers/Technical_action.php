<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Technical_action extends MY_Controller
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

        $this->load->model('Ldt_app_rev_form');
        $this->load->model('Ldt_app_rev_audit_team_plan');
        
        $this->load->model('Ldt_audit_program_form_model');
        $this->load->model('Ldt_audit_prog_process_model');

        $this->load->model('Ldt_audit_plan_form_model');
        $this->load->model('Ldt_audit_plan_team_plan_model');
        $this->load->model('Ldt_audit_plan_process_model');

        $this->load->model('Ldt_certi_issue_checklist_model');

        $this->load->model('Ldt_intimation_of_changes_model');
        $this->load->model('Ldt_scope_of_cert_model'); 
        
        $this->load->model('Mdt_users_model', '', true);
    }

//function to get list of schemes according to the selected scheme type
    public function get_scheme_list()
    {
        $scheme_type            =  $this->input->input_stream('scheme_type', TRUE);

        $query_result                  =   $this->Sdt_schemes_model->get_scheme_list($scheme_type);        
       
        foreach ($query_result as $key => $value) 
        {
            $scheme_id = $value['scheme_id'];
            $scheme_name = $value['scheme_name'];

            echo "<option value=\"$scheme_id\">" . $scheme_name . "</option>";
        }
    }  

//function to list all the tracksheet flow
    public function get_flow_form_page_view($i, $tracksheet_id)
    {
        if($i == 1)
            return base_url(). "technical/view_application_review_form/" . $tracksheet_id;
        else if($i == 2)
            return base_url(). "technical/technical_view_audit_program_form/" . $tracksheet_id;
        else if($i == 3)
            return base_url(). "technical/view_audit_plan1_form/" . $tracksheet_id;
        else if($i == 4) //audit on-site 1
            return "#";
        else if($i == 5)
             return "#";
        else if($i == 6)
             return "#";
        else if($i == 7)
             return "#";
        else if($i == 8)
            return base_url(). "technical/view_audit_plan2_form/" . $tracksheet_id;
        else if($i == 9) //audit on-site 2
            return "#";
        else if($i == 10)
             return "#";
        else if($i == 11)
             return "#";
        else if($i == 12)
            return base_url(). "technical/view_certi_issue_checklist/" . $tracksheet_id;        
        else if($i == 13)            
            return base_url(). "technical/view_certificate/" . $tracksheet_id;  
        else if($i == 14)
            return "#";
        else
            return "#";
    }

    public function get_flow_form_page($i, $tracksheet_id)
    {
        if($i == 1)
            return base_url(). "technical/application_review_form/" . $tracksheet_id;
        else if($i == 2)
            return base_url(). "technical/technical_audit_program_form/" . $tracksheet_id;
        else if($i == 3)
            return base_url(). "technical/audit_plan1_form/" . $tracksheet_id;
        else if($i == 4) //audit on-site 1
            return "#";
        else if($i == 5)
             return "#";
        else if($i == 6)
             return "#";
        else if($i == 7)
             return "#";
        else if($i == 8)
            return base_url(). "technical/audit_plan2_form/" . $tracksheet_id;
        else if($i == 9) //audit on-site 2
            return "#";
        else if($i == 10)
             return "#";
        else if($i == 11)
             return "#";
        else if($i == 12)
            return base_url(). "technical/fill_certi_issue_checklist/" . $tracksheet_id;        
        else if($i == 13)            
            return base_url(). "technical/view_certificate/" . $tracksheet_id;  
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

                        if($cert_type == 4)
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

//function to update the application review form
    public function update_app_rev_form()
    {
        $scheme_system = $this->input->post('scheme_system');  //no need to update assessment standard // or scheme_system        
        $tracksheet_id        =   $this->input->post('tracksheet_id');

        $cert_type        =   $this->input->post('cert_type');

    //general app rev form records
        $insArray['address_review_date']        =   $this->input->post('address_review_date');
        $insArray['scope']        =   $this->input->post('scope');
        $insArray['scope_clear']        =   $this->input->post('scope_clear');
        $insArray['total_emp_as_per']        =   $this->input->post('total_emp_as_per');
        $insArray['perma_emp']        =   $this->input->post('perma_emp');
        $insArray['part_emp']        =   $this->input->post('part_emp');
        $insArray['contract_lab']        =   $this->input->post('contract_lab');
        $insArray['temp_skill_un_worker']        =   $this->input->post('temp_skill_un_worker');
        $insArray['total_eff_emp']        =   $this->input->post('total_eff_emp');

        $insArray['just_for_eff_pers']        =   $this->input->post('just_for_eff_pers');
        $insArray['no_of_sites']        =   $this->input->post('no_of_sites');
        $insArray['repetitiveness']        =   $this->input->post('repetitiveness');
        $insArray['complexity_level']        =   $this->input->post('complexity_level');
        $insArray['scope_size']        =   $this->input->post('scope_size');
        $insArray['site_remarks']        =   $this->input->post('site_remarks');
        $insArray['accr_ava_as_req']        =   $this->input->post('accr_ava_as_req');
        $insArray['applicant_lang']        =   $this->input->post('applicant_lang');
        $insArray['statuary_applicable']        =   $this->input->post('statuary_applicable');
        $insArray['safety_req']        =   $this->input->post('safety_req');
        $insArray['threat_impart']        =   $this->input->post('threat_impart');

        $insArray['no_surv_audit_plan']        =   $this->input->post('no_surv_audit_plan');
        $insArray['stage1_man_days']        =   $this->input->post('stage1_man_days');
        $insArray['stage2_man_days']        =   $this->input->post('stage2_man_days');
        $insArray['surv1_man_days']        =   $this->input->post('surv1_man_days');
        $insArray['surv2_man_days']        =   $this->input->post('surv2_man_days');
     
        $insArray['oth_reas_inc_tym']        =   $this->input->post('oth_reas_inc_tym');
        $insArray['oth_reas_desc_tym']        =   $this->input->post('oth_reas_desc_tym');     
        $insArray['tym_change_warning']        =   $this->input->post('tym_change_warning');
        $insArray['reviewed_by_name']        =   $this->input->post('reviewed_by_name');
        $insArray['apporved_by_name']        =   $this->input->post('apporved_by_name');     
        $insArray['reviewed_by_date']        =   $this->input->post('reviewed_by_date');
        $insArray['apporved_by_date']        =   $this->input->post('apporved_by_date');
      
        $this->Ldt_app_rev_form->update_app_rev_gen_record($insArray, $tracksheet_id);

    //scheme secific qstns
        $schemeSpecific = [];
        if($scheme_system == "enms")
        {
            $schemeSpecific['enms_eff_perso']        =   $this->input->post('enms_eff_perso');
            $schemeSpecific['enms_fec']        =   $this->input->post('enms_fec');     
            $schemeSpecific['enms_fec_comp_factor']        =   $this->input->post('enms_fec_comp_factor');
            $schemeSpecific['enms_fes']        =   $this->input->post('enms_fes');
            $schemeSpecific['enms_fes_comp_factor']        =   $this->input->post('enms_fes_comp_factor');     
            $schemeSpecific['enms_fseu']        =   $this->input->post('enms_fseu');
            $schemeSpecific['enms_fseu_comp_factor']        =   $this->input->post('enms_fseu_comp_factor');  

            $this->Ldt_app_rev_form->update_ldt_app_rev_enms_qstn($schemeSpecific, $tracksheet_id);
        }
        else if($scheme_system == "fsms")
        {
            $schemeSpecific['no_of_pro_line']        =   $this->input->post('no_of_pro_line');
            $schemeSpecific['season_of_prod']        =   $this->input->post('season_of_prod');     
            $schemeSpecific['specific_issue']        =   $this->input->post('specific_issue');
            $schemeSpecific['td_man_days']        =   $this->input->post('td_man_days');
            $schemeSpecific['th_man_days']        =   $this->input->post('th_man_days');     
            $schemeSpecific['tms_man_days']        =   $this->input->post('tms_man_days');
            $schemeSpecific['tfte_man_days']        =   $this->input->post('tfte_man_days'); 

            $this->Ldt_app_rev_form->update_ldt_app_rev_fsms_qstn($schemeSpecific, $tracksheet_id);
        }
        else if($scheme_system == "ohsas")
        {
            $schemeSpecific['dang_good_expl']        =   $this->input->post('dang_good_expl');
            $schemeSpecific['dang_good_score']        =   $this->input->post('dang_good_score');     
            $schemeSpecific['veh_intr_remarks']        =   $this->input->post('veh_intr_remarks');
            $schemeSpecific['veh_intr_score']        =   $this->input->post('veh_intr_score');
            $schemeSpecific['powered_plant_remarks']        =   $this->input->post('powered_plant_remarks');     
            $schemeSpecific['powered_plant_score']        =   $this->input->post('powered_plant_score');
            $schemeSpecific['other_plant_remarks']        =   $this->input->post('other_plant_remarks'); 
            $schemeSpecific['other_plant_score']        =   $this->input->post('other_plant_score');
            $schemeSpecific['manual_handling_remarks']        =   $this->input->post('manual_handling_remarks');     
            $schemeSpecific['manual_handling_score']        =   $this->input->post('manual_handling_score');
            $schemeSpecific['hazardous_subs_remarks']        =   $this->input->post('hazardous_subs_remarks');
            $schemeSpecific['hazardous_subs_score']        =   $this->input->post('hazardous_subs_score');     
            $schemeSpecific['atmospheric_contam_remakrs']        =   $this->input->post('atmospheric_contam_remakrs');
            $schemeSpecific['atmospheric_contam_score']        =   $this->input->post('atmospheric_contam_score'); 
            $schemeSpecific['ionis_rad_remarks']        =   $this->input->post('ionis_rad_remarks');
            $schemeSpecific['ionis_rad_score']        =   $this->input->post('ionis_rad_score'); 
           
            $schemeSpecific['confined_space_remarks']        =   $this->input->post('confined_space_remarks');
            $schemeSpecific['confined_space_score']        =   $this->input->post('confined_space_score');     
            $schemeSpecific['slips_trips_remarks']        =   $this->input->post('slips_trips_remarks');
            $schemeSpecific['slips_trips_score']        =   $this->input->post('slips_trips_score');
            $schemeSpecific['noise_remarks']        =   $this->input->post('noise_remarks');     
            $schemeSpecific['noise_score']        =   $this->input->post('noise_score');
            $schemeSpecific['therm_environ_remarks']        =   $this->input->post('therm_environ_remarks'); 
            $schemeSpecific['therm_environ_score']        =   $this->input->post('therm_environ_score');
            $schemeSpecific['ground_work_remarks']        =   $this->input->post('ground_work_remarks');     
            $schemeSpecific['ground_work_score']        =   $this->input->post('ground_work_score');

            $schemeSpecific['use_of_explosive_remarks']        =   $this->input->post('use_of_explosive_remarks');
            $schemeSpecific['use_of_explosive_score']        =   $this->input->post('use_of_explosive_score');     
            $schemeSpecific['elec_hazard_remarks']        =   $this->input->post('elec_hazard_remarks');
            $schemeSpecific['elec_hazard_score']        =   $this->input->post('elec_hazard_score');
            $schemeSpecific['press_env_remarks']        =   $this->input->post('press_env_remarks');     
            $schemeSpecific['press_env_score']        =   $this->input->post('press_env_score');
            $schemeSpecific['total_ohs_score']        =   $this->input->post('total_ohs_score'); 
            $schemeSpecific['eff_ohs_perso']        =   $this->input->post('eff_ohs_perso');      

            $this->Ldt_app_rev_form->update_ldt_app_rev_ohsas_qstn($schemeSpecific, $tracksheet_id);                       
        }
        else if($scheme_system == "ems")
        {
            $schemeSpecific['env_complex_categ']        =   $this->input->post('env_complex_categ');
            $schemeSpecific['env_total_sites']        =   $this->input->post('env_total_sites');     
            $schemeSpecific['inc_env_complex']        =   $this->input->post('inc_env_complex');
            $schemeSpecific['sites_cov_cert_audit_1']        =   $this->input->post('sites_cov_cert_audit_1');
            $schemeSpecific['decr_env_complex']        =   $this->input->post('decr_env_complex');     
            $schemeSpecific['sites_cov_cert_audit_2']        =   $this->input->post('sites_cov_cert_audit_2');
            $schemeSpecific['tech_and_regul']        =   $this->input->post('tech_and_regul'); 

            $this->Ldt_app_rev_form->update_ldt_app_rev_ems_qstn($schemeSpecific, $tracksheet_id);                           
        }
        else if($scheme_system == "isms")
        {
            $schemeSpecific['type_buss_req']        =   $this->input->post('type_buss_req');
            $schemeSpecific['proc_and_tasks']        =   $this->input->post('proc_and_tasks');     
            $schemeSpecific['level_of_ms']        =   $this->input->post('level_of_ms');
            $schemeSpecific['type_buss_req_score']        =   $this->input->post('type_buss_req_score');
            $schemeSpecific['proc_and_tasks_score']        =   $this->input->post('proc_and_tasks_score');     
            $schemeSpecific['level_of_ms_score']        =   $this->input->post('level_of_ms_score');
            $schemeSpecific['buss_and_org_score']        =   $this->input->post('buss_and_org_score');   

            $schemeSpecific['it_comp']        =   $this->input->post('it_comp');
            $schemeSpecific['out_supp_service']        =   $this->input->post('out_supp_service');     
            $schemeSpecific['info_sys_dev']        =   $this->input->post('info_sys_dev');
            $schemeSpecific['it_comp_score']        =   $this->input->post('it_comp_score');
            $schemeSpecific['out_supp_service_score']        =   $this->input->post('out_supp_service_score');     
            $schemeSpecific['info_sys_dev_score']        =   $this->input->post('info_sys_dev_score');
            $schemeSpecific['it_env_score']        =   $this->input->post('it_env_score');   
                           
            $schemeSpecific['high_low']        =   $this->input->post('high_low');
            $schemeSpecific['high_med']        =   $this->input->post('high_med');     
            $schemeSpecific['high_high']        =   $this->input->post('high_high');
            $schemeSpecific['med_low']        =   $this->input->post('med_low');
            $schemeSpecific['med_med']        =   $this->input->post('med_med');     
            $schemeSpecific['med_high']        =   $this->input->post('med_high');
            $schemeSpecific['low_low']        =   $this->input->post('low_low');   
            $schemeSpecific['low_med']        =   $this->input->post('low_med');   
            $schemeSpecific['low_high']        =   $this->input->post('low_high');   

            $this->Ldt_app_rev_form->update_ldt_app_rev_isms_qstn($schemeSpecific, $tracksheet_id);                           
        }

        if($cert_type == 4)
        {
            redirect('technical/app_rev_re_cert');
        }
        else
        {
            redirect('technical/application_review');
        }
        
    }

//function to insert audit program proces list in the database
    public function insert_audit_prog_process()
    {
        $tracksheet_id                  =   $this->input->input_stream('tracksheet_id', TRUE);
        $process_data                  =   $this->input->input_stream('process_data', TRUE);

        $result = $this->Ldt_audit_prog_process_model->insert_audit_prog_process($tracksheet_id, $process_data);
        echo $result;
    }

//function to fill any_add_reso_req in the audit program form for a tracksheet_id
    public function fill_any_add_reso_req_in_audit_program()
    {
        $tracksheet_id = $this->input->input_stream('tracksheet_id', TRUE);
        $any_add_reso_req = $this->input->input_stream('any_add_reso_req', TRUE);

        $result = $this->Ldt_audit_program_form_model->fill_any_add_reso_req_in_audit_program($tracksheet_id, $any_add_reso_req);
        echo $result;
    }

//function to increase the flow status of a trackksheet
    public function incr_flow_status_of_tracksheet()
    {
        $tracksheet_id                  =   $this->input->input_stream('tracksheet_id', TRUE);

        $result = $this->Mdt_p_tracksheet->incr_flow_status_of_tracksheet($tracksheet_id);
        echo $result;            
    }

//function to increase the flow status of a trackksheet to a desired flow step
    public function incr_desired_flow_status_of_tracksheet()
    {
        $tracksheet_id                  =   $this->input->input_stream('tracksheet_id', TRUE);
        $flow_id                  =   $this->input->input_stream('flow_id', TRUE);

        $result = $this->Mdt_p_tracksheet->incr_desired_flow_status_of_tracksheet($tracksheet_id, $flow_id);
        echo $result;            
    }

//function to insert the review dates for the stite address of a customer and tracksheet
    public function insert_site_address_review_dates()
    {
        $site_review_dates                 =   $this->input->input_stream('site_review_dates', TRUE);
    
        $result = $this->Mdt_customer_site_model->insert_site_address_review_dates($site_review_dates);
        echo $result;
    }

//function to insert auditor team plan records of the application review form for a tracksheet
    public function insert_app_rev_audit_team_plan_records()
    {
        $tracksheet_id                   =   $this->input->input_stream('tracksheet_id', TRUE);
        $app_rev_form_id                 =   $this->input->input_stream('app_rev_form_id', TRUE);

        $audit_auditor_records               =   $this->input->input_stream('audit_auditor_records', TRUE);
    
        $result = $this->Ldt_app_rev_audit_team_plan->insert_app_rev_audit_team_plan_records($tracksheet_id, $app_rev_form_id, $audit_auditor_records);

        print_r($result);
    }

//function to delete any row from the ldt_audit_prog_process with given row_id
    public function delete_audit_prog_process_row()
    {
        $row_id                  =   $this->input->input_stream('row_id', TRUE);

        $result = $this->Ldt_audit_prog_process_model->delete_audit_prog_process_row($row_id);
        echo $result;
    }

//function to update any row from the ldt_audit_prog_process with given row_id
    public function update_audit_prog_process_row()
    {
        $row_id                  =   $this->input->input_stream('row_id', TRUE);

        $audit_prog_proc_list_input                  =   $this->input->input_stream('audit_prog_proc_list_input', TRUE);
        $stage1_selected                  =   $this->input->input_stream('stage1_selected', TRUE);
        $surv1_selected                  =   $this->input->input_stream('surv1_selected', TRUE);
        $surv2_selected                  =   $this->input->input_stream('surv2_selected', TRUE);

        $result = $this->Ldt_audit_prog_process_model->update_audit_prog_process_row($row_id, $audit_prog_proc_list_input, $stage1_selected, $surv1_selected, $surv2_selected);
        echo $result;
    }

//function to insert the audit plan team plan data into the database
    public function insert_audit_plan_team_plan()
    {
        $tracksheet_id                  =   $this->input->input_stream('tracksheet_id', TRUE);
        $level                  =   $this->input->input_stream('level', TRUE);
        $team_plan_array                  =   $this->input->input_stream('team_plan_array', TRUE);

        $result = $this->Ldt_audit_plan_team_plan_model->insert_audit_plan_team_plan($tracksheet_id, $level, $team_plan_array);
        echo $result;
    }

//function to insert the audit plan process list into the database
    public function insert_audit_prog_proc_list()
    {
        $tracksheet_id                  =   $this->input->input_stream('tracksheet_id', TRUE);
        $level                  =   $this->input->input_stream('level', TRUE);
        $process_array                  =   $this->input->input_stream('process_array', TRUE);

        $result = $this->Ldt_audit_plan_process_model->insert_audit_prog_proc_list($tracksheet_id, $level, $process_array);
        echo $result;
    }

//function to insert audit plan form record in database
    public function insert_audit_plan_form_record()
    {
        $tracksheet_id                  =   $this->input->input_stream('tracksheet_id', TRUE);
        $level                  =   $this->input->input_stream('level', TRUE);

        $result = $this->Ldt_audit_plan_form_model->insert_audit_plan_form_record($tracksheet_id, $level);
        echo $result;
    }

//function to upload jas photo
    public function upload_jas_photo()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

        if($_FILES["jas_photo"]["name"] != '')
        {
        //for getting extension of uploaded file
            $test = explode(".", $_FILES["jas_photo"]["name"]);
            $image_extension = end($test);

        //setting new name to the pic
            $image_name_new = "jas_" . $tracksheet_id . "_" . $level . "." . $image_extension;

        //uploading the pic at temp location
            $image_location = 'uploads/cert_proof_photo/' . $image_name_new;

            if(move_uploaded_file($_FILES["jas_photo"]["tmp_name"], $image_location))
            {
                echo "<img file_name=\"$image_name_new\" src=\"" . base_url('uploads/cert_proof_photo/') . $image_name_new . "\"/><br>";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }   

//function to upload eas photo
    public function upload_eas_photo()
    {
        $tracksheet_id = $this->uri->segment(3);
        $level = $this->uri->segment(4);

        if($_FILES["eas_photo"]["name"] != '')
        {
        //for getting extension of uploaded file
            $test = explode(".", $_FILES["eas_photo"]["name"]);
            $image_extension = end($test);

        //setting new name to the pic
            $image_name_new = "eas_" . $tracksheet_id . "_" . $level . "." . $image_extension;

        //uploading the pic at temp location
            $image_location = 'uploads/cert_proof_photo/' . $image_name_new;
        
            if(move_uploaded_file($_FILES["eas_photo"]["tmp_name"], $image_location))
            {
                echo "<img file_name=\"$image_name_new\" src=\"" . base_url('uploads/cert_proof_photo/') . $image_name_new . "\"/><br>";
            }
            else
            {
                echo "Something went wrong";
            }
        }
    }   

//function to insert the certificate issue checlist data into database
    public function insert_in_ldt_certi_issue_checklist()
    {
        $data['tracksheet_id']                  =   $this->input->input_stream('tracksheet_id', TRUE);
        $data['level']                           =   $this->input->input_stream('level', TRUE);  

        $data['manual_date']                           =   $this->input->input_stream('manual_date', TRUE);  
        $data['iqa_date']                           =   $this->input->input_stream('iqa_date', TRUE);  
        $data['mrm_date']                           =   $this->input->input_stream('mrm_date', TRUE);  

        $data['stage1_disc']                           =   $this->input->input_stream('stage1_disc', TRUE);  
        $data['stage2_disc']                           =   $this->input->input_stream('stage2_disc', TRUE); 

        $data['comm_raised']                           =   $this->input->input_stream('comm_raised', TRUE);  
        $data['comm_closed']                           =   $this->input->input_stream('comm_closed', TRUE);  

        $data['corr_for_plan']                           =   $this->input->input_stream('corr_for_plan', TRUE);  
        $data['rel_sec_evid']                           =   $this->input->input_stream('rel_sec_evid', TRUE);  
        $data['stat_req_veri']                           =   $this->input->input_stream('stat_req_veri', TRUE);

        $data['tech_cord']                           =   $this->input->input_stream('tech_cord', TRUE);  
        $data['cert_dec']                           =   $this->input->input_stream('cert_dec', TRUE);  
        $data['remarks']                           =   $this->input->input_stream('remarks', TRUE);  
        $data['date']                           =   $this->input->input_stream('date', TRUE);  
        $data['submitted_to_md']                           =   $this->input->input_stream('submitted_to_md', TRUE); 

        $data['jas_photo_src']                           =   $this->input->input_stream('jas_photo_src', TRUE); 
        $data['eas_photo_src']                           =   $this->input->input_stream('eas_photo_src', TRUE);  

        $result = $this->Ldt_certi_issue_checklist_model->insert_in_ldt_certi_issue_checklist($data);

        return $result;
    }

//function to notify account section about the completed certification process
    public function notify_acc_sec()
    {
        $tracksheet_id = $this->uri->segment(3);

        $result = $this->Mdt_p_tracksheet->notify_acc_sec($tracksheet_id);

        if($result == 1)
        {
            redirect('technical/list_certificate');
        }
        else
        {
            redirect('technical/list_certificate');
        }
    }

//functon to update the change of scope request status
    public function update_scope_change_status_nd_notify_account()
    {
        $id         = $this->input->input_stream('id', TRUE);

        $result = $this->Ldt_intimation_of_changes_model->update_scope_change_status_nd_notify_account($id);

        echo $result;
    }

//function to update a particular column of the table for a id
    public function update_column_in_table()
    {          
        $id                     = $this->input->input_stream('id', TRUE); 
        $column_name            = $this->input->input_stream('column_name', TRUE); 
        $value                  = $this->input->input_stream('value', TRUE); 

        $result = $this->Ldt_certi_issue_checklist_model->update_column_in_table($id, $column_name, $value);
        echo $result;
    }

//function to update the technical_accept request for that row_id in the table
    public function update_tech_accepted_in_scope_of_cert()
    {          
        $id                     = $this->input->input_stream('id', TRUE); 

        $result = $this->Ldt_scope_of_cert_model->update_tech_accepted_in_scope_of_cert($id);
        echo $result;
    }
}   