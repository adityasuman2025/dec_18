<?php
    if($out_of_index == 1)
        die("Page Not Found");    
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

<!--------client details-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Application Details</h3>

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
                    		<li><a href="#">Man Days<span class="pull-right  "><?php echo $app_rev_form_record->stage2_man_days; ?> days</span></a></li>
                    		
                    		<li>
                    			<div class="col-md-6 col-sl-6 col-xs-6">
	                    			Date From
	                    			<input type="date" style="width: 100%; height: 34px;" id="2_date_from" value="<?php echo $stage2_date_from; ?>">
	                    		</div>
	                    		<div class="col-md-6 col-sl-6 col-xs-6">
	                    			Date To
	                    			<input type="date" style="width: 100%; height: 34px;" id="2_date_to" value="<?php echo $stage2_date_to; ?>">
	                    		</div>
                    		</li>
                    	
                    	<!----tech expert------->	
                    		<br><br><br><br>
                    		<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
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
			               					<li class="rows_to_update" level="2" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?> <button class="btn btn-danger delete_team_button">Delete</button></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>
			               		<br>
			               		<ul id="stage1_more_tech_exp" class="nav nav-stacked col-md-6 col-sl-6 col-xs-6">
			               			<li>
			               				<select style="width: 100%; height: 34px;" level="2" class="audit_auditor" type="2" sector="1">
			               					<option value="0">Select Technical Expert</option>
			               					<?php
			               						foreach ($get_tech_exp as $key => $value) 
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
			               			<button class="btn btn-primary add_more_tech_exp_button">Add More</button>   
			               		</div>
                    		</div>
                    		
			            <!----primary auditors------->   		
			               	<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
			               		<b>Primary Auditors</b>                    	
								<?php
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];
			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary

			               				if($level == 2 && $type== 1 && $sector ==1)
			               				{
			               		?>
			               					<li class="rows_to_update" level="2" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               	</div>

			            <!----secondary auditors------->   		
			               	<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
			               		<b>Secondary Auditors</b>
			               		<?php									
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];

			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary
			               				
			               				if($level == 2 && $type == 1 && $sector == 2)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?> <button class="btn btn-danger delete_team_button">Delete</button></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               		<br>
								<ul id="stage1_more_tech_exp" class="nav nav-stacked col-md-6 col-sl-6 col-xs-6">
			               			<li>
			               				<select style="width: 100%; height: 34px;" level="2" class="audit_auditor" type="1" sector="2">
			               					<option value="0">Select Auditors</option>
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
			               	</div>	

			            <!--------choice of auditor sector-------->
			            	<b>Choose between Primary and Sceondary Auditors </b>
			            	<select style="width: 50%; height: 34px;" id="stage2_auditor_sector">
			            		<?php
			            			sector_list($stage2_auditor_sector);
			            		?>
			            	</select>
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
                    		<li><a href="#">Man Days<span class="pull-right  "><?php echo $app_rev_form_record->surv1_man_days; ?> days</span></a></li>
                    		
                    		<li>
                    			<div class="col-md-6 col-sl-6 col-xs-6">
	                    			Date From
	                    			<input type="date" style="width: 100%; height: 34px;" id="3_date_from" value="<?php echo $surv1_date_from; ?>">
	                    		</div>
	                    		<div class="col-md-6 col-sl-6 col-xs-6">
	                    			Date To
	                    			<input type="date" style="width: 100%; height: 34px;" id="3_date_to" value="<?php echo $surv1_date_to; ?>">
	                    		</div>
                    		</li>
                    	
                    	<!----tech expert------->	
                    		<br><br><br><br>
                    		<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
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
			               					<li class="rows_to_update" level="3" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?> <button class="btn btn-danger delete_team_button">Delete</button></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>
			               		<br>
			               		<ul id="stage1_more_tech_exp" class="nav nav-stacked col-md-6 col-sl-6 col-xs-6">
			               			<li>
			               				<select style="width: 100%; height: 34px;" level="3" class="audit_auditor" type="2" sector="1">
			               					<option value="0">Select Technical Expert</option>
			               					<?php
			               						foreach ($get_tech_exp as $key => $value) 
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
			               			<button class="btn btn-primary add_more_tech_exp_button">Add More</button>   
			               		</div>
                    		</div>
                    		
			            <!----primary auditors------->   		
			               	<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
			               		<b>Primary Auditors</b>                    	
								<?php
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];
			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary

			               				if($level == 3 && $type== 1 && $sector ==1)
			               				{
			               		?>
			               					<li class="rows_to_update" level="3" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               	</div>

			            <!----secondary auditors------->   		
			               	<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
			               		<b>Secondary Auditors</b>
			               		<?php									
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];

			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary
			               				
			               				if($level == 3 && $type == 1 && $sector == 2)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?> <button class="btn btn-danger delete_team_button">Delete</button></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               		<br>
								<ul id="stage1_more_tech_exp" class="nav nav-stacked col-md-6 col-sl-6 col-xs-6">
			               			<li>
			               				<select style="width: 100%; height: 34px;" level="3" class="audit_auditor" type="1" sector="2">
			               					<option value="0">Select Auditors</option>
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
			               	</div>	

			            <!--------choice of auditor sector-------->
			            	<b>Choose between Primary and Sceondary Auditors </b>
			            	<select style="width: 50%; height: 34px;" id="surv1_auditor_sector">
			            		<?php
			            			sector_list($surv1_auditor_sector);
			            		?>
			            	</select>
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
                    		<li><a href="#">Man Days<span class="pull-right  "><?php echo $app_rev_form_record->surv2_man_days; ?> days</span></a></li>
                    		
                    		<li>
                    			<div class="col-md-6 col-sl-6 col-xs-6">
	                    			Date From
	                    			<input type="date" style="width: 100%; height: 34px;" id="4_date_from" value="<?php echo $surv2_date_from; ?>">
	                    		</div>
	                    		<div class="col-md-6 col-sl-6 col-xs-6">
	                    			Date To
	                    			<input type="date" style="width: 100%; height: 34px;" id="4_date_to" value="<?php echo $surv2_date_to; ?>">
	                    		</div>
                    		</li>
                    	
                    	<!----tech expert------->	
                    		<br><br><br><br>
                    		<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
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
			               					<li class="rows_to_update" level="4" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?> <button class="btn btn-danger delete_team_button">Delete</button></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>
			               		<br>
			               		<ul id="stage1_more_tech_exp" class="nav nav-stacked col-md-6 col-sl-6 col-xs-6">
			               			<li>
			               				<select style="width: 100%; height: 34px;" level="4" class="audit_auditor" type="2" sector="1">
			               					<option value="0">Select Technical Expert</option>
			               					<?php
			               						foreach ($get_tech_exp as $key => $value) 
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
			               			<button class="btn btn-primary add_more_tech_exp_button">Add More</button>   
			               		</div>
                    		</div>
                    		
			            <!----primary auditors------->   		
			               	<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
			               		<b>Primary Auditors</b>                    	
								<?php
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];
			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary

			               				if($level == 4 && $type== 1 && $sector ==1)
			               				{
			               		?>
			               					<li class="rows_to_update" level="4" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               	</div>

			            <!----secondary auditors------->   		
			               	<div class="col-md-12 col-sl-12 col-xs-12 nav nav-stacked">
			               		<b>Secondary Auditors</b>
			               		<?php									
			               			foreach ($app_rev_audit_team_record as $key => $value) 
			               			{
			               				$id = $value['id'];
			               				$level = $value['level'];
			               				$auditor_id = $value['auditor_id'];
			               				$auditor_name = $value['username'];

			               				$type = $value['type'];
			               				$sector = $value['sector']; //1: primary, 2: secondary
			               				
			               				if($level == 4 && $type == 1 && $sector == 2)
			               				{
			               		?>
			               					<li class="rows_to_update" level="1" row_id="<?php echo $id; ?>"><a>Name<span class="pull-right"><?=$auditor_name ?> <button class="btn btn-danger delete_team_button">Delete</button></span></a></li>

			               		<?php	               				
			               				}
			               			}
			               		?>			               		
			               		<br>
								<ul id="stage1_more_tech_exp" class="nav nav-stacked col-md-6 col-sl-6 col-xs-6">
			               			<li>
			               				<select style="width: 100%; height: 34px;" level="4" class="audit_auditor" type="1" sector="2">
			               					<option value="0">Select Auditors</option>
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
			               	</div>	

			            <!--------choice of auditor sector-------->
			            	<b>Choose between Primary and Sceondary Auditors </b>
			            	<select style="width: 50%; height: 34px;" id="surv2_auditor_sector">
			            		<?php
			            			sector_list($surv2_auditor_sector);
			            		?>
			            	</select>
                    	</ul>
                    </div>
                </div> 

                <div class="col-sl-12 col-md-12 col-xs-12 text-center">
                	<br><br>
                	<button class="btn btn-primary" id="submit_form_button">Update</button>    
                </div>
                
            </div>
        </div>
	</section>


<script type="text/javascript">
//to add more technical expert	
	$('.add_more_tech_exp_button').click(function()
	{
		var html = $(this).parent().parent().find('ul li:first-child').html();
		$(this).parent().parent().find('ul').append("<li>" + html + "<li>");
	});

//to add more auditors expert	
	$('.add_more_auditors_button').click(function()
	{
		var html = $(this).parent().parent().find('ul li:first-child').html();
		$(this).parent().parent().find('ul').append("<li>" + html + "<li>");
	});

//on clicking on delete button
	$('.delete_team_button').click(function()
	{
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

//on clicking on submit buttonm
	$('#submit_form_button').click(function()
	{
		$(this).hide();
		
		var tracksheet_id = "<?php echo $database_record->tracksheet_id; ?>";
		var app_rev_form_id = "<?php echo $app_rev_form_record->app_rev_form_id; ?>";
		var audit_prog_form_id = "<?php echo $audit_prog_form_id; ?>";
		
		var stage1_auditor_sector = $('#stage1_auditor_sector').val();
		var stage1_date_from = $("#1_date_from").val();
		var stage1_date_to = $("#1_date_to").val();

		var stage2_auditor_sector = $('#stage2_auditor_sector').val();
		var stage2_date_from = $("#2_date_from").val();
		var stage2_date_to = $("#2_date_to").val();

		var surv1_auditor_sector = $('#surv1_auditor_sector').val();
		var surv1_date_from = $("#3_date_from").val();
		var surv1_date_to = $("#3_date_to").val();

		var surv2_auditor_sector = $('#surv2_auditor_sector').val();
		var surv2_date_from = $("#4_date_from").val();
		var surv2_date_to = $("#4_date_to").val();

	//entering data into audit program form table
		$.post('<?php echo base_url('planning_actions/update_data_in_audit_program_form_planning'); ?>', {audit_prog_form_id:audit_prog_form_id, stage1_auditor_sector:stage1_auditor_sector, stage2_auditor_sector:stage2_auditor_sector, surv1_auditor_sector:surv1_auditor_sector, surv2_auditor_sector:surv2_auditor_sector ,stage1_date_from:stage1_date_from, stage1_date_to:stage1_date_to, stage2_date_from:stage2_date_from, stage2_date_to:stage2_date_to, surv1_date_from:surv1_date_from, surv1_date_to:surv1_date_to, surv2_date_from:surv2_date_from, surv2_date_to:surv2_date_to}, function(data)
		{		
			//alert(data);
		//entering new data in the database
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

					//alert(auditor_id);

					$.post('<?=base_url()?>planning_actions/insert_in_app_rev_audit_team_plan', {tracksheet_id: tracksheet_id, app_rev_form_id: app_rev_form_id, level:level, auditor_id:auditor_id, type:type, date_from: date_from, date_to: date_to, sector:sector}, function(data)
					{
						//alert(data);
					});
				}			
			});

		//redirecting to the audit program page
			window.setTimeout(function()
			{
				location.href="<?php echo base_url('planning/fill_audit_program_re_cert'); ?>";
			}, 1500);
		});	
	});
</script>