<?php
//getting certification type from post request

	if(isset($_POST['cert_type']))
	{
		$cert_type =  $_POST['cert_type'];
	}
	else
	{
		$cert_type = "";
	}

	$cert_type_text = 0;
	if($cert_type == 1)
		$cert_type_text = "Cert";
	else if($cert_type == 2)
		$cert_type_text = "S1";
	else if($cert_type == 3)
		$cert_type_text = "S2";
	else if($cert_type == 4)
		$cert_type_text = "RC";
	//1 = initial (cert)
	//2 = Surveillance 1 (S1)
	//3 = Surveillance 2 (S2)
	//4 = Re-Certification (RC)

//getting all customers details
	$cm_id = $database_record->cm_id;
	$client_name = $database_record->client_name;
	$cb_type = $database_record->cb_type;
	$cb_type_name = 0;
	if($cb_type == 1)
		$cb_type_name = "EAS";
	else
		$cb_type_name = "IAS";
?>

<section class="content">
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Client Details</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
              </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
	            <div class="col-md-6 col-sl-12 col-xs-12">
	                <ul class="nav nav-stacked">
	                	<?php 
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
	                    
	                    <li>
	                    	<a href="#">
		                    	Address 
		                    	<span class="pull-right">
		                    		<?php 
										echo $database_record->contact_address;
									?>	
		                    	</span>
	                    	</a>
	                    </li>
	                    <li><a href="#">Scope<span class="pull-right  "><?php echo $database_record->scope; ?></span></a></li>
	                </ul>
	            </div>
            
            </div>
       
        </div>
    </div>
</section>

<section class="content">    
    <!-- left column -->
    <div class="col-md-12<?php //if(!isset($record)){echo 'col-md-6';}else{echo 'col-md-12';}?>">
      <!-- general form elements -->                
        <div class="box box-primary">
        <!-- /.box-header -->
            <div class="box-header">
                <h3 class="box-title">Enter data</h3>
            </div>

        <!-- form start -->   
            <div class="box-body">
                <div class="row">
                	<input type="hidden" id="cm_id" name="cm_id" value="<?php echo $cm_id;?>" />            
                
                    <div class="col-md-6">                                
                        <div class="form-group">
                            <label for="cb_type">CB Type</label>
                            <input type="text" class="form-control required" id="cb_type" name="cb_type" value="<?php echo $cb_type_name; ?>" disabled="disabled" />
                        </div>
                    </div>    

                    <input type="hidden" name="socpe" id="scope" value="<?= $database_record->scope;?>"> 

                    <div class="col-md-6">                                
                        <div class="form-group">
                            <label for="cert_type">Certification Type</label>
                            <?php
                            	if($cert_type != 0)
                            	{
                            ?>
                           	 		<input value="<?php echo $cert_type_text; ?>" disabled="disabled" type="text" class="form-control required" id="cert_type" name="certification_type" />
                            <?php
                            	}
                            	else
                            	{
                            ?>
                            	<select class="form-control " name="certification_type" id="cert_type">
		                            <option value="0">Select Cert. Type</option> 
		                            <option value="1">Cert</option> 
		                            <option value="2">S1</option> 
		                            <option value="3">S2</option> 
		                            <option value="4">RC</option>   
		                        </select>
                            <?php
                            	}
                            ?>
                        </div>
                    </div>                    
                                  
                    <div class="col-md-6">                                
                        <div class="form-group">
                            <label for="track_date">Date</label>
                            <input type="date" value="<?php echo date('Y-m-d'); ?>" class="required" style="width: 100%; border: none; background: #eee; height: 34px; padding: 2px;" id="track_date" name="track_date" />
                        </div>
                    </div>  
                                      
                    <div class="form-group col-sm-6">
                        <label for="month">Month</label>
                        <select class="form-control " name="track_month" id="track_month" required="required">
                            <option value="0">Select Month</option> 
                            <option value="JAN">JAN</option> 
                            <option value="FEB">FEB</option> 
                            <option value="MAR">MAR</option> 
                            <option value="APR">APR</option>                              
                            <option value="MAY">MAY</option> 
                            <option value="JUN">JUN</option> 
                            <option value="JULY">JULY</option> 
                            <option value="AUG">AUG</option> 
                            <option value="SEPT">SEPT</option> 
                            <option value="OCT">OCT</option> 
                            <option value="NOV">NOV</option> 
                            <option value="DEC">DEC</option>
                        </select>
                    </div>   

                    <div class="col-md-6">                                
                        <div class="form-group">
                            <label for="track_year">Year</label>
                            <input type="number" class="form-control required" id="track_year" name="track_year" />
                        </div>
                    </div>                                                                                       
               </div>

            <!--------scheme adding areas--------->
               	<div class="row">

               		<ul id="scheme_ul">
               			<li>
               				<div class="col-md-6">     
			                    <div class="form-group">
			                        <label for="scheme_type">Scheme Type</label>
			                        <select class="form-control " id="scheme_type" name="scheme_type" required="required">
			                        	<option value="0">Choose Scheme Type</option>
			                            <option value="1">Process</option> 
			                            <option value="2">Product</option> 
			                        </select>
			                    </div>  

		                    </div>            
		              		
		              		<div class="col-md-6">    
		                        <div class="form-group">
		                            <label for="scheme">Scheme</label>
		                            <select class="form-control " id="scheme" name="scheme" required="required">
		                            	<option value="0">Select Scheme</option> 

		                        	</select>
		                        </div>
		                    </div>
               			</li>

               		</ul>

               		<div class="col-md-6">     			                  
               			<button id="add_more_scheme_button" class="btn btn-primary">Add More Schemes</button>
		            </div>
               </div>
            <!--------scheme adding areas--------->

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
                <input id="create_tracksheet_button" type="submit" class="btn btn-primary" value="Submit" />
                <br><br>
                <span id="feed"></span>
            </div>
        </div>
    </div>
</section>

<!--------script---------->
<script type="text/javascript">
//for getting the schemes according to the choosed scheme type
	$('#scheme_ul li #scheme_type').change(function()
	{
		get_scheme_list($(this));
	});

//function to get scheme list
	function get_scheme_list(this_th)
	{		
		var scheme_type = this_th.val();
		var this_thing = this_th.parent().parent().parent().find('#scheme');
		
		$.post('<?=base_url()?>technical_action/get_scheme_list', {scheme_type: scheme_type}, function(data)
		{
			this_thing.html("<option value=\"0\">Select Scheme</option>");
			this_thing.append(data);
		});
	}

//for creating more scheme for a customer
	$('#add_more_scheme_button').click(function()
	{
		var html = $('#scheme_ul li:first').html();		
		$('#scheme_ul').append("<li>" + html + "</li>");
		$('#scheme_ul li:last-child').find('#scheme').html("<option value=\"0\">Select Scheme</option>");

		$('#scheme_ul li #scheme_type').change(function()
		{
			get_scheme_list($(this));
		});
	});

//for inserting the tracksheet data into the database
	$('#create_tracksheet_button').click(function()
	{
		$(this).hide();
		
		var cm_id = "<?=$cm_id ?>";
		var cb_type = "<?=$cb_type ?>";
		var track_month = $('#track_month').val();
		var track_year = $('#track_year').val();
		var track_date = $('#track_date').val();
		var cert_type = "<?=$cert_type ?>";
		var scope = $('#scope').val();

		var cert_type = $.trim(cert_type);
		if(cert_type =='')
		{
			var cert_type = $('#cert_type').val(); 
		};

		if(track_date !="" && track_month !=0 && track_year!="" && cert_type !=0)
		{			
			$('#feed').text("").css('color', 'black');

		//counting the numbers of scheme and all scheme in a uniqure tracksheet for that customer
			var count = $("#scheme_ul li").length;

			for(var i=0; i<count; i++)
			{
				var scheme_id = $('#scheme_ul li:nth-child(' + (i+1) + ') #scheme').val();
				if(scheme_id !=0)
				{		
					$.post('<?=base_url()?>planning/create_new_tracksheet', {cm_id: cm_id, cb_type: cb_type, track_month: track_month, track_year: track_year, track_date: track_date, scheme_id: scheme_id, cert_type: cert_type, scope: scope}, function(data)
					{
						if(data == 1)
						{
						}
						else
						{
							$('#feed').text("something went wrong while inserting data into the database").css('color', 'red');
						}
					});
				}
			}	

		//updating the tracksheet_status of that customer in mdt_customer_master table				
			$.post('<?=base_url()?>planning/update_customer_tracksheet_status', {cm_id: cm_id}, function(data)
			{
				if(data == 1)
				{
					location.href= "<?=base_url()?>planning/list_tracksheet";					
				}
				else
				{
					$('#feed').text("something went wrong while inserting data into the database").css('color', 'red');
				}
			});
		}
		else
		{
			$('#feed').text("please fill all the data").css('color', 'red');
		}
	});
</script>
