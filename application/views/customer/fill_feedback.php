<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	$tracksheet_id = $this->uri->segment(3);
    $level = $this->uri->segment(4);
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
		                		$tracksheet_id=$database_record->tracksheet_id;

		                		$cm_id=$database_record->cm_id;

								$cb_type=$database_record->cb_type;
								$cb_type_name = 0;
								if($cb_type == 1)
									$cb_type_name = "EAS";
								else
									$cb_type_name = "IAS";
							?>	
		                	<li><a href="#">Customer ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
		                	<li><a href="#">Organisation Name<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
		                	<li><a href="#">Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>
		                	
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Scheme<span class="pull-right  "><?php echo $database_record->scheme_name;; ?></span></a></li>
		              	</ul>
		            </div>		            
	            </div>           
            </div>
        </div>
	</section>

<!--------feedback area-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Customer Feedback</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
		            <div class="col-md-12 col-sl-12 col-xs-12">		
					<!-----displaying questions area----->            	
		        		<?php		        	
		        			foreach ($feedback_qstn_records as $key => $feedback_qstn_record)
		        			{
		        				$qstn_id = $feedback_qstn_record['qstn_id'];
		        				$qstn = $feedback_qstn_record['qstn'];
		        				$qstn_type = $feedback_qstn_record['qstn_type'];
		        			
	    						if($qstn_type == 1)//title text
	    						{
	    							echo "<br><h4><b>$qstn</b></h4>";
	    						}
	    						else if($qstn_type == 2) //qstn
	    						{    							
	    						?>
			        				<div class="old_qstn" qstn_id="<?php echo $qstn_id ?>">
		        						<div type="text" class="checklist_qstn"><?php echo $qstn; ?></div>
		        						<div class="feedback_ans_div">
		        							<b>Rating: </b>
		        							<select class="rating_type_ans" qstn_id="<?php echo $qstn_id ?>">
		        								<option value="1">Not Satisified</option>
		        								<option value="2">Satisified</option>
		        								<option value="3">More than Satisfied</option>
		        							</select>
		        							<br>

		        							<b>Remarks: </b><br>	        							
		        							<textarea class="remarks_type_ans" qstn_id="<?php echo $qstn_id ?>"></textarea>
		        						</div>	        						
		        					</div>
	    						<?php
	    						}	        					
		        			}
		        		?>

	        		<!----suggestion and date------>
		        		<br><br>
		        		<b>Suggestion</b><br>		        		
		        		<textarea class="feedback_sugg" style="width: 90%; "></textarea>
		        		<br>

		        		<b>Date: </b>
			        	<input type="date" class="feedback_date">
			        	<br><br><br><br>

			        	<input type="submit" class="btn btn-primary submit_btn">
		            </div>
		        </div>
		    </div>
		</div>
	</section>

	<style type="text/css">
		.old_qstn
		{
			border: 1px solid silver;
			padding: 2px;
			margin: 5px;
		}

		.feedback_ans_div
		{
			margin-left: 20px;
			vertical-align: middle;
		}

		.remarks_type_ans
		{
			vertical-align: middle;
			width: 90%;
		}

		.feedback_remarks_view
		{
			padding: 4px;
			background: #f1f1f1;
			border: 1px solid lightgrey;
		}
	</style>

	<script type="text/javascript">
	//variables
		var tracksheet_id = "<?php echo $tracksheet_id; ?>";
		var level = "<?php echo $level; ?>";

	//on clicking on submit button	
		$('.submit_btn').click(function()
		{
			$(this).hide();
			
			var suggestion = $('.feedback_sugg').val();
			var date = $('.feedback_date').val();
			
		//rating type qstns	
			var feedback_records = [];

			$('.rating_type_ans').each(function()
			{
				var temp = {};

				temp.qstn_id = $(this).attr('qstn_id');
				temp.answer = $(this).val();
				temp.ans_type = 1; //rating type

				feedback_records.push(temp);
			});

		//remarks type qstns	
			$('.remarks_type_ans').each(function()
			{
				var temp = {};

				temp.qstn_id = $(this).attr('qstn_id');
				temp.answer = $(this).val();
				temp.ans_type = 2; //remarks type

				feedback_records.push(temp);
			});

		//inserting in db
			$.post('<?php echo base_url('customer/insert_feedback_in_db'); ?>', {tracksheet_id: tracksheet_id, level:level, feedback_records: feedback_records}, function(e)
			{
				if(e == 1)
				{
					$.post('<?php echo base_url('customer/insert_tracksheet_feedback'); ?>', {tracksheet_id: tracksheet_id, level:level, suggestion:suggestion, date:date}, function(data)
					{
						if(data == 1)
							location.href= "<?php echo base_url('customer/list_feedback'); ?>";
						else
							alert("Something went wrong while inserting feedback details in db");
					});
				}
				else
				{
					alert("Something went wrong while inserting feedback answers in db");
				}
			});
		});
	</script>
		            