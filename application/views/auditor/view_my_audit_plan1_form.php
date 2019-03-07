<?php
  	if($out_of_index != 0)
        die("Page Not Found"); 

	$userid = $_SESSION['userid'];
	$count = count($audit_plan_notify_to_auditor_records);

	if($count == 0)
	{
		die("Permission denied");
	}
	else
	{
		$this_record = $audit_plan_notify_to_auditor_records[0];

		$user_id = $this_record['user_id'];
		if($user_id != $userid)
		{
			die("Permission denied");
		}
		else
		{
			$confid_agg_status = $this_record['confid_agg_status'];
			if($confid_agg_status != 3)
			{
				$audit_plan_notify_to_auditor_id = $this_record['id'];
				redirect(base_url('auditor/fill_confid_agg/' . $audit_plan_notify_to_auditor_id ) ,'refresh');
				die("Fill the confidentiality and no conflict of interest agreement");
			}
		}
	}
?>

<!--------application details-------->
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
		                    <li><a href="#">Customer ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                    <li><a href="#">Company Name<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                    <li><a href="#">Company Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>   
		                    <li><a href="#">Contact Name<span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>    
		                    <li><a href="#">Contact Number<span class="pull-right  "><?php echo $database_record->contact_number; ?></span></a></li>  
		                    <li><a href="#">Contact Email<span class="pull-right  "><?php echo $database_record->contact_email; ?></span></a></li>                                
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Scope<span class="pull-right  "><?php echo $app_rev_form_record->scope; ?></span></a></li>		
		                    <li>
		                    	<a href="#">
			                    	Standard 
			                    	<span class="pull-right">
			                    		<?= $database_record->scheme_name; ?>
			                    	</span>
		                    	</a>
		                    </li>
		                    <li><a href="#">Stage Of Audit<span class="pull-right  ">Stage 1</span></a></li>		
		              </ul>
		            </div>	
	            </div>   
	                
            </div>
        </div>
	</section>

<!--------THE AUDIT OBJECTIVE-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">THE AUDIT OBJECTIVE</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	            	<div class="col-md-12 col-sl-12 col-xs-12">
		            	<b>Stage1 </b>
						<br>
						<ul>
							<li>To review your organizations  documentation, evaluate your sites and specific conditions to ensure your prepardness for stage2. </li>
							<li>Your overall understanding of significant aspects, processes, objectives and operation.</li>
							<li>To ensure the scope, processes, locations and statutory and regulatory requirement if any..</li>
							<li>Required resources for stage 2  audit.</li>
							<li>Levels of controls established. </li>
							<li>Process and Equipments used.</li>
						</ul>
					</div>
	            </div>
	        </div>
	    </div>
	</section>

<!--------running loop between from date to to date for stage 1---->	
<!--------audit plan team plan------>
	<?php
		$tracksheet_id = $audit_program_form_records['tracksheet_id'];

	//getting the dates between from date to to date
		$stage1_date_from = $audit_program_form_records['stage1_date_from'];
		$stage1_date_to = $audit_program_form_records['stage1_date_to'];
		$stage1_auditor_sector = $audit_program_form_records['stage1_auditor_sector'];

		$new_stage1_date_to_in_time = strtotime($stage1_date_to) + 86400;
		$new_stage1_date_to_in_string = date('Y-m-d', $new_stage1_date_to_in_time);

		$begin = new DateTime($stage1_date_from);
		$end = new DateTime($new_stage1_date_to_in_string);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);

		$dates = []; //variable to store all the dates of stage 1 audit

		foreach ($period as $dt) 
		{
			$this_date = $dt->format("d-m-Y");
			$formatted_this_date = $dt->format("Y-m-d");
			array_push($dates, $this_date);
	?>
		<section class="content">
			<div class="col-md-12">
	            <!-- general form elements -->                
	            <div class="box box-primary">
	                <div class="box-header with-border">
		              	<h3 class="box-title"><?php echo $this_date; ?></h3>

		              	<div class="box-tools pull-right">
		                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		              	</div>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
		            	<div class="col-md-6 col-sl-6 col-xs-6">
		            		<b>Technical Expert</b>
		            		<ul class="nav nav-stacked">				
            				<?php
        						foreach ($audit_plan_team_plan_records as $key => $value) 
        						{
        							$date = $value['date'];
        							$type = $value['type'];
        							if($date == $formatted_this_date && $type == 2)
        							{	            								
        								$username = $value['username'];
	            					?>
	            						<li><a href="#">Name<span class="pull-right  "><?php echo $username; ?></span></a></li>	
	            					<?php		
        							}	            								            						
        						}
        					?> 	            			
		            		</ul>
		            	</div>

		            	<div class="col-md-6 col-sl-6 col-xs-6">
		            		<b>Auditor</b>
		            		<ul class="nav nav-stacked">
		            			<?php
        						foreach ($audit_plan_team_plan_records as $key => $value) 
        						{
        							$date = $value['date'];
        							$type = $value['type'];
        							if($date == $formatted_this_date && $type == 1)
        							{	            								
        								$username = $value['username'];
	            					?>
	            						<li><a href="#">Name<span class="pull-right  "><?php echo $username; ?></span></a></li>	
	            					<?php		
        							}	            								            						
        						}
        					?> 	  		            			
		            		</ul>		            				            		
		            	</div>
		            </div>
		        </div>
		    </div>
		</section>
	<?php				    
		}
	?>

<!----------audit plan process listing--------->
	<section class="content">
		<div class="col-md-12">
            <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Audit Plan Stage 1</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>	

		        <div class="box-body">
				<?php
					$tracksheet_id = $audit_program_form_records['tracksheet_id'];

				//getting the dates between from date to to date
					$stage1_date_from = $audit_program_form_records['stage1_date_from'];
					$stage1_date_to = $audit_program_form_records['stage1_date_to'];
					$stage1_auditor_sector = $audit_program_form_records['stage1_auditor_sector'];

					$new_stage1_date_to_in_time = strtotime($stage1_date_to) + 86400;
					$new_stage1_date_to_in_string = date('Y-m-d', $new_stage1_date_to_in_time);

					$begin = new DateTime($stage1_date_from);
					$end = new DateTime($new_stage1_date_to_in_string);

					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);

					$dates = []; //variable to store all the dates of stage 1 audit

					foreach ($period as $dt) 
					{
						$this_date = $dt->format("d-m-Y");
						$formatted_this_date = $dt->format("Y-m-d");
						array_push($dates, $this_date);
				?>
						<div class="col-md-12" style="background: ;">
							<h3><?php echo $this_date; ?></h3>
							<ul class="nav nav-stacked">
							<?php
								foreach ($audit_plan_process_list as $key => $value) 
								{
									$date = $value['date'];

									if($date == $formatted_this_date)
									{
										$time_from = $value['time_from'];
										$time_to = $value['time_to'];
										$process_name = $value['process_name'];										

										$grouped_type = $value['grouped_type'];
										$types = explode(',', $grouped_type);

										$grouped_username = $value['grouped_username'];
										$usernames = explode(',', $grouped_username);
									?>
									<li style="border: 2px lightgrey solid; padding: 5px; margin: 4px;">
										From: <input type="time" disabled="disabled" class="input_height" value="<?php echo $time_from; ?>">
										To: <input type="time" disabled="disabled" class="input_height" value="<?php echo $time_to; ?>">
										<input class="audit_prog_proc_list_input" disabled="disabled" type="text" value="<?php echo $process_name; ?>" /> 
										<br><br>
										<?php
											foreach ($types as $key => $type) 
											{										
												if($type == 2)
												{
												?>
													<div class="list-group-item"><a href="#">Technial Expert<span class="pull-right  "><?php echo $usernames[$key]; ?></span></a></div>
												<?php												
												}
												else if($type == 1)
												{
												?>
													<div class="list-group-item"><a href="#">Auditor<span class="pull-right  "><?php echo $usernames[$key]; ?></span></a></div>
												<?php												
												}
											}
										?>
					            	</li>
									<?php
									}
								}
							?>
							</ul>
						</div>
				<?php				    
					}
				?>
		        </div>
	        </div>
	    </div>
	</section>