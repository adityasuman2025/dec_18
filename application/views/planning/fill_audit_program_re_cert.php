<!--dashboard contents starts here-->
<?php
$page_no                            =   $this->uri->segment(3);
$result_array						=	$page_details['results'];
$page_array							=	$page_details['links']; 
$page_limit							=	$page_details['page_limit'];
$selcted                            =   'selected="selected"';
?>

<div class="db_filter">
	<div class="db_filter_mc">
	    <a href="#" class="x_this">X</a>
	    <h1>Filter by</h1>
        <div class="db_filter_cont">
	        <?php echo form_open(base_url('planning/fill_audit_program_re_cert'),['id' => 'searchForm','class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
	         <div class="form_comp">
	            <label>searchText</label>
	            <input type="text" class="form-control" name="searchText" placeholder="Enter page name" id="searchText" value="<?=$searchText?>">
	         </div>
        </div>
	    <input type="submit" value="Filter" class="nor_cta"/>
	    <?php echo form_close("\n")?>
    </div>
</div>
<!--filter ends-->

<div class="container-fluid">
    	<div class="db_common_tb_header">
        	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 x_m-p">
        	<h1>Page <?=($this->uri->segment(3))?($this->uri->segment(3)):1;?></h1>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 x_m-p">
            <a href="#" class="db_nor_btn" id="f_tab"><span class="filter_ico">Filter</span></a>
            <h6 class="db_result_count"><?=ceil($page_details['total_rows']/25)." Pages ".$page_details['total_rows'] ." Records"?></h6>
            </div>
        </div>
        <div class="table-responsive db_common_tb">
	    	<table border="0" cellspacing="0" cellpadding="0" class="table r_tabl">
	        <thead>
	           <tr>
	            <th><br/>Sl No</th>
	            <th><br/>TS ID</th>	
	            <th><br/>Client ID </th>	
	            <th><br/>Client Name </th>	
	            <th><br/>CB Type</th>		
	            <th><br/>Track Month</th> 
	            <th><br/>Track Year</th>
	            <th><br/>Scheme Name</th> 
	            <th><br/>Certification Type</th>
	            <th><br/>Flow Status</th>
	            <th><br/>Date Filled</th>
	            <th><br/>Intimation Status</th>
	          </tr>
	        </thead>
	       	 <tbody>
				<?php
		            if(!$page_no)
		            {
		                $SlNo               =   1;
		            }
		            else
		            {
		                $page_num            =   $page_no-1;
		                $SlNo				=	($page_num*$page_limit)+1;
		            }
					if($result_array)
					{
						foreach($result_array as  $resval)
						{	
							$data_id	=	$resval->tracksheet_id;	
							$query = "SELECT ldt_audit_program_form.dates_filled, ldt_audit_program_form.intimation_status FROM ldt_audit_program_form WHERE ldt_audit_program_form.tracksheet_id = $data_id";
							$query_run = $this->db->query($query);
							$result = $query_run->result_array();
							$results_count = count($result);

							if($results_count == 0)
							{
								$date_filled_status = "NO";
								$intimation_status = "NA";
							}
							else
							{
								$date_filled_result = $result[0]['dates_filled'];
								if($date_filled_result == 1)
									$date_filled_status = "YES";
								else
									$date_filled_status = "NO";

								$intimation_status_result = $result[0]['intimation_status'];
								if($intimation_status_result == 0)
									$intimation_status = "NO";
								else if($intimation_status_result == 1)
									$intimation_status = "Sent1";
								else if($intimation_status_result == 2)
									$intimation_status = "Sent2";
								else
									$intimation_status = "NA";
							}

				?>
						<?php 
							$cb_type=$resval->cb_type;
							$cb_type_name = 0;
							if($cb_type == 1)
								$cb_type_name = "EAS";
							else
								$cb_type_name = "IAS";
						?>	
						<tr>
							<td><?=$SlNo?></td>
							<td><?php echo $resval->tracksheet_id; ?></td>
							<td><?php echo $cb_type_name .sprintf("%05d", $resval->client_id); ?></td>
							<td><?php echo $resval->client_name; ?></td>
							<td>
								<?php 
									echo $cb_type_name;
								?>								
							</td>
							<td>
								<?php 
									$track_month = $resval->track_month;								
									echo $track_month;
								?>								
							</td>
							<td><?=$resval->track_year?></td>
							<td>
								<?php 
									echo $resval->scheme_name;								
								?>								
							</td>
							<td>
								<?php 
									$cert_type =  $resval->certification_type;	

									$cert_type_text = 0;
									if($cert_type == 1)
										$cert_type_text = "Cert";
									else if($cert_type == 2)
										$cert_type_text = "S1";
									else if($cert_type == 3)
										$cert_type_text = "S2";
									else if($cert_type == 4)
										$cert_type_text = "RC";

									echo $cert_type_text;
								?>								
							</td>		
							<td>
								<?php 
									echo $resval->tsf_name;								
								?>								
							</td>			
							<td>			
								<?php				
									if($date_filled_status == "NO")
									{
										echo $date_filled_status;
										echo " <a class=\"btn btn-primary\" href=\"" . base_url('planning/planning_audit_program_form_re_cert/'. $data_id) . "\">Fill</a>";
									}	
									else
									{
										echo $date_filled_status;
										echo " <a class=\"btn btn-primary\" href=\"" . base_url('planning/view_planning_audit_program_form_re_cert/'. $data_id) . "\">View</a>";
									}																		
								?>
							</td>
							<td>																				
								<?php				
									if($intimation_status == "NA")
									{
										echo $intimation_status;										
									}	
									else if($intimation_status == "NO")
									{
										$redirect_stage = 2;

										echo $intimation_status;
										echo " <a class=\"btn btn-primary\" href=\"" . base_url('planning/send_intim/2/'. $data_id . '/' . $redirect_stage) . "\">Send2</a>";
									}	
									else if($intimation_status = "Sent2")
									{
										echo "Sent For Stage 2";
									}																	
								?>
							</td>
						</tr>
				<?php	
							$SlNo							=	$SlNo+1;			
						}
					}			
				?>
	          </tbody>
			</table>
		</div>
    <div class="box-footer">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="pagination  col-md-offset-5">
                <?php echo $page_array; ?>
            </ul>
        </div>    
    </div>
</div><!--dashboard contents ends-->