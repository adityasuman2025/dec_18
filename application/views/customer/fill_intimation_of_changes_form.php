<?php
    if($out_of_index == 1)
        die("Page Not Found"); 

    $tracksheet_id = $this->input->input_stream('tracksheet_id', TRUE);
    $level = $this->input->input_stream('level', TRUE);
?>

<!--------INTIMATION OF CHANGES IN OUR MANAGEMENT-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
					<h3 class="box-title">INTIMATION OF CHANGES IN OUR MANAGEMENT</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
              	</div>
				<div class="box-body">				
				<!--basic changes---->	
					<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Any Change in Company Name</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" id="new_client_name" >
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Any Change in Company Address</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" id="new_contact_address" >
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Changes in No of Employees </div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="number" id="no_of_emp" >
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">No of Permanent Sites</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="number" id="perma_emp" >
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">No of Temporary Sites</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="number" id="temp_sites" >
	            	</div>
					<div class="col-md-12 col-sl-12 col-xs-12"><br></div>

            	<!-----new site adding area ----->
            	 	<h3><b>Sites</b></h3> 
					<ul class="nav nav-stacked new_site_ul">
						<li style="display: none;">
						    <a>Site 
						     	<input type="text" class="a_input new_site_address" value="">
						    </a>
						</li>  
					</ul>

              		<button class="btn btn-primary add_site_btn">Add Site</button>

	            <!--more changes---->
	            	<div class="col-md-12 col-sl-12 col-xs-12"><br></div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="big_qstn"><b>Existing Scope</b></div>	               		
	               		<?php echo $old_scope[0]['scope']; ?>
	               		<br><br>
	            	</div>
					
	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="big_qstn"><b>Revised Scope</b></div>	               		
	               		<textarea class="form-control" type="text" id="new_scope" style="width: 100%"></textarea>
	               		<br><br>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		
	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Any Changes In Your Process (Activities)</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" id="any_process_change" >
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Remarks</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" id="remarks" >
	            	</div>

	            	<div class="col-md-12 col-sl-12 col-xs-12 text-center">
	            		<br><br>
	            		<button class="btn btn-success submit_btn">Submit</button>	
	            	</div>
				</div>
            </div>           
        </div>
    </section>

<!----style and script-------->
    <style type="text/css">
	    .a_input
	    {
	      width: 50%;
	      height: 34px;
	      vertical-align: middle;
	    }
  	</style>

	<script type="text/javascript">
	//on clicking on add site button
	    $('.add_site_btn').click(function()
	    {
	      var html = $('.new_site_ul li:first').html();

	      $('.new_site_ul').append("<li>" + html + "</li>");
	    });

	//on clicking on submit button
		$('.submit_btn').click(function()
		{
			$(this).hide();
			
			var tracksheet_id = "<?php echo $tracksheet_id; ?>";
			var level = "<?php echo $level; ?>";

			var new_client_name = $('#new_client_name').val();
			var new_contact_address = $('#new_contact_address').val();
			var no_of_emp = $('#no_of_emp').val();
			var perma_emp = $('#perma_emp').val();
			var temp_sites = $('#temp_sites').val();
			var new_scope = $('#new_scope').val();
			var any_process_change = $('#any_process_change').val();
			var remarks = $('#remarks').val();

		//inserting the site address in database
			var site_address_records = [];
			$('.new_site_address').each(function()
			{
				var site_address = $(this).val();

				if(site_address != "")
				{					
					var temp = {};
					temp.site_address = site_address;
					site_address_records.push(temp);
				}
			});

			$.post('<?php echo base_url('customer/insert_inti_of_changes_records'); ?>', {tracksheet_id:tracksheet_id, level:level, new_client_name:new_client_name, new_contact_address:new_contact_address, no_of_emp:no_of_emp, perma_emp:perma_emp, temp_sites:temp_sites, new_scope:new_scope, any_process_change:any_process_change, remarks:remarks}, function(a)
			{
				var intimation_of_changes_id = a;

				$.post('<?php echo base_url('customer/insert_inti_of_changes_site_records'); ?>', {intimation_of_changes_id:intimation_of_changes_id, site_address_records:site_address_records}, function(b)
				{
					if(b == 1)
					{
						location.href = "<?php echo base_url('customer/fill_intimation_of_changes'); ?>";
					}
					else
					{
						alert('Something went wrong');
					}
				});
			});
		});
	</script>