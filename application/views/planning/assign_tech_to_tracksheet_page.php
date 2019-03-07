<?php
    if($out_of_index == 1)
        die("Page Not Found");    
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
		                		$cm_id=$database_record->cm_id;

								$cb_type=$database_record->cb_type;
								$cb_type_name = 0;
								if($cb_type == 1)
									$cb_type_name = "EAS";
								else
									$cb_type_name = "IAS";
							?>	
		                    <li><a href="#">ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                    <li><a href="#">Name <span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                    <li>
		                    	<a href="#">
			                    	CB Type 
			                    	<span class="pull-right">
			                    		<?php 
											echo $cb_type_name;
										?>	
			                    	</span>
		                    	</a>
		                    </li>                                      
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Contact Name <span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>
		                    <li><a href="#">Contact Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>
		                     <li>
		                    	<a href="#">
			                    	Location 
			                    	<span class="pull-right">
			                    		<?php 
											$cb_type=$database_record->location;
											if($cb_type == 1)
												echo "Local";
											else
												echo "Foreign";
										?>	
			                    	</span>
		                    	</a>
		                    </li>
		              </ul>
		            </div>		            

	              <!-- /.table-responsive -->	
	              	<div class="col-md-12 col-sl-12 col-xs-12" style="text-align: center;">
	              		<br>
		                <?php echo form_open(base_url('planning/view_customer_info/' . $cm_id),['class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
                       		<input id="view_tracksheet_button" type="submit" value="View Complete Details" class="btn btn-primary" />
                    	<?php echo form_close("\n")?>
		           	</div>  
	            </div>           
            </div>
        </div>
	   </section>

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
                    		$tracksheet_id = $database_record->tracksheet_id;

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
                            <li><a href="#">Scope Type<span class="pull-right  "><?php echo $database_record->scope;; ?></span></a></li>                           
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

<!--------assign tehcnical employee-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
        	<!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Assign Technical Employee</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>

		        <div class="box-body text-center">		        	
		        		<ul class=" nav nav-stacked form-group col-md-6 col-xs-12 col-sl-12" id="tech_employee_ul">
		        		<li>
		        			<select class="form-control tech_employee_li" id="tech_employee_li" name="scheme" required="required">
                            	<option value="0">Select Technical Employee</option> 
                            	<?php
                            		foreach ($users as $key => $value) 
                            		{		                            		
                            	?>
                            		<option value="<?php echo $value->user_id; ?>"><?php echo $value->employee_name; ?></option> 
                            	<?php	
                            		}
                            	?>
                        	</select>  
                        	<br>    
		        		</li>		                            
                                            	
		                </ul>

		                <a class="btn btn-primary" id="add_more_tech_button">Add More</a>
		                
		                <div class="form-group col-md-12 col-xs-12 col-sl-12">		  
		        			<input type="submit" class="btn btn-primary" value="Assign" id="assign_tech_button">
		        		</div>		        	
		        </div>

	        </div>
        </div>
    </section>


<script type="text/javascript">
//button to add more technical expert to a tracksheet	
	$('#add_more_tech_button').click(function()
	{
		var html = $('#tech_employee_ul li:first').html();		
		$('#tech_employee_ul').append("<li>" + html + "</li>");		
	});

//function to add technical expert to a tracksheet
	$('#assign_tech_button').click(function()
	{
		$(this).hide();
		
		var tracksheet_id = "<?php echo $tracksheet_id; ?>";
		var type = 2;
		var user_ids = [];
		$('.tech_employee_li').each(function()
		{
			user_ids.push($(this).val());			
		});

		$.post('<?php echo base_url('planning_actions/assign_users_to_tracksheet'); ?>', {tracksheet_id: tracksheet_id, type: type, user_ids: user_ids}, function(data)
		{
			var cert_type = "<?php echo $cert_type; ?>";

			if(cert_type == 1)
				location.href = "<?php echo base_url('planning/list_tracksheet') ?>";
			else if(cert_type == 2 || cert_type == 3)
			{
				location.href = "<?php echo base_url('planning/list_s_tracksheet') ?>";
			}
			else if(cert_type == 4)
			{
				location.href = "<?php echo base_url('planning/list_re_tracksheet') ?>";
			}
		});
	});
</script>