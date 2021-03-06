<?php
    if($out_of_index == 1)
        die("Page Not Found");    

    $tracksheet_id = $this->uri->segment(3);
    $level = $this->uri->segment(4);

    $id = $get_certi_issue_checklist_records['id'];
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
                        <li><a href="#">ANZSIC Codes 
                          <span class="pull-right  ">
                            <?php
                              $anzsic_codes = "";
                              foreach ($app_rev_anzsic_codes_record as $key => $app_rev_anzsic_codes_rec)
                              {
                                $anzsic_code = $app_rev_anzsic_codes_rec['anzsic_code'];

                                $anzsic_codes = $anzsic_codes . "#" . $anzsic_code;
                              }

                              echo ltrim($anzsic_codes,"#");
                            ?>                            
                          </span></a>
                        </li>
                  </ul>
                </div>  
              </div>           
            </div>
        </div>
     </section>

<!------Contract review records---->
 <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Contract Review Details</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
                <li><a href="#">Contract Review Date<span class="pull-right  "><?php echo $app_rev_form_record->reviewed_by_date; ?></span></a></li>
                <li><a href="#">Contract Reviewed By<span class="pull-right  "><?php echo $app_rev_form_record->reviewed_by_name; ?></span></a></li>
              </ul>
            </div>

            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
                <li><a href="#">Total Effective Employee<span class="pull-right  "><?php echo $app_rev_form_record->total_eff_emp; ?></span></a></li>

              </ul>
            </div>
            <div class="col-md-12 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
                <li><a>Manual Dated<span class="pull-right  "><?php echo $get_certi_issue_checklist_records['manual_date']; ?></span></a></li>
                <li><a>IQA Dated<span class="pull-right  "><?php echo $get_certi_issue_checklist_records['iqa_date']; ?></span></a></li>
                <li><a>MRM Dated<span class="pull-right  "><?php echo $get_certi_issue_checklist_records['mrm_date']; ?></span></a></li>
              </ul>
            </div>
          </div>
      </div>
    </div>
  </section>

<!------auditor allocation--->
 <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Auditor Allocation</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <?php
                $auditors = [];
                $tech_experts = [];
                $dec_maker = [];
                foreach ($app_rev_audit_team_record as $key => $app_rev_audit_team_rec)
                {
                  $type = $app_rev_audit_team_rec['type'];
                  
                  if($type == 1 OR $type == 3)
                  {
                    $username = $app_rev_audit_team_rec['username'];
                    array_push($auditors, $username);
                  }
                  else if($type == 2)
                  {
                    $username = $app_rev_audit_team_rec['username'];
                    array_push($tech_experts, $username);
                  }
                  else if($type == 4)
                  {
                    $username = $app_rev_audit_team_rec['username'];
                    array_push($dec_maker, $username);
                  }
                }
              ?>
          <!-------auditors---->    
            <div class="col-md-6 col-sl-12 col-xs-12">              
              <ul class="nav nav-stacked">
                <b>Auditors/Lead Auditors</b>
                <?php
                  foreach ($auditors as $key => $auditor)
                  {
                ?>
                   <li><a href="#">Name<span class="pull-right  "><?php echo $auditor; ?></span></a></li>
                
                <?php    
                  }
                ?>
              </ul>
            </div>

          <!-------technical experts---->
            <div class="col-md-6 col-sl-12 col-xs-12">              
              <ul class="nav nav-stacked">
                <b>Technical Expert</b>
                <?php
                  foreach ($tech_experts as $key => $tech_expert)
                  {
                ?>
                   <li><a href="#">Name<span class="pull-right  "><?php echo $tech_expert; ?></span></a></li>
                <?php    
                  }
                ?>
              </ul>
            </div>

          </div>
      </div>
    </div>
  </section>

<!----------man days------>
   <section class="content">
    <!-- left column -->
      <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Man-Days</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>

          <!-- /.box-header -->
          <div class="box-body">
              <?php
                if($level == 2)
                {
                  $date_from1 = "stage1_date_from";
                  $date_to1 = "stage1_date_to";

                  $date_from2 = "stage2_date_from";
                  $date_to2 = "stage2_date_to";

                  $man_days1 = "stage1_man_days";
                  $man_days2 = "stage2_man_days";

                  $stage1 = "Stage 1";
                  $stage2 = "Stage 2";
                }
                else if($level == 3 || $level ==4)
                {
                  $date_from1 = "surv1_date_from";
                  $date_to1 = "surv1_date_to";

                  $date_from2 = "surv2_date_from";
                  $date_to2 = "surv2_date_to";

                  $man_days1 = "surv1_man_days";
                  $man_days2 = "surv2_man_days";

                  $stage1 = "Surveillance 1";
                  $stage2 = "Surveillance 2";
                }

                $stage1_date_from = $audit_program_form_records[$date_from1];
                $stage1_date_to = $audit_program_form_records[$date_to1];

                $stage2_date_from = $audit_program_form_records[$date_from2];
                $stage2_date_to = $audit_program_form_records[$date_to2];

              //delievered man days calculation
                $del_man_days1 =  ((strtotime($stage1_date_to) -  strtotime($stage1_date_from))/(60*60*24)) + 1;
                $del_man_days2 =  ((strtotime($stage2_date_to) -  strtotime($stage2_date_from))/(60*60*24)) + 1;

              //planned man days calculation
                $pla_man_days1 = $app_rev_form_record->$man_days1;
                $pla_man_days2 = $app_rev_form_record->$man_days2;
              ?>
              <div class="col-md-6 col-sl-12 col-xs-12">              
                <ul class="nav nav-stacked">
                  <b>Planned</b>

                  <li><a href="#"><?php echo $stage1; ?>  
                    <span class="pull-right  ">
                      <?php echo $pla_man_days1; ?>                      
                    </span></a>
                  </li>

                  <li><a href="#"><?php echo $stage2; ?>  
                    <span class="pull-right  ">
                      <?php echo $pla_man_days2; ?>                      
                    </span></a>
                  </li>

                </ul>
              </div>
              <div class="col-md-6 col-sl-12 col-xs-12">              
                <ul class="nav nav-stacked">
                  <b>Delivered</b>

                  <li><a><?php echo $stage1; ?>  
                    <span class="pull-right  ">
                      <?php echo $del_man_days1; ?>                   
                    </span></a>
                  </li>

                  <li><a><?php echo $stage2; ?>  
                    <span class="pull-right  ">
                      <?php echo $del_man_days2; ?>                                
                    </span></a>
                  </li>                
                </ul>
              </div>
          </div>
        </div>  
      </div>
    </section>

<!------Audit Dates---->
  <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Audit Dates</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>

          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
                <b><?php echo $stage1; ?></b>
                
                <li><a href="#">From
                  <span class="pull-right  ">
                    <?php echo $stage1_date_from; ?>                      
                  </span></a>
                </li>

                <li><a href="#">To
                  <span class="pull-right  ">
                    <?php echo $stage1_date_to; ?>                      
                  </span></a>
                </li>

              </ul>
            </div>

            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
                <b><?php echo $stage2; ?></b>

                <li><a href="#">From
                  <span class="pull-right  ">
                    <?php echo $stage2_date_from; ?>                      
                  </span></a>
                </li>

                <li><a href="#">To
                  <span class="pull-right  ">
                    <?php echo $stage2_date_to; ?>                      
                  </span></a>
                </li>

              </ul>
            </div>
          </div>
          
       </div>
      </div>
    </section>

<!------Car Recieved---->
   <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Car Recieved</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">                
                <li><a>Stage 1 Discrepancies<span class="pull-right  ">
                  <?php echo $get_certi_issue_checklist_records['stage1_disc']; ?>
                  </span>
                </a></li></ul>
            </div>

            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
               <li><a>Stage 2 Discrepancies<span class="pull-right  ">
                    <?php echo $get_certi_issue_checklist_records['stage2_disc']; ?>
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
       </div>
      </div>
    </section>

<!------technical review---->
   <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Technical Review</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">                
                <li><a>Comments Raised<span class="pull-right  ">
                  <?php echo $get_certi_issue_checklist_records['comm_raised']; ?>
                  </span>
                  </a>
                </li>
              </ul>
            </div>

            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
               <li><a>Comments Closed<span class="pull-right  ">
                  <?php echo $get_certi_issue_checklist_records['comm_closed']; ?>
                  </span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
       </div>
      </div>
    </section>

<!------CAR and content review---->
   <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">CAR & Content Review</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-12 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">                
                
                <li><a>Is corrective action taken or plan submitted, verified and approved<span class="pull-right  ">
                  <?php echo $get_certi_issue_checklist_records['corr_for_plan']; ?>
                  </span>
                  </a>
                </li>

                <li><a>Is all sections relevant sections updated with proper evidences<span class="pull-right  ">
                  <?php echo $get_certi_issue_checklist_records['rel_sec_evid']; ?>
                  </span>
                  </a>
                </li>

                <li><a>Statutory Requirements Verified in the audit report<span class="pull-right  ">
                  <?php echo $get_certi_issue_checklist_records['stat_req_veri']; ?>
                  </span>
                  </a>
                </li>

              </ul>
            </div>
          </div>
       </div>
      </div>
    </section>

<!------general qstns-------->
  <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            
            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">   
                <li>
                  <a><b>Report Checked By</b>
                    <span class="pull-right  ">
                     <?php echo $get_certi_issue_checklist_records['tech_cord']; ?>                 
                    </span>
                  </a>
                </li>
              </ul>
            </div>
            
            <div class="col-md-6 col-sl-12 col-xs-12">              
              <ul class="nav nav-stacked">
                <li><a><b>Technical Review By</b></a></li>
                <?php
                  foreach ($dec_maker as $key => $dec_maker_name)
                  {
                ?>
                   <li><a href="#">Name<span class="pull-right  "><?php echo $dec_maker_name; ?></span></a></li>                
                <?php    
                  }
                ?>
              </ul>
            </div>

            <div class="col-md-12 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
                <li>
                  <a><b>Certification Decision</b>
                    <div style="width: 100%; " id="cert_dec"><?php echo $get_certi_issue_checklist_records['cert_dec']; ?></div>
                  </a>
                </li>
                <li>
                  <a><b>Remarks</b>
                    <div style="width: 100%; " id="remarks"><?php echo $get_certi_issue_checklist_records['remarks']; ?></div>
                  </a>
                </li>

                <li><a>Date<span class="pull-right"><input type="date" id="date" disabled="disabled" value="<?php echo $get_certi_issue_checklist_records['date']; ?>"></span></a></li>
              </ul>
            </div>

          </div>
        </div>
    </div>
  </section>

<!--------md approval area------>  
 <section class="content">
    <!-- left column -->
    <div class="col-md-12">
          <!-- general form elements -->                
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">MD Approval Status</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-12 col-xs-12 col-sl-12 text-center">
              <?php
                $submitted_to_md_date = $get_certi_issue_checklist_records['submitted_to_md_date'];
                echo "Certificate issue checklist was submitted to MD on " . $submitted_to_md_date . "<br><br>";
              ?> 
              <a class="btn btn-danger" href="<?php echo base_url('manage/list_filled_certi_issue_checklist'); ?>" >Disapprove</a>
              <button class="btn btn-success approve_btn">Approve</button>
            </div>
          </div>
      </div>
    </div>
  </section>

<!-------style and script------->
  <style type="text/css">
    .file
    {
      background: #3c8dbc;
      color: white;
      box-shadow: 0px 0px 3px grey;
      border: none;
      margin:0;
      padding: 0;
      padding: 20px;
      margin: auto;
      width: 100%;
    }  

    .upload_pic_feed
    {
      height: 500px;
      overflow-y: scroll;
    }

    .upload_pic_feed img
    {
      width: 100%;
    }
  </style>

  <script type="text/javascript">
  //on clicking on approve button  
    $('.approve_btn').click(function(argument)
    {
      var id = "<?php echo $id; ?>";
      var tracksheet_id = "<?php echo $tracksheet_id; ?>";
      var level = "<?php echo $level; ?>";

      $.post('<?php echo base_url('manage_actions/update_md_approval_status_of_certi_checklist'); ?>', {id: id}, function(data)
      {
        if(data == 1)
        {
        //certification certification dates for that tracksheet
          $.post('<?php echo base_url('manage_actions/update_certification_dates_for_tracsheet'); ?>', {tracksheet_id: tracksheet_id, level: level}, function(e)
          {
            if(e == 1)
              location.href = "<?php echo base_url('manage/list_filled_certi_issue_checklist'); ?>";
            else
              alert('something went wrong in updating certification date for tracksheet');
          });
        }
        else
        {
          alert('something went wrong in updating MD approval status');
        }
      });
    });

  </script>
