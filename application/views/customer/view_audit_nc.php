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
			$type = $get_all_infos_of_nc['type']; //major or minor or observation

			$nc_clear_status = $get_all_infos_of_nc['nc_clear_status'];
			
		//getting the due date for filling columns
			$added_on = $get_all_infos_of_nc['added_on'];
			$timestamp = strtotime($added_on);

			$last_date_for_corr_actn_time = $timestamp + 2592000; //30 days after NC declaring date
			$last_date_for_corr_actn_date = date('d-m-Y', $last_date_for_corr_actn_time);

			$last_date_for_evidence_time = 0;
			if($type == 2)//minor NC
				$last_date_for_evidence_time = $timestamp + 7776000; //90 days after NC declaring date
			else if($type == 3)//major NC
				$last_date_for_evidence_time = $timestamp + 5184000; //90 days after NC declaring date

			$last_date_for_evidence_date = date('d-m-Y', $last_date_for_evidence_time);
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
					
					<!------nc statement area---------->	
						<div class="col-md-12 col-sl-12 col-xs-12" style="padding: 0; margin: 0;">
							<b>Non Conformity Statement</b>
							<textarea class="nc_statement" disabled="disabled"><?php echo $nc_statement; ?></textarea>		
						</div>

					<!-------client reply area--->
						<div class="col-md-12 col-sl-12 col-xs-12" style="padding: 0; margin: 0;">
							<?php
								if($answer_options == 4) //observation
								{
							?>
									<b>Correction</b>
									<textarea class="correction_div" disabled="disabled"><?php echo $correction; ?></textarea>
									<button class="btn btn-primary pull-right edit_correction_div_btn">Edit</button>
									<button class="btn btn-success pull-right done_correction_div_btn" style="display: none;">Done</button>
							<?php		
								}
								elseif ($answer_options == 2 || $answer_options == 3) //minor or major NC
								{
							?>
								<!------root cause analyse area------->
									<b>Root Cause Analyse</b>
									<textarea class=" root_cause_ana_div" disabled="disabled"><?php echo $root_cause; ?></textarea>
									<button class="btn btn-primary pull-right edit_root_cause_btn">Edit</button>
									<button class="btn btn-success pull-right done_root_cause_btn" style="display: none;">Done</button>

								<!------correction area------->
									<br><br>
									<b>Correction</b>
									<textarea class="correction_div" disabled="disabled"><?php echo $correction; ?></textarea>
									<button class="btn btn-primary pull-right edit_correction_div_btn">Edit</button>
									<button class="btn btn-success pull-right done_correction_div_btn" style="display: none;">Done</button>

								<!------correction action area------->
									<br><br>
									<b>Corrective Action</b> (Due Date: <?php echo $last_date_for_corr_actn_date; ?>)
									<textarea class="corrective_act_text" disabled="disabled"><?php echo $corrective_action_text; ?></textarea>
									<button class="btn btn-primary pull-right edit_corrective_act_text_btn">Edit</button>
									<button class="btn btn-success pull-right done_corrective_act_text_btn" style="display: none;">Done</button>
									<br><br>

								<!------correction action document uploadng area------->
									<?php
										$corrective_action_array = explode('.', $corrective_action);
										$file_name = $corrective_action_array[0];
										
										$comparable_file_name = md5("corr_acc_" . $tracksheet_id . "_" . $level . "_" . $nc_id);

										if($file_name == $comparable_file_name)
										{
											$url = base_url('uploads/audit_report_nc_docs/' . $corrective_action);
											echo "<a class=\"btn btn-primary\" target=\"_blank\" href=\"$url\">View Attached Document</a><br>";
									?>
										<div>
											<b>Upload Supporting Documents</b>(Due Date: <?php echo $last_date_for_evidence_date; ?>)
											<br>
											<input type="file" name="file" id="file" class="file" nc_id="<?php echo $nc_id; ?>" />
											<br>
											<span class="doc_upload_feed"></span>
										</div>												
									<?php	
										}
										else
										{
									?>
										<div>
											<b>Upload Supporting Documents</b>(Due Date: <?php echo $last_date_for_evidence_date; ?>)
											<br>
											<input type="file" name="file" id="file" class="file" nc_id="<?php echo $nc_id; ?>" />
											<br>
											<span class="doc_upload_feed"></span>
										</div>
									<?php		
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
											$commented_by = $_SESSION['client_name'];
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

					<!----- NC clearance status area---------->
						<?php					
							echo "<div class=\"col-sl-12 col-md-12 col-xs-12 text-center\">";	
								if($nc_clear_status == 2)
								{	
									echo "<b style=\"color: green\">This NC has been cleared</b>";							
								}
								else
								{
									echo "<b style=\"color: red\">This NC has not been cleared yet</b>";
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

		.nc_statement, .root_cause_ana_div, .correction_div, .corrective_act_text
		{
			min-height: 60px;
			width: 100%;
			padding: 3px;
			margin: 0px;
			resize: none;
			vertical-align: top;
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

	//on clicking on add comment button
		$('.add_cmmnt_btn').click(function()
		{
			$(this).hide();
			
			var this_paa = $(this).parent();

			var comment = this_paa.find('.checklist_comment_textarea').val();
			var nc_id = this_paa.parent().attr('nc_id');
			var comment_type = 1; //client

			$.post("<?php echo base_url('customer/add_audit_report_nc_comments_in_db'); ?>", {tracksheet_id: tracksheet_id, level: level, nc_id: nc_id, comment: comment, comment_type: comment_type}, function(data)
			{
				if(data == 1)
					location.reload();
				else	
					alert('something went wrong while commenting');
			});
		});
	
	//on clicking on edit root cause button
		$('.edit_root_cause_btn').click(function()
		{
			$(this).hide();
			$(this).parent().find('.done_root_cause_btn').show();

			$(this).parent().find('.root_cause_ana_div').attr('disabled', false);

		//on clicking on save root cause btn
			$('.done_root_cause_btn').click(function()
			{	
				var root_cause = $(this).parent().find('.root_cause_ana_div').val();
				var id = $(this).parent().parent().attr('nc_id');

				var data = {};
				data.root_cause = root_cause;

				$.post('<?php echo base_url('customer/edit_nc_details'); ?>', {id: id, data: data}, function(e)
				{
					if(e == 1)
						location.reload();
					else	
						alert('Something went wrong while editing nc data.');
				});
			});
		});

	//on clicking on edit correction button
		$('.edit_correction_div_btn').click(function()
		{
			$(this).hide();
			$(this).parent().find('.done_correction_div_btn').show();

			$(this).parent().find('.correction_div').attr('disabled', false);

		//on clicking on save root cause btn
			$('.done_correction_div_btn').click(function()
			{	
				var correction = $(this).parent().find('.correction_div').val();
				var id = $(this).parent().parent().attr('nc_id');

				var data = {};
				data.correction = correction;

				$.post('<?php echo base_url('customer/edit_nc_details'); ?>', {id: id, data: data}, function(e)
				{
					if(e == 1)
						location.reload();
					else	
						alert('Something went wrong while editing nc data.');
				});
			});
		});

	//on clicking on edit corrective action text button
		$('.edit_corrective_act_text_btn').click(function()
		{
			$(this).hide();
			$(this).parent().find('.done_corrective_act_text_btn').show();

			$(this).parent().find('.corrective_act_text').attr('disabled', false);

		//on clicking on save root cause btn
			$('.done_corrective_act_text_btn').click(function()
			{	
				var corrective_action_text = $(this).parent().find('.corrective_act_text').val();
				var id = $(this).parent().parent().attr('nc_id');

				var data = {};
				data.corrective_action_text = corrective_action_text;

				$.post('<?php echo base_url('customer/edit_nc_details'); ?>', {id: id, data: data}, function(e)
				{
					if(e == 1)
						location.reload();
					else	
						alert('Something went wrong while editing nc data.');
				});
			});
		});

	//on choosing a file as corrective action to upload
		$(document).on('change', '.file', function()
		{			
			var id = $(this).attr('nc_id');

			var property = $(this)[0].files[0];
			var file_size = property.size;

			if(file_size > 20000000)
			{
				alert('File Size is more than 20 MB');
			}
			else
			{
				var form_data = new FormData();
				form_data.append("file", property);

				$.ajax({
					url: "<?php echo base_url('customer/upload_corrective_action/' . $tracksheet_id . '/' . $level . '/'); ?>" + id,
					method: "POST",
					data: form_data,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend:function()
					{
						$(this).parent().find('.doc_upload_feed').html('Uploading...');
					},
					success: function(e)
					{
						if(e == "Something went wrong")
						{
							$(this).parent().find('.doc_upload_feed').html(e);
						}
						else
						{											
							var data = {};
							data.corrective_action = e;

							$.post('<?php echo base_url('customer/edit_nc_details'); ?>', {id: id, data: data}, function(e)
							{
								if(e == 1)
									location.reload();
								else	
									alert('Something went wrong while editing nc data.');
							});
						}						
					}
				});
			}		
		});
	</script>