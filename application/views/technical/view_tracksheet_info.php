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

<!--------tracksheet assigned technical employee details-------->
  <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Assigned Technical Employee</h3>
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12 col-sl-12 col-xs-12">                      

                        <ul class="nav nav-stacked">
                          <?php
                            $tracksheet_id = $database_record->tracksheet_id;
                            $tech_emp_count = count($get_assigned_users_of_tracksheet);

                            if($tech_emp_count == 0)
                            {
                              echo "Technical Employee has not assigned yet <br/>";
                              //echo "<a href=\"". base_url('planning/assign_tech_to_tracksheet_page/' . $tracksheet_id) . "\" class=\"btn btn-primary\">Assign</a>";
                            }
                            else
                            {                      
                              foreach ($get_assigned_users_of_tracksheet as $key => $value) 
                              {
                            ?>
                                <li><a href="#">Name <span class="pull-right  "><?php echo $value['username']; ?></span></a></li>
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

<!--------tracksheet flow status-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">            	
                <div class="box-header with-border">
	                    <h3 class="box-title">Tracksheet Flow Status</h3>
	                    <div class="box-tools pull-right">
	                    	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	                    </div>
                </div>
                <!-- /.box-header -->

                <?php
                		$flow_status = $database_record->flow_id;	
            	?>
                <div class="box-body table-responsive " id="flow_div">                	
                   
                </div>     
            </div>
        </div>
	</section>

	<script type="text/javascript">
      var tracksheet_id = "<?= $database_record->tracksheet_id; ?>";
  		var flow_status = "<?= $flow_status?>";
  		var cert_type =  "<?= $cert_type?>";
  		$.post('<?=base_url()?>technical_action/list_tracksheet_flow', {flow_status:flow_status, cert_type:cert_type, tracksheet_id:tracksheet_id}, function(data) 
  		{
  			$('#flow_div').html(data);
  		});
	</script>

	<div style="clear: both;"></div>
	<style>
		.content
		{
		    height: auto !important;
		}
		span
		{
		    font-weight: 600;
		}

		#flow_div h4
		{
			text-align: center; 
		}

		#flow_div li
		{
			margin: 0;
			margin: 7px;
			border-radius: 3px;
			box-shadow: 0px 0px 5px grey;
		}

		#flow_div li span
		{
			height: 50px;
		}

		#flow_div li a
		{
			color: #f1f1f1;
		}

		#flow_div li a:hover
		{
			color: grey;
		}
	</style>

<!--------certificate details-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Certificate Details</h3>

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
                     	?>
                        <ul class="nav nav-stacked">
                        	<li><a href="#">Certificate No. <span class="pull-right  ">
                           		<?php 
                           			$certificate_no = $database_record->certificate_no; 
                           			if($certificate_no == "0")
                           				echo "NA";
                           			else
                           				echo $certificate_no;
                           		?>                           			
                           		</span></a>
                           	</li>
                           	<li><a href="#">Initial Certificaton Date <span class="pull-right  ">
                           		<?php 
                           			$initial_certification_date = $database_record->initial_certification_date; 
                           			if($initial_certification_date == "0000-00-00")
                           				echo "NA";
                           			else
                           				echo $initial_certification_date;
                           		?>                           			
                           		</span></a>
                           	</li>
                           	<li><a href="#">Certification Date From <span class="pull-right  ">
                           		<?php 
                           			$cert_date_from = $database_record->cert_date_from; 
                           			if($cert_date_from == "0000-00-00")
                           				echo "NA";
                           			else
                           				echo $cert_date_from;
                           		?>
                           		</span></a>
                           	</li>
                           	<li><a href="#">Certification Date To <span class="pull-right  ">
                           		<?php 
                           			$cert_date_to = $database_record->cert_date_to; 
                           			if($cert_date_to == "0000-00-00")
                           				echo "NA";
                           			else
                           				echo $cert_date_to;
                           		?>
                           		</span></a>
                           	</li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-sl-12 col-xs-12">                     	
		                <ul class="nav nav-stacked">		                	
                           <!-- 	<li><a href="#">Certificate Valadity <span class="pull-right  ">
                           		<?php 
                           			$certificate_valadity = $database_record->certificate_valadity; 
                           			if($certificate_valadity == "0")
                           				echo "NA";
                           			else
                           				echo $certificate_valadity;
                           		?>
                           		</span></a>
                           	</li> -->
		                  	<li><a href="#">Transfer Status <span class="pull-right  ">
                           		<?php 
                           			$transfer_status = $database_record->transfer_status; 
                           			if($transfer_status == "")
                           				echo "NA";
                           			else
                           				echo $transfer_status;
                           		?>
                           		</span></a>
                           	</li>
                           	<li><a href="#">Certificate Dispatch Date <span class="pull-right  ">
                           		<?php 
                           			$certificate_dispatch_date = $database_record->certificate_dispatch_date; 
                           			if($certificate_dispatch_date == "0000-00-00")
                           				echo "NA";
                           			else
                           				echo $certificate_dispatch_date;
                           		?>
                           		</span></a>
                           	</li>
                           	<li><a href="#">Upload Date <span class="pull-right  ">
                           		<?php 
                           			$upload_date = $database_record->upload_date; 
                           			if($upload_date == "0000-00-00")
                           				echo "NA";
                           			else
                           				echo $upload_date;
                           		?>
                           		</span></a>
                           	</li>
		              	</ul>
		            </div>	

		            <div class="col-md-12 col-sl-12 col-xs-12">                     	
		                <ul class="nav nav-stacked">
		                  	<!-- <li><a href="#">Scope:  <span class=""><?php echo $database_record->scope; ?></span></a></li> -->
		              	</ul>
		            </div>
                </div>     
            </div>
        </div>
	</section>
