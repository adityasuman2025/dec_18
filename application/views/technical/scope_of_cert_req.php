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
		                <?php echo form_open(base_url('technical/view_customer_info/' . $cm_id),['class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
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
                      <h3 class="box-title">Tracksheet Details</h3>

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
		              	</ul>
		            </div>			           
                </div>     
            </div>
        </div>
	</section>

<!------approval or disapproval area for change of scope request------->
	  <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Change of Scope Request</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
		            <div class="col-md-12 col-sl-12 col-xs-12">
		            	<ul class="nav nav-stacked">
		            		<li><a href="#">Scope <span class="pull-right"><?php echo $database_record->scope; ?></span></a></li>
		            		<li><a>Scope Proposed in Scope of Certification form<span class="pull-right"><?php echo $get_scope_of_cert['scope']; ?></span></a></li> 
		            	</ul>

			            <div class="text-center">
			            	<?php
			            		$id = $get_scope_of_cert['id'];
			            		$tech_accepted = $get_scope_of_cert['tech_accepted'];
			            		
			            		if($tech_accepted == 1) //already accepted
			            		{
			            			echo "Change of Scope Request has already been accepted.";
			            		}
			            		else
			            		{
			            	?>
			            			<button class="btn btn-danger reject_btn">Reject</button>
			            			<button class="btn btn-success accept_btn">Accept</button>
			            	<?php
			            		}
			            	?>
			            	
			            </div>			            	
		            </div>
		        </div>
		    </div>
		</div>
	</section>

	<script type="text/javascript">
	//on clicking on reject btn
		$('.reject_btn').click(function()
		{
			$(this).hide();
			
			location.href = "<?php echo base_url('technical/list_scope_of_cert_req'); ?>";
		});

	//on clicking on accept btn
		$('.accept_btn').click(function()
		{
			$(this).hide();

			var id = "<?php echo $id; ?>";

		//updating the technical_accept request for that row_id in the table
			$.post('<?php echo base_url('technical_action/update_tech_accepted_in_scope_of_cert'); ?>', {id:id}, function(b)
			{
				if(b == 1)
				{
					location.href = "<?php echo base_url('technical/list_scope_of_cert_req'); ?>";
				}
				else
					alert('Something went wrong while accepting scope of certification request');
			});	
		});
	</script>