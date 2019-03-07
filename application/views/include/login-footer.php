  
  <script src="<?php echo $this->config->item('plugins');?>jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $this->config->item('plugins');?>daterangepicker/moment.min.js"></script>
<script src="<?php echo $this->config->item('plugins');?>daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo $this->config->item('plugins');?>datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $this->config->item('bootstrap_js');?>bootstrap.min.js"></script>
<!-- FastClick -->

<script>
$(window).load(function() 
{
    $(".se-pre-con").fadeOut("slow");
});
$(document).on('click','.close',function(){
   $('.alert').hide(); 
   $(".alert-main").hide();
});
$(document).ready(function() {
    // tool tip
    $('[data-toggle="tooltip"]').tooltip();         
    // show the alert
    setTimeout(function() 
    {
        //$(".alert").alert('close');
        $(".alert").slideUp(500);
        $(".alert-main").hide();
    }, 2000);
});
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
<!--script src="<?php echo $this->config->item('js');?>app.js"></script--> 
</body>
</html>