<?php
	if($out_of_index != 0)
    	die("Page Not Found");

	$page_id = $questionnaire_form_record['page_id'];
?>

<!--------textarea qstn sample---------->
	<div class="hidden_sample" id="text_sample">
		<div class="sample_controls">
			<button class="btn btn-success sample_add_new">Add</button>	 
			<button class="btn btn-danger sample_delete1 pull-right">x</button>  
		</div>

		<span class="title_q">Title</span>
		<input type="text" class="form-control" id="qstn_title" />
		<br>
		
		<span class="title_q">Write Text</span>
		<textarea class="form-control" id="qstn_data"></textarea>
		<br>

		<textarea class="form-control" id="qstn_help" style="display: none"></textarea>
		<input type="hidden" id="qstn_type" value="1">
		<input type="hidden" id="qstn_parent" value="0">
	</div>

<!------title qstn sample---->
	<div class="hidden_sample" id="title_qstn_sample">
		<div class="sample_controls">
			<button class="btn btn-success sample_add_new" >Add</button>	 
			<button class="btn btn-danger sample_delete1 pull-right">x</button>  
		</div>

		<span class="title_q">Write Question</span>
		<textarea class="form-control" id="qstn_title"></textarea>
		<br>

		<input type="hidden" id="qstn_data">

		<span class="title_q">Question Type</span>
		<select class="form-control" id="qstn_type">
			<option value="2">Both Remarks & Option</option>
			<option value="3">Only Remarks</option>
		</select>	

		<span class="title_q">Write Help</span>
		<textarea class="form-control" id="qstn_help"></textarea>
		<br>
	</div>

<!--------title sample---------->
	<div class="hidden_sample" id="title_sample">
		<div class="sample_controls">
			<button class="btn btn-success sample_add_new" >Add</button>	 
			<button class="btn btn-danger sample_delete1 pull-right">x</button>  
		</div>
		
		<span class="title_q">Title</span>
		<input type="text" class="form-control" id="qstn_title" />
		<br>

		<span class="title_q">Write Data</span>
		<textarea class="form-control" id="qstn_data"></textarea>
		<br>

		<textarea class="form-control" id="qstn_help" style="display: none"></textarea>
		<input type="hidden" id="qstn_type" value="4">
		<input type="hidden" id="qstn_parent" value="0">    
	</div>

<!---------page title and intro----------->
	<div class="qstn_sample">
		<span class="title_q">Page Title</span>
		<input type="text" class="form-control" id="page_title" value="<?php echo $questionnaire_form_record['page_title']; ?>" />
		<br>

		<span class="title_q">Page Intro</span>
		<textarea class="form-control" id="page_intro"><?php echo $questionnaire_form_record['page_intro']; ?></textarea>
		<br>

		<button class="btn btn-success" id="save_page_details_btn">Save</button>
	</div>

<!-------adding buttons and qstns container div---------->
	<div class="qstn_area">
		<?php
		//function to get options (for qstn_type) in drop down menu
			function get_qstn_type_options($qstn_type)
			{
				if($qstn_type == 2)
				{
					echo "<option value=\"2\">Both Remarks & Option</option>";
					echo "<option value=\"3\">Only Remarks</option>";
				}
				else if($qstn_type == 3)
				{
					echo "<option value=\"3\">Only Remarks</option>";
					echo "<option value=\"2\">Both Remarks & Option</option>";
				}
			}

		//displaying already existing questions
			foreach ($get_questionnaire_form_qstns as $key => $get_questionnaire_form_qstn)
			{
				$qstn_type = $get_questionnaire_form_qstn['qstn_type'];

				if($qstn_type == 1)
				{
					$qstn_id = $get_questionnaire_form_qstn['qstn_id'];
					$qstn_title = $get_questionnaire_form_qstn['qstn_title'];
					$qstn_data = $get_questionnaire_form_qstn['qstn_data'];
				?>
					<div class="qstn_sample" qstn_id="<?php echo $qstn_id; ?>">
						<div class="sample_controls">
							<button class="btn btn-primary sample_edit">Edit</button>	 
							<button class="btn btn-success sample_save" style="display: none">Save</button>	 
							<button class="btn btn-danger sample_delete1_old pull-right">x</button>  
						</div>

						<span class="title_q">Title</span>
						<input type="text" class="form-control" id="qstn_title" disabled="disabled" value="<?php echo $qstn_title; ?>" />
						<br>
						
						<span class="title_q">Write Text</span>
						<textarea class="form-control" id="qstn_data" disabled="disabled"><?php echo $qstn_data; ?></textarea>
						<input type="hidden" id="qstn_type" value="1">
					</div>
				<?php
				}
				else if($qstn_type == 4)
				{
					$qstn_id = $get_questionnaire_form_qstn['qstn_id'];
					$qstn_title = $get_questionnaire_form_qstn['qstn_title'];
					$qstn_data = $get_questionnaire_form_qstn['qstn_data'];
				?>
					<div class="qstn_sample" qstn_id="<?php echo $qstn_id; ?>">
						<div class="sample_controls">
							<button class="btn btn-primary sample_edit">Edit</button>	 
							<button class="btn btn-success sample_save" style="display: none">Save</button>	
							<button class="btn btn-danger sample_delete1_old pull-right">x</button>  
						</div>
						
						<span class="title_q">Title</span>
						<input type="text" class="form-control" id="qstn_title" disabled="disabled" value="<?php echo $qstn_title; ?>" />
						<br>

						<span class="title_q">Write Data</span>
						<textarea class="form-control" id="qstn_data" disabled="disabled"><?php echo $qstn_data; ?></textarea>

						<input type="hidden" id="qstn_type" value="4">  

					<!----------child questions(title qstns sample) of this title sample------->
						<?php
							foreach ($get_questionnaire_form_qstns as $key => $value) 
							{
								$child_qstn_type = $value['qstn_type'];
								$child_qstn_parent = $value['qstn_parent'];

								if(($child_qstn_type == 2 OR $child_qstn_type == 3) && $child_qstn_parent == $qstn_id)
								{
									$child_qstn_id = $value['qstn_id'];
									$child_qstn_title = $value['qstn_title'];
									$child_qstn_help = $value['qstn_help'];
								?>
									<div class="title_qstn_sample" qstn_id="<?php echo $child_qstn_id; ?>">
										<div class="sample_controls">
											<button class="btn btn-primary sample_edit">Edit</button>	 
											<button class="btn btn-success sample_save" style="display: none">Save</button>	
											<button class="btn btn-danger sample_delete1_old pull-right">x</button>  
										</div>

										<span class="title_q">Write Question</span>
										<textarea class="form-control" id="qstn_title" disabled="disabled"><?php echo $child_qstn_title; ?></textarea>
										<br>

										<input type="hidden" id="qstn_data">

										<span class="title_q">Question Type</span>
										<select class="form-control" id="qstn_type" disabled="disabled">
											<?php
												get_qstn_type_options($child_qstn_type);
											?>
										</select>	
						
										<span class="title_q">Write Help</span>
										<textarea class="form-control" id="qstn_help" disabled="disabled"><?php echo $child_qstn_help; ?></textarea>

									</div>
								<?php
								}
							}
						?>

						<br>
				        <button class="btn btn-success add_qstn_in_title_sample_btn" id="add_qstn_in_title_sample_btn">Add Question</button>
					</div>
				<?php	
				}
			}
		?>
	</div>
	<br>

	<div class="qstn_sample">
		<button class="btn btn-primary" id="add_text_btn">Add Text</button>
		<button class="btn btn-primary" id="add_title_btn">Add Title</button>
		<br><br>
		<button class="btn btn-success" id="submit_form_button" style="margin: auto; display: block;" >Done</button>
	</div>

<!-------------script------------>
<script type="text/javascript">
//on clicking on delete button of already existing questions
	$('.sample_delete1_old').click(function()
	{
		var qstn_id = $(this).parent().parent().attr('qstn_id');

		$.post("<?php echo base_url('manage_actions/delete_qstn_from_db'); ?>", {qstn_id: qstn_id}, function(a)
		{
			location.reload();
		});
	});

//on clicking on edit button for already existing questions
	$('.sample_edit').click(function()
	{
		$(this).hide();
		var this_pa = $(this).parent().parent();
		this_pa.find('.sample_save:first').show();
		this_pa.find('#qstn_title:first, #qstn_data:first, #qstn_type:first, #qstn_help:first').attr('disabled', false);

		var qstn_id = this_pa.attr('qstn_id');

		$('.sample_save').click(function()
		{
			var qstn_title = this_pa.find('#qstn_title').val();
			var qstn_data = this_pa.find('#qstn_data').val();
			var qstn_type = this_pa.find('#qstn_type').val();
			var qstn_help = this_pa.find('#qstn_help').val();

			$.post("<?php echo base_url('manage_actions/update_qstn_from_db'); ?>", {qstn_id: qstn_id, qstn_title: qstn_title, qstn_data:qstn_data, qstn_type: qstn_type, qstn_help:qstn_help}, function(a)
			{
				location.reload();
			});
		});
	});

//function to add new question
	function add_new_qstns(this_paa)
	{
		var page_id = "<?php echo $page_id; ?>";

		var qstn_title = this_paa.find('#qstn_title').val();
		var qstn_data = this_paa.find('#qstn_data').val();
		var qstn_type = this_paa.find('#qstn_type').val();
		var qstn_help = this_paa.find('#qstn_help').val();
		
		if(qstn_type == 2 || qstn_type == 3)
		{
			var qstn_parent = this_paa.parent().attr('qstn_id');				
		}
		else
		{
			var qstn_parent = this_paa.find('#qstn_parent').val();
		}
		
		if(qstn_title != "" || qstn_data != "")
		{
			$.post("<?php echo base_url('manage_actions/add_sample_qstn_in_db'); ?>", {page_id: page_id, qstn_title: qstn_title, qstn_data:qstn_data, qstn_type:qstn_type, qstn_parent:qstn_parent, qstn_help:qstn_help}, function(a)
			{
				location.reload();
			});
		}
	}

//on clicking on add question button for already existing title sample	
	$('.add_qstn_in_title_sample_btn').click(function() //here unbind is used because some error was coming//
	{
		var html1 = $('#title_qstn_sample').html();

		$(this).parent().append("<div class=\"title_qstn_sample\">" + html1 + "</div>");

	//on clicking on delete button	
		$('.sample_delete1').click(function()
		{
			$(this).parent().parent().remove();
		});

	//on clicking on add button
		$('.sample_add_new').click(function()
		{					
			var this_paa = $(this).parent().parent();
			
			add_new_qstns(this_paa);
		});
	});

//on clicking on delete button	
	$('.sample_delete1').click(function()
	{
		$(this).parent().parent().remove();
	});

//on clicking on add text button
	$('#add_text_btn').click(function()
	{
		var html = $('#text_sample').html();
		//alert(html);
		$('.qstn_area').append("<div class=\"qstn_sample text_sample\">" + html + "</div>");

	//on clicking on delete button	
		$('.sample_delete1').click(function()
		{
			$(this).parent().parent().remove();
		});
	//on clicking on add button
		$('.sample_add_new').click(function()
		{					
			var this_paa = $(this).parent().parent();
			
			add_new_qstns(this_paa);
		});
	});

//on clicking on add title button
	$('#add_title_btn').click(function()
	{
		var html = $('#title_sample').html();
		$('.qstn_area').append("<div class=\"qstn_sample title_sample\">" + html + "</div>");

	//on clicking on delete button	
		$('.sample_delete1').click(function()
		{
			$(this).parent().parent().remove();
		});

	//on clicking on add button
		$('.sample_add_new').click(function()
		{					
			var this_paa = $(this).parent().parent();
			
			add_new_qstns(this_paa);
		});	
	});

//on clicking on save page details button
	$('#save_page_details_btn').click(function()
	{
		var page_id = "<?php echo $page_id; ?>";

		var page_title = $(this).parent().find('#page_title').val();
		var page_intro = $(this).parent().find('#page_intro').val();

		$.post("<?php echo base_url('manage_actions/update_page_details_db'); ?>", {page_id: page_id, page_title: page_title, page_intro:page_intro}, function(a)
		{
			location.reload();
		});
	});

//on clicking on done button
	$('#submit_form_button').click(function()
	{
		location.href = "<?php echo base_url('manage/list_questionnaire'); ?>";
	});

</script>