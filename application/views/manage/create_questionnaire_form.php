<!--------textarea qstn sample---------->
	<div class="hidden_sample" id="text_sample">
		<div class="sample_controls">
			<button class="btn btn-success sample_add">Add</button>	 
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
			<button class="btn btn-success sample_add" >Add</button>	 
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
			<button class="btn btn-success sample_add" >Add</button>	 
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

        <button class="btn btn-success add_qstn_in_title_sample_btn" id="add_qstn_in_title_sample_btn">Add Question</button>        
	</div>

<!---------page title and intro----------->
	<div>
		<input type="hidden" id="scheme_id" value="<?php echo $_POST['scheme_id']; ?>">
		<input type="hidden" id="level" value="<?php echo $_POST['level']; ?>">

		<span class="title_q">Page Title</span>
		<input type="text" class="form-control" id="page_title" style="border: 1px lightgrey solid; " />
		<br>

		<span class="title_q">Page Intro</span>
		<textarea class="form-control" id="page_intro" style="border: 1px lightgrey solid; "></textarea>
		<br>
	</div>

<!-------adding buttons and qstns container div---------->
	<div class="qstn_area"></div>
	<br>

	<div class="qstn_sample">
		<button class="btn btn-primary" id="add_text_btn">Add Text</button>
		<button class="btn btn-primary" id="add_title_btn">Add Title</button>
		<br><br>
		<button class="btn btn-success" id="submit_form_button" style="margin: auto; display: block;" >Submit</button>
	</div>
	
<!--------script----------->
	<script type="text/javascript">
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

		//on clicking on add question button
			$('.add_qstn_in_title_sample_btn').unbind().click(function() //here unbind is used because some error was coming//
			{
				var html1 = $('#title_qstn_sample').html();

				$(this).parent().append("<div class=\"title_qstn_sample\">" + html1 + "</div>");

			//on clicking on delete button	
				$('.sample_delete1').click(function()
				{
					$(this).parent().parent().remove();
				});
			});
		});
	
	//on clicking on submit buttonm
		$('#submit_form_button').click(function()
		{
		//variables	
			var scheme_id = $('#scheme_id').val();
			var level = $('#level').val();
			var page_title = $('#page_title').val();
			var page_intro = $('#page_intro').val();

		//creating a new questionnaire and storing it in database
			$.post("<?php echo base_url('manage_actions/add_new_questionnaire_db'); ?>", {scheme_id: scheme_id, level: level, page_title:page_title, page_intro:page_intro}, function(data)
			{				
				if(data == 0)
				{
					alert("something went wrong in inserting data into the database");
				}
				else
				{
					var page_id = data;
					var records = [];

				//inserting text sample and title sample questions in db
					$('.qstn_sample').each(function()
					{
						var temp = {};

						var qstn_title = $(this).find('#qstn_title').val();
						var qstn_data = $(this).find('#qstn_data').val();
						var qstn_type = $(this).find('#qstn_type').val();
						var qstn_help = $(this).find('#qstn_help').val();
						var qstn_parent = $(this).find('#qstn_parent').val();

						temp.qstn_title = qstn_title;
						temp.qstn_data = qstn_data;
						temp.qstn_type = qstn_type;
						temp.qstn_help = qstn_help;
						temp.qstn_parent = qstn_parent;
						
						if(qstn_title != "" || qstn_data != "")
						{
							records.push(temp);
						}
					});

					$.post("<?php echo base_url('manage_actions/add_text_sample_qstns_in_db'); ?>", {page_id: page_id, records: records}, function(a)
					{								
					//assigning its row id in database to each text sample and title sample					
						var first_insert_id = parseInt(a);		
						var c = first_insert_id;

						$('.qstn_sample').each(function()
						{
							$(this).attr("row_id", c);
							c++;		
						});

					//storing title qstn sample(child questions of the title sample) in the database
						var title_sample_qstns_records = [];
						$('.title_qstn_sample').each(function()
						{							
							var temp = {};

							var qstn_title = $(this).find('#qstn_title').val();
							var qstn_data = $(this).find('#qstn_data').val();
							var qstn_type = $(this).find('#qstn_type').val();
							var qstn_help = $(this).find('#qstn_help').val();
							var qstn_parent = $(this).parent().attr('row_id');

							temp.qstn_title = qstn_title;
							temp.qstn_data = qstn_data;
							temp.qstn_type = qstn_type;
							temp.qstn_help = qstn_help;
							temp.qstn_parent = qstn_parent;
						
							if(qstn_title != "")
							{
								title_sample_qstns_records.push(temp);
							}
						});

						$.post("<?php echo base_url('manage_actions/add_text_sample_qstns_in_db'); ?>", {page_id: page_id, records: title_sample_qstns_records}, function(e)
						{
							//alert(e);
							location.href = "<?php echo base_url('manage/list_questionnaire'); ?>";
						});
					});

				}//if else ends				
			});		

		});

	</script>