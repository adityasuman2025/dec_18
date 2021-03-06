<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	if($qstn_not_avail == 1)
		die("questionnaire does not exist for particular scheme and stage. Please contact the manage team and ask them to create a questionnaire");
	
	$tracksheet_id = $this->uri->segment(3);
	$level = $this->uri->segment(4);

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
		                  	<li><a href="#">Scope<span class="pull-right  ">
		                  		<?php 
		                  			if($level == 3 || $level == 4)
						            {						                
		                  				echo $database_record->scope; 
						            }
						            else
						            {
						               echo $app_rev_form_record->scope; 
						            }
		                  		?>		                  			
		                  		</span></a>
		                  	</li>
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

		function to_get_ans_options($answer_options)
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

										?>
											<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn_ans" qstn_id="<?php echo $child_qstn_id; ?>">
												<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn">									
													<?php echo $child_qstn_title; ?>
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

											<!--answer view area----->		
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

											<!----------watch history area--------->
												<div class="watch_history_area">
														
												</div>

												<button class="btn btn-primary watch_history_btn">Watch Edit History</button>			
											</div>
										<?php
										}
									// both remarks and options type questions
										else if($child_qstn_type == 2 && $child_qstn_parent == $qstn_id)
										{
											$child_qstn_id = $value['qstn_id'];
											$child_qstn_title = $value['qstn_title'];

										?>
											<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn_ans" qstn_id="<?php echo $child_qstn_id; ?>">
												<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn">
													<?php echo $child_qstn_title; ?>
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
																to_get_ans_options($answer_options);
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

											<!----------watch history area--------->
												<div class="watch_history_area">
												</div>

												<button class="btn btn-primary watch_history_btn">Watch Edit History</button>			
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

<!-------style and script--------->
	<style type="text/css">		
		.checklist_qstn_ans
		{
			padding: 0; 
			border: 1px lightgrey solid; 
			margin-bottom: 20px; 
			padding: 3px;
		}

		.checklist_qstn
		{	
			background: #e1e1e1;
			border: 1px lightgrey solid;
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

		.checklist_ans_view
		{
			background: #f5f5f5;
			border: 1px lightgrey solid;
			width: 100%;
			padding: 1px;
			margin-bottom: 10px;
			vertical-align: top;
			min-height: 50px;
		}

		.checklist_comment_textarea
		{
			background: #f5f5f5;
			border: 1px lightgrey solid;
			min-height: 20px;
			width: 100%;
			padding: 1px;
			margin-bottom: 10px;
			vertical-align: top;
			resize: none;
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

		.prev_comment_div
		{
			padding: 3px;
			margin-bottom: 5px;
			border-bottom: 1px solid lightgrey;
			border-radius:  5px;
		}

	</style>

	<script type="text/javascript">
	//variables	
		var tracksheet_id = "<?php echo $tracksheet_id; ?>";
		var level = "<?php echo $level; ?>";

	//on clicking on add comment button
		$('.add_cmmnt_btn').click(function()
		{
			var this_paa = $(this).parent();

			var comment = this_paa.find('.checklist_comment_textarea').val();
			var qstn_id = this_paa.parent().attr('qstn_id');
			var comment_type = 2;

			$.post("<?php echo base_url('auditor_actions/add_audit_report_comments_in_db'); ?>", {tracksheet_id: tracksheet_id, level: level, qstn_id: qstn_id, comment: comment, comment_type: comment_type}, function(data)
			{
				if(data == 1)
					location.reload();
				else	
					alert('something went wrong while inserting comment into the database.');
			});
		});

	//on clicking on watch history button
		$('.watch_history_btn').click(function()
		{
			$(this).hide();

			var this_paa = $(this).parent();
			var qstn_id = this_paa.attr('qstn_id');
			
			$.post("<?php echo base_url('auditor/watch_history_of_answers'); ?>", {qstn_id: qstn_id, tracksheet_id:tracksheet_id}, function(data)
			{
				this_paa.find('.watch_history_area').html(data);
			});						
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

				var tracksheet_id = "<?php echo $tracksheet_id; ?>";
				var page_id = "<?php echo $page_id; ?>";
				
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

					temp.ans_type = 2;
					temp.answer = checklist_ans;	

					records.push(temp);		
				}
				else //for questions of remarks and options type both
				{
					temp1 = {};

					temp1.ans_type = 1;
					temp1.answer = checklist_option;

					temp2 = {};

					temp2.ans_type = 2;
					temp2.answer = checklist_ans;

					records.push(temp1);		
					records.push(temp2);		
				}

				$.post("<?php echo base_url('auditor_actions/ins_ans_in_db'); ?>", {tracksheet_id: tracksheet_id, page_id: page_id, qstn_id: qstn_id, records: records}, function(e)
				{
					//alert(e);
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
	
	</script>