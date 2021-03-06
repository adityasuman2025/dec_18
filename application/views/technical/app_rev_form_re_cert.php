<?php
	if($out_of_index == 1)
    	die("Page Not Found");

    $userid = $_SESSION['userid'];
    $tech_emp_count = count($get_assigned_users_of_tracksheet);

    $user_ids = [];
    if($tech_emp_count == 0)
    {
      die("Permission Denied");
    }
    else
    {                      
      foreach ($get_assigned_users_of_tracksheet as $key => $value) 
      {
        $user_id = $value['user_id'];
        array_push($user_ids, $user_id);
      }

      if(!in_array($userid, $user_ids))
        die("Permission Denied");
    }
?>

<!--------client details-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Client Details</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                	<?php 
		                		$cm_id=$database_record->cm_id;

								$cb_type=$database_record->cb_type;
								$cb_type_name = 0;
								if($cb_type == 1)
									$cb_type_name = "EAS";
								else
									$cb_type_name = "IAS";
							?>	
		                    <li><a href="#">ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                    <li><a href="#">Name <span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                    <li>
		                    	<a href="#">
			                    	CB Type 
			                    	<span class="pull-right">
			                    		<?php 
											echo $cb_type_name;
										?>	
			                    	</span>
		                    	</a>
		                    </li>                                      
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Contact Name <span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>
		                    <li><a href="#">Contact Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>
		                     <li>
		                    	<a href="#">
			                    	Location 
			                    	<span class="pull-right">
			                    		<?php 
											$cb_type=$database_record->location;
											if($cb_type == 1)
												echo "Local";
											else
												echo "Foreign";
										?>	
			                    	</span>
		                    	</a>
		                    </li>
		              </ul>
		            </div>		            

	              <!-- /.table-responsive -->	
	              	<div class="col-md-12 col-sl-12 col-xs-12" style="text-align: center;">
	              		<br>
		                <?php echo form_open(base_url('planning/view_customer_info/' . $cm_id),['class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
                       		<input id="view_tracksheet_button" type="submit" value="View Complete Details" class="btn btn-primary" />
                    	<?php echo form_close("\n")?>
		           	</div>  
	            </div>           
            </div>
        </div>
	</section>

<!--------tracksheet basic details-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Tracksheet Details</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-6 col-sl-12 col-xs-12">
                    	<?php
                     		$cert_type =  $database_record->certification_type;	

							$cert_type_text = 0;
							if($cert_type == 1)
								$cert_type_text = "Cert";
							else if($cert_type == 2)
								$cert_type_text = "S1";
							else if($cert_type == 3)
								$cert_type_text = "S2";
							else if($cert_type == 4)
								$cert_type_text = "RC";
                     	?>
                        <ul class="nav nav-stacked">
                           	<li><a href="#">ID <span class="pull-right  "><?php echo $database_record->tracksheet_id; ?></span></a></li>
                           	<li><a href="#">Scheme <span class="pull-right  "><?php echo $database_record->scheme_name; ?></span></a></li>
                           	<li><a href="#">Certification Type<span class="pull-right  "><?php echo $cert_type_text; ?></span></a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-sl-12 col-xs-12">                     	
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Date <span class="pull-right  "><?php echo $database_record->track_date; ?></span></a></li>
                           	<li><a href="#">Month <span class="pull-right  "><?php echo $database_record->track_month; ?></span></a></li>
                           	<li><a href="#">Year<span class="pull-right  "><?php echo $database_record->track_year; ?></span></a></li>
		              	</ul>
		            </div>	

		            <div class="col-md-12 col-sl-12 col-xs-12">                     	
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Scope:  <span class=""><?php echo $database_record->scope; ?></span></a></li>
		              	</ul>
		            </div>
                </div>     
            </div>
        </div>
	</section>

<!-----questions area---->
	<?php
	//application review form general records
		$address_review_date = $app_rev_form_record->address_review_date;
		$site1_review_date = $app_rev_form_record->site1_review_date;
		$site2_review_date = $app_rev_form_record->site2_review_date;
		$site3_review_date = $app_rev_form_record->site3_review_date;
		$scope = $database_record->scope;
		$scope_clear = $app_rev_form_record->scope_clear;

		$total_emp_as_per = $app_rev_form_record->total_emp_as_per;
		$perma_emp = $app_rev_form_record->perma_emp;
		$part_emp = $app_rev_form_record->part_emp;
		$contract_lab = $app_rev_form_record->contract_lab;
		$temp_skill_un_worker = $app_rev_form_record->temp_skill_un_worker;
		$total_eff_emp = $app_rev_form_record->total_eff_emp;

		$just_for_eff_pers = $app_rev_form_record->just_for_eff_pers;
		$no_of_sites = $app_rev_form_record->no_of_sites;
		$repetitiveness = $app_rev_form_record->repetitiveness;
		$complexity_level = $app_rev_form_record->complexity_level;
		$scope_size = $app_rev_form_record->scope_size;
		$site_remarks = $app_rev_form_record->site_remarks;

		$accr_ava_as_req = $app_rev_form_record->accr_ava_as_req;
		$applicant_lang = $app_rev_form_record->applicant_lang;
		$statuary_applicable = $app_rev_form_record->statuary_applicable;
		$safety_req = $app_rev_form_record->safety_req;
		$threat_impart = $app_rev_form_record->threat_impart;
		$no_surv_audit_plan = $app_rev_form_record->no_surv_audit_plan;

		$stage1_man_days = $app_rev_form_record->stage1_man_days;
		$stage2_man_days = $app_rev_form_record->stage2_man_days;
		$surv1_man_days = $app_rev_form_record->surv1_man_days;
		$surv2_man_days = $app_rev_form_record->surv2_man_days;

		$oth_reas_inc_tym = $app_rev_form_record->oth_reas_inc_tym;
		$oth_reas_desc_tym = $app_rev_form_record->oth_reas_desc_tym;
		$tym_change_warning = $app_rev_form_record->tym_change_warning;

		$reviewed_by_name = $app_rev_form_record->reviewed_by_name;
		$apporved_by_name = $app_rev_form_record->apporved_by_name;
		$reviewed_by_date = $app_rev_form_record->reviewed_by_date;
		$apporved_by_date = $app_rev_form_record->apporved_by_date;

	//scheme specific records 
		$scheme_system = $database_record->scheme_system;

		if($scheme_system == "EnMS")
        {
           	$enms_eff_perso = $app_rev_scheme_secific_query_record['enms_eff_perso'];
			$enms_fec = $app_rev_scheme_secific_query_record['enms_fec'];
			$enms_fec_comp_factor = $app_rev_scheme_secific_query_record['enms_fec_comp_factor'];
			$enms_fes = $app_rev_scheme_secific_query_record['enms_fes'];
			$enms_fes_comp_factor = $app_rev_scheme_secific_query_record['enms_fes_comp_factor'];
			$enms_fseu = $app_rev_scheme_secific_query_record['enms_fseu'];
			$enms_fseu_comp_factor = $app_rev_scheme_secific_query_record['enms_fseu_comp_factor'];
        }
        else if($scheme_system == "FSMS")
        {
            $no_of_pro_line = $app_rev_scheme_secific_query_record['no_of_pro_line'];
			$season_of_prod = $app_rev_scheme_secific_query_record['season_of_prod'];
			$specific_issue = $app_rev_scheme_secific_query_record['specific_issue'];
			$td_man_days = $app_rev_scheme_secific_query_record['td_man_days'];
			$th_man_days = $app_rev_scheme_secific_query_record['th_man_days'];
			$tms_man_days = $app_rev_scheme_secific_query_record['tms_man_days'];
			$tfte_man_days = $app_rev_scheme_secific_query_record['tfte_man_days'];
        }
		else if($scheme_system == "OHSAS")
		{
			$dang_good_expl = $app_rev_scheme_secific_query_record['dang_good_expl'];
			$dang_good_score = $app_rev_scheme_secific_query_record['dang_good_score'];
			$veh_intr_remarks = $app_rev_scheme_secific_query_record['veh_intr_remarks'];
			$veh_intr_score = $app_rev_scheme_secific_query_record['veh_intr_score'];
			$powered_plant_remarks = $app_rev_scheme_secific_query_record['powered_plant_remarks'];
			$powered_plant_score = $app_rev_scheme_secific_query_record['powered_plant_score'];
			$other_plant_remarks = $app_rev_scheme_secific_query_record['other_plant_remarks'];
			$other_plant_score = $app_rev_scheme_secific_query_record['other_plant_score'];
			$manual_handling_remarks = $app_rev_scheme_secific_query_record['manual_handling_remarks'];
			$manual_handling_score = $app_rev_scheme_secific_query_record['manual_handling_score'];
			$hazardous_subs_remarks = $app_rev_scheme_secific_query_record['hazardous_subs_remarks'];
			$hazardous_subs_score = $app_rev_scheme_secific_query_record['hazardous_subs_score'];
			$atmospheric_contam_remakrs = $app_rev_scheme_secific_query_record['atmospheric_contam_remakrs'];
			$atmospheric_contam_score = $app_rev_scheme_secific_query_record['atmospheric_contam_score'];
			$ionis_rad_remarks = $app_rev_scheme_secific_query_record['ionis_rad_remarks'];
			$ionis_rad_score = $app_rev_scheme_secific_query_record['ionis_rad_score'];

			$confined_space_remarks = $app_rev_scheme_secific_query_record['confined_space_remarks'];
			$confined_space_score = $app_rev_scheme_secific_query_record['confined_space_score'];
			$slips_trips_remarks = $app_rev_scheme_secific_query_record['slips_trips_remarks'];
			$slips_trips_score = $app_rev_scheme_secific_query_record['slips_trips_score'];
			$noise_remarks = $app_rev_scheme_secific_query_record['noise_remarks'];
			$noise_score = $app_rev_scheme_secific_query_record['noise_score'];
			$therm_environ_remarks = $app_rev_scheme_secific_query_record['therm_environ_remarks'];
			$therm_environ_score = $app_rev_scheme_secific_query_record['therm_environ_score'];
			$ground_work_remarks = $app_rev_scheme_secific_query_record['ground_work_remarks'];
			$ground_work_score = $app_rev_scheme_secific_query_record['ground_work_score'];

			$use_of_explosive_remarks = $app_rev_scheme_secific_query_record['use_of_explosive_remarks'];
			$use_of_explosive_score = $app_rev_scheme_secific_query_record['use_of_explosive_score'];
			$elec_hazard_remarks = $app_rev_scheme_secific_query_record['elec_hazard_remarks'];
			$elec_hazard_score = $app_rev_scheme_secific_query_record['elec_hazard_score'];
			$press_env_remarks = $app_rev_scheme_secific_query_record['press_env_remarks'];
			$press_env_score = $app_rev_scheme_secific_query_record['press_env_score'];
			$total_ohs_score = $app_rev_scheme_secific_query_record['total_ohs_score'];
			$eff_ohs_perso = $app_rev_scheme_secific_query_record['eff_ohs_perso'];
		}
		else if($scheme_system == "EMS")
        {
            $env_complex_categ = $app_rev_scheme_secific_query_record['env_complex_categ'];
			$env_total_sites = $app_rev_scheme_secific_query_record['env_total_sites'];
			$inc_env_complex = $app_rev_scheme_secific_query_record['inc_env_complex'];
			$sites_cov_cert_audit_1 = $app_rev_scheme_secific_query_record['sites_cov_cert_audit_1'];
			$decr_env_complex = $app_rev_scheme_secific_query_record['decr_env_complex'];
			$sites_cov_cert_audit_2 = $app_rev_scheme_secific_query_record['sites_cov_cert_audit_2'];
			$tech_and_regul = $app_rev_scheme_secific_query_record['tech_and_regul'];
        }
        else if($scheme_system == "ISMS")
        {
            $type_buss_req = $app_rev_scheme_secific_query_record['type_buss_req'];
			$proc_and_tasks = $app_rev_scheme_secific_query_record['proc_and_tasks'];
			$level_of_ms = $app_rev_scheme_secific_query_record['level_of_ms'];
			$type_buss_req_score = $app_rev_scheme_secific_query_record['type_buss_req_score'];
			$proc_and_tasks_score = $app_rev_scheme_secific_query_record['proc_and_tasks_score'];
			$level_of_ms_score = $app_rev_scheme_secific_query_record['level_of_ms_score'];
			$buss_and_org_score = $app_rev_scheme_secific_query_record['buss_and_org_score'];

			$it_comp = $app_rev_scheme_secific_query_record['it_comp'];
			$out_supp_service = $app_rev_scheme_secific_query_record['out_supp_service'];
			$info_sys_dev = $app_rev_scheme_secific_query_record['info_sys_dev'];
			$it_comp_score = $app_rev_scheme_secific_query_record['it_comp_score'];
			$out_supp_service_score = $app_rev_scheme_secific_query_record['out_supp_service_score'];
			$info_sys_dev_score = $app_rev_scheme_secific_query_record['info_sys_dev_score'];
			$it_env_score = $app_rev_scheme_secific_query_record['it_env_score'];

			$high_low = $app_rev_scheme_secific_query_record['high_low'];
			$high_med = $app_rev_scheme_secific_query_record['high_med'];
			$high_high = $app_rev_scheme_secific_query_record['high_high'];
			$med_low = $app_rev_scheme_secific_query_record['med_low'];
			$med_med = $app_rev_scheme_secific_query_record['med_med'];
			$med_high = $app_rev_scheme_secific_query_record['med_high'];
			$low_low = $app_rev_scheme_secific_query_record['low_low'];
			$low_med = $app_rev_scheme_secific_query_record['low_med'];
			$low_high = $app_rev_scheme_secific_query_record['low_high'];
        }
	?>	

<!----------filled form details------------>
	<section class="content" >
	    <div class="col-md-12">	    	    
		    <!-- general form elements -->                
		        <div class="box box-primary">		        	
		            <div class="box-body" class="table-responsive db_common_tb">
		        	<!---org name------->	
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            		<div class="qstn_qstn">Organisation Name</div>
		            	</div>
		            	<div class="col-md-6 col-sl-6 col-xs-6 ans">
		            		<input class="form-control" type="text" id="org_name" disabled="disabled" value="<?php echo $database_record->client_name; ?>">
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            		<div class="qstn_qstn">Review Date</div>
		            	</div>

		            <!---org address------->
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		               		<div class="qstn_qstn">Address</div>
		            	</div>
		            	<div class="col-md-6 col-sl-6 col-xs-6 ans">
		            		<input class="form-control" type="text" id="org_address" disabled="disabled" value="<?php echo $database_record->contact_address; ?>" />
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            		<input type="date" id="address_review_date" value="<?php echo $address_review_date ?>">
		            	</div>

		            <!---site addresses------->
			            <?php
			            	foreach ($get_site_records as $key => $get_site_record)
			            	{
			            		$site_id = $get_site_record['site_id'];
			            		$site_address = $get_site_record['site_address'];
			            		$review_date = $get_site_record['review_date'];
			            ?>
			            		<div class="col-md-4 col-sl-4 col-xs-4 qstn">
				            		<div class="qstn_qstn">Site <?php echo ($key + 1); ?></div>
				            	</div>

				            	<div class="col-md-6 col-sl-6 col-xs-6 ans">
				            		<input class="form-control" type="text" value="<?php echo $site_address; ?>"  disabled="disabled">
				            	</div>

				            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
				            		<input type="date" class="site_review_date" site_id="<?php echo $site_id; ?>" value="<?php echo $review_date; ?>" >
				            	</div>			          
			            <?php		
			            	}
			            ?>

		            <!---scope------->
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            		<div class="qstn_qstn">Scope</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
		            		<input class="form-control" type="text" id="scope" value="<?php echo $scope; ?>">
		            	</div>

		            <!----assesment creteria------>
		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
	            			<div class="qstn_qstn">S. No.</div>
		            	</div>
		            	<div class="col-md-5 col-sl-5 col-xs-5 qstn">
		            		<div class="qstn_qstn">Assessment Criteria</div>
		            	</div>
		            	<div class="col-md-6 col-sl-6 col-xs-6 qstn">
		            		<div class="qstn_qstn">Result/Remarks</div>
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn">1</div>
		            	</div>
		            	<div class="col-md-5 col-sl-5 col-xs-5 qstn">
		            		<div class="qstn_qstn">Standard</div>
		            	</div>
		            	<div class="col-md-6 col-sl-6 col-xs-6 ans">
		            		<input class="form-control" type="text" val="<?= $database_record->scheme_system; ?>" value="<?= $database_record->scheme_name; ?>" disabled="disabled" id="assesment_standard">
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn">2</div>
		            	</div>
		            	<div class="col-md-5 col-sl-5 col-xs-5 qstn">
		            		<div class="qstn_qstn">Assessment type</div>
		            	</div>
		            	<div class="col-md-6 col-sl-6 col-xs-6 ans">
		            		<input class="form-control" type="text" value="<?= $cert_type_text; ?>" disabled="disabled" id="assesment_type">
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn">3</div>
		            	</div>
		            	<div class="col-md-5 col-sl-5 col-xs-5 qstn">
		            		<div class="qstn_qstn">Scope of the certification defined clearly?</div>
		            	</div>
		            	<div class="col-md-6 col-sl-6 col-xs-6 ans">
		            		<input class="form-control" type="text" id="scope_clear" value="<?php echo $scope_clear; ?>">
		            	</div>

		            <!----Effective personnel------>
			            <div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn">4A</div>
		            	</div>
		            	<div class="col-md-11 col-sl-11 col-xs-11 qstn">
		            		<div class="qstn_qstn">Effective Personnel</div>
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn"></div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            		<div class="qstn_qstn">Total Employee as per</div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            		<div class="qstn_qstn">Permanent Emp. (office + factory main shift)</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 qstn">
		            		<div class="qstn_qstn"></div>
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn"></div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
		            		<input class="form-control" type="text" id="total_emp_as_per" value="<?php echo $total_emp_as_per ?>">
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
		            		<input class="form-control" type="text" id="perma_emp" value="<?php echo $perma_emp ?>">
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            		<input class="form-control" type="text" id="part_emp" value="<?php echo $part_emp ?>">
		            	</div>
		            	

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn"></div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            		<div class="qstn_qstn">Contract lab</div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            		<div class="qstn_qstn">Temp. /Skilled / Unskilled Workers</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 qstn">
		            		<div class="qstn_qstn">Total Number of Effective Employees (FTE)</div>
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn"></div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
		            		<input class="form-control" type="text" id="contract_lab" value="<?php echo $contract_lab ?>">
		            		<div class="qstn_qstn"></div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
		            		<input class="form-control" type="text" id="temp_skill_un_worker" value="<?php echo $temp_skill_un_worker ?>">
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            		<input class="form-control" type="text" id="total_eff_emp" value="<?php echo $total_eff_emp ?>">
		            	</div>

		            <!----Justification for Effective personnel: ------>
		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn">4B</div>	            		
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            		<div class="qstn_qstn">Justification for Effective personnel</div>
		            	</div>
		            	<div class="col-md-7 col-sl-7 col-xs-7 ans">
		            		<input class="form-control" type="text" id="just_for_eff_pers" value="<?php echo $just_for_eff_pers; ?>">
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn">5</div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            		
		            		<div class="qstn_qstn">No of Sites</div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            		<div class="qstn_qstn">Repetitiveness</div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            		<div class="qstn_qstn">Complexity level</div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            		<div class="qstn_qstn">Scope Size</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 qstn">
		            		<div class="qstn_qstn">Site Remarks</div>
		            	</div>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn"></div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            		<input class="form-control" type="text" id="no_of_sites" value="<?php echo $no_of_sites; ?>">
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            		<input class="form-control" type="text" id="repetitiveness" value="<?php echo $repetitiveness; ?>">
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            		<input class="form-control" type="text" id="complexity_level" value="<?php echo $complexity_level; ?>">
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            		<input class="form-control" type="text" id="scope_size" value="<?php echo $scope_size; ?>">
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            		<input class="form-control" type="text" id="site_remarks" value="<?php echo $site_remarks; ?>">
		            	</div>

		            <!------EnMS effective personeel----------->	
		            	<?php
		            		$scheme_system = $database_record->scheme_system;

		            		if($scheme_system == "EnMS")
		            		{
		            	?>
		            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            			<div class="qstn_qstn">6</div>
			            	</div>
			            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
			            		<div class="qstn_qstn">EnMS Effective Personnel (Top management + MR + Energy Management Team + personnel responsible for major changes affecting energy performance + personnel responsible for effectiveness of EnMS + personnel responsible for developing, implementing or maintaining energy performance improvements including objectives, targets and action plans + personnel responsible for significant energy uses. )</div>	  
			            		<!-- <textarea class="form-control big_qstn"  rows="4" ></textarea> -->
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            			<input class="form-control big_ans" type="text" id="enms_eff_perso" value="<?php echo $enms_eff_perso; ?>">
		            		</div>
		            		<div class="col-md-12 col-sl-12 col-xs-12"></div>
		            	<?php
		            		}
		            	?>

		            <!------accrediation info---------->
		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
	            			<div class="qstn_qstn">7</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
		            		<div class="qstn_qstn">Accreditation available as per requirement?</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
	            			<input class="form-control" type="text" id="accr_ava_as_req" value="<?php echo $accr_ava_as_req; ?>">
	            		</div>
		            	
		            <!--------anzsic code selecting area--------->
	            		<div class="col-md-12 col-sl-12 col-xs-12 qstn">
	            			<div class="qstn_qstn qstn_heading">Accreditation ANZSIC Code(s)?</div>
		            	</div>
		            	
		               	<div class="col-md-12 col-sl-12 col-xs-12 center-block">
		               		<br>
		               		<ul id="anzsic_ul" style="width: 70%; display: block; margin: auto; list-style: none;">
		               			<li>	               				
				              		<div class="col-md-12 ans">
				              			<input id="anzsic_sugg_input" class="form-control anzsic_sugg_input" type="text" />
				              			<div id="anzsic_sugg_div" class="sugg_div" style="width: 100%; padding: 5px;"></div> 
				                    </div>
				                    <br><br>
		               			</li>
		               		</ul>

		               		<div class="col-md-12 text-center">   
		               			<br>  			                  
		               			<button id="add_more_anzsic_button" class="btn btn-primary">Add ANZSIC Code</button>
		               			<br><br>
				            </div>
		               </div>
		            
		            <!--------applicant lang--------->
	            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
	            			<div class="qstn_qstn">9</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
		            		<div class="qstn_qstn">Language of the applicant?</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
	            			<input class="form-control" type="text" id="applicant_lang" value="<?php echo $applicant_lang ?>">
	            		</div>

	            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
	            			<div class="qstn_qstn">10</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
		            		<div class="qstn_qstn">Is statutory and regulatory requirements applicable?</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
	            			<input class="form-control" type="text" id="statuary_applicable" value="<?php echo $statuary_applicable ?>">
	            		</div>

	            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
	            			<div class="qstn_qstn">11</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
		            		<div class="qstn_qstn">Safety precautions required?</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
	            			<input class="form-control" type="text" id="safety_req" value="<?php echo $safety_req ?>">
	            		</div>

	            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
	            			<div class="qstn_qstn">12</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
		            		<div class="qstn_qstn">Any threats to impartiality?</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
	            			<input class="form-control" type="text" id="threat_impart" value="<?php echo $threat_impart ?>">
	            		</div>		

	            	<!----------FSMS qstns---------->
	            		<?php
		            		$scheme_system = $database_record->scheme_system;
		            		//echo $scheme_system;

		            		if($scheme_system == "FSMS")
		            		{
		            	?>
		            		<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

		            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            			<div class="qstn_qstn">13</div>
			            	</div>
			            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
			            		<div class="qstn_qstn">Number of Product Lines and HACCP Studies carried (FSMS)</div>
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            			<input class="form-control" type="text" id="no_of_pro_line" value="<?php echo $no_of_pro_line ?>">
		            		</div>

		            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            			<div class="qstn_qstn">14</div>
			            	</div>
			            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
			            		<div class="qstn_qstn">Seasonality of the product. If so, best/peak seasonal period (FSMS)</div>            
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            			<input class="form-control" type="text" id="season_of_prod" value="<?php echo $season_of_prod ?>">
		            		</div>
		            	<?php
		            		}
		            	?>

		            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            		<div class="qstn_qstn">15</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
		            		<div class="qstn_qstn">No. of surveillance audit planned.</div>
		            	</div>
		            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
	            			<input class="form-control" type="text" id="no_surv_audit_plan" value="<?php echo $no_surv_audit_plan ?>">
	            		</div>

	            		<?php
		            		$scheme_system = $database_record->scheme_system;
		            		//echo $scheme_system;

		            		if($scheme_system == "FSMS")
		            		{
		            	?>
		            		<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            			<div class="qstn_qstn">16</div>
			            	</div>
			            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
			            		<div class="qstn_qstn">Is there specific issues pertaining to locality, Industry, legislation, Organization. If so, elaborate the issue. (FSMS)</div>
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            			<input class="form-control" type="text" id="specific_issue" value="<?php echo $specific_issue ?>">
		            		</div>
		            	<?php
		            		}
		            	?>	         

		            <!------OHSAS qsnts----------->	            	
		            	<?php
		            		$scheme_system = $database_record->scheme_system;
		            		//echo $scheme_system;

		            		if($scheme_system == "OHSAS")
		            		{
		            	?>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!--intro------>
		            		<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            			<div class="qstn_qstn qstn_heading">Risk Category (For OHSAS Only)</div>
			            	</div>
			            	
			            	<div class="col-md-3 col-sl-3 col-xs-3 qstn">
			            		<div class="qstn_qstn">Potential hazards and other factors</div>
			            	</div>
			            	<div class="col-md-7 col-sl-7 col-xs-7 qstn">
			            		<div class="qstn_qstn">Range indicators for determining scores</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            			<div class="qstn_qstn">Score</div>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----dangerous goods-------->
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div class="qstn_qstn qstn_min_heading">Dangerous Goods</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div class="qstn_qstn">(0,5 or 10) 5: There are some dangerous goods (but not licensable quantities) 10: There are licensable quantities of dangerous goods.</div>
			            		<!-- <textarea class="form-control big_qstn"></textarea> -->
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="dang_good_score"  value="<?php echo $dang_good_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="dang_good_expl"><?php echo $dang_good_expl ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----Vehicle interaction-------->		            
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div class="qstn_qstn qstn_min_heading">Vehicle/pedestrian interaction (including fork-lifts)</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div class="qstn_qstn">(0,5 or 10) 5: there is vehicle traffic that has the potential to interact with employees or other persons but this interaction is very limited due to the low numbers of vehicles involved and limited potential pedestrian impact; 10: there are a number of forklifts or other vehicle movements around employee work areas, and/or pedestrians are able to enter vehicle work zones.</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="veh_intr_score" value="<?php echo $veh_intr_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="veh_intr_remarks" ><?php echo $veh_intr_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----powered plant-------->	
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            			<div type="text" class="qstn_qstn qstn_min_heading">Powered plant (including building plant rooms)</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0,5 or 10) 5: powered plant is used occasionally; 10: powered plant is used regularly or daily.</div>	
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="powered_plant_score" value="<?php echo $powered_plant_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="powered_plant_remarks" ><?php echo $powered_plant_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----other plant-------->	
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Other plant (including scaffolding) or mechanical hazards</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0,5 or 10) 5: other plant is used occasionally; 10: other plant is used regularly or daily.</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="other_plant_score" value="<?php echo $other_plant_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="other_plant_remarks" ><?php echo $other_plant_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
		            
			            <!----Manual handling-------->		
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Manual handling (includes Occupational Overuse Syndrome)</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0,5 or 15) 5: There is manual handling but it is limited to a small number of tasks; 15 There are many manual handling tasks</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="manual_handling_score" value="<?php echo $manual_handling_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="manual_handling_remarks"><?php echo $manual_handling_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----hazardous substance-------->		            
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Hazardous substances (includes asbestos)</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0,5 or 15) 5: There is handling, storage, transport or use of hazardous substances; 15: There is handling, storage, transport or use of hazardous substances on a daily basis by a number of persons</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="hazardous_subs_score" value="<?php echo $hazardous_subs_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="hazardous_subs_remarks" ><?php echo $hazardous_subs_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----atmospheric contaminants-------->		       
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Atmospheric contaminants other than hazardous substances (excludes confined spaces)</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 2 or 5) 2: There has been or could be the need to test atmospheric contaminants to confirm they are below hazardous levels; 5: There are known airborne contaminants in the atmosphere requiring breathing apparatus to be worn on a regular basis (may be inlimited parts of the worksite)</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="atmospheric_contam_score" value="<?php echo $atmospheric_contam_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="atmospheric_contam_remakrs" ><?php echo $atmospheric_contam_remakrs ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----ionis_rad-------->		
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Use of ionising or non-ionising radiation</div>	
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 5 or 10) 5 There are low radiation sources; 10: There are high radiation sources</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="ionis_rad_score" value="<?php echo $ionis_rad_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="ionis_rad_remarks" ><?php echo $ionis_rad_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----confined_space-------->		    
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Confined Space (as per AS/NZS 2865)</div>	           
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 10 or 20) 10: There is a confined space requiring entry; 20: There are a variety of confined spaces requiring entry and/or a number of teams operating in confined spaces</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="confined_space_score" value="<?php echo $confined_space_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="confined_space_remarks" ><?php echo $confined_space_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----slips_trips-------->	
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Slips, trips and falls</div>	            			
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(5 or 20) 5: There are slip, trip or fall hazards; 20: There are a range of activities that expose people to slip, trip and fall hazards</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="slips_trips_score" value="<?php echo $slips_trips_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="slips_trips_remarks" ><?php echo $slips_trips_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----noise-------->		   
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Noise</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 5 or 15) 5: There are nuisance noise levels that do not exceed the maximum legislated noise level; 15: There are noise levels that exceed the maximum legislated noise level</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="noise_score" value="<?php echo $noise_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="noise_remarks" ><?php echo $noise_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----therm_environ-------->		    
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Thermal environment</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 5) 5: There is exposure to extreme thermal discomfort</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="therm_environ_score" value="<?php echo $therm_environ_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="therm_environ_remarks" ><?php echo $therm_environ_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----ground_work-------->	
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Below ground work environment</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 10 or 30) 10: There is occasional below ground work; 30: There is regular or daily below ground work.</div>		            		
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="ground_work_score" value="<?php echo $ground_work_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="ground_work_remarks" ><?php echo $ground_work_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----use_of_explosive-------->		
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Storage and/or use of explosives</div>	            	
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 5 or 10) 5: There are explosives on site; 10: There are explosives being used.</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="use_of_explosive_score" value="<?php echo $use_of_explosive_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="use_of_explosive_remarks"><?php echo $use_of_explosive_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----elec_hazard-------->		          
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Electrical hazards</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0, 2, 5 or 10) 2: Use of electrical equipment; 5: Occasional need for personnel to work on electrical equipment; 10: Regular or daily need for personnel to work on electrical equipment</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="elec_hazard_score" value="<?php echo $elec_hazard_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="elec_hazard_remarks" ><?php echo $elec_hazard_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!----press_env-------->		    
			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            		<div type="text" class="qstn_qstn qstn_min_heading">Pressurised environment</div>
			            	</div>
			            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
			            		<div type="text" class="qstn_qstn">(0 or 5) 5: There is work in a pressurized environment</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="press_env_score" value="<?php echo $press_env_score ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12 ans">	            			
		            			<textarea class="form-control" id="press_env_remarks"><?php echo $press_env_remarks ?></textarea>
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!--------OHS results------->
			            	<div class="col-md-3 col-sl-3 col-xs-3 qstn">
			            		<div type="text" class="qstn_qstn big_qstn">Total score for determining OHS Complexity</div>	            
			            	</div>
			            	<div class="col-md-7 col-sl-7 col-xs-7 qstn">
			            		<div type="text" class="qstn_qstn big_qstn">
			            			Low OHS complexity Score = 0 to 80
			            			<br> 
									Medium OHS complexity Score = 81 to 115 
									<br>
									High OHS complexity Score ≥116
			            		</div>	
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control big_ans" type="text" id="total_ohs_score" value="<?php echo $total_ohs_score ?>">
			            	</div>	

			            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
			            		<div type="text" class="qstn_qstn">Effective Number of Personnel</div>	 
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
		            			<input class="form-control" type="text" id="eff_ohs_perso" value="<?php echo $eff_ohs_perso ?>">
		            		</div>		            	
		            	<?php
		            		}
		            	?>
		            	
		            <!------EMS qsnts----------->
		            	<?php
		            		$scheme_system = $database_record->scheme_system;
		            		//echo $scheme_system;

		            		if($scheme_system == "EMS")
		            		{
		            	?>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

		            		<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            			<div type="text" class="qstn_qstn qstn_heading">Complexity Category (For EMS only)</div>
			            	</div>

			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">Environmental Complexity Category</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="env_complex_categ" value="<?php echo $env_complex_categ ?>">
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">Total Sites</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="env_total_sites" value="<?php echo $env_total_sites ?>">
			            	</div>

			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">Increase for Environ, Complexity</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="inc_env_complex" value="<?php echo $inc_env_complex ?>">
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">Sites to be  covered in certification Audit</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="sites_cov_cert_audit_1" value="<?php echo $sites_cov_cert_audit_1 ?>">
			            	</div>

			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">Decrease for Environ, Complexity</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="decr_env_complex" value="<?php echo $decr_env_complex ?>">
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">Sites to be covered in Surveillance  Audit</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="sites_cov_cert_audit_2" value="<?php echo $sites_cov_cert_audit_2 ?>">
			            	</div>

			            	<div class="col-md-8 col-sl-8 col-xs-8 qstn">
			            		<div type="text" class="qstn_qstn">Technological and regulatory on text, Out sourced Process, if any</div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
		            			<input class="form-control" type="text" id="tech_and_regul" value="<?php echo $tech_and_regul ?>">
			            	</div>
		            	<?php
		            		}
		            	?>

		            <!------EnMS qsnts----------->
		            	<?php
		            		$scheme_system = $database_record->scheme_system;
		            		//echo $scheme_system;

		            		if($scheme_system == "EnMS")
		            		{
		            	?>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

		            		<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            			<div type="text" class="qstn_qstn qstn_heading">Complexity Category (for EnMS only)</div>
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn">Sl No</div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">Category</div>
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 qstn">
			            		<div type="text" class="qstn_qstn">Clients Value</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn">Weight age</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn">Complexity factor</div>
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn">1</div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">FEC - annual energy consumption</div>
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            			<input class="form-control" type="text" id="enms_fec" value="<?php echo $enms_fec; ?>">
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn">30%</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="enms_fec_comp_factor" value="<?php echo $enms_fec_comp_factor; ?>">
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn">2</div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">FES - number of energy sources (Electricity, diesel, solar, natural gas)</div>
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            			<input class="form-control" type="text" id="enms_fes" value="<?php echo $enms_fes; ?>">
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn">30%</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="enms_fes_comp_factor" value="<?php echo $enms_fes_comp_factor; ?>">
			            	</div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn">3</div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn">FSEU - number of significant energy uses</div>
			            	</div>
			            	<div class="col-md-3 col-sl-3 col-xs-3 ans">
		            			<input class="form-control" type="text" id="enms_fseu" value="<?php echo $enms_fseu; ?>">
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn">40%</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="enms_fseu_comp_factor" value="<?php echo $enms_fseu_comp_factor; ?>">
			            	</div>
		            	<?php
		            		}
		            	?>

		            <!------ISMS qsnts----------->
		            	<?php
		            		$scheme_system = $database_record->scheme_system;
		            		//echo $scheme_system;

		            		if($scheme_system == "ISMS")
		            		{
		            	?>
		            		<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <!------intro--------->
		            		<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            			<div type="text" class="qstn_qstn qstn_heading">Complexity Category (for ISMS only) Audit Time Calculation</div>
			            	</div>

			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn "></div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            		<div type="text" class="qstn_qstn "></div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
		            			<div type="text" class="qstn_qstn "></div>
			            	</div>
			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            			<div type="text" class="qstn_qstn ">Grade</div>
			            	</div>
			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
		            			<div type="text" class="qstn_qstn "></div>
			            	</div>

			            <!----bussiness and org qstns------>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn big_big_qstn">Factors Related to Business and organization</div>
			            	</div>

			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">		
				            	<div type="text" class="qstn_qstn ">Type(s) of business and regulatory requirements</div>            		
				            	<div type="text" class="qstn_qstn ">Process and tasks</div>
				            	<div type="text" class="qstn_qstn ">Level of establishment of the MS</div>
			            	</div>
			            	<div class="col-md-4 col-sl-4 col-xs-4 ans">		            		
			            		<input type="text" class="form-control ans" id="type_buss_req" value="<?php echo $type_buss_req; ?>" />
			            		<input type="text" class="form-control ans" id="proc_and_tasks"  value="<?php echo $proc_and_tasks; ?>"/>
			            		<input type="text" class="form-control ans" id="level_of_ms" value="<?php echo $level_of_ms; ?>" />
			            	</div>
			            	<div class="col-md-1 col-sl-1 col-xs-1 ans">		            		
			            		<input type="text" class="form-control ans" id="type_buss_req_score"  value="<?php echo $type_buss_req_score; ?>"/>
			            		<input type="text" class="form-control ans" id="proc_and_tasks_score"  value="<?php echo $proc_and_tasks_score; ?>"/>
			            		<input type="text" class="form-control ans" id="level_of_ms_score"  value="<?php echo $level_of_ms_score; ?>"/>
			            	</div>
			            	<div class="col-md-1 col-sl-1 col-xs-1 ans">		            		
			            		<textarea class="big_big_qstn" id="buss_and_org_score"><?php echo $buss_and_org_score; ?></textarea>
			            	</div>

			            <!----it qstns---------->		
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn big_big_qstn">Factors related to IT environment</div>
			            	</div>

			            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">		
				            	<div type="text" class="qstn_qstn ">IT infrastructure complexity</div>            		
				            	<div type="text" class="qstn_qstn ">Dependency on outsourcing and suppliers, including cloud services</div>
				            	<div type="text" class="qstn_qstn ">Information System development</div>
			            	</div>		            	
			            	<div class="col-md-4 col-sl-4 col-xs-4 ans">		            		
			            		<input type="text" class="form-control ans" id="it_comp"  value="<?php echo $it_comp; ?>"/>
			            		<input type="text" class="form-control ans" id="out_supp_service"  value="<?php echo $out_supp_service; ?>"/>
			            		<input type="text" class="form-control ans" id="info_sys_dev"  value="<?php echo $info_sys_dev; ?>"/>
			            	</div>
			            	<div class="col-md-1 col-sl-1 col-xs-1 ans">		            		
			            		<input type="text" class="form-control ans" id="it_comp_score"  value="<?php echo $it_comp_score; ?>"/>
			            		<input type="text" class="form-control ans" id="out_supp_service_score" value="<?php echo $out_supp_service_score; ?>" />
			            		<input type="text" class="form-control ans" id="info_sys_dev_score" value="<?php echo $info_sys_dev_score; ?>" />
			            	</div>
			            	<div class="col-md-1 col-sl-1 col-xs-1 ans">		            		
			            		<textarea class="big_big_qstn" id="it_env_score"><?php echo $type_buss_req; ?></textarea>
			            	</div>

			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn"></div>

			            <!----Impact of factors on audit time---------->
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            			<div type="text" class="qstn_qstn big_qstn"></div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn big_qstn">Business complexity</div>
			            	</div>
			            	<div class="col-md-8 col-sl-8 col-xs-8 double">
			            		<div class="qstn">
				            		<div type="text" class="qstn_qstn" style="text-align: center;">IT complexity</div>
				            	</div>

			            		<div class="col-md-4 col-sl-4 col-xs-4 qstn">
			            			<div type="text" class="qstn_qstn" >Low (from 3 to 4)</div>
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
				            		<div type="text" class="qstn_qstn" > Medium (from 5 to 7)</div>
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
				            		<div type="text" class="qstn_qstn"> High (from 7 to 9)</div>
				            	</div>
			            	</div>

			            	<div class="col-md-12 col-sl-12 col-xs-12 qstn"></div>

			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn big_big_qstn">Impact of factors on audit time</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn">High (from 7 to 9)</div>
			            		<div type="text" class="qstn_qstn">Medium (from 5 to 7)</div>
			            		<div type="text" class="qstn_qstn">Low (from 3 to 4)</div>
			            	</div>
			            	<div class="col-md-8 col-sl-8 col-xs-8 double">		          		            		
			            		<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="high_low" value="<?php echo $high_low; ?>">
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="high_med" value="<?php echo $high_med; ?>">
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="high_high" value="<?php echo $high_high; ?>">
				            	</div>

				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="med_low" value="<?php echo $med_low; ?>">
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="med_med" value="<?php echo $med_med; ?>">
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="med_high" value="<?php echo $med_high; ?>">
				            	</div>

				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="low_low" value="<?php echo $low_low; ?>">
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="low_med" value="<?php echo $low_med; ?>">
				            	</div>
				            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
			            			<input class="form-control" type="text" id="low_high" value="<?php echo $low_high; ?>">
				            	</div>
			            	</div>

			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
		            	<?php
		            		}
		            	?>

		            <!------FSMS qsnts----------->
		           		<?php
		            		$scheme_system = $database_record->scheme_system;
		            		//echo $scheme_system;

		            		if($scheme_system == "FSMS")
		            		{
		            	?>
		            		<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

		            		<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            			<div type="text" class="qstn_qstn qstn_heading">Audit Man-days Calculation for FSMS</div>
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn">Sl. No.</div>
			            	</div>
			            	<div class="col-md-9 col-sl-9 col-xs-9 qstn">
			            		<div type="text" class="qstn_qstn ">Areas considered</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            		<div type="text" class="qstn_qstn ">Audit Man-days</div>
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn ">1</div>
			            	</div>
			            	<div class="col-md-9 col-sl-9 col-xs-9 qstn">
			            		<div type="text" class="qstn_qstn ">Basic on-site audit time (TD)</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="td_man_days" value="<?php echo $td_man_days ?>">
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn ">2</div>
			            	</div>
			            	<div class="col-md-9 col-sl-9 col-xs-9 qstn">
			            		<div type="text" class="qstn_qstn ">Audit days for each additional HACCP Studies (TH)</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
			            		<input class="form-control" type="text" id="th_man_days" value="<?php echo $th_man_days ?>">
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn ">3</div>
			            	</div>
			            	<div class="col-md-9 col-sl-9 col-xs-9 qstn">
			            		<div type="text" class="qstn_qstn ">Is this organization certified for QMS/FSMS by EAS, if not, man-days (TMS)</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="tms_man_days" value="<?php echo $tms_man_days ?>">
			            	</div>

			            	<div class="col-md-1 col-sl-1 col-xs-1 qstn">
			            		<div type="text" class="qstn_qstn ">4</div>
			            	</div>
			            	<div class="col-md-9 col-sl-9 col-xs-9 qstn">
			            		<div type="text" class="qstn_qstn ">Audit days as per Employees (TFTE)</div>
			            	</div>
			            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
		            			<input class="form-control" type="text" id="tfte_man_days" value="<?php echo $tfte_man_days ?>">
			            	</div>
			           <?php
		            		}
		            	?>
		        
		        	<!--------man days required------>
		        		<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            <div class="col-md-12 col-sl-12 col-xs-12"></div>

		        		<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		        			<div type="text" class="qstn_qstn qstn_heading">Man-days Required</div>
		            	</div>

		            	<div class="col-md-2 col-sl-2 col-xs-2 ans" style="display: none;">
	            			<input class="form-control" type="number" id="stage1_man_days" value="<?php echo $stage1_man_days ?>">
		            	</div>

		            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
		            		<div type="text" class="qstn_qstn ">Stage 2: Audit Man-days</div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
	            			<input class="form-control" type="number" id="stage2_man_days" value="<?php echo $stage2_man_days ?>">
		            	</div>

		            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
		            		<div type="text" class="qstn_qstn ">Surveillance 1 Audit Man-days</div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
	            			<input class="form-control" type="number" id="surv1_man_days" value="<?php echo $surv1_man_days ?>">
		            	</div>

		            	<div class="col-md-10 col-sl-10 col-xs-10 qstn">
		            		<div type="text" class="qstn_qstn ">Surveillance 2 Audit Man-days</div>
		            	</div>
		            	<div class="col-md-2 col-sl-2 col-xs-2 ans">
	            			<input class="form-control" type="number" id="surv2_man_days" value="<?php echo $surv2_man_days ?>">
		            	</div>

		            <!--------reasons to change time------>		           
		        		<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            <div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <div class="col-md-12 col-sl-12 col-xs-12 qstn">
			            	<div type="text" class="qstn_qstn qstn_heading">REMARKS (Refer: Guidance on the Auditor Time Assessments)</div>
		            	</div>

		            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            		<div type="text" class="qstn_qstn ">Other reasons to increase time</div>
		            	</div>
		            	<div class="col-md-12 col-sl-12 col-xs-12 ans">
	            			<input class="form-control" type="text" id="oth_reas_inc_tym" value="<?php echo $oth_reas_inc_tym ?>">
		            	</div>

		            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            		<div type="text" class="qstn_qstn ">Other reasons to decrease time:</div>
		            	</div>
		            	<div class="col-md-12 col-sl-12 col-xs-12 ans">
	            			<input class="form-control" type="text" id="oth_reas_desc_tym" value="<?php echo $oth_reas_desc_tym ?>">
		            	</div>

		            	<div class="col-md-3 col-sl-3 col-xs-3 qstn">
		            		<div type="text" class="qstn_qstn ">Warnings</div>
		            	</div>
		            	<div class="col-md-9 col-sl-9 col-xs-9 ans">
	            			<input class="form-control" type="text" id="tym_change_warning" value="<?php echo $tym_change_warning ?>">
		            	</div>
		          
		            <!---------audit 2 team planned----------->
		            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            <div class="col-md-12 col-sl-12 col-xs-12"></div>
		            	
		            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
	            			<div type="text" class="qstn_qstn qstn_heading">Audit 2 Team Planned</div>
		            	</div>

		            	<div class="col-md-12 col-sl-12 col-xs-12 center-block">
		               		<br>
		               		<ul class="audit_auditor_ul" style="width: 80%; display: block; margin: auto; list-style: none;" >
		               			<li>	               				
				              		<div class="col-md-5 col-sl-5 col-xs-5 ans"> 
			                            <select class="form-control audit_auditor" level="2">
			                            	<option value="0">Select</option> 
			                           		<!-----these options will come from the	sdt_anzsic_codes table dynamically with the auditor suggestions from the selected anzsic codes--------->
			                        	</select>
				                    </div>
				                    
				                    <div class="col-md-2 col-sl-2 col-xs-2"></div>

				                    <div class="col-md-5 col-sl-5 col-xs-5 ans"> 
			                            <select class="form-control audit_type" level="2">
			                            	<option value="0">Select Type</option>
			                            	<option value="1">Auditor</option>
			                            	<option value="2">Technical Expert</option>
			                            	<option value="3">Reviewer</option>
			                            	<option value="4">Decision Maker</option>
			                        	</select>
				                    </div>
				                    <br><br>
		               			</li>
		               		</ul>

		               		<div class="col-md-12 text-center">   
		               			<br>  			                  
		               			<button class="add_audit_auditor btn btn-primary">Add Auditor</button>
		               			<br><br>
				            </div>
		              	</div>

		            <!---------surveillance 1 team planned----------->
		            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            <div class="col-md-12 col-sl-12 col-xs-12"></div>
		            	
		            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
		            		<div type="text" class="qstn_qstn qstn_heading">Surveillance 1 Team Planned</div>
		            	</div>

		            	<div class="col-md-12 col-sl-12 col-xs-12 center-block">
		               		<br>
		               		<ul class="audit_auditor_ul" style="width: 80%; display: block; margin: auto; list-style: none;" >
		               			<li>	               				
				              		<div class="col-md-5 col-sl-5 col-xs-5 ans"> 
			                            <select class="form-control audit_auditor" level="3">
			                            	<option value="0">Select</option> 
			                           		<!-----these options will come from the	sdt_anzsic_codes table dynamically with the auditor suggestions from the selected anzsic codes--------->
			                        	</select>
				                    </div>
				                    
				                    <div class="col-md-2 col-sl-2 col-xs-2"></div>

				                    <div class="col-md-5 col-sl-5 col-xs-5 ans"> 
			                            <select class="form-control audit_type" level="3">
			                            	<option value="0">Select Type</option>
			                            	<option value="1">Auditor</option>
			                            	<option value="2">Technical Expert</option>
			                            	<option value="3">Reviewer</option>
			                            	<option value="4">Decision Maker</option>
			                        	</select>
				                    </div>
				                    <br><br>
		               			</li>
		               		</ul>

		               		<div class="col-md-12 text-center">   
		               			<br>  			                  
		               			<button class="add_audit_auditor btn btn-primary">Add Auditor</button>
		               			<br><br>
				            </div>
		              	</div>
		        
		        	<!---------surveillance 2 team planned----------->
		            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            <div class="col-md-12 col-sl-12 col-xs-12"></div>
		            	
		            	<div class="col-md-12 col-sl-12 col-xs-12 qstn">
	            			<div type="text" class="qstn_qstn qstn_heading">Surveillance 2 Team Planned</div>
		            	</div>

		            	<div class="col-md-12 col-sl-12 col-xs-12 center-block">
		               		<br>
		               		<ul class="audit_auditor_ul" style="width: 80%; display: block; margin: auto; list-style: none;" >
		               			<li>	               				
				              		<div class="col-md-5 col-sl-5 col-xs-5 ans"> 
			                            <select class="form-control audit_auditor" level="4">
			                            	<option value="0">Select</option> 
			                           		<!-----these options will come from the	sdt_anzsic_codes table dynamically with the auditor suggestions from the selected anzsic codes--------->
			                        	</select>
				                    </div>
				                    
				                    <div class="col-md-2 col-sl-2 col-xs-2"></div>

				                    <div class="col-md-5 col-sl-5 col-xs-5 ans"> 
			                            <select class="form-control audit_type" level="4">
			                            	<option value="0">Select Type</option>
			                            	<option value="1">Auditor</option>
			                            	<option value="2">Technical Expert</option>
			                            	<option value="3">Reviewer</option>
			                            	<option value="4">Decision Maker</option>
			                        	</select>
				                    </div>
				                    <br><br>
		               			</li>
		               		</ul>

		               		<div class="col-md-12 text-center">   
		               			<br>  			                  
		               			<button class="add_audit_auditor btn btn-primary">Add Auditor</button>
		               			<br><br>
				            </div>
		              	</div>

		            <!---------review and apporve--------->
		            	<div class="col-md-12 col-sl-12 col-xs-12"></div>
			            <div class="col-md-12 col-sl-12 col-xs-12"></div>

			            <div class="col-md-2 col-sl-2 col-xs-2 qstn">
			            	<div type="text" class="qstn_qstn qstn_heading">Reviewed By</div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
	            			<input class="form-control" type="text" id="reviewed_by_name" value="<?php echo $reviewed_by_name ?>">
		            	</div>
		            	 <div class="col-md-2 col-sl-2 col-xs-2 qstn">
	            			<div type="text" class="qstn_qstn qstn_heading">Approve By</div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
	            			<input class="form-control" type="text" id="apporved_by_name" value="<?php echo $apporved_by_name ?>">
		            	</div>

		            	<div class="col-md-2 col-sl-2 col-xs-2 qstn">
		            		<div type="text" class="qstn_qstn qstn_heading">Date</div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
	            			<input type="date" id="reviewed_by_date" value="<?php echo $reviewed_by_date ?>">
		            	</div>
		            	 <div class="col-md-2 col-sl-2 col-xs-2 qstn">
	            			<div type="text" class="qstn_qstn qstn_heading">Date</div>
		            	</div>
		            	<div class="col-md-4 col-sl-4 col-xs-4 ans">
	            			<input type="date" id="apporved_by_date" value="<?php echo $apporved_by_date ?>">
		            	</div>

		            </div>

		            <div class="text-center" >	      
		            	<b>This is a computer generated form. It does not required signature</b>
		            	<br><br><br>

		          		<input type="submit" class="btn btn-primary text-center" id="app_review_submit_button" value="Submit">
		          		<br><br>
		            </div>
		        </div>
	    </div>
	</section>

<!--------script------------>
	<script type="text/javascript">
	//function to add name attr to all the answers field	
		$('.ans').each(function(i,obj)
		{
			var val = $(this).find('input, textarea, select').attr('id');
			$(this).find('input, textarea, select').attr('name', val);
		});

	//for adding more anzsic codes for a customer
		$('#add_more_anzsic_button').click(function()
		{
			var html = $('#anzsic_ul li:first').html();		
			$('#anzsic_ul').append("<li>" + html + "</li>");	
				
		//on typing in anzic code suggestion input
			$('#anzsic_ul li #anzsic_sugg_input').keyup(function()
			{
				anzsic_sugg_func($(this));			
			});
		});

	//on typing in anzic code suggestion input
		$('#anzsic_ul li #anzsic_sugg_input').keyup(function()
		{
			anzsic_sugg_func($(this));			
		});

	//function to suggest anzsic code	
		function anzsic_sugg_func(this_input) 
		{			
			var anzsic_sugg_input = this_input.val();
			var scheme_system = "<?= $database_record->scheme_system; ?>";
			var scheme_system = scheme_system.toLowerCase();

			var this_thing = this_input.parent().parent().find('#anzsic_sugg_div');

			if(anzsic_sugg_input != "")
			{
				this_thing.fadeIn(100);

				if(scheme_system != "enms" && scheme_system != "fsms")
				{
					var to_get = ['anzsic_code', 'group_name', 'code_description', 'risk'];
					
					$.post('<?=base_url()?>technical/get_anzsic_sugg', {scheme_system:scheme_system, anzsic_sugg_input:anzsic_sugg_input, to_get:to_get}, function(data)
					{
						this_thing.html(data);
						$('.anzsic_sug_val').click(function()
						{
							var anzsic_code = $(this).attr('val');
							this_input.val(anzsic_code);
							this_thing.fadeOut(100);
							this_thing.html("");
						});
					});
				}
				else if(scheme_system == "fsms")
				{
					var to_get = ['category', 'category_name', 'category_code', 'sub_category'];

					$.post('<?=base_url()?>technical/get_anzsic_sugg', {scheme_system:scheme_system, anzsic_sugg_input:anzsic_sugg_input, to_get:to_get}, function(data)
					{
						this_thing.html(data);
						$('.anzsic_sug_val').click(function()
						{
							var anzsic_code = $(this).attr('val');
							this_input.val(anzsic_code);
							this_thing.fadeOut(100);
							this_thing.html("");
						});
					});
				}
				else if(scheme_system == "enms")
				{
					var to_get = ['technical_area', 'anzsic_code_id', 'anzsic_code_id', 'anzsic_code_id'];

					$.post('<?=base_url()?>technical/get_anzsic_sugg', {scheme_system:scheme_system, anzsic_sugg_input:anzsic_sugg_input, to_get:to_get}, function(data)
					{
						this_thing.html(data);
						$('.anzsic_sug_val').click(function()
						{
							var anzsic_code = $(this).attr('val');
							this_input.val(anzsic_code);
							this_thing.fadeOut(100);
							this_thing.html("");
						});
					});
				}
			}
			else
			{
				this_thing.fadeOut(100);
				this_thing.html("");
			}
		}
	
	//for adding more audit auditors for a customer
		$('.add_audit_auditor').click(function()
		{
			var html = $(this).parent().parent().find('.audit_auditor_ul li:first').html();		
			$(this).parent().parent().find('.audit_auditor_ul').append("<li>" + html + "</li>");			
		});

	//function for getting auditor name suggestion
		function get_auditor_sugg(this_thing)
		{
			var this_thing = this_thing;

			var scheme_system = "<?= $database_record->scheme_system; ?>";
			var scheme_system = scheme_system.toLowerCase();

			var anzsic_codes = [];
			var anzsic_sugg_inputs = $('.anzsic_sugg_input');
			$(anzsic_sugg_inputs).each(function(i, obj) 
			{
			    anzsic_codes.push("'" + $(this).val() + "'");
			});

			$.post('<?=base_url()?>technical/get_auditor_sugg', {scheme_system:scheme_system, anzsic_codes:anzsic_codes}, function(data)
			{
				this_thing.html("<option value=\"0\">Select</option>");
				this_thing.append(data);
			});
		}

	//for suggesting auditor name in team plan
		$('.audit_auditor').focus(function()
		{
			get_auditor_sugg($(this));
		});

	//on clicking on submit button
		$('#app_review_submit_button').click(function()
		{
			$(this).hide();

			var scheme_system = "<?= $database_record->scheme_system; ?>";
			var scheme_system = scheme_system.toLowerCase();

		/*------intro variables----------*/	
			var tracksheet_id = "<?= $database_record->tracksheet_id; ?>";
			var cm_id = "<?= $database_record->cm_id; ?>";
			var application_form_id = "";
			var address_review_date = $('#address_review_date').val();

			var site1_review_date = "";
			var site2_review_date = "";
			var site3_review_date = "";

			var scope = $('#scope').val();

		//assessment info
			var assesment_standard = "<?= $database_record->scheme_system; ?>"; //scheme_system
			var assesment_standard = assesment_standard.toLowerCase();
			var assesment_type = "<?= $cert_type; ?>";
			var scope_clear =  $('#scope_clear').val();

		//Effective personnel------>
			var total_emp_as_per = $('#total_emp_as_per').val();
			var perma_emp = $('#perma_emp').val();
			var part_emp = $('#part_emp').val();
			var contract_lab = $('#contract_lab').val();
			var temp_skill_un_worker = $('#temp_skill_un_worker').val();
			var total_eff_emp = $('#total_eff_emp').val();

		//just_for_eff_pers
			var just_for_eff_pers = $('#just_for_eff_pers').val();
			var no_of_sites = $('#no_of_sites').val();
			var repetitiveness = $('#repetitiveness').val();
			var complexity_level = $('#complexity_level').val();
			var scope_size = $('#scope_size').val();
			var site_remarks = $('#site_remarks').val();

		//enms_eff_perso	
			var enms_eff_perso = $('#enms_eff_perso').val();

		//accrediation info---------->
			var accr_ava_as_req = $('#accr_ava_as_req').val();
			var applicant_lang = $('#applicant_lang').val();
			var statuary_applicable = $('#statuary_applicable').val();
			var safety_req = $('#safety_req').val();
			var threat_impart = $('#threat_impart').val();

		//FSMS qstns
			var no_of_pro_line = $('#no_of_pro_line').val();
			var season_of_prod = $('#season_of_prod').val();
			var specific_issue = $('#specific_issue').val();

		//no of surv audit plan
			var no_surv_audit_plan = $('#no_surv_audit_plan').val();

		//OHSAS qsnts //dangerous goods-
			var dang_good_expl = $('#dang_good_expl').val();
			var dang_good_score = $('#dang_good_score').val();

		//OHSAS qsnts //vechicale remakrs
			var veh_intr_remarks = $('#veh_intr_remarks').val();
			var veh_intr_score = $('#veh_intr_score').val();

		//OHSAS qsnts //power plant
			var powered_plant_remarks = $('#powered_plant_remarks').val();
			var powered_plant_score = $('#powered_plant_score').val();

		//OHSAS qsnts //other plant
			var other_plant_remarks = $('#other_plant_remarks').val();
			var other_plant_score = $('#other_plant_score').val();

		//OHSAS qsnts //Manual handling
			var manual_handling_remarks = $('#manual_handling_remarks').val();
			var manual_handling_score = $('#manual_handling_score').val();

		//OHSAS qsnts //hazardous_subs
			var hazardous_subs_remarks = $('#hazardous_subs_remarks').val();
			var hazardous_subs_score = $('#hazardous_subs_score').val();

		//OHSAS qsnts //atmospheric contaminants
			var atmospheric_contam_remakrs = $('#atmospheric_contam_remakrs').val();
			var atmospheric_contam_score = $('#atmospheric_contam_score').val();

		//OHSAS qsnts //ionis_rad
			var ionis_rad_remarks = $('#ionis_rad_remarks').val();
			var ionis_rad_score = $('#ionis_rad_score').val();

		//OHSAS qsnts //confined_space
			var confined_space_remarks = $('#confined_space_remarks').val();
			var confined_space_score = $('#confined_space_score').val();

		//OHSAS qsnts //slips_trips
			var slips_trips_remarks = $('#slips_trips_remarks').val();
			var slips_trips_score = $('#slips_trips_score').val();

		//OHSAS qsnts //noise
			var noise_remarks = $('#noise_remarks').val();
			var noise_score = $('#noise_score').val();

		//OHSAS qsnts //therm_environ
			var therm_environ_remarks = $('#therm_environ_remarks').val();
			var therm_environ_score = $('#therm_environ_score').val();

		//OHSAS qsnts //ground_work
			var ground_work_remarks = $('#ground_work_remarks').val();
			var ground_work_score = $('#ground_work_score').val();

		//OHSAS qsnts //use_of_explosive
			var use_of_explosive_remarks = $('#use_of_explosive_remarks').val();
			var use_of_explosive_score = $('#use_of_explosive_score').val();

		//OHSAS qsnts //elec_hazard
			var elec_hazard_remarks = $('#elec_hazard_remarks').val();
			var elec_hazard_score = $('#elec_hazard_score').val();

		//OHSAS qsnts //press_env
			var press_env_remarks = $('#press_env_remarks').val();
			var press_env_score = $('#press_env_score').val();

		//OHSAS qsnts //ohs results
			var total_ohs_score = $('#total_ohs_score').val();
			var eff_ohs_perso = $('#eff_ohs_perso').val();

		//EMS qsnts
			var env_complex_categ = $('#env_complex_categ').val();
			var env_total_sites = $('#env_total_sites').val();
			var inc_env_complex = $('#inc_env_complex').val();
			var sites_cov_cert_audit_1 = $('#sites_cov_cert_audit_1').val();
			var decr_env_complex = $('#decr_env_complex').val();
			var sites_cov_cert_audit_2 = $('#sites_cov_cert_audit_2').val();
			var tech_and_regul = $('#tech_and_regul').val();
			
		//EnMS qsnts
			var enms_fec = $('#enms_fec').val();
			var enms_fec_comp_factor = $('#enms_fec_comp_factor').val();
			var enms_fes = $('#enms_fes').val();
			var enms_fes_comp_factor = $('#enms_fes_comp_factor').val();
			var enms_fseu = $('#enms_fseu').val();
			var enms_fseu_comp_factor = $('#enms_fseu_comp_factor').val();

		//ISMS qsnts //buss and org
			var type_buss_req = $('#type_buss_req').val();
			var proc_and_tasks = $('#proc_and_tasks').val();
			var level_of_ms = $('#level_of_ms').val();

			var type_buss_req_score = $('#type_buss_req_score').val();
			var proc_and_tasks_score = $('#proc_and_tasks_score').val();
			var level_of_ms_score = $('#level_of_ms_score').val();

			var buss_and_org_score = $('#buss_and_org_score').val();
			
		//ISMS qsnts //it qstns
			var it_comp = $('#it_comp').val();
			var out_supp_service = $('#out_supp_service').val();
			var info_sys_dev = $('#info_sys_dev').val();

			var it_comp_score = $('#it_comp_score').val();
			var out_supp_service_score = $('#out_supp_service_score').val();
			var info_sys_dev_score = $('#info_sys_dev_score').val();

			var it_env_score = $('#it_env_score').val();

		//ISMS qsnts //Impact of factors on audit time
			var high_low = $('#high_low').val();
			var high_med = $('#high_med').val();
			var high_high = $('#high_high').val();

			var med_low = $('#med_low').val();
			var med_med = $('#med_med').val();
			var med_high = $('#med_high').val();

			var low_low = $('#low_low').val();
			var low_med = $('#low_med').val();
			var low_high = $('#low_high').val();

		//FSMS qsnts
			var td_man_days = $('#td_man_days').val();
			var th_man_days = $('#th_man_days').val();
			var tms_man_days = $('#tms_man_days').val();
			var tfte_man_days = $('#tfte_man_days').val();

		//man days required-
			var stage1_man_days = $('#stage1_man_days').val();
			var stage2_man_days = $('#stage2_man_days').val();
			var surv1_man_days = $('#surv1_man_days').val();
			var surv2_man_days = $('#surv2_man_days').val();

		//reasons to change time		
			var oth_reas_inc_tym = $('#oth_reas_inc_tym').val();
			var oth_reas_desc_tym = $('#oth_reas_desc_tym').val();
			var tym_change_warning = $('#tym_change_warning').val();

		//review and approve
			var reviewed_by_name = $('#reviewed_by_name').val();
			var apporved_by_name = $('#apporved_by_name').val();
			var reviewed_by_date = $('#reviewed_by_date').val();
			var apporved_by_date = $('#apporved_by_date').val();			

		//inserting application review info into the application review table	
			$.post('<?=base_url()?>technical/insert_app_rev_gen_record', {tracksheet_id:tracksheet_id, cm_id:cm_id, application_form_id:application_form_id, address_review_date:address_review_date, site1_review_date:site1_review_date, site2_review_date:site2_review_date, site3_review_date:site3_review_date, scope:scope, assesment_standard:assesment_standard, assesment_type:assesment_type, scope_clear:scope_clear, total_emp_as_per:total_emp_as_per, perma_emp:perma_emp, part_emp: part_emp, contract_lab:contract_lab, temp_skill_un_worker:temp_skill_un_worker, total_eff_emp:total_eff_emp, just_for_eff_pers:just_for_eff_pers, no_of_sites:no_of_sites, repetitiveness:repetitiveness, complexity_level:complexity_level, scope_size:scope_size, site_remarks:site_remarks, accr_ava_as_req:accr_ava_as_req, applicant_lang:applicant_lang, statuary_applicable:statuary_applicable, safety_req:safety_req, threat_impart:threat_impart, no_surv_audit_plan:no_surv_audit_plan, stage1_man_days:stage1_man_days, stage2_man_days:stage2_man_days, surv1_man_days:surv1_man_days, surv2_man_days:surv2_man_days, oth_reas_inc_tym:oth_reas_inc_tym, oth_reas_desc_tym:oth_reas_desc_tym, tym_change_warning:tym_change_warning, reviewed_by_name:reviewed_by_name, apporved_by_name:apporved_by_name, reviewed_by_date:reviewed_by_date, apporved_by_date:apporved_by_date}, function(e)
			{	
				var app_rev_form_id = e;	

				alert(app_rev_form_id);

			//inserting scheme specfic questions in their respective tabless				
				var qstn_query;

				if(scheme_system == "enms")
				{
					//alert(scheme_system);
					qstn_query = "VALUES('', '" + tracksheet_id + "', '" + app_rev_form_id + "', '" + enms_eff_perso + "', '" + enms_fec + "', '" + enms_fec_comp_factor + "', '" + enms_fes + "', '" + enms_fes_comp_factor + "', '" + enms_fseu + "', '" + enms_fseu_comp_factor + "', '')";
					
				}
				else if(scheme_system == "fsms")
				{
					//alert(scheme_system);
					qstn_query = "VALUES('', '" + tracksheet_id + "', '" + app_rev_form_id + "', '" + no_of_pro_line + "', '" + 	season_of_prod + "', '" + specific_issue + "', '" + td_man_days + "', '" + th_man_days + "', '" + tms_man_days + "', '" + tfte_man_days + "', '')";
				}
				else if(scheme_system == "ohsas")
				{
					//alert(scheme_system);
					qstn_query = "VALUES('', '" + tracksheet_id + "', '" + app_rev_form_id + "', '" + dang_good_expl + "', '" + 	dang_good_score + "', '" + veh_intr_remarks + "', '" + veh_intr_score + "', '" + powered_plant_remarks + "', '" + powered_plant_score + "', '" + other_plant_remarks + "', '" + other_plant_score + "', '" + manual_handling_remarks + "', '" + manual_handling_score + "', '" + hazardous_subs_remarks + "', '" + hazardous_subs_score + "', '" + atmospheric_contam_remakrs + "', '" + atmospheric_contam_score + "', '" + ionis_rad_remarks + "', '" + ionis_rad_score + "', '" + confined_space_remarks + "', '" + confined_space_score + "', '" + slips_trips_remarks + "', '" + slips_trips_score + "', '" + noise_remarks + "', '" + noise_score + "', '" + therm_environ_remarks + "', '" + therm_environ_score + "', '" + ground_work_remarks + "', '" + ground_work_score + "', '" + use_of_explosive_remarks + "', '" + use_of_explosive_score + "', '" + elec_hazard_remarks + "', '" + elec_hazard_score + "', '" + press_env_remarks + "', '" + press_env_score + "', '" + total_ohs_score + "', '" + eff_ohs_perso + "', '')";
				}
				else if(scheme_system == "ems")
				{
					//alert(scheme_system);
					qstn_query = "VALUES('', '" + tracksheet_id + "', '" + app_rev_form_id + "', '" + env_complex_categ + "', '" + env_total_sites + "', '" + inc_env_complex + "', '" + sites_cov_cert_audit_1 + "', '" + decr_env_complex + "', '" + sites_cov_cert_audit_2 + "', '" + tech_and_regul + "', '')";
				}
				else if(scheme_system == "isms")
				{
					//alert(scheme_system);
					qstn_query = "VALUES('', '" + tracksheet_id + "', '" + app_rev_form_id + "', '" + type_buss_req + "', '" + proc_and_tasks + "', '" + level_of_ms + "', '" + type_buss_req_score + "', '" + proc_and_tasks_score + "', '" + level_of_ms_score + "', '" + buss_and_org_score + "', '" + it_comp + "', '" + out_supp_service + "', '" + info_sys_dev + "', '" + it_comp_score + "', '" + out_supp_service_score + "', '" + info_sys_dev_score + "', '" + it_env_score + "', '" + high_low + "', '" + high_med + "', '" + high_high + "', '" + med_low + "', '" + med_med + "', '" + med_high + "', '" + low_low + "', '" + low_med + "', '" + low_high + "', '')";
				}
				else
				{
					qstn_query = "";
				}
						
			//inserting the anzsic codes of that application review form in the application review anzsic codes table
				var anzsic_codes = [];			
				$('.anzsic_sugg_input').each(function(i,obj)
				{
					var anzsic_val = $(this).val();

					if(anzsic_val != "")
					{
						anzsic_codes.push(anzsic_val);	
					}										
				});

			//inserting the site review dates into database	
				var site_review_dates = [];				
				$('.site_review_date').each(function()
				{
					var site_id = $(this).attr('site_id');
					var review_date = $(this).val();

					temp = {};
					temp.site_id = site_id;
					temp.review_date = review_date;

					site_review_dates.push(temp);
				});

			//inserting auditor team plan info of the application review form in the application review audit team table
				var audit_auditor_records = [];	
				$('.audit_auditor').each(function(i,obj)
				{
					var auditor_id = $(this).val();
					var level = $(this).attr('level');
					var type = $(this).parent().parent().find('.audit_type').val();

					if(auditor_id != 0 && type != 0)
					{
						var temp = {};

						temp.auditor_id = auditor_id;
						temp.level = level;
						temp.type = type;

						audit_auditor_records.push(temp);
					}				
				});							

			//running query to insert records in the databse
				$.post('<?=base_url()?>technical/insert_app_rev_scheme_specific_record', {qstn_query:qstn_query, scheme_system: scheme_system}, function(data)
				{	
					$.post('<?=base_url()?>technical/insert_app_rev_anzsic_codes_record', {app_rev_form_id: app_rev_form_id, tracksheet_id:tracksheet_id, scheme_system: scheme_system, anzsic_codes: anzsic_codes}, function(data)
					{	
					//increasing the flow id of that tracksheet
						$.post('<?=base_url()?>technical_action/incr_flow_status_of_tracksheet', {tracksheet_id: tracksheet_id}, function(data)
						{
						//inserting the site review dates into database	
							$.post('<?php echo base_url('technical_action/insert_site_address_review_dates'); ?>', {site_review_dates:site_review_dates}, function(e)
							{
							//inserting the audtor team records in the app_rev audit_tem_plan table	
								$.post('<?php echo base_url('technical_action/insert_app_rev_audit_team_plan_records'); ?>', {tracksheet_id: tracksheet_id, app_rev_form_id: app_rev_form_id, audit_auditor_records: audit_auditor_records}, function(a)
								{		
									if(a == 1)
									{
										location.href= "<?=base_url()?>technical/app_rev_re_cert";	
									}
									else
									{
										alert('something went wrong in increasing the flow status of the tracksheet.');
									}			
								});								
							});														
						});	
					});
				});																						
			});	

		});	
	</script>