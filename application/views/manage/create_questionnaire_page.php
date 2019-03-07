<form method="post" class="create_q text-center" action="<?php echo base_url('manage/create_questionnaire_form'); ?>">
	<span class="title_q">Scheme</span>
	<select class="form-control" name="scheme_id">
		<option value="0">Select Scheme</option>
		<?php
			foreach ($scheme_list_record as $key => $value) 
			{
				$scheme_id = $value['scheme_id'];
				$scheme_name = $value['scheme_name'];
				$scheme_system = $value['scheme_system'];

				echo "<option value=\"$scheme_id\">$scheme_name</option>";
			}
		?>
	</select>
	<br>

	<span class="title_q">Level</span>
	<select class="form-control" name="level">
		<option value="0">Select level</option>
		<option value="1">Stage 1</option>
		<option value="2">Stage 2</option>
	</select>
	<br>

	<button class="btn btn-primary">Next</button>
</form>
