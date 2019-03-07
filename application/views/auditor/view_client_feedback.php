<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	foreach ($get_client_feedback as $key => $get_client_feedback_details)
	{
		$suggestion = $get_client_feedback_details['suggestion'];
		$date = $get_client_feedback_details['date'];
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

<!--------client details-------->
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
				<!-----displaying questions area----->
		            <div class="col-md-12 col-sl-12 col-xs-12">		            	
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
    							$rating_ans = 0;
    							$rating_ans_text = "";
    							$remarks_ans = "";

    							foreach ($get_client_feedback_anss as $key => $get_client_feedback_ans)
    							{
    								$ans_qstn_id = $get_client_feedback_ans['qstn_id'];

    								if($ans_qstn_id == $qstn_id)
    								{
    									$ans_type = $get_client_feedback_ans['ans_type'];
    									$answer = $get_client_feedback_ans['answer'];

    									if($ans_type == 1)//rating
    										$rating_ans = $answer;
    									else if($ans_type == 2)//remarks
    										$remarks_ans = $answer;
    								}
    							}
    							
    							if($rating_ans == 1)
    								$rating_ans_text = "Not Satisfied";
    							else if($rating_ans == 2)
    								$rating_ans_text = "Satisfied";
    							else if($rating_ans == 3)
    								$rating_ans_text = "More than Satisfied";
    							else
    								$rating_ans_text = "NA";
    						?>
		        				<div class="old_qstn" >
	        						<div type="text" class="checklist_qstn"><?php echo $qstn; ?></div>
	        						<div class="feedback_ans_div">
	        							<b>Rating: </b> <?php echo $rating_ans_text; ?>
	        							<div class="feedback_remarks_view"><?php echo $remarks_ans; ?></div>
	        						</div>	        						
	        					</div>
    						<?php
    						}	        					
	        			}
	        		?>

	        		<!----suggestion and date------>
		        		<br><br>
		        		<b>Suggestion</b>
		        		<br>
		        		<div class="feedback_remarks_view">
		        			<?php
		        				echo $suggestion;
		        			?>
		        		</div>

		        		<b>Date: </b>
		        		<?php
		        				echo $date;
		        		?>
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
		}

		.feedback_remarks_view
		{
			padding: 4px;
			background: #f1f1f1;
			border: 1px solid lightgrey;
		}
	</style>
		            