<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>var map;
var initialize;
var geocoder;


function initialize(){
	geocoder=new google.maps.Geocoder();
	var latLng = new google.maps.LatLng(48.87151859999999, 2.3422328000000334); // Correspond au coordonnées de Lille
	var myOptions = {
	zoom      : <?php echo $this->iZoom?>,
	center    : latLng,
	mapTypeId : google.maps.MapTypeId.TERRAIN, // Type de carte, différentes valeurs possible HYBRID, ROADMAP, SATELLITE, TERRAIN
	maxZoom   : 24
	};

	map      = new google.maps.Map(document.getElementById('map'), myOptions);
}

function setPoint(address,sTitle,sLink){

	geocoder.geocode( { 'address': address}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		map.setCenter(results[0].geometry.location);
		var marker = new google.maps.Marker({
			map: map,
			position: results[0].geometry.location,
			title: sTitle,
		});
		google.maps.event.addListener(marker, 'click', function() {
			document.location.href=sLink;
		});
		
		
	  }
	});

}

function setPointWithContent(address,sTitle,sContent){

	var myWindowOptions = {
		content:
			sContent
	};

	var myInfoWindow = new google.maps.InfoWindow(myWindowOptions);

	geocoder.geocode( { 'address': address}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		map.setCenter(results[0].geometry.location);
		
		
		
		var marker = new google.maps.Marker({
			map: map,
			position: results[0].geometry.location,
			title: sTitle,
		});
		google.maps.event.addListener(marker, 'click', function() {
			myInfoWindow.open(map,marker);
		});
		
		
	  }
	});

}
</script>
 <style>
 #map{width:<?php echo $this->iWidth?>px;height:<?php echo $this->iHeight?>px;}
 </style>
<div id="map" >
	<p>Veuillez patienter pendant le chargement de la carte...</p>
</div>

<script>initialize();</script>

<?php if($this->tPosition or $this->tPositionWithContent):?>
<script>
<?php 
if($this->tPosition):
	foreach($this->tPosition as $tAdresse):
		list($sAdresse,$sTitle,$sLink)=$tAdresse;
		?>setPoint('<?php echo $sAdresse?>','<?php echo $sTitle?>','<?php echo $sLink?>');<?php
	endforeach;
endif;

if($this->tPositionWithContent):
	foreach($this->tPositionWithContent as $tAdresse):
		list($sAdresse,$sTitle,$tContent)=$tAdresse;
		$sContent='';
		foreach($tContent as $sLine){
			$sContent.=str_replace("'",'\'',$sLine);
		}
		?>setPointWithContent('<?php echo $sAdresse?>','<?php echo $sTitle?>','<?php echo $sContent?>');<?php
	endforeach;
endif;

?>
</script>
<?php endif;?>
