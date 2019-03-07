<?php
    if($out_of_index == 1)
        die("Page Not Found");    
?>
<div class="col-md-6">
    <section class="content">
        <!-- left column -->
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
            <div class="col-md-12 col-sl-12 col-xs-12">
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

                    <li><a href="#">Consultant ID <span class="pull-right  "><?php echo $database_record->consultant_name; ?></span></a></li>
                    <li><a href="#">Scope<span class="pull-right  "><?php echo $database_record->scope; ?></span></a></li>
                
                  	<li><a href="#">Note <span class="pull-right  "><?php echo $database_record->note; ?></span></a></li>
                    <li><a href="#">Remarks<span class="pull-right  "><?php echo $database_record->remarks; ?></span></a></li>
              </ul>
            </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
                  
                </div>
            </div>
	</section>

	<section class="content">
        <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->                
                <div class="box box-primary">
                    <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="col-md-12 col-sl-12 col-xs-12">
                <ul class="nav nav-stacked">
                    <li><a href="#">Contact Name<span class="pull-right  "><?php echo $database_record->contact_name; ?></span></a></li>
                    <li><a href="#">Contact Address <span class="pull-right  "><?php echo $database_record->contact_address; ?></span></a></li>                 
                    <li><a href="#">Contact Number <span class="pull-right  "><?php echo $database_record->contact_number; ?></span></a></li>
                    <li><a href="#">Contact Email<span class="pull-right  "><?php echo $database_record->contact_email; ?></span></a></li>
              </ul>
            </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
                  
                </div>
            </div>
	</section>

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
                    <div class="col-md-12 col-sl-12 col-xs-12">
                        <ul class="nav nav-stacked">
                           <li>
                           		<a href="#">TrackSheet Status<span class="pull-right  ">
        							<?php 
        								$tracksheet_status =$database_record->tracksheet_status;
        								if($tracksheet_status == 1)
        									echo "Started";
        								else if($tracksheet_status == 2)
        									echo "Not Started";								
        								else
        									echo "Don't know";
        							?>	
        							</span>
        						</a>							
        					</li>

        					<?php //if tracksheet has already been started
        						if($tracksheet_status == 1)
        						{
        					?>
                                <?php echo form_open(base_url('planning/list_tracksheet'),['class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
                                    <input value="<?php echo $database_record->cm_id; ?>" type="hidden" name="special_cm_id"/>
                                    <input id="view_tracksheet_button" type="submit" value="View Tracksheet" class="btn btn-primary" />
                                <?php echo form_close("\n")?>
                                <br><br>
        					<?php		
        						}
        						else if($tracksheet_status == 2) //if tracksheet has not been started
        						{
        					?>
        					    <?php echo form_open(base_url('planning/create_tracksheet_page'),['class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
        					    	<input style="display: none" value="<?php echo $database_record->cm_id; ?>" type="text" name="data_id"/>
        					    	<input style="display: none" value="1" type="text" name="cert_type"/>
        				        	<input type="submit" class="btn btn-primary" value="Create Tracksheet"/>
        					    <?php echo form_close("\n")?>
        					<?php						
        						}								
        					?>
                        </ul>
                    </div>
                </div>               
            </div>
        </div>
	</section>
</div>
<div class="col-md-6">
            <?php 
                if($application_form)
                {
                    ?>
                    
                        <div class="col-lg-6 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-yellow" style="position: relative;">
                          
                            <div class="inner">
                            
                              <p>Application</p>
                              <h3><a href="<?php echo base_url('database/view_add_application/'.$database_record->data_id);?>" ><i class="fa fa-pencil"></i></a></h3>
                            </div>
                            <div class="icon">
                              <i class="fa fa-file-text-o"></i>
                            </div>
                            <a class="small-box-footer" target="_blank" href="<?php echo base_url('database/view_filled_application/'.$application_form->form_id); ?>" >
                             Application View <i class="fa fa-arrow-circle-right"></i>
                            </a>
                          </div>
                        </div>
                        <?php
                }
                if($quotation_details)
                {
                    foreach($quotation_details          as $qtnDtl)
                    {
                        $aq_id              =   $qtnDtl->aq_id;
                        $approve_status     =   $qtnDtl->approve_status;
                        $quotation_file_name=   $qtnDtl->quotation_file_name;
                        if($approve_status  == 1)
                        {
                            $title          =   "Pending";
                            $bg             =   "bg-info";
                        }
                        elseif($approve_status  == 2)
                        {
                            $title          =   "Submited For Approval";
                            $bg             =   "bg-success";
                        }
                        elseif($approve_status  == 3)
                        {
                            $title          =   "Approved";
                            $bg             =   "btn-success";
                        }
                        elseif($approve_status  == 4)
                        {
                            $title          =   "Rejected";
                            $bg             =   "btn-danger";
                        }
                        elseif($approve_status  == 5)
                        {
                            $title          =   "Contract Created";
                            $bg             =   "btn-success";
                            if($quotation_file_name)
                            {
                                ?>
                        <div class="col-lg-6 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-aqua">
                            <div class="inner">
                              <p><?php echo $schemes_list[$qtnDtl->scheme]; ?></p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-file-pdf-o"></i>
                            </div>
                            <a class="small-box-footer" target="_blank" href="<?php echo $this->config->item('uploaded_applications_url').$quotation_file_name; ?>" >
                              Contract View <i class="fa fa-arrow-circle-right"></i>
                            </a>
                          </div>
                        </div>
                                <?php
                            }
                        }
                        /*
                    ?>
                        <div class="col-lg-6 col-xs-6">
                          <!-- small box -->
                          <div class="small-box <?php echo $bg; ?>">
                            <div class="inner">
                              <p><?php echo $schemes_list[$qtnDtl->scheme]; ?></p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-stats-bars"></i>
                            </div>
                            <a class="small-box-footer" title="<?php echo $title; ?>" href="<?php echo base_url('database/view_quotations/'.$qtnDtl->aq_id); ?>">
                              Quotation View <i class="fa fa-arrow-circle-right"></i>
                            </a>
                          </div>
                        </div>
                        <?php
                        */
                        }
                }
                if($document_details)
                {
                    ?>
                    
                    <div class="col-lg-6 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-yellow">
                            <div class="inner">
                            <h3><?php echo count($document_details); ?></h3>
                              <p>Uploads</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-files-o"></i>
                            </div>
                            <a class="small-box-footer" title="Uploads" data-toggle="modal" data-target="#uploadimg" href="#">
                              View Files <i class="fa fa-arrow-circle-right"></i>
                            </a>
                          </div>
                        </div>
                    
                    
                    
                        <div id="uploadimg" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                        
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Uploaded Files</h4>
                              </div>
                              <div class="modal-body">
                               <div id="modal-carousel" class="carousel">
   
                    <div class="carousel-inner">  
                    <?php 
                         $active                         =   'active';
                    foreach($document_details          as $dodDtl)
                    {
                        $doc_name                       =   $dodDtl->doc_name;
                        $doc_url                        =   $dodDtl->doc_url;
                       
                      ?>
                        <div class="item <?php echo $active;?>" id="image-1">
                        <img class="thumbnail img-responsive" title="<?php echo $doc_name;?>" src="<?php echo $this->config->item('uploaded_img_display_url').'Document/signed_copy/'.$doc_url; ?>"/>
                        </div>    
                        <?php 
                         $active                         =   '';
                        }
                        
                        ?>
                           
                    </div>
            
            <a class="carousel-control left" href="#modal-carousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <a class="carousel-control right" href="#modal-carousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
            
          </div>
                              </div>
                            </div>
                        
                          </div>
                        </div>
                    <?php 
                }
                ?>
                
                    
</div>