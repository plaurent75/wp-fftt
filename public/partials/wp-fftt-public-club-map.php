<?php
/**
 * Include Google Maps.
 *
 * @global $nomsallle
 * @global $adresse_salle
 * @global $adressesalle1
 * @global $adressesalle2
 * @global $latitude
 * @global $longitude
 *
 */
$map_adress = $nomsallle.', '.$adressesalle1.', '.$adresse_salle.' France';
wp_enqueue_script('wpfftt_google_map', 'http://maps.googleapis.com/maps/api/js?key='.$this->api_map.'');
?>
<script>
  var geocoder;
  var map;
  window.onload = function WindowLoad(event) {
    <?php if($latitude && $longitude) { ?>
    var myLatlng = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
    map.setCenter(myLatlng);
    <?php }else{ ?>
    geocoder = new google.maps.Geocoder();
    var address ="<?php echo $map_adress ?>";
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == 'OK') {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
        });
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
    <?php } ?>
    var myOptions = {
      zoom: 14,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
     map = new google.maps.Map(document.getElementById("wpfftt_map_canvas"), myOptions);
  }



</script>
<div id="wpfftt_map_canvas" class="<?php echo $this->wpfftt_css_prefix ?>col-12 <?php echo $this->wpfftt_css_prefix ?>col-sm-6"></div>
