<!--------Page -------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Manage Client Feedback Questions</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	            <!----sample qstn----->	
	            	<div class="sample_qstn" style="display: none;">
	            		<input type="text" class="sample_qstn_input form-control">
	            		<br>
	            		<select class="sample_qstn_type">	            			
	            			<option value= "2">Question</option>
	            			<option value= "1">Title Text</option>
	            		</select>
	            		<button class="btn btn-success pull-right add_qstn_btn">Add</button>
	            		<br><br>
	            	</div>

	            <!-----displaying old questions area----->
		            <div class="col-md-12 col-sl-12 col-xs-12 old_qstns_area">		            	
		        		<?php
		        			function to_get_options($option)
		        			{
		        				if($option == 2)
		        				{
		        					echo "<option value=\"2\">Question</option>";
		        					echo "<option value=\"1\">Title Text</option>";
		        				}
		        				else if($option == 1)
		        				{
		        					echo "<option value=\"1\">Title Text</option>";
		        					echo "<option value=\"2\">Question</option>";
		        				}
		        				else
		        				{
		        					echo "<option value=\"0\">NA</option>";
		        				}
		        			}

		        			foreach ($feedback_qstn_records as $key => $feedback_qstn_record)
		        			{
		        				$qstn_id = $feedback_qstn_record['qstn_id'];
		        				$qstn = $feedback_qstn_record['qstn'];
		        				$qstn_type = $feedback_qstn_record['qstn_type'];
		        			?>
		        				<div class="old_qstn" qstn_id="<?php echo $qstn_id; ?>">		        					
		        					<button class="btn btn-danger pull-right delt_qstn_btn">x</button>
				            		<button class="btn btn-primary pull-right edit_qstn_btn">Edit</button>
				            		<button class="btn btn-success pull-right save_qstn_btn" style="display: none;">Save</button>
									<br><br>

				            		<input type="text" disabled="disabled" class="sample_qstn_input form-control" value="<?php echo $qstn; ?>">
				            		<br>
				            		<select class="sample_qstn_type" disabled="disabled">	 
				            			<?php
				            				to_get_options($qstn_type);
				            			?>
				            		</select>
				            		<br><br>
				            	</div>
		        			<?php	
		        			}
		        		?>
		            </div>
		            <br>

	            <!-----new qstn adding area----->
		            <div class="col-md-12 col-sl-12 col-xs-12 new_qstns_area">		            	
		            </div>
		            <br>

		            <button class="btn btn-primary add_new_qstn_btn">Add Question</button>
	            </div>           
            </div>
        </div>
	</section>

<!-------style and script-------->
	<style type="text/css">
		.sample_qstn_input
		{
			border: 1px solid silver;
			height: 34px;
		}

		.sample_qstn_type
		{
			width: 300px;
			border: 1px solid silver;
			height: 34px;
		}
	</style>

	<script type="text/javascript">
	//on clicking on add question button	
		$('.add_new_qstn_btn').click(function(argument)
		{
			var html = $('.sample_qstn:first').html();

			$('.new_qstns_area').append("<div class=\"new_qstn\">" + html + "</html>");

		//in clicking on add button
			$('.add_qstn_btn').unbind().click(function()
			{
				var qstn = $(this).parent().find('.sample_qstn_input').val();
				var qstn_type = $(this).parent().find('.sample_qstn_type').val();
				
				$.post('<?php echo base_url('manage_actions/add_feedback_qstn_in_db'); ?>', {qstn: qstn, qstn_type: qstn_type}, function(e)
				{
					if(e ==1)
						location.reload();
					else
						alert('something went wrong while adding feedback question');
				});
			});
		});

	//on clicking on delete button
		$('.delt_qstn_btn').click(function()
		{
			var qstn_id = $(this).parent().attr('qstn_id');

			$.post('<?php echo base_url('manage_actions/delt_feedback_qstn_in_db'); ?>', {qstn_id: qstn_id}, function(e)
			{
				if(e ==1)
					location.reload();
				else
					alert('something went wrong while deleting feedback question');
			});
		});
	
	//on clicking on edit button
		$('.edit_qstn_btn').click(function()
		{
			$(this).hide();
			$(this).parent().find('.save_qstn_btn').show();
			$(this).parent().find('input, select').attr('disabled', false);

		//on clicking on save qstn button
			$('.save_qstn_btn').click(function()
			{
				var qstn_id = $(this).parent().attr('qstn_id');

				var qstn = $(this).parent().find('.sample_qstn_input').val();
				var qstn_type = $(this).parent().find('.sample_qstn_type').val();

				$.post('<?php echo base_url('manage_actions/edit_feedback_qstn_in_db'); ?>', {qstn_id: qstn_id, qstn_type: qstn_type, qstn: qstn}, function(e)
				{
					if(e ==1)
						location.reload();
					else
						alert('something went wrong while adding feedback question');
				});
			});			
		});
	
	</script>
