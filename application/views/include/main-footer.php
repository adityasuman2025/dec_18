<footer class="main-footer"> Page rendered in {elapsed_time} seconds</footer>
</div>  
<style>
.popover{
    max-width: 100%; /* Max Width of the popover (depending on the container!) */
}
.content
{
    height: auto !important;
}
span
{
    font-weight: 600;
}
.inner
{
    height: 80px;
}
.thumbnail {margin-bottom:6px;}

.carousel-control.left,.carousel-control.right{
  background-image:none;
  margin-top:10%;
  width:5%;
}
</style>  
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $this->config->item('bootstrap_js');?>bootstrap.min.js"></script>
<!-- form validator -->
<script src="<?php echo $this->config->item('aset_url');?>js/validator.min.js"></script>
<!-- FastClick 
<script src="<?php //echo $this->config->item('plugins');?>fastclick/fastclick.js"></script>-->
<!-- AdminLTE App -->
<script src="<?php echo $this->config->item('aset_url');?>dist/js/app.min.js"></script>
<script src="<?php echo $this->config->item('aset_url');?>themejs/jquery-ui-1.10.3.min.js"></script>
<!-- Sparkline 
<script src="<?php //echo $this->config->item('plugins');?>sparkline/jquery.sparkline.min.js"></script>-->
<!-- jvectormap -->
<script src="<?php echo $this->config->item('plugins');?>jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo $this->config->item('plugins');?>jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 
<script src="<?php //echo $this->config->item('plugins');?>slimScroll/jquery.slimscroll.min.js"></script>-->
<!-- ChartJS 1.0.1 
<script src="<?php //echo $this->config->item('plugins');?>chartjs/Chart.min.js"></script>-->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $this->config->item('aset_url');?>dist/js/demo.js"></script>
<!-- Select2 -->
<script src="<?php echo $this->config->item('plugins');?>select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="<?php echo $this->config->item('plugins');?>input-mask/jquery.inputmask.js"></script>
<script src="<?php echo $this->config->item('plugins');?>input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo $this->config->item('plugins');?>input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="<?php echo $this->config->item('plugins');?>daterangepicker/moment.min.js"></script>
<script src="<?php echo $this->config->item('plugins');?>daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo $this->config->item('plugins');?>datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap color picker -->
<script src="<?php echo $this->config->item('plugins');?>colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="<?php echo $this->config->item('plugins');?>timepicker/bootstrap-timepicker.min.js"></script>
<script  src="<?php echo $this->config->item('bootstrap_js');?>bootstrap-multiselect.js"/> </script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
<script type="text/javascript">
$(window).load(function() 
{
    $(".se-pre-con").fadeOut("slow");
});
$( document ).ready(function() 
{
    setTimeout(function(){ $('.alert_div').delay(5000).fadeOut("slow"); }, 9000);
    $('a[rel=popover]').popover(
    {
      html: true,
      trigger: 'hover',
      placement: 'left',
      container: 'body',
      content: function(){return '<img src="'+$(this).data('img') + '" />';}
    });
    
    $("#modal-carousel").carousel({interval:false});
    $("#modal-carousel").on("slid.bs.carousel",       function () 
    {
            $(".modal-title")
            .html($(this)
            .find(".active img")
            .attr("title"));
    });
});
    
        var windowURL = window.location.href;
        pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
        var x= $('a[href="'+pageURL+'"]');
            x.addClass('active');
            x.parent().addClass('active');
        var y= $('a[href="'+windowURL+'"]');
            y.addClass('active');
            y.parent().addClass('active');
    jQuery(document).ready(function()
    {
        $('.datepicker').datepicker({ 
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
    });
    $(window).load(function() 
    {
        $(".se-pre-con").fadeOut("slow");
    });
    /*search in pagignation post the data*/
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            //var value = link.substring(link.lastIndexOf('/') + 1);
            //debugger;
            jQuery("#searchList").attr("action", link);
            //jQuery("#searchList").attr("action", baseURL + "college/" + value);
            jQuery("#searchList").submit();
        });
        });
    /**/
    </script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrvcsG2MFQQ6BZcgQcmGsqpj9l7KiZJ0s"></script> 
 <script>
function getLocation() 
{
  if (navigator.geolocation) 
  {
    navigator.geolocation.getCurrentPosition(showPosition);
  } 
  else 
  { 
    alert("Geolocation is not supported by this browser.");
  }
}

function showPosition(position) 
{
  //x.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
  codeLatLng(position.coords.latitude, position.coords.longitude);
}
function codeLatLng(lat, lng)
{
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) 
    {
      if (status == google.maps.GeocoderStatus.OK) 
      {
          if (results[1]) 
          {
         
            //alert(results[0].formatted_address);
            $('.live_location_address').val(results[0].formatted_address);
            } 
            else 
            {
                alert("No location found");
            }
      } 
      else 
      {
        alert("location failed due to: " + status);
      }
    });
  }
</script>  
</body>
</html>