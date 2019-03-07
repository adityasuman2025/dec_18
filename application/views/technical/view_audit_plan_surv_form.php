<?php
	if($out_of_index == 1)
    	//die("Page Not Found");
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

								$certification_type = $database_record->certification_type;

								$certification_type_text = "Surveillance";

								if($certification_type == 2)
								{									
									$certification_type_text = "Surveillance 1";
								}
								else if($certification_type == 3)
								{
									$certification_type_text = "Surveillance 2";
								}
							?>	
		                    <li><a href="#">Customer ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                    <li><a href="#">Company Name<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                    <li><a href="#">Compnay Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>   
		                    <li><a href="#">Contact Name<span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>    
		                    <li><a href="#">Contact Number<span class="pull-right  "><?php echo $database_record->contact_number; ?></span></a></li>  
		                    <li><a href="#">Contact Email<span class="pull-right  "><?php echo $database_record->contact_email; ?></span></a></li>                                
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Scope<span class="pull-right  "><?php echo $database_record->scope; ?></span></a></li>		
		                    <li>
		                    	<a href="#">
			                    	Standard 
			                    	<span class="pull-right">
			                    		<?= $database_record->scheme_name; ?>
			                    	</span>
		                    	</a>
		                    </li>
		                    <li><a href="#">Stage Of Audit<span class="pull-right  "><?php echo $certification_type_text; ?></span></a></li>		
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
		            	<b>Surveillance</b>
						<br>
						<ul>
							<li>To evaluate the changes in scope, processes, locations and statutory and regulatory requirement, documentation and implentation against all standards, its effectiveness and its improvements</li>
							<li>And additionally the requirements stated in stage2.</li>
						</ul>
					</div>
	            </div>
	        </div>
	    </div>
	</section>

<!--------running loop between from date to to date for stage 1---->	
<!--------audit plan team plan------>
	<?php
		$tracksheet_id = $this->uri->segment(3);

		if($certification_type == 2)
		{
			$date_from = "surv1_date_from";
			$date_to = "surv1_date_to";
			$auditor_sector = "surv1_auditor_sector";
			
			$level = 3;
		}
		else if($certification_type == 3)
		{
			$date_from = "surv2_date_from";
			$date_to = "surv2_date_to";
			$auditor_sector = "surv2_auditor_sector";

			$level = 4;
		}

	//getting the dates between from date to to date
		$stage1_date_from = $audit_program_form_records[$date_from];
		$stage1_date_to = $audit_program_form_records[$date_to];
		$stage1_auditor_sector = $audit_program_form_records[$auditor_sector];

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
	              	<h3 class="box-title">Audit Plan Stage 2</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>	

		        <div class="box-body">
				<?php									
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