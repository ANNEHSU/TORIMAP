<?php 
//TORI Map
//================== Description ============================
//1、使用leaflet；顯示至少兩種以上的basemap
//2、測試從Remote DB撈出資料包裝成GeoJSON，產生Marker on the layer
//3、Marker可以顯示圖檔

//================== connect to database ======================================
     ini_set('date.timezone','Asia/Taipei');
	 include_once("IHMT_DB_remote.php");
//include_once("IHMT_DB.php");
  mysql_query("set names 'utf8'");
//include_once("IHMT_DB_in81.php");//IHMT_DB_remote.php
	//===========================================================================================
$sql1="SELECT * FROM IHMT_WindStations;";//列出最新資料前24筆；一天一筆
$rt_wind_data = mysql_query($sql1);$rt_wind_records=mysql_num_rows($rt_wind_data);
for($j=0;$j<$rt_wind_records;$j++)
{
$SID=mysql_result($rt_wind_data, $j, "Station_ID");

//echo $SID; 

}
?>

<html>
<head>
  <title>A TORI Map Testing!</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  

 <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.1.0/dist/dist/MarkerCluster.css"/> 
 <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.1.0/dist/MarkerCluster.Default.css">
  <script src="https://unpkg.com/leaflet.markercluster@1.1.0/dist/leaflet.markercluster.js"></script> 
  <script src="https://unpkg.com/leaflet.markercluster@1.1.0/dist/leaflet.markercluster-src.js"></script>
  
  <!--Leaflet.PolylineMeasure  -->
		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
		<link rel="stylesheet" href="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.css" />
       	<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>
       
 
<!--<script src="leaflet/leaflet.js"></script> -->
  <style>
    #map{ height: 100% }
  </style>
</head>
<body>
<div id="show_location"></div>
  <div id="map"></div>

  <script>
//============ 設定map 樣式
	
//===============OpenStreetMap 
	var OpenStreetMap =L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors,Data by <a href="http://www.tori.org.tw/">TORI</a>',
      maxZoom: 18,
      minZoom: 8
});
//===============Esri_WorldStreetMap
var Esri_WorldStreetMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
});

//=========== Esri_WorldImagery
var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
});
//========== Esri_OceanBasemap
var Esri_OceanBasemap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Ocean_Basemap/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Sources: GEBCO, NOAA, CHS, OSU, UNH, CSUMB, National Geographic, DeLorme, NAVTEQ, and Esri',
	maxZoom: 18
});
//========== 將所有map放入同一個控制項內
var baseMaps = {
    "OpenStreetMap": OpenStreetMap,
	"Esri_WorldImagery": Esri_WorldImagery,
	"Esri_OceanBasemap": Esri_OceanBasemap,
	"Esri_WorldStreetMap": Esri_WorldStreetMap
};
//============== Setting map basic parameters include(points of center ,initial zoom size,initial layer map)==============
	//var map = L.map('map').setView([22.993013, 120.233937], 10);
	    var map = L.map('map', {
    center: [22.993013, 120.233937],
    zoom: 10,
    layers: [OpenStreetMap] //setting initial map layer
});

//============ Setting Map bounder area =====================
var bounds = [[21, 118], [26, 123]];

map.fitBounds(bounds);

//=========== 新增 control layers =============================
var controlLayers = L.control.layers(baseMaps).addTo(map);

// ======== TORI maker ======================
L.marker([22.632043136488733, 120.28491981538909]).addTo(map) //the point of TORI Location
    .bindPopup('<a href="http://www.tori.org.tw/">TORI</a>.<br> Narlabs. Kaohsiung')
	
// ========= plot maker from remote database and plot them on the map ===========================
 //var controlLayers = L.control.layers().addTo(map);
$.getJSON("PHP_to_geoJSON.php",function(data){
    var buoyIcon = L.icon({
    iconUrl: 'images/map_buoy_yes.png',
    iconSize: [20,30]
  }); 
   var rodents =L.geoJson(data  ,{
    pointToLayer: function(feature,latlng){
	var marker = L.marker(latlng,{icon: buoyIcon});
	marker.bindPopup(feature.properties.CNname +"("+feature.properties.Latitude+','+feature.properties.Longitude+')' + '<br/>' + feature.properties.SID);
  return marker; 
	 // return L.marker(latlng,{icon: buoyIcon});
    }
  }  ).addTo(map);
  controlLayers.addOverlay(rodents, 'Stations');
});



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

// ========= plot maker2 on the map ===========================

 $.getJSON("PHP_to_geoJSON_tide.php",function(data){
	/*  switch (feature.properties.DataType) {
            case 'Republican': return {color: "#ff0000"};
            case 'Democrat':   return {color: "#0000ff"};
        } */
    var torosIcon = L.icon({
    iconUrl: 'images/map_toros_yes.png',
    iconSize: [20,30]
  }); 
  var rodents2 =L.geoJson(data  ,{
    pointToLayer: function(feature,latlng){
	var marker = L.marker(latlng,{icon: torosIcon});
	marker.bindPopup(feature.properties.CNname +"("+feature.properties.Latitude+','+feature.properties.Longitude+')' + '<br/>' + feature.properties.SID);
  return marker; 
	 // return L.marker(latlng,{icon: buoyIcon});
    }
  }  ).addTo(map);
  controlLayers.addOverlay(rodents2, 'Stations_2');
}); 


	L.control.scale(baseMaps).addTo(map);
L.control.polylineMeasure(baseMaps).addTo(map);  	
/* 			var clusters = L.markerClusterGroup(baseMaps).addTo(map);
			controlLayers.addLayer(clusters); 
clusters.addLayer(rodents);
map.addLayer(clusters);  */
  </script>
</body>
</html>