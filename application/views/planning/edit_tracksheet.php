<?php
    if($out_of_index == 1)
        die("Page Not Found");    
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
                          $cm_id=$database_record->cm_id;

                          $cb_type=$database_record->cb_type;
                          $cb_type_name = 0;
                          if($cb_type == 1)
                            $cb_type_name = "EAS";
                          else
                            $cb_type_name = "IAS";
                        ?>  
                        <li><a href="#">ID<span class="pull-right  "><?php echo $cb_type_name .sprintf("%05d", $database_record->client_id); ?></span></a></li>
                        <li><a href="#">Name <span class="pull-right  "><?php echo $client_name = $database_record->client_name; ?></span></a></li>
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
                        <li><a href="#">Contact Address<span class="pull-right  "><?php echo $contact_address = $database_record->contact_address; ?></span></a></li>
                         <li>
                          <a href="#">
                            Location 
                            <span class="pull-right">
                              <?php 
                      $cb_type=$database_record->location;
                      if($cb_type == 1)
                        echo "Local";
                      else
                        echo "Foreign";
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
                      <h3 class="box-title">Tracksheet Details</h3>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                      </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-6 col-sl-12 col-xs-12">
                    	<?php
                        $tracksheet_id = $database_record->tracksheet_id; ;

                     		$cert_type =  $database_record->certification_type;	

                        if($cert_type == 2)
                          $level = 3;
                        else if($cert_type == 3)
                          $level = 4;
                        else if($cert_type == 4)
                          $level = 5;
                        else
                          $level = 0;

          							$cert_type_text = 0;
          							if($cert_type == 1)
          								$cert_type_text = "Cert";
          							else if($cert_type == 2)
          								$cert_type_text = "S1";
          							else if($cert_type == 3)
          								$cert_type_text = "S2";
          							else if($cert_type == 4)
          								$cert_type_text = "RC";
                     	?>
                        <ul class="nav nav-stacked">
                           	<li><a href="#">ID <span class="pull-right  "><?php echo $database_record->tracksheet_id; ?></span></a></li>
                           	<li><a href="#">Scheme <span class="pull-right  "><?php echo $database_record->scheme_name; ?></span></a></li>
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

		            <div class="col-md-12 col-sl-12 col-xs-12">                     	
		                <ul class="nav nav-stacked">
		                  	<li><a href="#">Scope:  <span class=""><?php echo $database_record->scope; ?></span></a></li>
		              	</ul>
		            </div>
                </div>     
            </div>
        </div>
	</section>

<!-----edit tracksheet area-------->
  <section class="content">
    <div class="col-md-12">             
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Tracksheet</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-12 col-sl-12 col-xs-12">
              <?php                
              //function to get the options for tracksheet status
                function to_get_status_options($ts_status)
                {
                  if($ts_status == 1)
                  {
                    echo "<option value=\"1\">Running</option>";
                    echo "<option value=\"2\">Pending</option>";
                    echo "<option value=\"3\">Withdrawn</option>";
                    echo "<option value=\"4\">Suspended</option>";
                  }
                  else if($ts_status == 2)
                  {
                    echo "<option value=\"2\">Pending</option>";                    
                    echo "<option value=\"1\">Running</option>";
                    echo "<option value=\"3\">Withdrawn</option>";
                    echo "<option value=\"4\">Suspended</option>";
                  }
                  else if($ts_status == 3)
                  { 
                    echo "<option value=\"3\">Withdrawn</option>";                  
                    echo "<option value=\"1\">Running</option>";
                    echo "<option value=\"2\">Pending</option>";
                    echo "<option value=\"4\">Suspended</option>";
                  }
                  else if($ts_status == 4)
                  {
                    echo "<option value=\"4\">Suspended</option>";
                    echo "<option value=\"1\">Running</option>";
                    echo "<option value=\"2\">Pending</option>";
                    echo "<option value=\"3\">Withdrawn</option>";                    
                  }
                  else
                  {
                    echo "<option value=\"1\">Running</option>";
                    echo "<option value=\"2\">Pending</option>";
                    echo "<option value=\"3\">Withdrawn</option>";
                    echo "<option value=\"4\">Suspended</option>";
                  }

                }

                $tracksheet_status = $database_record->status;

                $get_changes_records_count = count($get_changes_records); 

              //if intimation of change form has already been by client                               
                $new_scope = "";
                $initmation_of_changes_id = 0;

                if($get_changes_records_count == 1)
                {                  
                  $get_changes_record = $get_changes_records[0];

                  $initmation_of_changes_id = $get_changes_record['id'];
                  $no_of_emp = $get_changes_record['no_of_emp'];
                  $perma_emp = $get_changes_record['perma_emp'];
                  $temp_sites = $get_changes_record['temp_sites'];
                  $new_scope = $get_changes_record['new_scope'];

                  $notify_technical_of_scope_change = $get_changes_record['notify_technical_of_scope_change'];
                  $technical_accept_scope_change = $get_changes_record['technical_accept_scope_change'];

              ?>
                  <a href="<?php echo base_url('planning/view_initmation_of_changes/' . $tracksheet_id); ?>" class="btn btn-primary" target="_blank">View Initation Of Changes</a>
                  <br>

                <!------client details ans scope editting area------->  
                  <h3><b>Client Details</b></h3>
                  <ul class="nav nav-stacked">
                    <li><a>Name <input type="text" class="pull-right a_input client_name" value="<?php echo $client_name; ?>"></a></li>
                    <li><a>Address <input type="text" class=" pull-right a_input contact_address" value="<?php echo $contact_address; ?>"></a></li>

                    <h3><b>Scope</b></h3>
                    <li><a>Scope On Last Certificate<span class="pull-right"><?php echo $old_scope[0]['scope']; ?></span></a></li>                                         
                    <br><br>
                    <button class="btn btn-success pull-right save_client_details_btn" cm_id="<?php echo $cm_id; ?>">Save</button>
                  </ul>

                <!-----Application Review Details------->  
                  <h3><b>Application Review Details</b></h3>
                  <ul class="nav nav-stacked">
                    <li><a>Number of Employees <span class="pull-right"><?php echo $no_of_emp; ?></span></a></li>
                    <li><a>No of Permanent Sites <span class="pull-right"><?php echo $perma_emp; ?></span></a></li>
                    <li><a>No of Temporary Sites <span class="pull-right"><?php echo $temp_sites; ?></span></a></li>   
                  </ul>

                <!-----old site editing area----->
                  <ul class="nav nav-stacked">
                   <?php 
                    foreach ($get_site_records as $key => $get_site_record)
                    {
                      $site_id = $get_site_record['site_id'];
                      $site_address = $get_site_record['site_address'];
                  ?>
                      <li>
                        <a>Site 
                          <input type="text" class="a_input site_address" disabled="disabled" value="<?php echo $site_address; ?>">

                          <button class="btn btn-danger site_delt_btn" site_id="<?php echo $site_id; ?>">x</button>
                          <button class="btn btn-primary site_edit_btn" site_id="<?php echo $site_id; ?>">Edit</button>
                          <button class="btn btn-success site_save_btn" style="display: none;" site_id="<?php echo $site_id; ?>">Save</button>
                        </a>
                      </li>  
                  <?php                        
                    }
                    ?>
                  </ul>

                <!-----new site adding area -----> 
                  <ul class="nav nav-stacked new_site_ul">
                    <li style="display: none;">
                        <a>Site 
                          <input type="text" class="a_input new_site_address" value="">

                          <button class="btn btn-success new_site_save_btn">Save</button>
                        </a>
                      </li>  
                  </ul>

                  <button class="btn btn-primary add_site_btn">Add Site</button>              
              <?php    
                }
                else
                {
                  echo "No initmation of changes is proposed by client.";
              ?>
                  <br>
                  <a href="<?php echo base_url('planning/fill_initmation_of_changes/' . $tracksheet_id . '/' . $level); ?>" class="btn btn-primary">Fill Initation Of Changes</a>
                  <br>
              <?php    
                  
                }
              ?>
              
            <!-----approval of scope change from technical employee---->
              <?php 
                if($new_scope != "") //i.e a new scope is propsed by the client
                {
              ?>
                  <h3><b>Approval of Scope Change from Technical Employee</b></h3>
                <?php
                  if($notify_technical_of_scope_change ==1)
                  {
                    echo "<b>Technical Employee has been notified of scope change.</b>";

                    if($technical_accept_scope_change == 1) //technical employee accept request of scope change
                    {
                    //checking if any withdraw request is done to MD  
                      $withdrawn_md_approval_req_count = count($withdrawn_md_approval_req);

                      if($withdrawn_md_approval_req_count == 0) //no md apporval request is done
                      {                        
                 ?>   
                      <br><span style="color: green;">Technical Employee has accepted the scope change request.</span>                     
                    <!------tracksheet status------>                 
                      <h3><b>Tracksheet Status</b></h3>
                      <div>
                        <select class="a_input ts_status">
                        <?php
                          to_get_status_options($tracksheet_status);
                        ?>
                        </select>
                      
                        <div class="status_remarks_area">
                          <br>
                          Remarks
                          <textarea class="status_remarks" style="width: 100%; "></textarea>
                          <br>
                        </div>                     

                        <button class="btn btn-success save_status_btn">Save</button>
                      </div>                    
                 <?php                      
                      }
                      else //md apporval request is done
                      {
                        $withdrawn_md_approval_req = $withdrawn_md_approval_req[0];

                        $md_approved = $withdrawn_md_approval_req['md_approved'];

                        if($md_approved == 1)
                        {
                          echo "<br>MD has approved your request to withdraw this tracksheet.";
                        }
                        else
                        {
                          echo "<br>Your request to withdraw this tracksheet is pending for MD approval.";
                        }
                      }
                    }
                    else if($technical_accept_scope_change == 2) //technical employee rejected request of scope change
                    {
                    //checking if any withdraw request is done to MD  
                      $withdrawn_md_approval_req_count = count($withdrawn_md_approval_req);

                      if($withdrawn_md_approval_req_count == 0) //no md apporval request is done
                      {                      
                 ?>   
                      <br><span style="color: red;">Technical Employee has rejected the scope change request.</span>                     
                    <!------tracksheet status------>                 
                      <h3><b>Tracksheet Status</b></h3>
                      <div>
                        <select class="a_input ts_status">
                        <?php
                          to_get_status_options($tracksheet_status);
                        ?>
                        </select>
                      
                        <div class="status_remarks_area">
                          <br>
                          Remarks
                          <textarea class="status_remarks" style="width: 100%; "></textarea>
                          <br>
                        </div>                     

                        <button class="btn btn-success save_status_btn">Save</button>
                      </div>                    
                 <?php                      
                      }
                      else //md apporval request is done
                      {
                        $withdrawn_md_approval_req = $withdrawn_md_approval_req[0];

                        $md_approved = $withdrawn_md_approval_req['md_approved'];

                        if($md_approved == 1)
                        {
                          echo "<br>MD has approved your request to withdraw this tracksheet.";
                        }
                        else
                        {
                          echo "<br>Your request to withdraw this tracksheet is pending for MD approval.";
                        }
                      }
                    }
                    else 
                    {
                      echo "<br>Approval from Technical Employee of scope change is pending";
                    }
                  }
                  else
                  {
                ?>
                    <button class="btn btn-primary notify_tech">Notify Technical Employee of Scope Change</button>
                <?php
                  }
                }
                else
                {
                  echo "<br><b>No any change in scope is proposed by client.</b>";

                //checking if any withdraw request is done to MD  
                    $withdrawn_md_approval_req_count = count($withdrawn_md_approval_req);

                    if($withdrawn_md_approval_req_count == 0) //no md apporval request is done
                    {                      
               ?>   
                  <!------tracksheet status------>                 
                    <h3><b>Tracksheet Status</b></h3>
                    <div>
                      <select class="a_input ts_status">
                      <?php
                        to_get_status_options($tracksheet_status);
                      ?>
                      </select>
                    
                      <div class="status_remarks_area">
                        <br>
                        Remarks
                        <textarea class="status_remarks" style="width: 100%; "></textarea>
                        <br>
                      </div>                     

                      <button class="btn btn-success save_status_btn">Save</button>
                    </div>                    
               <?php                      
                    }
                    else //md apporval request is done
                    {
                      $withdrawn_md_approval_req = $withdrawn_md_approval_req[0];

                      $md_approved = $withdrawn_md_approval_req['md_approved'];

                      if($md_approved == 1)
                      {
                        echo "<br>MD has approved your request to withdraw this tracksheet.";
                      }
                      else
                      {
                        echo "<br>Your request to withdraw this tracksheet is pending for MD approval.";
                      }
                    }
                }
              ?> 
            </div>  
          </div>
        </div>
    </div>
  </section>

<!-------style and script------->
  <style type="text/css">
    .a_input
    {
      width: 50%;
      height: 34px;
      vertical-align: middle;
    }

    .status_remarks_area
    {
      display: none;
    }
  </style>

  <script type="text/javascript">
  //on clicking on save client details button  
    $('.save_client_details_btn').click(function()
    {
      var cm_id = $(this).attr('cm_id');
      var tracksheet_id = "<?php echo $tracksheet_id; ?>";

      var client_name = $(this).parent().find('.client_name').val();
      var contact_address = $(this).parent().find('.contact_address').val();

      var scope = "";

      $.post('<?php echo base_url('planning_actions/edit_client_details'); ?>', {cm_id: cm_id, client_name: client_name, contact_address: contact_address, scope: scope}, function(data)
      {
        if(data == 1)
        {
          location.reload();
        }
        else
          alert('Something went wrong while editting customer details.');
      });

    });

  //on clicking on update scope btn
    $('.update_scope_btn').click(function()
    {
      var cm_id = $(this).attr('cm_id');
      var tracksheet_id = "<?php echo $tracksheet_id; ?>";

      var scope = $('.new_scope').val();

      $.post('<?php echo base_url('planning_actions/edit_tracksheet_scope'); ?>', {tracksheet_id: tracksheet_id, scope: scope}, function(e)
      {
        if(e == 1)
        {
          location.reload();
        }
        else
          alert('Something went wrong while editting scope.');
      });
    });

  //on clicking on delete site button
    $('.site_delt_btn').click(function()
    {
      var site_id = $(this).attr('site_id');

      $.post('<?php echo base_url('planning_actions/delete_client_site'); ?>', {site_id: site_id}, function(data)
      {
        if(data == 1)
          location.reload();
        else
          alert('Something went wrong while deletig the client site');
      });
    });

  //on clicking on edit site button
    $('.site_edit_btn').click(function()
    {
      var site_id = $(this).attr('site_id');

      $(this).parent().find('.site_address').attr('disabled', false);

      $(this).hide();
      $(this).parent().find('.site_save_btn').show();

    //on clicking on save site button
      $('.site_save_btn').unbind().click(function()
      {
        var site_address =  $(this).parent().find('.site_address').val();
       
        $.post('<?php echo base_url('planning_actions/edit_client_site'); ?>', {site_id: site_id, site_address: site_address}, function(data)
        {
          if(data == 1)
            location.reload();
          else
            alert('Something went wrong while deletig the client site');
        });
      });
    });

  //on clicking on add site button
    $('.add_site_btn').click(function()
    {
      var html = $('.new_site_ul li:first').html();

      $('.new_site_ul').append("<li>" + html + "</li>");

    //on clicking on save new site button
      $('.new_site_save_btn').unbind().click(function()
      {
        var site_address =  $(this).parent().find('.new_site_address').val();
        var cm_id = "<?php echo $cm_id; ?>";

        $.post('<?php echo base_url('planning_actions/add_client_site'); ?>', {cm_id: cm_id, site_address: site_address}, function(data)
        {
          if(data == 1)
            location.reload();
          else
            alert('Something went wrong while adding new client site');
        });
      });
    });
  
  //on clicking on save status button
    $('.save_status_btn').click(function()
    {
      $(this).hide();
      
      var tracksheet_id = "<?php echo $tracksheet_id; ?>";
      var status = $(this).parent().find('.ts_status').val();

      var cert_type = "<?php echo $cert_type; ?>";

      if(status == 3) //if changed to withdrawn
      {
        var remarks = $(this).parent().find('.status_remarks').val();

      //inserting that tracksheet into ldt_tracksheet_status table for MD approval
        $.post('<?php echo base_url('planning_actions/insert_tracksheet_for_md_approval_for_status'); ?>', {tracksheet_id: tracksheet_id, status: status, remarks:remarks}, function(data)
        {        
          if(data == 1)
          {
            if(cert_type == 2 || cert_type == 3)
              location.href = "<?php echo base_url('planning/list_s_tracksheet'); ?>";
            else if(cert_type == 4)
              location.href = "<?php echo base_url('planning/list_re_tracksheet'); ?>";
          }
          else
            alert('Something went wrong while adding new client site');
        });
      }
      else
      {
      //updating tracksheet status of that tracksheet in database  
        $.post('<?php echo base_url('planning_actions/update_tracksheet_status'); ?>', {tracksheet_id: tracksheet_id, status: status}, function(data)
        {
          if(data == 1)
          {
            if(cert_type == 2 || cert_type == 3)
              location.href = "<?php echo base_url('planning/list_s_tracksheet'); ?>";
            else if(cert_type == 4)
              location.href = "<?php echo base_url('planning/list_re_tracksheet'); ?>";
          }
          else
            alert('Something went wrong while adding new client site');
        });
      }
  
    });
  
  //on clicking on notify technical button
    $('.notify_tech').click(function()
    {
      $(this).hide();

      var id = "<?php echo $initmation_of_changes_id; ?>";

      $.post('<?php echo base_url('planning_actions/notify_technical_of_scope_change'); ?>', {id: id}, function(a)
      {        
        if(a == 1)
          location.reload();
        else
          alert('Something went wrong while notifying Technical Employee of scope change');
      });
    });

  //on changing status of tracksheet to withdrawn
    $('.ts_status').change(function()
    {
      var status = $(this).val();

      if(status == 3) //if changed to withdrawn
      {
        $(this).parent().find('.status_remarks_area').show();
      }
      else
      {
        $(this).parent().find('.status_remarks_area').hide();
      }
    });
  </script>