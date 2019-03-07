<!doctype html>
<html>
<head>
<?php header('X-Frame-Options: deny'); ?>    
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link rel="icon" href="<?php echo $this->config->item('img_url');?>favicon.ico" type="image"/>
<title>Sign In - CRM </title>

<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('bootstrap_css');?>bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css');?>crm_db.css"/>
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto:100,400,700" rel="stylesheet"/> 
</head>
<body onload="startTime()">
<!--main container starts here-->
<div class="container-fluid db_x_m_p">
    <!--right side starts here-->
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 db_x_m_p" style="height: 100vh;">
    <div class="message_cont">
      <div class="col-lg-4 col-md-4 col-sm-4"> 
      <div class="c_time">
            <h5 id="t_update"></h5>
        </div> 
      </div>   
            
            <!--left side starts here-->
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 db_x_m_p" style="background-color: #ffffff; opacity: 0.75; ">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 login_area">
    <img src="<?php echo $this->config->item('img_url');?>easias_logo.png" style="width: 200px; margin-left: 10%;" />    
        
    <h6 class="hidingclass"><?php echo $this->session->flashdata('success_message'); ?></h6>
    <h6 class="hidingclasserr"><?php echo $this->session->flashdata('error_message'); ?></h6>
   <?php    
    $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) 
        {
            $this->session->unset_userdata($key);
        }
        session_destroy();     
     ?> 
	<div class="db_slide_frm_area">
	<!--sign in form starts here-->
	<?php echo form_open(base_url('customer/verify_login'),['id' => 'signin_form', 'class'=>'db_s_form', 'name' => 'signin_form', 'method' => 'post'])?>
        <div class="f_r_bx">
        <input type="text" id="username" name="username" placeholder="User Name" required="required"/>
        </div>
        <div class="f_r_bx">
        <input type="password" id="password" name="password" placeholder="Password" required="required"/>
        </div>
        <div class="spacer_01">&nbsp;</div>
        <div class="f_r_bx">
            <a href="#" class="nor_nav question_ico" id="frgt_pass">Forgot Password?</a>
        </div>
        <div class="spacer_05">&nbsp;</div>
		<button type="submit" href="#" class="db_cta_btn">Sign In</button>
    <?php echo form_close("\n")?>
    <!--sign in form ends-->
    
    
	
    
    </div>
    
    </div>
    </div><!--left side ends-->
    
            
        </div>
    </div><!--right side ends-->





	
    
    
</div><!--main container ends-->

<script type="text/javascript" src="<?php echo $this->config->item('plugins');?>jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('bootstrap_js');?>bootstrap.min.js"></script>
<script type="text/javascript">
function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('t_update').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

$(document).ready(function(){
	$('#frgt_pass').click(function(){
		$('#signin_form').addClass('a_slideup');
		$('.email_screen').css({"top":"0%","opacity":"1"});
	});
	$('.x_req_pass').click(function(){
		$('#signin_form').removeClass('a_slideup');
		$('.db_hidden_form').css({"top":"100%","opacity":"0"});
	});
});



setTimeout(function(){
    $('.hidingclass').css('display', 'none').html("");
}, 4000);
setTimeout(function(){
    $('.hidingclasserr').css('display', 'none').html("");
}, 4000);
</script>
</body>
</html>