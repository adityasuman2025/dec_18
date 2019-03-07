<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	$level = $this->uri->segment(4);
?>

<!--------summary of the audit report--------->
<?php
	if($level != 1)
	{
?>
   <section class="content">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Summary of the Audit Report</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>

	            <!-- /.box-header -->
	            <div class="box-body">
		            <div class="col-md-12 col-sl-12 col-xs-12">
	            	<?php
	            	//function to get audit stage options
	            		function to_get_stage_of_audit($stage)
	            		{
	            			if($stage == 1)
	            			{
	            				echo "<option value=\"1\">Initial Certification</option>";
	            			}
	            			else if($stage == 2)
	            			{
								echo "<option value=\"2\">Post Audit</option>";
	            			}
	            			else if($stage == 3)
	            			{
	            				echo "<option value=\"3\">Surveillance</option>";
	            			}
	            			else if($stage == 4)
	            			{
	            				echo "<option value=\"4\">Modification</option>";
	            			}
	            			else if($stage == 5)
	            			{
	            				echo "<option value=\"5\">Renewal</option>";
	            			}
	            			else if($stage == 6)
	            			{
	            				echo "<option value=\"6\">Upgrade From</option>";
	            			}
	            			else if($stage == 7)
	            			{
	            				echo "<option value=\"7\">Other</option>";	            				
	            			}
	            			else
	            			{
	            				echo "<option value=\"0\">NA</option>";
	            			}
	            		}

	            	//function to get recommendation options
	            		function to_get_recomm($recomm)
	            		{
	            			if($recomm == 1)
	            			{
	            				echo "<option value=\"1\">Issuance of Certificate</option>";
	            			}
	            			else if($recomm == 2)
	            			{
	            				echo "<option value=\"2\">Use of the EAS & JAS-ANZ Logo</option>";
	            			}
	            			else if($recomm == 3)
	            			{
	            				echo "<option value=\"3\">Refusal of the Certificate</option>";
	            			}
	            			else if($recomm == 4)
	            			{
	            				echo "<option value=\"4\">Post audit</option>";
	            			}
	            			else if($recomm == 5)
	            			{
	            				echo "<option value=\"5\">Modification of the current certificate (registration n° and expiration date remain unchanged)</option>";		            				
	            			}
	            			else if($recomm == 6)
	            			{
	            				echo "<option value=\"6\">Other</option>";        				
	            			}		            		
	            			else
	            			{
	            				echo "<option value=\"0\">NA</option>";
	            			}
	            		}

	            	//function to get reason options
	            		function to_get_reason($reason)
	            		{
	            			if($reason == 1)
	            			{
	            				echo "<option value=\"1\">1</option>";
	            			}
	            			else if($reason == 2)
	            			{
	            				echo "<option value=\"2\">2</option>";
	            			}
	            			else if($reason == 3)
	            			{
	            				echo "<option value=\"3\">3</option>";
	            			}
	            			else if($reason == 4)
	            			{		            				
	            				echo "<option value=\"4\">4</option>";
	            			}		            				            	
	            			else
	            			{
	            				echo "<option value=\"0\">NA</option>";
	            			}
	            		}

	            	//getting audit summary records
	            		$surv_date = 0;
	            		$date = 0;
	            		foreach ($get_audit_summary_records as $key => $get_audit_summary_record)
	            		{
	            			$row_id = $get_audit_summary_record['id'];

	            			$minor = $get_audit_summary_record['minor'];
	            			$major = $get_audit_summary_record['major'];

	            			$stage = $get_audit_summary_record['stage'];
	            			$recomm = $get_audit_summary_record['recomm'];
	            			$reason = $get_audit_summary_record['reason'];

	            			$surv_date = $get_audit_summary_record['surv_date'];
	            			$date = $get_audit_summary_record['date'];	
	            			$re_audit_date = $get_audit_summary_record['re_audit_date'];	

	            			$approved_by_reviewer = $get_audit_summary_record['approved_by_reviewer'];	            			
	            		}
	            	?>

		            <!--count of major and minor NCs------->	
		            	<b><?php echo $minor; ?> Minor/ <?php echo $major; ?> Major Non Conformance identified in the Stage 2 audit,  details of Non Conformance</b> Please respond by using your own corrective action form and include the root cause analysis with systemic corrective action. Failure to include root cause analysis with systemic corrective action will result in your responses being rejected by Lead Auditor
		            	<br><br>

		            	<b>A.	Stage of audit:</b>
		            	<select id="stage_of_audit" style="height: 34px;" disabled="disabled">
		            		<?php to_get_stage_of_audit($stage); ?>
		            	</select>
		            	<br><br>

		            	<b>B.	Recommendation</b>
		            	<select id="recomm" style="height: 34px;" disabled="disabled">
		            		<?php to_get_recomm($recomm); ?>
		            	</select>
		            	<br><br>

		            	<b>C.	Reason</b>
		            	<select id="reason" style="height: 34px;" disabled="disabled">
		            		<?php to_get_reason($reason); ?>	            		
		            	</select>
		            	<ul class="nav nav-stacked">
		            		<li><b>1. The Information Security Management Systems complies with the requirements of the reference standard:</b> Congratulations, on the basis of the above summary, Lead Auditor is pleased to put forward a recommendation for issuance of certificate.</li>

		            		<li><b>2. The Information Security Management Systems complies with the requirements of the reference standard with exception of minor NC:</b> Congratulations, Lead Auditor is pleased to put forward a recommendation for registration of Organization upon off-site verification of closure of all issues within 90 days from the date of Stage 2 audit.  If all non-conformances are not closed within 90 days, a full reassessment may be required.</li>

		            		<li><b>3. Evidence of major non conformities: </b> Organization is not recommended for next assessment at this time. A follow-up assessment will be scheduled to allow for on-site verification and closure of all issues within 60 days from the date of Stage 2 audit. If all non-conformances are not closed within 60 days, a full reassessment may be required.</li>

		            		<li><b>4. Not Recommended: </b> Organization is not recommended for certification, a Stage 2 audit will be required. To progress your application for registration, please respond to each non-conformances, with a plan showing proposed actions, timescales and responsibilities for resolution. The organization should consider the root cause of the non-conformance and the potential for related issues in other parts of your system.</li>
		            	</ul>
		            	<br>

		            	<i>Disclaimer: Auditing is based on a sampling process of the available information. The results were arrived based on the sample verified.</i>
		            	<br><br>

		            	<b>Proposed Audit Date for Surveillance Audit: </b> 
		            	<input id="surv_date" type="date" disabled="disabled" style="height: 34px; width: auto;" value="<?php echo $surv_date; ?>">
		            	<br><br>

		            	<b>Sign Off Date: </b>
		            	<input id="date" type="date" disabled="disabled" style="height: 34px; width: auto;" value="<?php echo $date; ?>">		        
	            	
	            	<!---deciding date for re-audit--->
		            	<br><br><br><br>
		            	<?php
		            		if($recomm == 3 && $reason == 3)
		            		{
		            			$max_date_time = strtotime($date) + 4752000;
		            			$max_date = date('Y-m-d', $max_date_time);
		            	?>
		            			<h4><b>Date for Re-Audit</b></h4>		            	
								<input type="date" id="re_audit_date" style="height: 34px; width: auto;" value="<?php echo($re_audit_date); ?>" min="<?php echo $date; ?>" max="<?php echo $max_date; ?>">
								<br><br>
								<button class="btn btn-success" id="done_btn" row_id="<?php echo $row_id; ?>">Done</button>
								<span id="hello"></span>
		            	<?php		
		            		}
		            	?>						
	            	</div>
	            </div>
	        </div>
	    </div>
	</section>
<?php		
	}
?>

<script type="text/javascript">	
// on clicking on done button
	$('#done_btn').click(function()
	{
		$(this).hide();
		
		var row_id = $(this).attr('row_id');
		var re_audit_date = $('#re_audit_date').val();

		var level = "<?php echo $level; ?>";

		$.post("<?php echo base_url('planning_actions/update_re_audit_date'); ?>", {row_id: row_id, re_audit_date: re_audit_date}, function(data)
		{
			if(data ==1)
				if(level ==2)
					location.href= "<?php echo base_url('planning/list_re_audit'); ?>";
				else if(level ==3 || level ==4)
					location.href= "<?php echo base_url('planning/list_re_audit_surv'); ?>";
			else				
				alert("Something went wrong in update Re-Audit Date");
		});

	});
</script>
