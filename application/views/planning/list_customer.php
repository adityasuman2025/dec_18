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
	        <?php echo form_open(base_url('planning/list_customer'),['id' => 'searchForm','class' => 'nor_form', 'name' => 'searchForm', 'method' => 'post'])?>
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
            <th><br/>Client ID</th>	
            <th><br/>Client Name </th>	
            <th><br/>Address</th>	
            <th><br/>CB Type</th>		
            <th><br/>Certificate Status</th> 
            <th><br/>Scope</th>
            <th><br/>TrackSheet Status</th> 
            <th><br/>Action</th> 
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
						$data_id	=	$resval->cm_id;	
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
						<td><?php echo $cb_type_name .sprintf("%05d", $resval->client_id); ?></td>
						<td><?=$resval->client_name?></td>
						<td><?=$resval->contact_address?></td>
						<td>
							<?php 
								echo $cb_type_name;
							?>								
						</td>
						<td>
							<?php 
								$certificate_status=$resval->certificate_status;
								if($certificate_status == 1)
									echo "Pending";
								else if($certificate_status == 2)
									echo "Active";
								else if($certificate_status == 3)
									echo "Suspended";
								else if($certificate_status == 4)
									echo "Withdrawn";
								else
									echo "Don't know";
							?>								
						</td>
						<td><?=$resval->scope?></td>
						<td>
							<?php 
								$tracksheet_status =$resval->tracksheet_status;
								if($tracksheet_status == 1)
									echo "Started";
								else if($tracksheet_status == 2)
									echo "Not Started";								
								else
									echo "Don't know";
							?>								
						</td>
						<td><a href="<?php echo base_url('planning/view_customer_info/'.$data_id); ?>">View</a></td>
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
