<?php
    if($out_of_index == 1)
        die("Page Not Found"); 

    $tracksheet_id = $this->uri->segment(3);   
?>

<!--------INTIMATION OF CHANGES details-------->
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
					<?php						
						$new_client_name = $get_changes_records['new_client_name'];
						$new_contact_address = $get_changes_records['new_contact_address'];
						$no_of_emp = $get_changes_records['no_of_emp'];
						$perma_emp = $get_changes_records['perma_emp'];
						$temp_sites = $get_changes_records['temp_sites'];
						$new_scope = $get_changes_records['new_scope'];
						$any_process_change = $get_changes_records['any_process_change'];
						$remarks = $get_changes_records['remarks'];
					?>				

				<!--basic changes---->	
					<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Any Change in Company Name</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" value="<?php echo $new_client_name; ?>" disabled="disabled">
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Any Change in Company Address</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" value="<?php echo $new_contact_address; ?>" disabled="disabled">
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Changes in No of Employees </div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="number" value="<?php echo $no_of_emp; ?>" disabled="disabled">
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">No of Permanent Sites</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="number" value="<?php echo $perma_emp; ?>" disabled="disabled">
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">No of Temporary Sites</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="number" value="<?php echo $temp_sites; ?>" disabled="disabled">
	            	</div>
					<div class="col-md-12 col-sl-12 col-xs-12"><br></div>

            	<!-----new site adding area ----->
            	 	<h3><b>Sites</b></h3> 
					<!-----old site editing area----->
	                  <ul class="nav nav-stacked">
	                   <?php 
	                    foreach ($get_site_changes_records as $key => $get_site_record)
	                    {
	                      $site_id = $get_site_record['id'];
	                      $site_address = $get_site_record['site_address'];
	                  ?>
	                      <li>
	                        <a>Site <?php echo $key+1; ?>
	                          <span type="text" class="pull-right"><?php echo $site_address; ?></span>
	                        </a>
	                      </li>  
	                  <?php                        
	                    }
	                    ?>
                  </ul>

	            <!--more changes---->
	            	<div class="col-md-12 col-sl-12 col-xs-12"><br></div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="big_qstn" style="background: #D0D0D0;">Existing Scope</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 big_ans" style="border: 1px black solid;">
	            		<?php echo $old_scope[0]['scope']; ?>
	            	</div>

					
	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="big_qstn" style="background: #D0D0D0;">Revised Scope</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<textarea class="form-control" type="text" disabled="disabled"><?php echo $new_scope; ?></textarea>
	            	</div>
	            	<div class="col-md-12 col-sl-12 col-xs-12"></div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Any Changes In Your Process (Activities)</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" value="<?php echo $any_process_change; ?>" disabled="disabled">
	            	</div>

	            	<div class="col-md-4 col-sl-4 col-xs-4 qstn">
	               		<div class="qstn_qstn">Remarks</div>
	            	</div>
	            	<div class="col-md-8 col-sl-8 col-xs-8 ans">
	            		<input class="form-control" type="text" value="<?php echo $remarks; ?>" disabled="disabled">
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
