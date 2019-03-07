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
		                		$cm_id=$database_record->cm_id;

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
		                </ul>
		            </div>
		            <div class="col-md-6 col-sl-12 col-xs-12">
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Contact Name <span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>
		                    <li><a href="#">Contact Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>
		                     <li>
		                    	<a href="#">
			                    	Contact Email 
			                    	<span class="pull-right">
			                    		<?php 
          											$contact_email = $database_record->contact_email;
          											echo $contact_email;
          										?>	
			                    	</span>
		                    	</a>
		                    </li>
		              </ul>
		            </div>		            

	              <!-- /.table-responsive -->	
	              	<div class="col-md-12 col-sl-12 col-xs-12" style="text-align: center;">
	              		<br>
		                <?php echo form_open(base_url('planning/view_customer_info/' . $cm_id),['class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
                       		<input id="view_tracksheet_button" type="submit" value="View Complete Details" class="btn btn-primary" />
                    	<?php echo form_close("\n")?>
		           	</div>  
	            </div>           
            </div>
        </div>
	   </section>

<!--------tracksheet basic details-------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">TrackSheet Details</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-6 col-sl-12 col-xs-12">
                    	<?php
                        $tracksheet_id = $database_record->tracksheet_id;
                     		$cert_type =  $database_record->certification_type;	

                        $scheme_name = $database_record->scheme_name;
                        $cert_date_to = $database_record->cert_date_to;

                        if($cb_type == 1)//EAS
                        {
                            $sign = "Empowering Assurance Systems Pvt Ltd";
                        }
                        else
                        {
                            $sign = "Integrated Assessment Services Pvt Ltd";
                        }

          							$cert_type_text = 0;
          							if($cert_type == 1)
          								$cert_type_text = "Cert";
          							else if($cert_type == 2)
                        {                          
                          $mail_subject = "Surveillance Audit Reminder " . $notify_no . " For " . $scheme_name;

                          $stage = "First Surveillance";                          
          								$cert_type_text = "S1";
                        }
          							else if($cert_type == 3)
                        {
                          $mail_subject = "Surveillance Audit Reminder " . $notify_no . " For " . $scheme_name;

                          $stage = "Second Surveillance";                          
          								$cert_type_text = "S2";
                        }
          							else if($cert_type == 4)
                        {
                          $mail_subject = "Re-Certification Reminder " . $notify_no . " For " . $scheme_name;

                          $stage = "Re- Certification";                          
          								$cert_type_text = "RC";
                        }
                        ?>
                        <ul class="nav nav-stacked">
                           	<li><a href="#">ID <span class="pull-right  "><?php echo $database_record->tracksheet_id; ?></span></a></li>
                           	<!-- <li><a href="#">Scheme <span class="pull-right  "><?php echo $database_record->scheme_name; ?></span></a></li> -->
                           	<li><a href="#">Certification Type<span class="pull-right  "><?php echo $cert_type_text; ?></span></a></li>
                        </ul>
                    </div>
                     <div class="col-md-6 col-sl-12 col-xs-12">                     	
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Date <span class="pull-right  "><?php echo $database_record->track_date; ?></span></a></li>
                         	<li><a href="#">Month <span class="pull-right  "><?php echo $track_month = $database_record->track_month; ?></span></a></li>
                         	<li><a href="#">Year<span class="pull-right  "><?php echo $database_record->track_year; ?></span></a></li>
		              	</ul>
		            </div>	
                </div>     
            </div>
        </div>
	</section>

<!-----mail body defining area------->  
<?php
$surv_body = "This is to inform you that your " . $stage . " audit is due in the month of " . $track_month . ". Your surveillance audit shall be convened on Third Week of _________. You are expected to complete the surveillance audit without delay.\nDuring the course of surveillance audit the auditors shall verify the Continuation of your ISO system as per the planned arrangements and major focus shall be in the area of the following:
\n1. Achievement of your quality objectives
\n2. Measurement of Process.
\n3. Continual Improvement 
\n4. Analysis of data
\n5. Customer complaints and rectification of the same.\n
If there is any change in the process or activities of your organization’s management System, please inform us to amend our audit plan accordingly or you can fill the intimation of changes form in your client login at www.act.iasiso.com/customer.\n
For further queries and assistance you can contact our technical team 
(044-42693624/9789900430) of Integrated Assessment Services Pvt. Ltd.\n
As per the requirement of UQAS, Surveillance audit should be completed on 11th month. Failing to complete the surveillance audit will lead to cancellation of your certification status and your original certificate copies will be collected by IAS.\n
If we are not hearing from you, we shall proceed with the same date.\n
Certificate Expiry Date: " . $cert_date_to . "\n
Thanks and regards.\n" . $sign;

$rc_body = "This is to inform you that your Re- Certification is due and is to be completed before Third Week of _________. This Re- Certification Audit will enable you to get a Certificate for a validity of three years.\n
In this regard our staff will be in touch with you for Re- Certification purpose. The Re- Certification will be done in " . $scheme_name . " (Latest Version).\n
If there is any change in the process or activities of your organization’s management System, please inform us to amend our audit plan accordingly or you can fill the intimation of changes form in your client login at www.act.iasiso.com/customer.\n
For further queries and assistance you can contact our technical team (044-42693624/9789900430) of Integrated Assessment Services Pvt Ltd.\n
Certificate Expiry Date: " . $cert_date_to . "\n
Thanks and regards.\n" . $sign;

  if($cert_type == 2 || $cert_type == 3)
  {
    $mail_body = $surv_body;
  }
  else if($cert_type == 4)
  {
    $mail_body = $rc_body;
  }
  else
  {
    $mail_body = "";
  }
?>

<!--------sending notification area------->
	<section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Send Notification</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12 col-sl-12 col-xs-12">
                    	<form action="<?php echo base_url('planning/send_mail'); ?>" method="post">                    
                    		<input type="hidden" value="<?php echo $tracksheet_id; ?>" name="tracksheet_id">
                        <input type="hidden" value="<?php echo $notify_no; ?>" name="notify_no">
                        <input type="hidden" value="<?php echo $type; ?>" name="type">

                    		<label for="">Email</label>
                    		<input style="border: 1px grey solid; border-radius: 3px" class="form-control" type="text" value="<?=$contact_email ?>" name="mail_email">
                    		<br>
                    		<label for="">Subject</label>
                    		<input style="border: 1px grey solid; border-radius: 3px" class="form-control" type="text" name="mail_subject" value="<?php echo $mail_subject; ?>">
                    		<br>
                    		<label for="">Body</label>
                    		<textarea class="form-control" style="border: 1px grey solid; border-radius: 3px" rows="25" placeholder="write mail body here" name="mail_body"><?php echo $mail_body; ?></textarea>
                    		<br>
                    		<input type="submit" class="btn btn-primary center-block" value="notify" name="notify">
                    	</form>
                    	<br>
                    </div>
                </div>     
            </div>
        </div>
	</section>
