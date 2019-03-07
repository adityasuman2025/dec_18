<?php
    if($out_of_index == 1)
        die("Page Not Found");    

    $userid = $_SESSION['userid'];
    $tech_emp_count = count($get_assigned_users_of_tracksheet);

    $user_ids = [];
    if($tech_emp_count == 0)
    {
      die("Permission Denied");
    }
    else
    {                      
      foreach ($get_assigned_users_of_tracksheet as $key => $value) 
      {
        $user_id = $value['user_id'];
        array_push($user_ids, $user_id);
      }

      if(!in_array($userid, $user_ids))
        die("Permission Denied");
    }

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

                        if($cert_type == 2)
                        {
                          $level = 3;
                        }
                        else if($cert_type == 3)
                        {
                          $level = 4;
                        }
                        else
                        {
                          $level = 2;
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
                <li><a>Manual Dated<span class="pull-right  "><input type="date" id="manual_date"></span></a></li>
                <li><a>IQA Dated<span class="pull-right  "><input type="date" id="iqa_date"></span></a></li>
                <li><a>MRM Dated<span class="pull-right  "><input type="date" id="mrm_date"></span></a></li>
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
                  <input type="text" id="stage1_disc">
                  </span>
                </a></li></ul>
            </div>

            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
               <li><a>Stage 2 Discrepancies<span class="pull-right  ">
                    <input type="text" id="stage2_disc">
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
       </div>
      </div>
    </section>

<!------technicak review---->
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
                  <input type="text" id="comm_raised">
                  </span>
                  </a>
                </li>
              </ul>
            </div>

            <div class="col-md-6 col-sl-12 col-xs-12">
              <ul class="nav nav-stacked">
               <li><a>Comments Closed<span class="pull-right  ">
                  <input type="text" id="comm_closed">
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
                  <input type="text" id="corr_for_plan">
                  </span>
                  </a>
                </li>

                <li><a>Is all sections relevant sections updated with proper evidences<span class="pull-right  ">
                  <input type="text" id="rel_sec_evid">
                  </span>
                  </a>
                </li>

                <li><a>Statutory Requirements Verified in the audit report<span class="pull-right  ">
                  <input type="text" id="stat_req_veri">
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
                  <a>Report Checked By
                    <span class="pull-right  ">
                      <select id="tech_cord">
                        <?php
                          foreach ($tech_cords as $key => $tech_cord)
                          {
                            $user_id = $tech_cord->user_id;
                            $name = $tech_cord->employee_name;
                        ?>
                            <option value="<?php echo $user_id ?>"><?php echo $name; ?></option>
                        <?php    
                          }
                        ?>
                      </select>                  
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
                  <a>Certification Decision
                    <textarea style="width: 100%; " id="cert_dec"></textarea>
                  </a>
                </li>
                <li>
                  <a>Remarks
                    <textarea style="width: 100%; " id="remarks"></textarea>
                  </a>
                </li>

                <li><a>Date<span class="pull-right"><input type="date" id="date" disabled="disabled" value="<?php echo date('Y-m-d'); ?>"></span></a></li>
              </ul>
            </div>

          </div>
        </div>
    </div>
  </section>

<!---------buttons----area----->
  <div class="text-center">
    <button class="btn btn-primary submit_md_btn">Submit to MD</button>
  </div>

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
      max-height: 300px;
      overflow-y: scroll;
    }

  </style>

  <script type="text/javascript">
  //on clicking on submit to md button
    $('.submit_md_btn').click(function()
    {      
      $(this).hide();
      
      var tracksheet_id = "<?php echo $tracksheet_id; ?>";
      var level = "<?php echo $level; ?>";

      var manual_date = $('#manual_date').val();
      var iqa_date = $('#iqa_date').val();
      var mrm_date = $('#mrm_date').val();

      var stage1_disc = $('#stage1_disc').val();
      var stage2_disc = $('#stage2_disc').val();
      
      var comm_raised = $('#comm_raised').val();
      var comm_closed = $('#comm_closed').val();

      var corr_for_plan = $('#corr_for_plan').val();
      var rel_sec_evid = $('#rel_sec_evid').val();
      var stat_req_veri = $('#stat_req_veri').val();

      var tech_cord = $('#tech_cord').val();
      var cert_dec = $('#cert_dec').val();
      var remarks = $('#remarks').val();
      var date = $('#date').val();

      var submitted_to_md = 2;     

      var jas_photo_src = "";
      var eas_photo_src = "";
      
      $.post('<?php echo base_url('technical_action/insert_in_ldt_certi_issue_checklist'); ?>', {tracksheet_id:tracksheet_id, level:level, manual_date:manual_date, iqa_date:iqa_date, mrm_date:mrm_date, stage1_disc:stage1_disc, stage2_disc:stage2_disc, comm_raised:comm_raised, comm_closed:comm_closed, corr_for_plan:corr_for_plan, rel_sec_evid:rel_sec_evid, stat_req_veri:stat_req_veri, tech_cord:tech_cord, cert_dec:cert_dec, remarks:remarks, date:date, submitted_to_md:submitted_to_md, jas_photo_src:jas_photo_src, eas_photo_src:eas_photo_src}, function(data)
      {
        if(data == 1)         
          location.href = "<?php echo base_url('technical/list_certi_issue_checklist'); ?>";
        else
          alert('something went wrong');
      });
    });

  </script>