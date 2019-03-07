<?php
	if($out_of_index == 1)
		die("Page Not Found");
	else if($out_of_index == 2)
		die("Permission Denied");

	foreach ($get_scope_of_cert as $key => $get_scope_of_cert_record)
	{
		$id = $get_scope_of_cert_record['id'];
		$scope = $get_scope_of_cert_record['scope'];
		$comment = $get_scope_of_cert_record['comment'];
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

		                		$cm_id = $database_record->cm_id;

		                		$cert_type = $database_record->certification_type;

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
		                  	<li><a href="#">Scope<span class="pull-right  "><?php echo $database_record->scope;; ?></span></a></li>
		              	</ul>
		            </div>		            
	            </div>           
            </div>
        </div>
	</section>

<!--------scope of certification-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Scope Of Certification</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
		           <b>Recommended Certification Scope</b>
	            	<br>
	            	<div class="scope_textarea" name="scope"><?php echo $scope; ?></div>
	            	<br>
	            	<b>Lead Auditor Comment</b>
	            	<br>
	            	<div class="scope_textarea" name="comment"><?php echo $comment; ?></div>
		        </div>
		    </div>
		</div>
	</section>


	<style type="text/css">
		.scope_textarea
		{
			width: 100%;
			min-height: 60px;
		}
	</style>	