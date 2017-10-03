<?php //index_template.php
// simple version
 //================== connect to database ======================================
      ini_set('date.timezone','Asia/Taipei');
	 include_once("IHMT_DB_remote.php");
//include_once("IHMT_DB.php");
  mysql_query("set names 'utf8'");
?>

<html>
<head>
  <title>A TORI Map Testing!</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
 <link rel="shortcut icon" type="image/x-icon" href="images/tori.ico">


 
 
   <!--<script src="https://unpkg.com/leaflet.markercluster@1.1.0/dist/leaflet.markercluster-src.js"></script> -->

  <!--Leaflet.PolylineMeasure  -->
		
		<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
		<!-- 所有新增css and js都要在leaflet之後，否則很多功能無法顯示如Marker Cluster
		      Note: if you want to link others css or JS, to be remembered those links must be behind leaflet.css
		-->
		<link rel="stylesheet" href="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.css" />
		 <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.1.0/dist/dist/MarkerCluster.css"/> 
		<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.1.0/dist/MarkerCluster.Default.css">  
		<!-- using easy button -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
		 <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		
       	<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
		<script src="https://unpkg.com/leaflet.markercluster@1.1.0/dist/leaflet.markercluster.js"></script> 
        <script src="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>
       <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script data-require="jquery@2.2.0" data-semver="2.2.0" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <!-- user can not download highchart plot figures-->
  <!-- <script data-require="highcharts@4.1.7" data-semver="4.1.7" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/4.1.7/highcharts.js"></script> -->
  <script src="https://use.fontawesome.com/2dd1c6750e.js"></script><!-- my Font Awesome CDN embed code!    -->
  <!-- below comments those highchart link can show a function which user can download figure -->
  <!-- using highchart -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<!-- using easy button -->
<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.js"></script>
<!--<script src="leaflet/leaflet.js"></script> -->
  <style>
    #map{ height: 90% }

    
    button {
        width: 100px;
    }
  </style>
</head>
<body>
<div id="show_location"></div>
<div class="btn-group">
        <button type="button" id="On_image" class="btn btn-success">On image</button>
        <button type="button" id="Off_image" class="btn btn-danger">OFF image</button>
    </div>
  <div id="map"></div>

  <script>


	
	//=====================================
	var OpenStreetMap =L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {//id: 'OpenStreetMap', attribution: mapboxAttribution,
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors,Data by <a href="http://www.tori.org.tw/">TORI</a>',
      maxZoom: 18,
      minZoom: 8
});
//===============Esri_WorldStreetMap
var Esri_WorldStreetMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
	//id: 'Esri_World', attribution: mapboxAttribution,
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
});//Esri_WorldStreetMap.addTo(map);
var baseMaps = {
    "OpenStreetMap": OpenStreetMap,
    "Esri_World": Esri_WorldStreetMap
};
//=============
	//var map = L.map('map').setView([22.993013, 120.233937], 10);
	    var map = L.map('map', {
    center: [22.993013, 120.233937],
    zoom: 10,
    layers: [OpenStreetMap]
});

//OpenStreetMap.addTo(map);
var bounds = [[21, 118], [26, 123]];

map.fitBounds(bounds);
var controlLayers = L.control.layers(baseMaps).addTo(map);
//=========== 新增 control layers =============================

// ======== maker ======================
L.marker([22.632043136488733, 120.28491981538909]).addTo(map)
    .bindPopup('<a href="http://www.tori.org.tw/">TORI</a>.<br> Narlabs. Kaohsiung')



//=============== circle ===============
var circle = L.circle([22.63204313648, 120.2849198153], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 500
}).addTo(map);

circle.bindPopup("TORI nearby circle.");
//=============== Show mouse click location ===============
var popup = L.popup();

function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        .openOn(map);
		document.getElementById("show_location").innerHTML = "Welcome to TORI MAP!"+e.latlng.toString();
}

map.on('click', onMapClick);
document.getElementById("show_location").innerHTML = "Welcome to TORI MAP!";
 // load GeoJSON from an external file


//================ image overlay ====================
var imageUrl = 'images/201612_201702.png',
    imageBounds = [[-90, -180], [90, 180]],
	image_options = { opacity: 0.4 };
	
var world_img=L.imageOverlay(imageUrl, imageBounds,image_options);//.addTo(map);不預先加入display
controlLayers.addOverlay(world_img, 'world_img');
//============= Using button to control it ======================
// using addLayer and removeLayer
 $("#On_image").click(function() {
            map.addLayer(world_img)          
        });
$("#Off_image").click(function() {        
            map.removeLayer(world_img)
        }); 
		//---------------- using easy button   ---------------
 var add_image = L.easyButton({
  states: [{
    stateName: 'add-image',
    icon: 'fa-picture-o fa-2x',
    title: 'Image',
    onClick: function(control) {
      map.addLayer(world_img);
      control.state('remove-image');
    }
  }, {
    icon: 'fa-trash-o fa-2x',
    stateName: 'remove-image',
    onClick: function(control) {
      map.removeLayer(world_img);
      control.state('add-image');
    },
    title: 'remove image'
  }]
});
add_image.addTo(map);
  </script>
</body>
</html>