<?php
  	if($out_of_index != 0)
        die("Page Not Found"); 

    $id = $audit_plan_notify_to_auditor_records_id['id'];
    $tracksheet_id = $audit_plan_notify_to_auditor_records_id['tracksheet_id'];
    $level = $audit_plan_notify_to_auditor_records_id['level'];
?>
<!--------application details-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
	              	<h3 class="box-title">Undertaking of Confidentiality & Conflict of Interest Declaration</h3>

	              	<div class="box-tools pull-right">
	                	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	              	</div>
	            </div>
	            <!-- /.box-header -->

	            <div class="box-body">
		            <div class="col-md-12 col-sl-12 col-xs-12" style="padding: 30px;">
						I, <b><?php echo $audit_plan_notify_to_auditor_records_id['username']; ?></b> Agree that I shall not, at any time during the continuance of my engagement or at anytime thereafter directly or indirectly use, record or disclose any confidential information.
						<br>
						I agree not to use any EAS documentation in any other capacity other than for EAS clients.
						I agree not to show or disclose any information or documentation to anyone who is not an EAS client unless required to do so by the authorities or a Court of Law.
						<br>
						Upon termination of my engagement I shall, without retaining copies thereon, return all such documents, electronic files or extracts of documents and all other notes, memoranda, records, or other material made or procured to be made by me or issued to me during my engagement to the business of EAS or any of its clients.
						<br>
						For the purpose of this undertaking I acknowledge that confidential information means all technical and business information of EAS and its clients, which is deemed to be of a confidential nature.
						<br>
						I hereby declare that, there is no conflict of interest and I will carry out this task in a  
						Professional and impartial manner with this client <b><?php echo $database_record->client_name; ?></b> 
						As I have not being involved through a previous dispute, shareholding or directorship,  
						Consultancy, internal audit, training, etc.,
						<br><br>

						I also agree to declare any conflict of interest that may occur during my tenure as an auditor (if applicable).
						<br><br>

						Guidance on potential conflicts is as follows.						
						<ul style="margin: 15px;">
							<li>Formal links/interests with another certification body - e.g. a director, Committee Member or shareholder</li>
							<li>Declaration of involvement (either directly or indirectly) with any EAS client Please record any conflicts below</li>
						</ul>
						<br><br>

						Please record any conflicts below
						<br>
						1.	CONSULTANCY PROVIDED TO THE CLIENT WITH IN THE PERIOD OF 2 YEARS
						<br>
						2.	NOT BEING A SHAREHOLDER OR DIRECTOR IN THE AUDITED COMPANY
						<br><br> <br><br> 

						<b>Managing Director</b>
						<br>
						Empowering Assurance Systems Pvt. Ltd.


		            </div>	
	            </div>   
	            <div class="text-center">
	            	<a class="btn btn-success" href="<?php echo base_url('auditor/accept_cofid_agg/'. $id. '/' . $tracksheet_id . '/' .$level); ?>">Accept</a>
	            	<a class="btn btn-danger" href="<?php echo base_url('auditor/list_my_audit_plan'); ?>">Reject</a>
	            	<br><br>
	            </div>	                
            </div>
        </div>
	</section>