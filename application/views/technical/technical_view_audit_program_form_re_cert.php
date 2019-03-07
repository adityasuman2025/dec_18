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

<!--------tracksheet basic details-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">TrackSheet Details</h3>

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

                        $tracksheet_status =  $database_record->status;  

                        $tracksheet_status_text = 0;
                        if($tracksheet_status == 1)
                          $tracksheet_status_text = "Running";
                        else if($tracksheet_status == 2)
                          $tracksheet_status_text = "Pending";
                        else if($tracksheet_status == 3)
                          $tracksheet_status_text = "Withdrawn";
                        else if($tracksheet_status == 4)
                            $tracksheet_status_text = "Suspended";
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
                          <li><a href="#">Tracksheet Status<span class="pull-right  "><?php echo $tracksheet_status_text; ?></span></a></li>
  		              	</ul>
		                </div>	
                </div>     
            </div>
        </div>
	</section>

<!--------variables-------->
	<?php
		$audit_prog_form_id = $audit_program_form_records['audit_prog_form_id'];
		$no_of_sites = $audit_program_form_records['no_of_sites'];

		$stage1_auditor_sector = $audit_program_form_records['stage1_auditor_sector'];
		$stage1_date_from = $audit_program_form_records['stage1_date_from'];
		$stage1_date_to = $audit_program_form_records['stage1_date_to'];

		$stage2_auditor_sector = $audit_program_form_records['stage2_auditor_sector'];
		$stage2_date_from = $audit_program_form_records['stage2_date_from'];
		$stage2_date_to = $audit_program_form_records['stage2_date_to'];

		$surv1_auditor_sector = $audit_program_form_records['surv1_auditor_sector'];
		$surv1_date_from = $audit_program_form_records['surv1_date_from'];
		$surv1_date_to = $audit_program_form_records['surv1_date_to'];

		$surv2_auditor_sector = $audit_program_form_records['surv2_auditor_sector'];
		$surv2_date_from = $audit_program_form_records['surv2_date_from'];
		$surv2_date_to = $audit_program_form_records['surv2_date_to'];

		function sector_list($selected)
		{
			if($selected == 0)
			{
				echo "	<option value=\"0\">Select</option>
	            		<option value=\"1\">Primary Auditors</option>
	            		<option value=\"2\">Secondary Auditors</option>";
			}
			elseif ($selected == 1) 
			{
				echo "	<option value=\"1\">Primary Auditors</option>	            		
	            		<option value=\"2\">Secondary Auditors</option>";
			}
			elseif ($selected == 2) 
			{
				echo "	<option value=\"2\">Secondary Auditors</option>
						<option value=\"1\">Primary Auditors</option>";
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
		                    <li><a href="#">Name of Organization<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                    <li><a href="#">Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>                                   
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

		                    <li><a href="#">No. of sites<span class="pull-right  "><?php echo $no_of_sites; ?></span></a></li>
		              </ul>
		            </div>	
	            </div>           
            </div>
        </div>
	   </section>

<!--------Stage 2-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Stage 2 Audit</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12 col-sl-12 col-xs-12">
                    	<ul class="nav nav-stacked">

                    		<li><a href="#">Man Days<span class="pull-right"><?php echo $app_rev_form_record->stage2_man_days; ?> days</span></a></li>                    		
                			<li><a href="#">Date From<span class="pull-right"><?php echo $stage2_date_from; ?></span></a></li>                		
                			<li><a href="#">Date To<span class="pull-right"><?php echo $stage2_date_to; ?></span></a></li>
                    		                    		                    
                    	<!----tech expert------->	                    		
                    		<div class="col-md-6 col-sl-6 col-xs-6 nav nav-stacked">
                    			<b>Technical Expert</b>                      			              	
								<?php									
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];

			               				$type = $value['type'];
			               				
			               				if($level == 2 && $type == 2)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
                    		</div>

                    	<!-------auditors---->
			               	<div class="col-md-6 col-sl-6 col-xs-6 nav nav-stacked">			               		               
								<?php
	                    			if($stage2_auditor_sector == 1)
	                    			{
	                    				echo "<b>Primary Auditors</b>";
	                    			}
	                    			else
	                    			{
	                    				echo "<b>Secondary Auditors</b>";
	                    			}

			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];
			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary

			               				if($level == 2 && $type== 1 && $sector == $stage2_auditor_sector)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>
			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               	</div>
                    	</ul>
                    </div>
                </div>     
            </div>
        </div>
	</section>

<!--------Surveillance 1-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Surveillance 1</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12 col-sl-12 col-xs-12">
                    	<ul class="nav nav-stacked">

                    		<li><a href="#">Man Days<span class="pull-right"><?php echo $app_rev_form_record->surv1_man_days; ?> days</span></a></li>
                			<li><a href="#">Date From<span class="pull-right"><?php echo $surv1_date_from; ?></span></a></li>                		
                			<li><a href="#">Date To<span class="pull-right"><?php echo $surv1_date_to; ?></span></a></li>
                    		                    		                    
                    	<!----tech expert------->	                    		
                    		<div class="col-md-6 col-sl-6 col-xs-6 nav nav-stacked">
                    			<b>Technical Expert</b>                      			              	
								<?php									
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];

			               				$type = $value['type'];
			               				
			               				if($level == 3 && $type == 2)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
                    		</div>

                    	<!-------auditors---->
			               	<div class="col-md-6 col-sl-6 col-xs-6 nav nav-stacked">			               		               
								<?php
	                    			if($surv1_auditor_sector == 1)
	                    			{
	                    				echo "<b>Primary Auditors</b>";
	                    			}
	                    			else
	                    			{
	                    				echo "<b>Secondary Auditors</b>";
	                    			}

			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];
			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary

			               				if($level == 3 && $type== 1 && $sector == $surv1_auditor_sector)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>
			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               	</div>
                    	</ul>
                    </div>
                </div>     
            </div>
        </div>
	</section>

<!--------Surveillance 2-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Surveillance 2</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12 col-sl-12 col-xs-12">
                    	<ul class="nav nav-stacked">

                    		<li><a href="#">Man Days<span class="pull-right"><?php echo $app_rev_form_record->surv2_man_days; ?> days</span></a></li>
                			<li><a href="#">Date From<span class="pull-right"><?php echo $surv2_date_from; ?></span></a></li>                		
                			<li><a href="#">Date To<span class="pull-right"><?php echo $surv2_date_to; ?></span></a></li>
                    		                    		                    
                    	<!----tech expert------->	                    		
                    		<div class="col-md-6 col-sl-6 col-xs-6 nav nav-stacked">
                    			<b>Technical Expert</b>                      			              	
								<?php									
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];

			               				$type = $value['type'];
			               				
			               				if($level == 4 && $type == 2)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
                    		</div>

                    	<!-------auditors---->
			               	<div class="col-md-6 col-sl-6 col-xs-6 nav nav-stacked">			               		               
								<?php
	                    			if($surv2_auditor_sector == 1)
	                    			{
	                    				echo "<b>Primary Auditors</b>";
	                    			}
	                    			else
	                    			{
	                    				echo "<b>Secondary Auditors</b>";
	                    			}

			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];
			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary

			               				if($level == 4 && $type== 1 && $sector == $surv2_auditor_sector)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>
			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               	</div>
                    	</ul>
                    </div>
                </div>
            </div>
        </div>
	</section>

<!-----------listing process area------------>
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">List Process</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12 col-sl-12 col-xs-12">
                    	<div class="col-md-4 col-sl-4 col-xs-4">
		            		<div class="qstn_qstn">Is any Additional Resources required for audit:</div>
		            	</div>
		            	<div class="col-md-8 col-sl-8 col-xs-8">
		            		<?php  
		            			echo $audit_program_form_records['any_add_reso_req'];
		            		?>
		            	</div>
		            	<br><br><br>

                    	<ul class="nav nav-stacked process_ul">
                    	<!-----listing processes of that scheme system----->	
                    		<?php
                    			function audit_selected_status($status)
                				{
                					if($status == 1) //yes
                					{
                						echo "<option value=\"1\">Yes</option>";
                						echo "<option value=\"2\">No</option>";
                					}
                					else
                					{
                						echo "<option value=\"2\">No</option>";
                						echo "<option value=\"1\">Yes</option>";
                					}
                				}

                    			foreach ($audit_prog_process_list as $key => $value) 
                    			{
                    				$row_id = $value['id'];
                    				$process_name = $value['process_name'];
                    				$stage2_status = $value['stage2_status'];
                    				$surv1_status = $value['surv1_status'];
                    				$surv2_status = $value['surv2_status'];                    				
                    			?>
                    				<li class="audit_prog_proc_list" row_id="<?php echo $row_id; ?>">
                    					<input class="audit_prog_proc_list_input" disabled="disabled" id="audit_prog_proc_list_input" type="text" value="<?php echo $process_name; ?>">
                    					 Stage 2
                    					<select id="stage1_selected" disabled="disabled">                    		
                    						<?php
                    							audit_selected_status($stage2_status);
                    						?>
                    					</select>

                    					 Surveillance 1
                    					<select id="surv1_selected" disabled="disabled">
                    						<?php
                    							audit_selected_status($surv1_status);
                    						?>
                    					</select>

                    					 Surveillance 2
                    					<select id="surv2_selected" disabled="disabled">
                    						<?php
                    							audit_selected_status($surv2_status);
                    						?>
                    					</select>
                    					<button class="btn btn-primary update_process_button">Edit</button>
                    					<button class="btn btn-success save_process_button" style="display: none;">Save</button>
                    					<button class="btn btn-danger delete_process_button">x</button>
                    					<br><br>
                    				</li>
                    			<?php
                    			}	                    		
     						?>

     					<!-----adding more processes----->
     						<li class="audit_prog_proc_list_new">
            					<input class="audit_prog_proc_list_input" id="audit_prog_proc_list_input" type="text" >
            					 Stage 2
            					<select id="stage1_selected">
            						<option value="2">No</option>
            						<option value="1">Yes</option>
            					</select>

            					 Surveillance 1
            					<select id="surv1_selected">
            						<option value="2">No</option>
            						<option value="1">Yes</option>
            					</select>

            					 Surveillance 2
            					<select id="surv2_selected">
            						<option value="2">No</option>
            						<option value="1">Yes</option>
            					</select>
            					 <button class="btn btn-danger delete_new_process_button">x</button>
            					<br><br>
            				</li>    						
                    	</ul> 	

                    	<button id="add_more_process_button" class="btn btn-success">Add Process</button>				
                    </div>
                   

                    <div class="col-md-12 col-sl-12 col-xs-12 text-center">
                    	<br><br>
 						<button id="submit_form_button" class="btn btn-primary">Update</button>
 					</div>
                </div>
            </div>
        </div>
    </section>

<script type="text/javascript">
//on clicking on add more process button	
	$('#add_more_process_button').click(function()
	{
		var html = $('.process_ul li:last').html();
		//alert(html);
		$('.process_ul').append("<li class=\"audit_prog_proc_list\">" + html + "</li>");
	//on clicnking on delete process button
		$('.delete_new_process_button').click(function()
		{
			$(this).parent().remove();
		});
	});

//on clicking on delete process button
	$('.delete_process_button').click(function()
	{
		var this_thing = $(this).parent();
		var row_id = $(this).parent().attr('row_id');

		$.post('<?=base_url()?>technical_action/delete_audit_prog_process_row', {row_id: row_id}, function(data)
		{
			if(data == 1)
			{
				location.reload();
			}
		});
	});

//on clicking on edit button
	$('.update_process_button').click(function()
	{
		$(this).hide();
		var this_thing = $(this).parent();
		this_thing.find('.save_process_button').fadeIn();
		this_thing.find('select, input').attr('disabled', false);
		
	//on clicking on save button	
		$('.save_process_button').click(function()
		{
			var this_paa = $(this).parent();

			var row_id = this_paa.attr('row_id');

			var audit_prog_proc_list_input = this_paa.find('#audit_prog_proc_list_input').val();
			var stage1_selected = this_paa.find('#stage1_selected').val();
			var surv1_selected = this_paa.find('#surv1_selected').val();
			var surv2_selected = this_paa.find('#surv2_selected').val();

			$.post('<?=base_url()?>technical_action/update_audit_prog_process_row', {row_id: row_id, audit_prog_proc_list_input: audit_prog_proc_list_input, stage1_selected:stage1_selected, surv1_selected:surv1_selected, surv2_selected:surv2_selected}, function(data)
			{
				if(data == 1)
				{
					location.reload();
				}
			});
		});
	});

//on clicking on submit buttonm
	$('#submit_form_button').click(function()
	{
		$(this).hide();
		
		var tracksheet_id = "<?php echo $database_record->tracksheet_id; ?>";
		var process_data =[];

		$('.audit_prog_proc_list_new').each(function()
		{
			var temp_data = {};
			var audit_prog_proc_list_input = $(this).find('#audit_prog_proc_list_input').val();

			var stage1_selected = $(this).find('#stage1_selected').val();
			var surv1_selected = $(this).find('#surv1_selected').val();
			var surv2_selected = $(this).find('#surv2_selected').val();

			temp_data.process_name = audit_prog_proc_list_input;
			temp_data.stage2_status = stage1_selected;
			temp_data.surv1_status = surv1_selected;
			temp_data.surv2_status = surv2_selected;

			if(audit_prog_proc_list_input != "")
			{
				process_data.push(temp_data);
			}	
		});

		$.post("<?php echo base_url('technical_action/insert_audit_prog_process'); ?>", {tracksheet_id: tracksheet_id, process_data: process_data}, function(data)
		{	
			if(data == 1)
			{
				location.href = "<?php echo base_url('technical/technical_list_dated_audit_program_form_re_cert'); ?>";			
			}
			else
			{
				alert('something went wrong while entering data into the database.');
			}
		});
	});
</script>