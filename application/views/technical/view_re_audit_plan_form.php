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

    $tracksheet_id = $this->uri->segment(3);
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
							<?php 
								$level = 2;

								$cert_type =  $database_record->certification_type;	
								$cert_type_text = 0;
								
								if($cert_type == 1)
								{
									$level = 2;
									$cert_type_text = "Cert";
									$stage = "Stage 2";
								}
								else if($cert_type == 2)
								{
									$level = 3;
									$cert_type_text = "S1";
									$stage = "Surveillance 1";
								}
								else if($cert_type == 3)
								{
									$level = 4;
									$cert_type_text = "S2";
									$stage = "Surveillance 2";
								}
								else if($cert_type == 4)
								{
									$level = 2;
									$cert_type_text = "RC";
									$stage = "Stage 2";
								}
							?>	
		                    <li><a href="#">Customer ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                    <li><a href="#">Company Name<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                    <li><a href="#">Compnay Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>   
		                    <li><a href="#">Contact Name<span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>    
		                                             
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                   <li><a href="#">Contact Number<span class="pull-right  "><?php echo $database_record->contact_number; ?></span></a></li>  
		                    <li><a href="#">Contact Email<span class="pull-right  "><?php echo $database_record->contact_email; ?></span></a></li>      	
		                    <li>
		                    	<a href="#">
			                    	Standard 
			                    	<span class="pull-right">
			                    		<?= $database_record->scheme_name; ?>
			                    	</span>
		                    	</a>
		                    </li>
		                    <li><a href="#">Stage Of Audit<span class="pull-right  "><?php echo $stage; ?></span></a></li>		
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
		            <?php
	            		if($level == 2)
	            		{
	            			if($cert_type == 1)
	            			{
	            	?>
		            			<b>Stage 2</b>
								<br>
								<ul>
									<li>To evaulate the implementation against all requirement of standard and its effectiveness.</li>
									<li>To evaluate the performance monitoring, measuring, reporting and reviewing against key performance objectives and targets  and response to your client’s policies.</li>
									<li>To check the operational control of the client's processes</li>
									<li>Ensure internal auditing and management review is effectively performed</li>
									<li>links between the normative requirements, policy, performance objectives and targets</li>
									<li>Applicable legal requirements, responsibilities, competence of personnel, operations, procedures</li>
								</ul>
	            	<?php
	            			}
	            			else if($cert_type == 4)
	            			{
	            	?>
	            				<b>Re-Certification</b>
								<br>
								<ul>
									<li>To review your organizations documentation, evaluate your sites and specific conditions.</li>
									<li>Your overall understanding of significant aspect, processes, objectives and operation and additionally the requirements stated in stage2. </li>
								</ul>
	            	<?php		
	            			}
	            		}
	            		else if($level ==3 || $level ==4)
	            		{
	            	?>
	            			<b>Surveillance</b>
							<br>
							<ul>
								<li>To evaluate the changes in scope, processes, locations and statutory and regulatory requirement, documentation and implentation against all standards, its effectiveness and its improvements</li>
								<li>And additionally the requirements stated in stage2.</li>
							</ul>
	            	<?php
	            		}
	            	?>		        
					</div>
	            </div>
	        </div>
	    </div>
	</section>

<!--------audit plan team plan------>
	<?php
		$dates = [];
		foreach ($get_audit_summary_records as $key => $get_audit_summary_record)
		{			
			$re_audit_date = $get_audit_summary_record['re_audit_date'];	          			

			array_push($dates, $re_audit_date);
		}
	?>
		<section class="content">
			<div class="col-md-12">
	            <!-- general form elements -->                
	            <div class="box box-primary">
	                <div class="box-header with-border">
		              	<h3 class="box-title"><?php echo $re_audit_date; ?></h3>

		              	<div class="box-tools pull-right">
		                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		              	</div>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">		          
		            	<div class="col-md-6 col-sl-6 col-xs-6">
		            		<b>Auditor</b>
		            		<ul class="nav nav-stacked">
		            		<?php
        						foreach ($audit_plan_team_plan_records as $key => $value) 
        						{
        							$date = $value['date'];
        							$type = $value['type'];
        							if($date == $re_audit_date && $type == 1)
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

<!----------audit plan process listing--------->
	<section class="content">
		<div class="col-md-12">
            <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Audit Plan Re-Audit</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>	

		        <div class="box-body">
				<?php
					foreach ($get_audit_summary_records as $key => $get_audit_summary_record)
					{			
						$re_audit_date = $get_audit_summary_record['re_audit_date'];	          			
						$formatted_this_date = $re_audit_date;
				?>
						<div class="col-md-12" style="background: ;">
							<h3><?php echo $formatted_this_date; ?></h3>
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