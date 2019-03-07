<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('customer/header');
?>

<div class="container"  style="min-height: 1600px;">
    <section class="content-header">
      <h1> <?=$title?> <small><?=$subtitle?></small></h1>

      <?php 
        if($this->session->userdata('cm_id'))
        {
            if(isset($view))
            {
                $viewname               =   $view; 
                $nopagepermishionStatus =   1;
                if (file_exists(APPPATH."views/customer/{$viewname}.php"))
                {
                    $this->load->view('customer/'.$viewname);  
                }
                else
                {
                    $data['developerhint']      =   '003 - Page (View) Not Found in '.$viewname;
                    $this->load->view('customer/error404', $data);
                }
            }
            else
            {
                //no activity added
                $data['developerhint']      =   '002 - View is not Defined in Controller';
                $this->load->view('customer/error404', $data);
                $nopagepermishionStatus =   1;
            }
        }
        else
        {
            redirect(base_url('customer'));
        }
        ?>
</section>
</div>

