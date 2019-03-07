<?php
    if($out_of_index == 1)
        die("Page Not Found");    

    $tracksheet_id = $this->uri->segment(3);
?>
  
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
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-6 col-sl-12 col-xs-12">
                      <?php
                        $cert_type =  $database_record->certification_type; 

                        $cert_type_text = 0;
                        if($cert_type == 1)
                          $cert_type_text = "Cert";
                        else if($cert_type == 2)
                          $cert_type_text = "S1";
                        else if($cert_type == 3)
                          $cert_type_text = "S2";
                        else if($cert_type == 4)
                          $cert_type_text = "RC";

                        $tracksheet_status =  $database_record->status;  

                        $tracksheet_status_text = 0;
                        if($tracksheet_status == 1)
                          $tracksheet_status_text = "Running";
                        else if($tracksheet_status == 2)
                          $tracksheet_status_text = "Pending";
                        else if($tracksheet_status == 3)
                          $tracksheet_status_text = "Withdrawn";
                        else if($tracksheet_status == 4)
                            $tracksheet_status_text = "Suspended";
                      ?>
                        <ul class="nav nav-stacked">
                            <li><a href="#">ID <span class="pull-right  "><?php echo $database_record->tracksheet_id; ?></span></a></li>
                            
                            <li><a href="#">Certification Type<span class="pull-right  "><?php echo $cert_type_text; ?></span></a></li>
                                                      
                        </ul>
                    </div>
                    <div class="col-md-6 col-sl-12 col-xs-12">                      
                      <ul class="nav nav-stacked">
                          <li><a href="#">Date <span class="pull-right  "><?php echo $database_record->track_date; ?></span></a></li>
                          <li><a href="#">Month <span class="pull-right  "><?php echo $database_record->track_month; ?></span></a></li>
                          <li><a href="#">Year<span class="pull-right  "><?php echo $database_record->track_year; ?></span></a></li>
                          
                      </ul>
                    </div>  
                </div>     
            </div>
        </div>
  </section>

<!--------client details-------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Application Details</h3>

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
                        <li><a href="#">Customer ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
                        <li><a href="#">Name of Organization<span class="pull-right  "><?php echo $database_record->client_name; ?></span></a></li>
                        <li><a href="#">Address<span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>                                   
                    </ul>
                </div>

                <div class="col-md-6 col-sl-12 col-xs-12">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Scope<span class="pull-right  "><?php echo $database_record->scope; ?></span></a></li>
                        <li><a href="#">Scheme <span class="pull-right  "><?php echo $database_record->scheme_name; ?></span></a></li>
                  </ul>
                </div>  
              </div>           
            </div>
        </div>
     </section>

<!--------Tracksheet Status Change Request ------->
    <section class="content">
        <!-- left column -->
        <div class="col-md-12">
              <!-- general form elements -->                
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Tracksheet Status Change Request</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="col-md-12 col-sl-12 col-xs-12">
                  <?php
                    $row_id = $withdrawn_md_approval_req['id'];
                    $md_approved = $withdrawn_md_approval_req['md_approved'];
                    $remarks = $withdrawn_md_approval_req['remarks'];
                  ?>  
                      <b>Remarks</b>
                      <div><?php echo $remarks; ?></div><br>
                  <?php
                    if($md_approved == 1)
                      echo "Already Approved";
                    else if($md_approved == 2) //rejected
                    {
                      echo "<b>It was rejected by you earlier.</b><br><br>";
                  ?>
                      <button class="btn btn-danger reject_btn">Reject</button>
                      <button class="btn btn-success approve_btn">Approve</button>
                  <?php    
                    }
                    else
                    {
                  ?>
                      <button class="btn btn-danger reject_btn">Reject</button>
                      <button class="btn btn-success approve_btn">Approve</button>
                  <?php        
                    }
                  ?>
                </div>
              </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
    //on clicking on reject button  
      $('.reject_btn').click(function(argument)
      {
        var row_id = "<?php echo $row_id; ?>";
        var md_approved = 2; //rejected

        $.post('<?php echo base_url('manage_actions/update_md_approved_status_in_tracksheet_status_table'); ?>', {row_id: row_id, md_approved: md_approved}, function(data)
          {
            if(data == 1)
              location.href = "<?php echo base_url('manage/list_tracksheet_status_change_req'); ?>"              
            else
              alert('Something went wrong while rejecting the MD Approval');
          });
      });

    //on clicking on approve button  
      $('.approve_btn').click(function(argument)
      {
        var row_id = "<?php echo $row_id; ?>";
        var md_approved = 1; //accepted

        var tracksheet_id = "<?php echo $tracksheet_id; ?>";
        var status = 3; //withdrawn

        $.post('<?php echo base_url('manage_actions/update_md_approved_status_in_tracksheet_status_table'); ?>', {row_id: row_id, md_approved: md_approved}, function(data)
          {
            if(data == 1)
            {
              $.post('<?php echo base_url('manage_actions/update_tracksheet_status'); ?>', {tracksheet_id: tracksheet_id, status: status}, function(e)
              {
                if(e == 1)
                  location.href = "<?php echo base_url('manage/list_tracksheet_status_change_req'); ?>";
                else
                  alert('Something went wrong while changing the tracksheet status');
              });              
            }
            else
              alert('Something went wrong while aproving the MD Approval');
          });
      });
    </script>