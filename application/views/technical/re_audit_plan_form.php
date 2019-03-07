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

		                		$cert_type = $database_record->certification_type;

								$cb_type = $database_record->cb_type;
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
		            			<li>
		            				<select date="<?php echo $re_audit_date; ?>" class="audit_plan_team_input audit_plan_team_plan">
		            					<?php
	            						foreach ($app_rev_audit_team_record as $key => $value) 
	            						{
	            							$type = $value['type'];
	            							$sector = $value['sector'];

	            							if($type == 1) // auditor
	            							{
	            								$auditor_id = $value['auditor_id'];
	            								$username = $value['username'];

	            							?>
	            								<option type="<?php echo $type; ?>" value="<?php echo $auditor_id; ?>"><?php echo $username; ?></option>
	            							<?php            							
	            							}
	            						}
	            					?>
		            				</select>            					
		            				<br><br>            					
		            			</li>		            			
		            		</ul>		            		
		            		<button class="btn btn-primary add_more_auditor_button">Add More</button>
		            	</div>
		            </div>
		        </div>
		    </div>
		</section>	

<!----------audit plan process listing--------->	
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Audit Plan Stage 2</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
		            <div class="col-md-12 col-sl-12 col-xs-12">
		            	<ul class="nav nav-stacked process_ul">
                    	<!-----listing processes of that scheme system----->	
                    		<?php
                    			foreach ($default_audit_plan_process as $key => $value) 
                    			{
                    				$process_name = $value['process_name'];
                    			?>
                    				<li class="audit_prog_proc_list" style="border-bottom: 4px lightgrey solid; margin-bottom: 5px;">
                    					<br>	
                    				<!-------process name and date input-------->		
			            				<div style="width:100%; ">
				            				<select class="input_height" id="process_date">
					            				<?php
					            					foreach ($dates as $key => $date) 
					            					{
					            						$date = $date;
					            						echo "<option value=\"$date\">$date</option>";
					            					}
					            				?>
					            			</select>   

	                    					<input class="audit_prog_proc_list_input" id="audit_plan_proc_input" type="text" value="<?php echo $process_name; ?>" />                    					
	                    					Time From: <input type="time" class="input_height" id="time_from">
				            				Time To: <input type="time" class="input_height" id="time_to">
	                    					<button class="btn btn-danger pull-right delete_process_button">x</button>
                    					</div>
				            			<br>

				            		<!-------technial expert input-------->
				            			<div style="width: 50%; display: inline-block; vertical-align: top;">
				            				<b>Technical Expert</b>
				            				<ul class="nav nav-stacked">
					            				<li>
						            				<select class="audit_plan_team_input audit_plan_auditor">
						            					<option value="0">Select Technical Expert</option>
						            					<?php
					            						foreach ($app_rev_audit_team_record as $key => $value) 
					            						{
					            							$type = $value['type'];
					            							if($type == 2)
					            							{
					            								$auditor_id = $value['auditor_id'];
					            								$username = $value['username'];
					            							?>
					            								<option type="<?php echo $type; ?>" value="<?php echo $auditor_id; ?>"><?php echo $username; ?></option>
					            							<?php
					            							}
					            						}
					            					?>
						            				</select>
						            				<br><br>            					
						            			</li>		            			
						            		</ul>		            		
						            		<button class="btn btn-primary add_more_auditor_button">Add More</button>
				            			</div>

				            		<!-------auditor input-------->
				            			<div style="width: 49%; display: inline-block; vertical-align: top;">
						            		<b>Auditor</b>
						            		<ul class="nav nav-stacked">
						            			<li>
						            				<select class="audit_plan_team_input audit_plan_auditor">
						            					<!-- <option value="0">Select Auditor</option> -->
						            					<?php
					            						foreach ($app_rev_audit_team_record as $key => $value) 
					            						{
					            							$type = $value['type'];
					            							$sector = $value['sector'];

					            							if($type == 1)
					            							{
					            								$auditor_id = $value['auditor_id'];
					            								$username = $value['username'];
					            							?>
					            								<option type="<?php echo $type; ?>" value="<?php echo $auditor_id; ?>"><?php echo $username; ?></option>
					            							<?php            							
					            							}
					            						}
					            					?>
						            				</select>            					
						            				<br><br>            					
						            			</li>		            			
						            		</ul>		            		
						            		<button class="btn btn-primary add_more_auditor_button">Add More</button>
						            	</div>				            			
				            			<br><br>
                    				</li>
                    			<?php
                    			}	                    		
     						?>

     					<!-----adding more processes----->
     						<li class="audit_prog_proc_list" style="border-bottom: 4px lightgrey solid; margin-bottom: 5px;">
            					<br>	
            				<!-------process name and date input-------->		
	            				<div style="width:100%; ">
		            				<select class="input_height" id="process_date">
			            				<?php
			            					foreach ($dates as $key => $date) 
			            					{
			            						$date = $date;
			            						echo "<option value=\"$date\">$date</option>";
			            					}
			            				?>
			            			</select>                   					
                					<input class="audit_prog_proc_list_input" id="audit_plan_proc_input" type="text" />                    					
                					Time From: <input type="time" class="input_height" id="time_from">
		            				Time To: <input type="time" class="input_height" id="time_to">
                					<button class="btn btn-danger pull-right delete_process_button">x</button>
            					</div>
		            			<br>

		            		<!-------technial expert input-------->
		            			<div style="width: 50%; display: inline-block; vertical-align: top;">
		            				<b>Technical Expert</b>
		            				<ul class="nav nav-stacked">					            						
			            				<li>
				            				<select class="audit_plan_team_input audit_plan_auditor">
				            					<option value="0">Select Technial Expert</option>
				            				<?php
			            						foreach ($app_rev_audit_team_record as $key => $value) 
			            						{
			            							$type = $value['type'];
			            							if($type == 2)
			            							{
			            								$auditor_id = $value['auditor_id'];
			            								$username = $value['username'];
			            							?>
			            								<option type="<?php echo $type; ?>" value="<?php echo $auditor_id; ?>"><?php echo $username; ?></option>
			            							<?php            							
			            							}
			            						}
			            					?>
				            				</select>
				            				<br><br>            					
				            			</li>		            			
				            		</ul>		            		
				            		<button class="btn btn-primary add_more_auditor_button">Add More</button>
		            			</div>

		            		<!-------auditor input-------->
		            			<div style="width: 49%; display: inline-block; vertical-align: top;">
				            		<b>Auditor</b>
				            		<ul class="nav nav-stacked">
				            			<li>				            				
				            				<select class="audit_plan_team_input audit_plan_auditor">
				            					<!-- <option value="0">Select Auditor</option> -->
				            				<?php
			            						foreach ($app_rev_audit_team_record as $key => $value) 
			            						{
			            							$type = $value['type'];
			            							$sector = $value['sector'];

			            							if($type == 1)
			            							{
			            								$auditor_id = $value['auditor_id'];
			            								$username = $value['username'];

			            							?>
			            								<option type="<?php echo $type; ?>" value="<?php echo $auditor_id; ?>"><?php echo $username; ?></option>
			            							<?php            							
			            							}
			            						}
			            					?>
				            				</select>            					
				            				<br><br>            					
				            			</li>		            			
				            		</ul>		            		
				            		<button class="btn btn-primary add_more_auditor_button">Add More</button>
				            	</div>				            			
		            			<br><br>
            				</li>						
                    	
                    	</ul> 	

                    	<button id="add_more_process_button" class="btn btn-success">Add Process</button>				
		            </div>
		        </div>

		        <div class="text-center">
		        	<br><br>
		        	<button id="submit_form_button" class="btn btn-primary">Submit</button>	
		        	<br><br>
		        </div>		        
		    </div>
		</div>
	</section>

<!---------script------------>
	<script type="text/javascript">
	//on clicking on add more auditor button	
		$('.add_more_auditor_button').click(function() 
		{
			var this_paa = $(this).parent().find('ul');
			var html = this_paa.find('li:first').html();
			this_paa.append("<li>" + html + "</li>");
		});

	//on clicking on delete process button
		$('.delete_process_button').click(function()
		{
			$(this).parent().parent().remove();
		});

	//on clicking on add more process button	
		$('#add_more_process_button').click(function()
		{
			var html = $('.process_ul .audit_prog_proc_list:last').html();

			$('.process_ul').append("<li class=\"audit_prog_proc_list\">" + html + "</li>");

		//on clicking on delete process button
			$('.delete_process_button').click(function()
			{
				$(this).parent().parent().remove();
			});

		//on clicking on add more auditor button	
			$('.add_more_auditor_button').click(function() 
			{
				var this_paa = $(this).parent().find('ul');
				var html = this_paa.find('li:first').html();
				this_paa.append("<li>" + html + "</li>");
			});
		});

	//on clicking on submit button
		$('#submit_form_button').click(function()
		{
			$(this).hide();
			
			var tracksheet_id = "<?php echo $tracksheet_id; ?>";
			var level = 11; //re-audit

			var redirect_level = "<?php echo $level; ?>";
			var cert_type = "<?php echo $cert_type; ?>";

		//inserting the team plan data into the database
			var team_plan_array = [];

			$('.audit_plan_team_plan').each(function()
			{
				var team_plan = {}; //object to store all the properties
				var date = $(this).attr('date');
				var auditor_id = $(this).val();
				var type = $(this).find('option:selected').attr('type');
				
				team_plan.date = date;
				team_plan.auditor_id = auditor_id;
				team_plan.type = type;

				team_plan_array.push(team_plan);
			});

		//inserting the audit plan process into the database
			var process_array = [];

			$('.audit_prog_proc_list').each(function()
			{				
				var date = $(this).find('#process_date').val();
				var process_name = $(this).find('#audit_plan_proc_input').val();
				var time_from = $(this).find('#time_from').val();
				var time_to = $(this).find('#time_to').val();

				if(process_name != "")
				{
					$(this).find('.audit_plan_auditor').each(function()
					{
						var auditor_id = $(this).val();
						if(auditor_id != 0)
						{
							var type = $(this).find('option:selected').attr('type');

							var processes = {}; //object to store all the properties

							processes.date = date;
							processes.process_name = process_name;
							processes.time_from = time_from;
							processes.time_to = time_to;

							processes.auditor_id = auditor_id;
							processes.type = type;

							process_array.push(processes);
						}						
					});				
				}
			});	

		//running query to insert			
			$.post("<?php echo base_url('technical_action/insert_audit_prog_proc_list'); ?>", {tracksheet_id: tracksheet_id, level: level, process_array: process_array}, function(ka)
			{
				$.post("<?php echo base_url('technical_action/insert_audit_plan_team_plan'); ?>", {tracksheet_id: tracksheet_id, level: level, team_plan_array: team_plan_array}, function(da)
				{
				//inserting general records of audit plan form in database
					$.post("<?php echo base_url('technical_action/insert_audit_plan_form_record'); ?>", {tracksheet_id: tracksheet_id, level: level}, function(ok)
					{
					//increasing the flow id of that tracksheet
						$.post('<?=base_url()?>technical_action/incr_flow_status_of_tracksheet', {tracksheet_id: tracksheet_id}, function(k)
						{	
							if(redirect_level == 2)
							{
								if(cert_type == 4)
									location.href= "<?php echo base_url('technical/list_re_audit_re_cert'); ?>";
								else
									location.href= "<?php echo base_url('technical/list_re_audit'); ?>";
							}
							else if(redirect_level == 3 || redirect_level == 4)
								location.href= "<?php echo base_url('technical/list_re_audit_surv'); ?>";
						});						
					});
				});
			});			
		});

	</script>