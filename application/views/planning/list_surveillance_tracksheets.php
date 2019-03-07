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
	        <?php echo form_open(base_url('planning/list_surveillance_tracksheets'),['id' => 'searchForm','class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
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
            <!-- <a href="#" class="db_nor_btn" id="f_tab"><span class="filter_ico">Filter</span></a> -->
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
	            <th><br/>Initial Certification Date</th> 
	            <th><br/>Certification Date From</th>
	            <th><br/>Certification Date To</th> 	
	            <th><br/>Cert. Type</th>
	            <th><br/>Notify</th>	
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
							<td>
								<?php 
									$tracksheet_id = $resval->tracksheet_id; 
									echo $tracksheet_id;
								?>							
							</td>
							<td><?php echo $cb_type_name .sprintf("%05d", $resval->client_id); ?></td>
							<td><?php echo $resval->client_name; ?></td>
							<td>
								<?php 
									echo $cb_type_name;
								?>								
							</td>
							<td>
								<?php 
									$initial_certification_date = $resval->initial_certification_date;								
									echo $initial_certification_date;
								?>								
							</td>
							<td><?=$resval->cert_date_from?></td>
							<td><?=$resval->cert_date_to?></td>
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
									$surveillance_notify =  $resval->surveillance_notify;

									if($surveillance_notify == 0)
									{
								?>
									<form method="post" class="form_notify" action="<?php echo base_url('planning/send_notification_page'); ?>">
										<input type="hidden" name="tracksheet_id" value="<?php echo($data_id); ?>">
										<input type="hidden" name="notify_no" value="1">
										<input type="hidden" name="type" value="1"><!-----surveillance--->
										<button class="btn btn-primary">1</a>	
									</form>
									
									<form method="post" class="form_notify" action="<?php echo base_url('planning/send_notification_page'); ?>">
										<input type="hidden" name="tracksheet_id" value="<?php echo($data_id); ?>">
										<input type="hidden" name="notify_no" value="2">
										<button class="btn btn-primary">2</a>	
									</form>
								<?php
									}	
									else if($surveillance_notify == 1)
									{
								?>
									<form method="post" class="form_notify" action="<?php echo base_url('planning/send_notification_page'); ?>">
										<input type="hidden" name="tracksheet_id" value="<?php echo($data_id); ?>">
										<input type="hidden" name="notify_no" value="2">
										<input type="hidden" name="type" value="1"><!-----surveillance--->
										<button class="btn btn-primary">2</a>	
									</form>
								<?php		
									}
									else
									{
										echo "Already Notified";
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

<style type="text/css">
	.form_notify
	{
		display: inline-block;
		margin:0;
		padding: 0;
		margin: 3px;
	}
</style>