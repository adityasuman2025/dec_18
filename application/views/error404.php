<?php
$pieces = explode("-", $developerhint);
?>
<div class="error-page">
        <h2 class="headline text-yellow"> <?=$pieces[0]?></h2>

        <div class="error-content">
          <h3 title=""><i class="fa fa-warning text-yellow"></i> Error : <?=$developerhint?></h3>

          <p>
            you may return to <a href="<?php echo base_url('dashboard/main'); ?>"> Dashboard</a>.
          </p>
          <p>
            
          </p>

        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->