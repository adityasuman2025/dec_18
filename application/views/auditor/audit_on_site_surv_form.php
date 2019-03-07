<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	if($qstn_not_avail == 1)
		die("questionnaire does not exist for particular scheme and stage. Please contact the manage team and ask them to create a questionnaire");

	$level = $this->uri->segment(4);
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
		                		$tracksheet_id=$database_record->tracksheet_id;

		                		$cm_id=$database_record->cm_id;

								$cb_type=$database_record->cb_type;
								$cb_type_name = 0;
								if($cb_type == 1)
									$cb_type_name = "EAS";
								else
									$cb_type_name = "IAS";
							?>	
		                	<li><a href="#">Customer ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                	<li><a href="#">Organisation Name<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                	<li><a href="#">Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>

		                	<?php		                		
		                		foreach ($get_site_records as $key => $get_site_record)
		                		{
		                			$site_address = $get_site_record['site_address'];
		                	?>
		                			<li><a href="#">Site <?php echo $key + 1; ?> Address<span class="pull-right  "><?php echo $site_address; ?></span></a></li>
		                	<?php
		                		}
		                	?>
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Scope<span class="pull-right  "><?php echo $database_record->scope; ?></span></a></li>
		                  	<li>
		                  		<a href="#">ANZSIC Codes<span class="pull-right">
		                  		<?php 
		                  			$anzsic_code = "";
		                  			foreach ($app_rev_anzsic_code_record as $key => $value) 
		                  			{
		                  				$anzsic_code = $anzsic_code . ", " . $value['anzsic_code'];
		                  			}

		                  			$anzsic_code = ltrim($anzsic_code, ',');
		                  			echo $anzsic_code;
		                  		?>		                  		
		                  		</span></a>
		                  	</li>
		                  	<li><a href="#">Auditor Name<span class="pull-right  "><?php echo $get_auditor_name['username']; ?></span></a></li>
		              </ul>
		            </div>		            
	            </div>           
            </div>
        </div>
	</section>

<!------form questionnaire qstns----->
	<?php
		$count_minor_nc = 0; //count of minor NC
		$count_major_nc = 0; //count of major NC

	//function to get options
		function to_get_options($answer_options)
		{
			if($answer_options == 1)
			{
				echo "<option value=\"1\">C</option>";
				echo "<option value=\"2\">Minor NC</option>";
				echo "<option value=\"3\">Major NC</option>";
				echo "<option value=\"4\">Observation</option>";
			}
			else if($answer_options == 2)
			{
				echo "<option value=\"2\">Minor NC</option>";
				echo "<option value=\"1\">C</option>";
				echo "<option value=\"3\">Major NC</option>";
				echo "<option value=\"4\">Observation</option>";
			}	
			else if($answer_options == 3)
			{
				echo "<option value=\"3\">Major NC</option>";
				echo "<option value=\"1\">C</option>";
				echo "<option value=\"2\">Minor NC</option>";				
				echo "<option value=\"4\">Observation</option>";
			}	
			else if($answer_options == 4)
			{
				echo "<option value=\"4\">Observation</option>";
				echo "<option value=\"1\">C</option>";
				echo "<option value=\"2\">Minor NC</option>";
				echo "<option value=\"3\">Major NC</option>";				
			}	
			else
			{
				echo "<option value=\"1\">C</option>";
				echo "<option value=\"2\">Minor NC</option>";
				echo "<option value=\"3\">Major NC</option>";
				echo "<option value=\"4\">Observation</option>";
			}											
		}

	//displaying already existing questions
		foreach ($get_questionnaire_form_qstns as $key => $get_questionnaire_form_qstn)
		{
			$page_id = $get_questionnaire_form_qstn['page_id'];
			$qstn_type = $get_questionnaire_form_qstn['qstn_type'];

			if($qstn_type == 1) //sample text type (where no questions are there)
			{
				$qstn_id = $get_questionnaire_form_qstn['qstn_id'];
				$qstn_title = $get_questionnaire_form_qstn['qstn_title'];
				$qstn_data = $get_questionnaire_form_qstn['qstn_data'];
			?>
				<div class="col-md-12 col-xs-12 col-sl-12 checklist_text" qstn_id="<?php echo $qstn_id; ?>">
					<h3><?php echo $qstn_title; ?></h3>																
					<div><?php echo $qstn_data; ?></div>
					<br>
				</div>
			<?php
			}
			else if($qstn_type == 4) //sample title type(where questions are present)
			{
				$qstn_id = $get_questionnaire_form_qstn['qstn_id'];
				$qstn_title = $get_questionnaire_form_qstn['qstn_title'];
				$qstn_data = $get_questionnaire_form_qstn['qstn_data'];
			?>
				<section class="content" qstn_id="<?php echo $qstn_id; ?>">
					<div class="col-md-12 col-xs-12 col-sl-12">			            
			            <div class="box box-primary">
			            	<!-- /.box-header -->
			                <div class="box-header with-border">
				              	<h3 class="box-title"><b><?php echo $qstn_title; ?></b></h3>
				              	<div class="box-tools pull-right">
				                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				              	</div>
				            </div>
				            
				            <div class="box-body">
			            		<div class="checklist_title_data">
			            			<?php echo $qstn_data; ?>
			            		</div>											

							<!----------child questions(title qstns sample) of this title sample------->
								<?php
									foreach ($get_questionnaire_form_qstns as $key => $value) 
									{
										$child_qstn_type = $value['qstn_type'];
										$child_qstn_parent = $value['qstn_parent'];

									// only remarks type questions
										if($child_qstn_type == 3 && $child_qstn_parent == $qstn_id)
										{
											$child_qstn_id = $value['qstn_id'];
											$child_qstn_title = $value['qstn_title'];
											$child_qstn_help = $value['qstn_help'];
										?>
											<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn_ans" qstn_id="<?php echo $child_qstn_id; ?>">
												<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn">									
													<?php echo $child_qstn_title; ?>
												</div>
												<div class="col-md-12 col-xs-12 col-sl-12 help_div_area">
													<div class="help_div">
														<b>Help</b>
														<br>
														<?php echo $child_qstn_help; ?>
													</div>					
													<button class="pull-right help_btn" >get help</button>
												</div>
												<!---checking in database if already some answer exist for that qstn_id or not-------->	
													<?php
														$ans_found = 0;
														$answer = "";
														foreach ($get_questionnaire_form_anss as $key => $get_questionnaire_form_ans)
														{
															$qstn_id_of_ans = $get_questionnaire_form_ans['qstn_id'];
															$ans_type = $get_questionnaire_form_ans['ans_type'];														

															if($qstn_id_of_ans == $child_qstn_id && $ans_type == 2)
															{
																$ans_found = 1;
																
																$answer = $get_questionnaire_form_ans['answer'];
																$ans_id = $get_questionnaire_form_ans['ans_id'];

																break;
															}
														}													
													?>

												<div class="col-md-12 col-xs-12 col-sl-12" style="margin: 0; padding: 0;">
													<?php
														if($ans_found == 0) //answer is not found for that qstn_id in the database
														{
															echo "<textarea class=\"checklist_ans\"></textarea>";
														}
														else if($ans_found == 1)  //some answer already present in the databse for that qstn_id
														{
															echo "<textarea class=\"checklist_ans\" disabled=\"disabled\" ans_id=\"$ans_id\">$answer</textarea>";
														}
													?>													
												</div>

											<!-----buttons area------>
												<div class="col-md-12 col-sl-12 col-xs-12 text-right">
													<br>
													<?php
														if($ans_found == 0) //answer is not found for that question id in the database
														{
															echo "<button class=\"btn btn-success save_ans_button\">Save</button>";									
														}
														else if($ans_found == 1)  //some answer already present in the databse for that qstn_id
														{
															echo "<button class=\"btn btn-primary edit_ans_button\">Edit</button>";
															echo "<button class=\"btn btn-success done_ans_button\" style=\"display: none;\">Done</button>";
														}
													?>									            	
									            	<br><br>
									            </div>
											</div>
										<?php
										}
									// both remarks and options type questions
										else if($child_qstn_type == 2 && $child_qstn_parent == $qstn_id)
										{
											$child_qstn_id = $value['qstn_id'];
											$child_qstn_title = $value['qstn_title'];
											$child_qstn_help = $value['qstn_help'];
										?>
											<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn_ans" qstn_id="<?php echo $child_qstn_id; ?>">
												<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn">
													<?php echo $child_qstn_title; ?>
												</div>
												<div class="col-md-12 col-xs-12 col-sl-12 help_div_area">
													<div class="help_div">
														<b>Help</b>
														<br>
														<?php echo $child_qstn_help; ?>
													</div>					
													<button class="pull-right help_btn" >get help</button>
												</div>
												<!---checking in database if already some answer exist for that qstn_id or not-------->	
													<?php
														$ans_found = 0;

														$answer_options = "";
														$answer_remarks = "";
														$ans_options_id = 0;

														$c =0;
														foreach ($get_questionnaire_form_anss as $key => $get_questionnaire_form_ans)
														{
															$qstn_id_of_ans = $get_questionnaire_form_ans['qstn_id'];
															$ans_type = $get_questionnaire_form_ans['ans_type'];

															if($qstn_id_of_ans == $child_qstn_id && $ans_type == 1) //answer of option type ans
															{
																$ans_found = 1;

																$answer_options = $get_questionnaire_form_ans['answer'];
																$ans_options_id = $get_questionnaire_form_ans['ans_id'];

																$c++;											
															}

															if($qstn_id_of_ans == $child_qstn_id && $ans_type == 2) //answer of remarks type ans
															{
																$ans_found = 1;

																$answer_remarks = $get_questionnaire_form_ans['answer'];	
																$ans_remarks_id = $get_questionnaire_form_ans['ans_id'];

																$c++;										
															}

															if($c == 2)
																break;
														}													
													?>
												
												<div class="col-md-2 col-xs-2 col-sl-2" style="padding: 0; margin: 0; padding-right: 3px;">
													<select class="checklist_option" ans_id="<?php echo $ans_options_id; ?>">
														<?php
															if($ans_found == 0) //answer is not found for that qstn_id in the database
															{
																echo "<option value=\"1\">C</option>";
																echo "<option value=\"2\">Minor NC</option>";
																echo "<option value=\"3\">Major NC</option>";
																echo "<option value=\"4\">Observation</option>";
															}
															else if($ans_found == 1)  //some answer already present in the databse for that qstn_id
															{
																if($answer_options == 2) //for counting minor NCs
																{
																	$count_minor_nc++;
																}
																else if($answer_options == 3) //for counting major NCs
																{
																	$count_major_nc++;
																}

																to_get_options($answer_options);
															}
														?>
													</select>
												</div>

												<div class="col-md-10 col-xs-10 col-sl-10" style="padding: 0; margin: 0;">
													<?php
														if($ans_found == 0) //answer is not found for that qstn_id in the database
														{
															echo "<textarea class=\"checklist_ans\"></textarea>";
														}
														else if($ans_found == 1)  //some answer already present in the databse for that qstn_id
														{
															echo "<textarea class=\"checklist_ans\" disabled=\"disabled\" ans_id=\"$ans_remarks_id\">$answer_remarks</textarea>";
														}
													?>		
												</div>

											<!-----buttons area------>
												<div class="col-md-12 col-sl-12 col-xs-12 text-right">
													<br>
													<?php
														if($ans_found == 0) //answer is not found for that question id in the database
														{
															echo "<button class=\"btn btn-success save_ans_button\">Save</button>";
														}
														else if($ans_found == 1)  //some answer already present in the databse for that qstn_id
														{
															echo "<button class=\"btn btn-primary edit_ans_button\">Edit</button>";
															echo "<button class=\"btn btn-success done_ans_button\" style=\"display: none;\">Done</button>";
														}
													?>									            	
									            	<br><br>
									            </div>
											</div>
										<?php
										}
									}
								?>
								
				            </div><!------end of box body------>				            
				        </div>
				    </div>
				</section>					
			<?php	
			}
		}
	?>

<!--------summary of the audit report--------->
   <section class="content">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Summary of the Audit Report</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>

	            <!-- /.box-header -->
	            <div class="box-body">
		            <div class="col-md-12 col-sl-12 col-xs-12">
		            	<?php
		            	//function to get audit stage options
		            		function to_get_stage_of_audit($stage)
		            		{
		            			if($stage == 1)
		            			{
		            				echo "<option value=\"1\">Initial Certification</option>";
		            				echo "<option value=\"2\">Post Audit</option>";
		            				echo "<option value=\"3\">Surveillance</option>";
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"6\">Upgrade From</option>";
		            				echo "<option value=\"7\">Other</option>";
		            			}
		            			else if($stage == 2)
		            			{
									echo "<option value=\"2\">Post Audit</option>";
									echo "<option value=\"1\">Initial Certification</option>";
		            				echo "<option value=\"3\">Surveillance</option>";
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"6\">Upgrade From</option>";
		            				echo "<option value=\"7\">Other</option>";
		            			}
		            			else if($stage == 3)
		            			{
		            				echo "<option value=\"3\">Surveillance</option>";
		            				
									echo "<option value=\"1\">Initial Certification</option>";
									echo "<option value=\"2\">Post Audit</option>";									
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"6\">Upgrade From</option>";
		            				echo "<option value=\"7\">Other</option>";
		            			}
		            			else if($stage == 4)
		            			{
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"1\">Initial Certification</option>";
		            				
									echo "<option value=\"2\">Post Audit</option>";
									echo "<option value=\"3\">Surveillance</option>";
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"6\">Upgrade From</option>";
		            				echo "<option value=\"7\">Other</option>";
		            			}
		            			else if($stage == 5)
		            			{
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"1\">Initial Certification</option>";
		            				
									echo "<option value=\"2\">Post Audit</option>";
									echo "<option value=\"3\">Surveillance</option>";
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"6\">Upgrade From</option>";
		            				echo "<option value=\"7\">Other</option>";
		            			}
		            			else if($stage == 6)
		            			{
		            				echo "<option value=\"6\">Upgrade From</option>";
		            				echo "<option value=\"1\">Initial Certification</option>";
		            				
									echo "<option value=\"2\">Post Audit</option>";
									echo "<option value=\"3\">Surveillance</option>";
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"7\">Other</option>";
		            			}
		            			else if($stage == 7)
		            			{
		            				echo "<option value=\"7\">Other</option>";

		            				echo "<option value=\"1\">Initial Certification</option>";		            				
									echo "<option value=\"2\">Post Audit</option>";
									echo "<option value=\"3\">Surveillance</option>";
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"6\">Upgrade From</option>";		            				
		            			}
		            			else
		            			{
		            				echo "<option value=\"1\">Initial Certification</option>";
		            				echo "<option value=\"2\">Post Audit</option>";
		            				echo "<option value=\"3\">Surveillance</option>";
		            				echo "<option value=\"4\">Modification</option>";
		            				echo "<option value=\"5\">Renewal</option>";
		            				echo "<option value=\"6\">Upgrade From</option>";
		            				echo "<option value=\"7\">Other</option>";
		            			}
		            		}

		            	//function to get recommendation options
		            		function to_get_recomm($recomm)
		            		{
		            			if($recomm == 1)
		            			{
		            				echo "<option value=\"1\">Issuance of Certificate</option>";
		            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
		            				echo "<option value=\"3\">Refusal of the Certificate</option>";
		            				echo "<option value=\"4\">Post audit</option>";
		            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";
		            				echo "<option value=\"6\">Other</option>";
		            			}
		            			else if($recomm == 2)
		            			{
		            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
		            				
									echo "<option value=\"1\">Issuance of Certificate</option>";
		            				echo "<option value=\"3\">Refusal of the Certificate</option>";
		            				echo "<option value=\"4\">Post audit</option>";
		            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";
		            				echo "<option value=\"6\">Other</option>";
		            			}
		            			else if($recomm == 3)
		            			{
		            				echo "<option value=\"3\">Refusal of the Certificate</option>";
		            				
		            				echo "<option value=\"1\">Issuance of Certificate</option>";
		            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
		            				echo "<option value=\"4\">Post audit</option>";
		            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";
		            				echo "<option value=\"6\">Other</option>";
		            			}
		            			else if($recomm == 4)
		            			{
		            				echo "<option value=\"4\">Post audit</option>";
		            				
		            				echo "<option value=\"1\">Issuance of Certificate</option>";
		            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
		            				echo "<option value=\"3\">Refusal of the Certificate</option>";
		            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";
		            				echo "<option value=\"6\">Other</option>";
		            			}
		            			else if($recomm == 5)
		            			{
		            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";
		            				
		            				echo "<option value=\"1\">Issuance of Certificate</option>";
		            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
		            				echo "<option value=\"3\">Refusal of the Certificate</option>";
		            				echo "<option value=\"4\">Post audit</option>";
		            				echo "<option value=\"6\">Other</option>";
		            			}
		            			else if($recomm == 6)
		            			{
		            				echo "<option value=\"6\">Other</option>";

		            				echo "<option value=\"1\">Issuance of Certificate</option>";
		            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
		            				echo "<option value=\"3\">Refusal of the Certificate</option>";
		            				echo "<option value=\"4\">Post audit</option>";
		            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";		            				
		            			}		            		
		            			else
		            			{
		            				echo "<option value=\"1\">Issuance of Certificate</option>";
		            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
		            				echo "<option value=\"3\">Refusal of the Certificate</option>";
		            				echo "<option value=\"4\">Post audit</option>";
		            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";
		            				echo "<option value=\"6\">Other</option>";
		            			}
		            		}

		            	//function to get reason options
		            		function to_get_reason($reason)
		            		{
		            			if($reason == 1)
		            			{
		            				echo "<option value=\"1\">1</option>";
		            				echo "<option value=\"2\">2</option>";
		            				echo "<option value=\"3\">3</option>";
		            				echo "<option value=\"4\">4</option>";
		            			}
		            			else if($reason == 2)
		            			{
		            				echo "<option value=\"2\">2</option>";
		            				
		            				echo "<option value=\"1\">1</option>";
		            				echo "<option value=\"3\">3</option>";
		            				echo "<option value=\"4\">4</option>";
		            			}
		            			else if($reason == 3)
		            			{
		            				echo "<option value=\"3\">3</option>";
		            				
		            				echo "<option value=\"1\">1</option>";
		            				echo "<option value=\"2\">2</option>";
		            				echo "<option value=\"4\">4</option>";
		            			}
		            			else if($reason == 4)
		            			{		            				
		            				echo "<option value=\"4\">4</option>";
		            				echo "<option value=\"1\">1</option>";
		            				echo "<option value=\"2\">2</option>";
		            				echo "<option value=\"3\">3</option>";
		            			}		            				            	
		            			else
		            			{
		            				echo "<option value=\"1\">1</option>";
		            				echo "<option value=\"2\">2</option>";
		            				echo "<option value=\"3\">3</option>";
		            				echo "<option value=\"4\">4</option>";
		            			}
		            		}

		            	//getting audit summary records
		            		$surv_date = 0;
		            		$date = 0;
		            		foreach ($get_audit_summary_records as $key => $get_audit_summary_record)
		            		{
		            			$stage = $get_audit_summary_record['stage'];
		            			$recomm = $get_audit_summary_record['recomm'];
		            			$reason = $get_audit_summary_record['reason'];

		            			$surv_date = $get_audit_summary_record['surv_date'];
		            			$date = $get_audit_summary_record['date'];		            			
		            		}
		            	?>
		            <!--count of major and minor NCs------->	
		            	<b><?php echo $count_minor_nc; ?> Minor/ <?php echo $count_major_nc; ?> Major Non Conformance identified in the Stage 2 audit,  details of Non Conformance</b> Please respond by using your own corrective action form and include the root cause analysis with systemic corrective action. Failure to include root cause analysis with systemic corrective action will result in your responses being rejected by Lead Auditor
		            	<br><br>

		            	<b>A.	Stage of audit:</b>
		            	<select id="stage_of_audit" style="height: 34px;">
		            		<?php to_get_stage_of_audit($stage); ?>
		            	</select>
		            	<br><br>

		            	<b>B.	Recommendation</b>
		            	<select id="recomm" style="height: 34px;">
		            		<?php to_get_recomm($recomm); ?>
		            	</select>
		            	<br><br>

		            	<b>C.	Reason</b>
		            	<select id="reason" style="height: 34px;">
		            		<?php to_get_reason($reason); ?>	            		
		            	</select>
		            	<ul class="nav nav-stacked">
		            		<li><b>1. The Information Security Management Systems complies with the requirements of the reference standard:</b> Congratulations, on the basis of the above summary, Lead Auditor is pleased to put forward a recommendation for issuance of certificate.</li>

		            		<li><b>2. The Information Security Management Systems complies with the requirements of the reference standard with exception of minor NC:</b> Congratulations, Lead Auditor is pleased to put forward a recommendation for registration of Organization upon off-site verification of closure of all issues within 90 days from the date of Stage 2 audit.  If all non-conformances are not closed within 90 days, a full reassessment may be required.</li>

		            		<li><b>3. Evidence of major non conformities: </b> Organization is not recommended for next assessment at this time. A follow-up assessment will be scheduled to allow for on-site verification and closure of all issues within 60 days from the date of Stage 2 audit. If all non-conformances are not closed within 60 days, a full reassessment may be required.</li>

		            		<li><b>4. Not Recommended: </b> Organization is not recommended for certification, a Stage 2 audit will be required. To progress your application for registration, please respond to each non-conformances, with a plan showing proposed actions, timescales and responsibilities for resolution. The organization should consider the root cause of the non-conformance and the potential for related issues in other parts of your system.</li>
		            	</ul>
		            	<br>

		            	<i>Disclaimer: Auditing is based on a sampling process of the available information. The results were arrived based on the sample verified.</i>
		            	<br><br>

		            	<b>Proposed Audit Date for Surveillance Audit: </b> 
		            	<input id="surv_date" type="date" style="height: 34px; width: auto;" value="<?php echo $surv_date; ?>">
		            	<br><br>

		            	<b>Sign Off Date: </b>
		            	<input id="date" type="date" style="height: 34px; width: auto;" value="<?php echo $date; ?>">

	            	</div>
	            </div>
	        </div>
	    </div>
	</section>
	
<!------attendence sheet------>
	<div class="col-md-12 col-xs-12 col-sl-12 content">
		<h3>Upload Attendence Sheet</h3>
		<input type="file" name="file" id="file" accept="image/jpeg, image/png"/>
		<br>
		<ul class="nav nav-stacked upload_ins">
			<li>Image size must be less than 5 MB.</li>
			<li>Only jpeg and png file format are allowed</li>
		</ul>
		<br>
		<div class="upload_pic_feed">
			<?php
				$image_url = base_url('uploads/attendence_sheet/pic_' . $tracksheet_id . "_" . $level . ".jpg");
			?>
			<img src="<?php echo $image_url; ?>" />
		</div>		
	</div>

	<div class="col-md-12 text-center">
		<button class="btn btn-primary continue_btn">Update</button>
		<br>
	</div>

	
<!-------style and script--------->
	<style type="text/css">
		.checklist_title_data
		{
			padding: 3px;
			font-size: 120%;
		}

		.checklist_qstn
		{	
			background: #e1e1e1;
			border: 1px silver solid;
			vertical-align: top;
			padding: 3px;
			margin-bottom: 2px;
		}

		.checklist_ans
		{
			min-height: 80px;
			width: 100%;
			padding: 3px;
			margin: 0px;
			resize: none;
			vertical-align: top;
		}

		.checklist_option
		{
			width: 100%;			
			height: 34px;
			padding: 3px;
			margin: 0px;
		}

		#file
		{
			background: #3c8dbc;
			color: white;
			box-shadow: 0px 0px 3px grey;
			border: none;
			margin:0;
			padding: 0;
			padding: 20px;
			margin: auto;
			width: 100%;
		}

		.upload_pic_feed
		{
			max-height: 500px;
			overflow: scroll;
		}

		.upload_pic_feed img
		{
			max-width:100%;
			max-height:100%;
		}
	</style>

	<script type="text/javascript">
	//for styling height of the checklist_ans class	
		$('.checklist_ans').each(function()
		{
			var qstn_height = $(this).parent().parent().find('.checklist_qstn').css('height');
			
			$(this).css('height', qstn_height);			
		});

	//on clicking on help button
		$('.help_btn').click(function()
		{
			$(this).parent().find('.help_div').fadeIn(500);
		});

	//on clicking on edit_ans_button button
		$('.edit_ans_button').click(function()
		{
			$(this).hide();

			var this_paa = $(this).parent().parent();

			this_paa.find('.done_ans_button').show();
			this_paa.find('input, textarea, select').attr('disabled', false);			

		//on clicking on done button
			$('.done_ans_button').unbind().click(function()
			{
				$(this).hide();
				
				var this_paa = $(this).parent().parent();
				var qstn_id = this_paa.attr('qstn_id');

				this_paa.find('.edit_ans_button').show();
				this_paa.find('input, textarea, select').attr('disabled', true);	

				var checklist_option = this_paa.find('.checklist_option').val();
				var ans_options_id = this_paa.find('.checklist_option').attr('ans_id');

				var checklist_ans = this_paa.find('.checklist_ans').val();
				var ans_remarks_id = this_paa.find('.checklist_ans').attr('ans_id');
				
				var records = [];

				if(checklist_option == undefined && ans_options_id == undefined) //for questions of remarks type only
				{
					temp = {};

					temp.ans_id = ans_remarks_id;
					temp.answer = checklist_ans;	

					records.push(temp);		
				}
				else //for questions of remarks and options type both
				{
					temp1 = {};

					temp1.ans_id = ans_options_id;
					temp1.answer = checklist_option;

					temp2 = {};

					temp2.ans_id = ans_remarks_id;
					temp2.answer = checklist_ans;

					records.push(temp1);		
					records.push(temp2);		
				}

				$.post("<?php echo base_url('auditor_actions/update_ans_in_db'); ?>", {records: records}, function(e)
				{
					if(e ==1)
						location.reload();
				});
			});
		});
	
	//on clicking on save_ans_button button
		$('.save_ans_button').click(function()
		{
			$(this).hide();
			
			var tracksheet_id = "<?php echo $tracksheet_id; ?>";
			var page_id = "<?php echo $page_id; ?>";

			var this_paa = $(this).parent().parent();
			var qstn_id = this_paa.attr('qstn_id');

			var checklist_option = this_paa.find('.checklist_option').val();
			var checklist_ans = this_paa.find('.checklist_ans').val();

			var records = [];

			if(checklist_option == undefined) //for questions of remarks type only
			{
				temp = {};
				temp.answer = checklist_ans;
				temp.ans_type = 2;	
				records.push(temp);		
			}
			else //for questions of remarks and options type both
			{
				temp1 = {};
				temp1.answer = checklist_option;
				temp1.ans_type = 1;	

				temp2 = {};
				temp2.answer = checklist_ans;
				temp2.ans_type = 2;	

				records.push(temp1);		
				records.push(temp2);		
			}

			$.post("<?php echo base_url('auditor_actions/ins_ans_in_db'); ?>", {tracksheet_id: tracksheet_id, page_id: page_id, qstn_id: qstn_id, records: records}, function(e)
			{
				if(e ==1)
					location.reload();
			});		
		});
	
	//on choosing a file as Attendence sheet to upload
		$(document).on('change', '#file', function()
		{
			var tracksheet_id = "<?php echo $tracksheet_id; ?>";
			var level = "<?php echo $level; ?>";

			var property = document.getElementById("file").files[0];
			var image_name = property.name;
			var image_extension = image_name.split('.').pop().toLowerCase();
			var image_size = property.size;

			if(jQuery.inArray(image_extension, ['jpg', 'jpeg', 'png']) == -1)
			{
				alert("Choose a valid image file");
			}
			else
			{
				if(image_size > 6000000)
				{
					alert('Image File Size is more than 1 MB');
				}
				else
				{
					var form_data = new FormData();
					form_data.append("file", property);

					$.ajax({
						url: "<?php echo base_url('auditor_actions/upload_attendence_sheet/' . $tracksheet_id . '/'); ?>" + level,
						method: "POST",
						data: form_data,
						contentType: false,
						cache: false,
						processData: false,
						beforeSend:function()
						{
							$('.upload_pic_feed').text('Uploading...');
						},
						success: function(data)
						{
							location.reload();
						}
					});

				}
			}			
		});

	//on clicking on update button
		$('.continue_btn').click(function()
		{
			$(this).hide();

			var tracksheet_id = "<?php echo $tracksheet_id; ?>";
			var level = "<?php echo $level; ?>";

			var count_minor_nc = "<?php echo $count_minor_nc; ?>";
			var count_major_nc = "<?php echo $count_major_nc; ?>";

			var cm_id = "<?php echo $cm_id; ?>";

			var stage_of_audit = $('#stage_of_audit').val();
			var recomm = $('#recomm').val();
			var reason = $('#reason').val();
			var surv_date = $('#surv_date').val();
			var date = $('#date').val();

			$.post('<?php echo base_url('auditor_actions/update_insert_report_summary_data'); ?>', {tracksheet_id: tracksheet_id, level:level, count_minor_nc:count_minor_nc, count_major_nc:count_major_nc, stage_of_audit:stage_of_audit, recomm:recomm, reason:reason, surv_date:surv_date, date:date, cm_id:cm_id}, function(data)
			{
				if(data == 1)
					location.href= "<?php echo base_url('auditor/audit_on_site_surv'); ?>";
				else
					alert('Something went wrong while updating report summary');
			});
		});
	</script>