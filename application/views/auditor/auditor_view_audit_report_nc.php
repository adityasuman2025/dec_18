<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	if($qstn_not_avail == 1)
		die("questionnaire does not exist for particular scheme and stage. Please contact the manage team and ask them to create a questionnaire");

	$tracksheet_id = $this->uri->segment(3);
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

		                		$cert_type=$database_record->certification_type;
		                		$cm_id=$database_record->cm_id;

								$cb_type=$database_record->cb_type;
								$cb_type_name = 0;
								if($cb_type == 1)
									$cb_type_name = "EAS";
								else
									$cb_type_name = "IAS";
							?>	
		                	<li><a href="#">Customer ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                	<li><a href="#">Organisation Name<span class="pull-right  "><?php echo $client_name = $database_record->client_name; ?></span></a></li>
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
				echo "<option value=\"0\">NA</option>";
			}												
		}

	//getting all the auditors name 
		$auditors = [];

		foreach ($get_all_auditors as $key => $get_all_auditor)
		{
			$user_id = $get_all_auditor['user_id'];
			$username = $get_all_auditor['username'];

			$auditors[$user_id] = $username;
		}

	//displaying already existing questions
		foreach ($get_all_infos_of_nc as $key => $get_all_infos_of_nc)
		{
			$nc_id = $get_all_infos_of_nc['id'];

			$qstn_title = $get_all_infos_of_nc['qstn_title'];
			$qstn_help = $get_all_infos_of_nc['qstn_help'];

			$answers = $get_all_infos_of_nc['answers'];
			$answer_array = explode('^', $answers);

			$answers_type = $get_all_infos_of_nc['answers_type'];
			$answers_type_array = explode('^', $answers_type);	

			$nc_statement = $get_all_infos_of_nc['nc_statement'];
			$correction = $get_all_infos_of_nc['correction'];
			$root_cause = $get_all_infos_of_nc['root_cause'];
			$corrective_action = $get_all_infos_of_nc['corrective_action'];
			$corrective_action_text = $get_all_infos_of_nc['corrective_action_text'];

			$nc_clear_status = $get_all_infos_of_nc['nc_clear_status'];
		?>

		<section class="content">
			<div class="col-md-12 col-xs-12 col-sl-12">			            
	            <div class="box box-primary">
	            	<!-- /.box-header -->
	                <div class="box-header with-border">
		              	<h3 class="box-title"></h3>
		              	<div class="box-tools pull-right">
		                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		              	</div>
		            </div>
		            
		            <div class="box-body checklist_qstn_ans" nc_id="<?php echo $nc_id; ?>">
		            <!------question and answer area---------->	
	            		<div class="col-md-12 col-xs-12 col-sl-12 checklist_qstn">
	            			<?php echo $qstn_title; ?>
	            		</div>	
	            		<div class="col-md-12 col-xs-12 col-sl-12 help_div_area">
							<div class="help_div">
								<b>Help</b>
								<br>
								<?php echo $qstn_help; ?>
							</div>					
							<button class="pull-right help_btn" >get help</button>
						</div>

	            		<div class="col-md-2 col-xs-2 col-sl-2" style="padding: 0; margin: 0; padding-right: 3px;">
	            			<?php
	            				$answer_options = 0;
	            				for($i = 0; $i<2; $i++)
	            				{
	            					$ans_type = $answers_type_array[$i];

	            					if($ans_type == 1) //option
	            					{
	            						$answer_options = $answer_array[$i];
	            						break;
	            					}
	            				}
	            			?>
							<select class="checklist_option" disabled="disabled">
								<?php
									to_get_options($answer_options);
								?>
							</select>
						</div>
						<div class="col-md-10 col-xs-10 col-sl-10" style="padding: 0; margin: 0;">
							<?php
								$answer_remarks = "";
	            				for($i = 0; $i<2; $i++)
	            				{
	            					$ans_type = $answers_type_array[$i];

	            					if($ans_type == 2) //remakrs
	            					{
	            						$answer_remarks = $answer_array[$i];
	            						break;
	            					}
	            				}
								echo "<div class=\"checklist_ans_view\" >$answer_remarks</div>";
							?>		
						</div>

					<!------nc statement area---------->	
						<div class="col-md-12 col-sl-12 col-xs-12" style="padding: 0; margin: 0;">
							<b>Non Conformity Statement</b>
							<textarea class="nc_statement" disabled="disabled"><?php echo $nc_statement; ?></textarea>
							<br><br>

							<button class="btn btn-primary edit_nc_statement_btn pull-right">Edit</button>
							<button class="btn btn-success done_nc_statement_btn pull-right" style="display: none;">Done</button>
						</div>

					<!-------client reply area--->
						<div class="col-md-12 col-sl-12 col-xs-12" style="padding: 0; margin: 0;">
							<?php
								if($answer_options == 4) //observation
								{
							?>
									<b>Correction</b>
									<div class="correction_div"><?php echo $correction; ?></div>
							<?php		
								}
								elseif ($answer_options == 2 || $answer_options == 3) //minor or major NC
								{
							?>
									<b>Root Cause Analyse</b>
									<div class="root_cause_ana_div"><?php echo $root_cause; ?></div>

									<b>Correction</b>
									<div class="correction_div"><?php echo $correction; ?></div>

									<b>Corrective Action</b>
									<div class="correction_div"><?php echo $corrective_action_text; ?></div>
									
									<?php
										$corrective_action_array = explode('.', $corrective_action);
										$file_name = $corrective_action_array[0];

										$comparable_file_name = md5("corr_acc_" . $tracksheet_id . "_" . $level . "_" . $nc_id);

										if($file_name == $comparable_file_name)
										{
											$url = base_url('uploads/audit_report_nc_docs/' . $corrective_action);
											echo "<a class=\"btn btn-primary\" target=\"_blank\" href=\"$url\">View Attached Document</a>";
										}
										else
										{
											echo "<div class=\"corrective_action_div\">$corrective_action</div>";
										}
									?>
									
							<?php	
								}
							?>
						</div>
	            	
					<!-------comment area--------->
						<div class="col-md-12 col-xs-12 col-sl-12" style=" padding: 0; margin: 0;">
							<br>
							<h4>Comment Section</h4>
							<?php
								foreach ($get_audit_nc_comments as $key => $get_audit_report_nc_comment)
								{
									$comment_nc_id = $get_audit_report_nc_comment['nc_id'];

									if($comment_nc_id == $nc_id)
									{
										$comment = $get_audit_report_nc_comment['comment'];
										$comment_type = $get_audit_report_nc_comment['comment_type'];
										$commented_by = $get_audit_report_nc_comment['commented_by'];
										
										$comment_type_text = "";
										if($comment_type == 1)
										{
											$commented_by = $client_name;
											$comment_type_text = "Client";
										}
										else if($comment_type == 2)
										{
											$commented_by = $auditors[$commented_by];
											$comment_type_text = "Auditor";
										}
								?>
										<div class="prev_comment_div">
											<b><?php echo $comment_type_text . ": " . $commented_by; ?></b>
											<br>
											<?php echo $comment; ?>
										</div>
								<?php		
									}
								}
							?>

							<textarea class="checklist_comment_textarea"></textarea>
							<br>
							<button class="btn btn-primary pull-right add_cmmnt_btn">Add Comment</button>
							<br><br>
						</div>	

					<!-----approve NC area---------->
						<?php					
							echo "<div class=\"col-sl-12 col-md-12 col-xs-12 text-center\">";	
								if($nc_clear_status == 2)
								{	
									echo "<b style=\"color: green\">This NC has been cleared</b>";							
								}
								else
								{
									echo "<button class=\"btn btn-success clear_nc_btn\">Clear NC</button>";
								}
							echo "</div>";
						?>
	            	</div>
	            </div>
	        </div>
	    </section>
		<?php	
		}
	?>

	<div class="col-md-12 text-center">
		<button class="btn btn-danger disapprove_btn">Disapprove</button>
		<button class="btn btn-success approve_btn">Forward to Next Stage</button>
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

		.checklist_option
		{
			width: 100%;			
			height: 34px;
			padding: 3px;
			margin: 0px;
		}

		.nc_statement
		{
			min-height: 60px;
			width: 100%;
			padding: 3px;
			margin: 0px;
			resize: none;
			vertical-align: top;
		}

		.correction_div, .root_cause_ana_div, .corrective_action_div
		{
			background: #e1e1e1;
			border: 1px silver solid;
			vertical-align: top;
			padding: 3px;
			margin-bottom: 2px;
			min-height: 20px;
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
	</style>

	<script type="text/javascript">
	//variables
		var tracksheet_id = "<?php echo $tracksheet_id ?>";
		var level = "<?php echo $level ?>";
		var cert_type = "<?php echo $cert_type ?>";

	//on clicking on help button
		$('.help_btn').click(function()
		{
			$(this).parent().find('.help_div').fadeIn(500);
		});
		
	//on clicking on edit_ans_button button
		$('.edit_nc_statement_btn').click(function()
		{
			$(this).hide();

			var this_paa = $(this).parent();

			this_paa.find('.done_nc_statement_btn').show();
			this_paa.find('input, textarea, select').attr('disabled', false);			

		//on clicking on done button
			$('.done_nc_statement_btn').unbind().click(function()
			{
				$(this).hide();
				
				var this_paa = $(this).parent();
				var nc_id = this_paa.parent().attr('nc_id');

				this_paa.find('.edit_nc_statement_btn').show();
				this_paa.find('input, textarea, select').attr('disabled', true);	

				var nc_statement = this_paa.find('.nc_statement').val();

				$.post("<?php echo base_url('auditor_actions/update_nc_statement_in_db'); ?>", {nc_id: nc_id, nc_statement: nc_statement}, function(e)
				{
					if(e ==1)
						location.reload();
				});
			});
		});
	
	//on clicking on add comment button
		$('.add_cmmnt_btn').click(function()
		{
			$(this).hide();

			var this_paa = $(this).parent();

			var comment = this_paa.find('.checklist_comment_textarea').val();
			var nc_id = this_paa.parent().attr('nc_id');
			var comment_type = 2; //auditor

			$.post("<?php echo base_url('auditor_actions/add_audit_report_nc_comments_in_db'); ?>", {tracksheet_id: tracksheet_id, level: level, nc_id: nc_id, comment: comment, comment_type: comment_type}, function(data)
			{
				if(data == 1)
					location.reload();
				else	
					alert('something went wrong while inserting comment into the database.');
			});
		});

	//on clicking on clear nc button
		$('.clear_nc_btn').click(function()
		{
			$(this).hide();

			var nc_id = $(this).parent().parent().attr('nc_id');
			//alert(nc_id);

			$.post("<?php echo base_url('auditor_actions/clear_audit_report_nc'); ?>", {nc_id: nc_id}, function(data)
			{
				if(data == 1)
					location.reload();
				else	
					alert('something went wrong while clearing this NC');
			});
		});	
	
	//on clicking on disapprove report button
		$('.disapprove_btn').click(function()
		{
			$(this).hide();

			if(level == 1)
				location.href = "<?php echo base_url('auditor/list_nc1') ?>";
			else if(level == 2)
			{
				if(cert_type == 4)
					location.href = "<?php echo base_url('auditor/list_nc_re_cert') ?>";
				else
					location.href = "<?php echo base_url('auditor/list_nc2') ?>";
			}
			else if(level == 3 || level == 4)
				location.href = "<?php echo base_url('auditor/list_nc_surv') ?>";
		});

	//on clicking on Forward to Next Stage button
		$('.approve_btn').click(function()
		{
			$(this).hide();
			
			$.post("<?php echo base_url('technical_action/incr_flow_status_of_tracksheet'); ?>", {tracksheet_id: tracksheet_id}, function(data)
			{
				if(level == 1 && data == 1)
					location.href = "<?php echo base_url('auditor/list_nc1') ?>";
				else if(level == 2 && data == 1)
				{
					if(cert_type == 4)
						location.href = "<?php echo base_url('auditor/list_nc_re_cert') ?>";
					else
						location.href = "<?php echo base_url('auditor/list_nc2') ?>";
				}
				else if((level == 3 || level == 4) && data == 1)
					location.href = "<?php echo base_url('auditor/list_nc_surv') ?>";
				else
					alert('something went wrong.');
			});
		});
	</script>