<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	$tracksheet_id = $this->uri->segment(3);

	$level1 = $this->uri->segment(4);
	$defined_level = $this->uri->segment(4);

	if($defined_level == 1)
		$stage = "Stage 1";
	else if($defined_level == 2)
		$stage = "Stage 2";
	else if($defined_level == 3)
		$stage = "Surveillance 1";
	else if($defined_level == 4)
		$stage = "Surveillance 2";
	else if($defined_level == 11)
	{
		$defined_level = 2;
		$stage = "Re-Audit";
	}
?>

<!------client and tracksheet details-------->
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
		                		$old_tracksheet_id = $database_record->old_tracksheet_id;
		                		
		                		$cert_type = $database_record->certification_type;

		                		$cb_type = $database_record->cb_type;
								$cb_type_name = 0;
								if($cb_type == 1)
									$cb_type_name = "EAS";
								else
									$cb_type_name = "IAS";
							?>	
		                	<li><a href="#">Tracksheet ID<span class="pull-right  "><?php echo $tracksheet_id; ?></span></a></li>
		                	<li><a href="#">Client ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                	<li><a href="#">Client Name<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                	
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Contact Name<span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>
		                  	<li><a href="#">Contact Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>
		                  	<li><a href="#">Scheme<span class="pull-right  "><?php echo $database_record->scheme_name; ?></span></a></li>
		              </ul>
		            </div>		          	          
	            </div>           
            </div>
        </div>
	</section>

<!--------stage1 audit team plan-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title"> <?php echo $stage; ?> Reviewer Team</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	            <!-----showing already assigned reviewers------>	
	            	<ul class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked ul_reviewer">
	            		<?php		            		
		            		$auditor_ids1 = [];
		            		foreach ($app_rev_audit_team_record as $key => $app_rev_audit_team)
		            		{
		            			$type = $app_rev_audit_team['type'];
		            			$level = $app_rev_audit_team['level'];

		            			if($type == 3 && $level == $defined_level) // type = 3 = reviewer
		            			{
		            				$id = $app_rev_audit_team['id'];
		            				$auditor_id = $app_rev_audit_team['auditor_id'];
		            				$auditor_name = $app_rev_audit_team['username'];

		            				array_push($auditor_ids1, $auditor_id);

		            			?>
		            				<li class="rows_to_update" row_id="<?php echo $id; ?>" auditor_id="<?php echo $auditor_id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?> <button class="btn btn-danger delete_team_button">x</button></span></a></li><br>
		            			<?php
		            			}	            		
		            		}
		            	?>
	            	</ul>	
	            	<br>

	            <!-----space to assign new reviewers------>
	            	<ul id="stage1_more_reviewers" class="nav nav-stacked col-md-6 col-sl-6 col-xs-6">
               			<li>
               				<select style="width: 100%; height: 34px;" level="<?php echo $defined_level; ?>" class="audit_auditor" type="3" sector="1">
               					<option value="0">Select Reviewer</option>
               					<?php
               						foreach($get_auditors as $key => $value) 
               						{
               							$auditor_id = $value['ac_id'];
							            $anzsic_code = $value['anzsic_code'];
							            $auditor_name = $value['username'];
	               						echo "<option value=\"$auditor_id\">" . $auditor_name . " - " . $anzsic_code . "</option>";
               						}
               					?>
               				</select>
               				<br><br>
               			</li>
               		</ul>
	            	<div class="col-md-6 col-sl-6 col-xs-6">
               			<button class="btn btn-primary add_more_auditors_button">Add More</button>   
               		</div> 

               		<div class="col-md-12 col-sl-12 col-xs-12 text-center">
               			<button class="btn btn-success submit_button">Done</button>   
               		</div>

               	<!-----notify ------>
               		<div class="col-md-12 col-sl-12 col-xs-12" >		               
		            	<br>
		            	<b>Notify Reviewer: </b>
		            	<?php
		            		$flag1 = 0;

		            		foreach ($get_notify_status as $key => $value)
		            		{
		            			$level = $value['level'];
		            			if($level == $level1) //comparing with original level
		            			{
		            				$flag1 = 1;
		            				break;
		            			}
		            		}

		            		if($flag1 == 0)
		            		{
		            			if($level1 == 11)
		            			{
		            				echo "<button class=\"btn btn-primary notify\" level=\"$level1\">Notify</button>";
		            			}
		            			else
		            			{
		            				echo "<button class=\"btn btn-primary notify\" level=\"$defined_level\">Notify</button>";
		            			}		            			
		            		}
		            		else
		            		{
		            			echo "Already Notified";
		            		}
		            	?>		                	           
		            </div>	              	
	            </div>
	        </div>
	    </div>
	</section>

<!---script-------->
	<script type="text/javascript">
	//on clicking on delete button
		$('.delete_team_button').click(function()
		{
			$(this).hide();
			
			var row_id = $(this).parent().parent().parent().attr('row_id');

			$.post('<?php echo base_url('planning_actions/delete_row_in_app_rev_audit_team_plan'); ?>', {row_id: row_id}, function(data)
			{
				if(data == 1)
				{
					location.reload();
				}
				else
				{
					alert('something went wrong in deleting the team plan');
				}
			});		
		});

	//to add more auditors expert	
		$('.add_more_auditors_button').click(function()
		{
			var html = $(this).parent().parent().find('#stage1_more_reviewers li:first').html();
			$(this).parent().parent().find('#stage1_more_reviewers').append("<li>" + html + "<li>");
		});

	//on clicking on done button
		$('.submit_button').click(function()
		{
			$(this).hide();

			var defined_level = "<?php echo $defined_level; ?>";

			if(defined_level == 3 || defined_level == 4)
			{
				var tracksheet_id = "<?php echo $old_tracksheet_id; ?>";
			}	
			else
			{
				var tracksheet_id = "<?php echo $tracksheet_id; ?>";
			}
			
			var app_rev_form_id = 0;

			$('.audit_auditor').each(function()
			{
				var auditor_id =  $(this).val();
				
				if(auditor_id != 0)
				{
					var level = $(this).attr('level');
					var type = $(this).attr('type');
					var sector = $(this).attr('sector');
					
					var date_from = "";
					var date_to = "";

					$.post('<?=base_url()?>planning_actions/insert_in_app_rev_audit_team_plan', {tracksheet_id: tracksheet_id, app_rev_form_id: app_rev_form_id, level:level, auditor_id:auditor_id, type:type, date_from: date_from, date_to: date_to, sector:sector}, function(data)
					{

					});			
				}					
			});		
				
		//redirecting to the audit program page
			window.setTimeout(function()
			{
				location.reload();
			}, 1000);	
		});
	
	//on clicking on notify button for stage 1
		$('.notify').click(function()
		{
			$(this).hide();

			var level = $(this).attr('level');
			var tracksheet_id = "<?php echo $tracksheet_id; ?>";

			var defined_level = "<?php echo $defined_level; ?>";
			var cert_type = "<?php echo $cert_type; ?>";

			var auditor_ids = [];
			$('.rows_to_update').each(function()
			{
				var auditor_id =  $(this).attr('auditor_id');
				
				auditor_ids.push(auditor_id);						
			});	

			$.post('<?php echo base_url('planning_actions/notify_reviewer_about_audit_report'); ?>', {tracksheet_id: tracksheet_id, level:level, auditor_ids: auditor_ids}, function(data)
			{	
				location.reload();
			});		
		});
	</script>