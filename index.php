<?php 
//TORI Map
//tori_map_template.php
//================== Description ============================
//1、使用leaflet；顯示至少兩種以上的basemap
//2、測試從Remote DB撈出資料包裝成GeoJSON，產生Marker on the layer
//3、Marker可以顯示圖檔
// Reference: http://fontawesome.io/icons/ 
//================== connect to database ======================================
     ini_set('date.timezone','Asia/Taipei');
	 include_once("IHMT_DB_remote.php");
	 mysql_query("set names 'utf8'");
  //include_once("IHMT_DB.php");
//include_once("IHMT_DB_in81.php");//IHMT_DB_remote.php
/*======================================================================
Leaflet's default projection is EPSG:3857,Leaflet's default projection is EPSG:3857, 
also known as "Google Mercator" or "Web Mercator" and sometimes designated with the number "900913". 
This projection is what most slippy tile based maps use, including the common tile sets from Google, Bing, OpenStreetMap, and others.
 You can easily use this projection in QGIS by selecting "Google Mercator EPSG:900913".
=======================================================================*/
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
<div id="show_location"></div> <!-- display map point location (latitude,longitude)  -->
<!-- display button and button event to control image_on or image_off  -->
<div class="btn-group">
        <button type="button" id="On_image" class="btn btn-success">On image</button>
        <button type="button" id="Off_image" class="btn btn-danger">OFF image</button>
    </div>
<!-- show map-->
  <div id="map"></div>

  <script>
//============ 設定map 樣式
	
//===============OpenStreetMap 
	var OpenStreetMap =L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors,Data by <a href="http://www.tori.org.tw/">TORI</a>',
      maxZoom: 18
	  //,minZoom: 8
      
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
    "Esri_OceanBasemap": Esri_OceanBasemap,
	"Esri_WorldImagery": Esri_WorldImagery,
	"Esri_WorldStreetMap": Esri_WorldStreetMap,
	"OpenStreetMap": OpenStreetMap
};
//============== Setting map basic parameters include(points of center ,initial zoom size,initial layer map)==============
	//var map = L.map('map').setView([22.993013, 120.233937], 10);
	    var map = L.map('map', {
    center: [22.993013, 120.233937],
    zoom: 10,
    layers: [Esri_WorldImagery] //setting initial map layer
});

//============ Setting Map bounder area =====================
var bounds = [[21, 118], [26, 123]]; //[latitude,longitude]

map.fitBounds(bounds);

//=========== 新增 control layers =============================
var controlLayers = L.control.layers(baseMaps).addTo(map);

// ======== TORI marker ======================
var myIcon = L.icon({
    iconUrl: 'images/tori.png',
    iconSize: [32, 32]

});
/* L.marker([22.632043136488733, 120.28491981538909], {icon: myIcon}).addTo(map) //the point of TORI Location
    .bindPopup('<a href="http://www.tori.org.tw/">TORI</a>.<br> Narlabs. Kaohsiung') */

var tori_description='<div class="padding20"><ul class="maplist"><li><img src="images/pos_01.jpg" alt="高雄總部"></li><li><strong>高雄總部</strong></li><li><span class="colorG">地　　址</span><br> 801 高雄市前金區河南二路196號</li><li><span class="colorG">聯絡資訊</span><br> 電話：07- 2618688<br> 傳真：07- 2618703</li></ul></div>';
var tori_marker=L.marker([22.63259, 120.2882], {icon: myIcon});//.addTo(map) //the point of TORI Location
var	tori_popup = L.popup({minWidth : 300,maxHeight : 320}).setContent(tori_description+'<a href="http://www.tori.org.tw/">TORI</a>.<br> Narlabs. Kaohsiung');    
	tori_marker.bindPopup(tori_popup);
	controlLayers.addOverlay(tori_marker, 'tori_marker');
//---------------- using easy button   ---------------
 var add_tori_location = L.easyButton({
  states: [{
    stateName: 'about',
    icon: 'fa-building-o fa-2x',
    title: 'Show TORI',
    onClick: function(control) {
      map.addLayer(tori_marker);
      control.state('remove');
    }
  }, {
    icon: 'fa-building fa-2x',
    stateName: 'remove',
    onClick: function(control) {
      map.removeLayer(tori_marker);
      control.state('about');
    },
    title: 'Hide TORI'
  }]
});
add_tori_location.addTo(map);
 //-----------	 end add TORI icon

// ========= plot maker from remote database IHMT and plot them on the map ===========================

//var controlLayers = L.control.layers().addTo(map);
$.getJSON("PHP_to_geoJSON.php",function(data){
    /* var buoyIcon = L.icon({
    iconUrl: 'images/map_buoy_yes.png',
    iconSize: [20,30]
  });  */
   var IHMTIcon = L.AwesomeMarkers.icon({
        prefix: 'fa', //font awesome rather than bootstrap
        markerColor: 'red', // see colors above
        icon: 'anchor' //http://fortawesome.github.io/Font-Awesome/icons/
    });
   var rodents =L.geoJson(data  ,{
    pointToLayer: function(feature,latlng){
	var marker = L.marker(latlng,{icon: IHMTIcon});//buoyIcon
	marker.bindPopup(feature.properties.CNname +"("+feature.properties.Latitude+','+feature.properties.Longitude+')' + '<br/>' + feature.properties.SID);
  return marker; 
	 // return L.marker(latlng,{icon: buoyIcon});
    }
  }  );//.addTo(map);不事先加入 initial display
 var clusters_IHMT = L.markerClusterGroup();
clusters_IHMT.addLayer(rodents);
//map.addLayer(clusters_IHMT); //此選項可以決定是否initial就show出 layer
 controlLayers.addOverlay(clusters_IHMT, 'IHMT_Stations');
  //controlLayers.addOverlay(rodents, 'IHMT_Stations');
  //---------------- // toggle-on toggle-off
 var toggle1 = L.easyButton({
  states: [{
    stateName: 'add-markers',
    icon: 'fa-toggle-on fa-2x',
    title: 'IHMT Station',
    onClick: function(control) {
	map.setView([22.993013, 120.233937],8);
      //map.addLayer(rodents);
	  map.addLayer(clusters_IHMT);
      control.state('remove-markers');
    }
  }, {
    icon: 'fa-toggle-off fa-2x',
    stateName: 'remove-markers',
    onClick: function(control) {
		map.setView([22.993013, 120.233937],8);
		map.removeLayer(clusters_IHMT);
      //map.removeLayer(rodents); //若使用clusters就需要使用markerClusterGroup
      control.state('add-markers');
    },
    title: 'remove IHMT Station'
  }]
});
toggle1.addTo(map);
 //----------------
});



//=============== plot circle 測試如何顯示 json  ===============
// Using tori location to Plot a circle area 
//==============================================================
/*     var popup_json = L.popup().setContent('<p style="font-size:130%;"><b>Some Name</b></p><div id="container" style="min-width: 300px; height: 200px; margin: 0 auto">Loading...</div>');

var circle = L.circle([22.63204313648, 120.2849198153], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 500
}).addTo(map);
circle.bindPopup(popup_json);

//circle.bindPopup("TORI nearby circle.");
  
    map.on('popupopen', function(e) {
      $.ajax({
          type: "GET",
          url: "data.json"
        })
        .done(function(data) {
          $('#container').highcharts({
            chart: {height: 175, width: 295},
            title: {text: ''},
            series: data
          });
        });
    });
    
    map.on('popupclose', function(e){
      $('#container').html("Loading...");
    }); */
//=============== end plot circle ===============
//=============== Show mouse click location 抓取顯示位置===============
var popup = L.popup();

function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        //.openOn(map); //Do not show point location on popup window
		document.getElementById("show_location").innerHTML = "Welcome to TORI MAP!"+e.latlng.toString();
}

map.on('click', onMapClick);
document.getElementById("show_location").innerHTML = "Welcome to TORI MAP!";

//===========  load GeoJSON from an external file (WRA Databse)==============================
// We can show different type data with different icon
// Users can click marker icon and then the marker will pop up a  window with information
// information include basic info , figures 
//==========================================================================================

 $.getJSON("PHP_to_geoJSON_WRA.php",function(data){
 var tempicon;   
 var SID; // record 目前測站參數
 var popup_json2;
  var rodents2 =L.geoJson(data  ,{
    pointToLayer: function(feature,latlng){
	//========= different type data icon setting ==============	
			
		switch(feature.properties.DataType_EN) {	
		case "buoy":
			tempicon="images/map_buoy_yes.png";break;
			// popup_json2 = L.popup({minWidth : 960,maxHeight : 800}).setContent('<p style="font-size:130%;"><b>水利署(WRA)'+feature.properties.CNname+"("+feature.properties.name+','+feature.properties.Latitude+','+feature.properties.Longitude+',SID='+ feature.properties.SID+')' +'</b></p><div id="container_wave1" style="min-width: 800px; height: 600px; margin: 0 auto">Loading...</div><p><div id="container_waveRose1" style="min-width: 500px; height: 500px; margin: 0 auto">Loading...</div>');
 
		case "tide":
			tempicon="images/map_toros_yes.png";break;
			//popup_json2 =L.popup({minWidth : 960,maxHeight : 800}).setContent(feature.properties.CNname +"("+feature.properties.name+','+feature.properties.Latitude+','+feature.properties.Longitude+')' + '<br/>' + feature.properties.SID+feature.properties.DataType_EN);
		}
	//================= setting icon attribution
	var torosIcon = L.icon({

	iconUrl: tempicon,
    iconSize: [32,48]
  }); 
  //=============== initial version  ===================
  	//var popup_json2 = L.popup({minWidth : 960,maxHeight : 800}).setContent('<p style="font-size:130%;"><b>水利署(WRA)'+feature.properties.CNname+"("+feature.properties.Latitude+','+feature.properties.Longitude+','+ feature.properties.SID+')' +'</b></p><div id="container_wave1" style="min-width: 800px; height: 600px; margin: 0 auto">Loading...</div><p><div id="container_waveRose1" style="min-width: 500px; height: 500px; margin: 0 auto">Loading...</div>');
  //var marker = L.marker(latlng,{icon: torosIcon});
  //============== end initial version  ================
	var marker = L.marker(latlng,{icon: torosIcon}).on('mouseover', function() {
		switch(feature.properties.DataType_EN) {	
		case "buoy":
			popup_json2 = L.popup({minWidth : 960,maxHeight : 800}).setContent('<p style="font-size:130%;"><b>水利署(WRA)'+feature.properties.CNname+"("+feature.properties.name+','+feature.properties.Latitude+','+feature.properties.Longitude+',SID='+ feature.properties.SID+')' +'</b></p><div id="container_wave1" style="min-width: 800px; height: 600px; margin: 0 auto">Loading...</div><p><div id="container_waveRose1" style="min-width: 500px; height: 500px; margin: 0 auto">Loading...</div><div id="container_curr" style="min-width: 500px; height: 500px; margin: 0 auto">Loading...</div>');break;
 
		case "tide":
			popup_json2 =L.popup({minWidth : 960,maxHeight : 800}).setContent('<p style="font-size:130%;"><b>水利署(WRA)'+feature.properties.CNname +"("+feature.properties.name+','+feature.properties.Latitude+','+feature.properties.Longitude+',SID=' +feature.properties.SID+')' +'</b></p><div id="container_tide" style="min-width: 800px; height: 600px; margin: 0 auto">Loading...</div>');break;
		}
                    this.bindPopup(popup_json2); //show popup information
					//this.bindPopup(feature.properties.CNname +"("+feature.properties.Latitude+','+feature.properties.Longitude+')' + '<br/>' + feature.properties.SID+feature.properties.DataType_EN).openPopup();
 SID=feature.properties.SID;
//document.getElementById("show_location").innerHTML = feature.properties.SID;    // for debugging to make sure whether get correct station id           
//====================================================
 map.on('popupopen', function(e) {
	
				var id1 = SID;
				//var id = 1;
				
				getAjaxData1(id1);
                var wave_options = {
                    chart: {
                        renderTo: 'container_wave1',
                        type: 'spline',
						zoomType: 'xy'
                    },
                    title: {
                        text: 'WRA OPEN DATA',
                        x: -20 //center
                    },
                    subtitle: {
                        text: 'TORI',
                        x: -20
                    },
                    xAxis: {
                        categories: [],
                        title: {
                            text: 'Date'
                        }
						
                    },
                    yAxis:  [{ // Primary yAxis

       
        title: {
            text: 'Wave Hight (cm)',
            style: {
                color: Highcharts.getOptions().colors[0]
            }
        },
		 labels: {
            format: '{value} cm',
            style: {
                color: Highcharts.getOptions().colors[0]
            }
        },
		
		tickInterval:10

    }, { // Secondary yAxis
        gridLineWidth: 0,
        title: {
            text: 'Wave Period (S)',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        labels: {
            format: '{value} s',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },opposite: true,tickInterval:2

    }, { // third yAxis
	
	
	
	//
        gridLineWidth: 0,
        title: {
            text: 'Wave Direction (°)',
            style: {
                color: Highcharts.getOptions().colors[2]
            }
        },
        labels: {
            format: '{value} °', 
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
		opposite: true,
	
		tickInterval:60

    }],
                    tooltip: {
					
					pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
                    valueDecimals: 2,			
					 shared: true
						 
                    },
                    legend: {
						layout: 'vertical',
						align: 'left',
						x: 80,
						verticalAlign: 'top',
						y: 15,
						floating: true,
						backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                    },
                    series: []
                };
				function getAjaxData1(id1) {
                $.getJSON("WRA_wave_series_data.php", {id: id1}, function(json) {
                    wave_options.xAxis.categories = json[0]['data']; //xAxis: {categories: []}
                    wave_options.series[0] = json[1];//height
					wave_options.series[1] = json[2];//period
					wave_options.series[2] = json[3];//dir
					chart = new Highcharts.Chart(wave_options);
                });
				}
				//-------------------- end wave series data
//========Wave ROSE==============================
	var waveDirectionJSON, waveSpeedJSON, waveDataJSON,wave_recDateJSON,wave_dateStr;

    waveDataJSON = [];
	var wave_xmlhttp = new XMLHttpRequest();

wave_xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      var myObj = JSON.parse(this.responseText);
		waveDirectionJSON=myObj[0].data;
		waveSpeedJSON=myObj[1].data;
		wave_recDateJSON=myObj[2].data;
    }
	
	    for (i = 0; i < waveDirectionJSON.length; i++) {
        waveDataJSON.push([waveDirectionJSON[i], waveSpeedJSON[i]]);
		
    }
	wave_dateStr=wave_recDateJSON[waveDirectionJSON.length-1]+"~"+wave_recDateJSON[0];
	var categories = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
    $('#container_waveRose1').highcharts({
        series: [{
			name:"Wave Hight",
            data: waveDataJSON
        }],
        chart: {
            polar: true,
            type: 'column'
        },
        title: {
            text: 'Wave Direction'+"<br/>"+wave_dateStr
        },
        pane: {
            size: '85%'
        },
        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 100,
            layout: 'vertical'
        },
        xAxis: {
            min: 0,
            max: 360,
            type: "",
            tickInterval: 22.5,
            tickmarkPlacement: 'on',
            labels: {
                formatter: function () {
                    return categories[this.value / 22.5] + '°';
                }
            }
        },
        yAxis: {
            min: 0,
            endOnTick: false,
            showLastLabel: true,
            title: {
                text: 'Frequency (%)'
            },
            labels: {
                formatter: function () {
                    return this.value + '%';
                }
            },
            reversedStacks: false
        },
        tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>:<b>{point.y}</b> <br/>Direction：{point.x} °<br/>',
            valueSuffix: ' cm'
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                shadow: false,
                groupPadding: 0,
                pointPlacement: 'on'
            }
        }
    });
	
	
	
};

wave_xmlhttp.open("POST", "WRA_wave_data.php", true);
wave_xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
wave_xmlhttp.send("id=" + SID);
//=== current ROSE==============================================================
				
		var windDirectionJSON, windSpeedJSON, windDataJSON,recDateJSON,dateStr;

		windDataJSON = [];
		
		var xmlhttp = new XMLHttpRequest();

xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      var myObj = JSON.parse(this.responseText);
		windDirectionJSON=myObj[0].data;
		windSpeedJSON=myObj[1].data;
		recDateJSON=myObj[2].data;
    }
	
	    for (i = 0; i < windDirectionJSON.length; i++) {
        windDataJSON.push([windDirectionJSON[i], windSpeedJSON[i]]);

    }
	dateStr=recDateJSON[windDirectionJSON.length-1]+"~"+recDateJSON[0];
	
	var categories = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
    $('#container_curr').highcharts({
        series: [{
			name:"Current",
            data: windDataJSON
        }],
        chart: {
            polar: true,
            type: 'column'
        },
        title: {
            text: 'Current Direction'+"<br/>"+dateStr
        },
        pane: {
            size: '85%'
        },
        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 100,
            layout: 'vertical'
        },
        xAxis: {
            min: 0,
            max: 360,
            type: "",
            tickInterval: 22.5,
            tickmarkPlacement: 'on',
            labels: {
                formatter: function () {
                    return categories[this.value / 22.5] + '°';
                }
            }
        },
        yAxis: {
            min: 0,
            endOnTick: false,
            showLastLabel: true,
            title: {
                text: 'Frequency (%)'
            },
            labels: {
                formatter: function () {
                    return this.value + '%';
                }
            },
            reversedStacks: false
        },
        tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>Direction：{point.x} °<br/>',
            valueSuffix: 'cm/s'
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                shadow: false,
                groupPadding: 0,
                pointPlacement: 'on'
            }
        }
    });
	
	
	
};

xmlhttp.open("POST", "WRA_Current_Data.php", true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send("id=" + SID);
//==================== end current =====================
//------------------- Tide series data -----------------------------

getAjaxData(SID);
                var options = {
                    chart: {
                        renderTo: 'container_tide',
                        type: 'line'
                    },
                    title: {
                        text: 'WRA OPEN DATA',
                        x: -20 //center
                    },
                    subtitle: {
                        text: 'TORI',
                        x: -20
                    },
                    xAxis: {
                        categories: [],
                        title: {
                            text: 'Date'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Tide Hight (cm)'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        valueSuffix: 'cm'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: []
                };
				function getAjaxData(id) {
                $.getJSON("WRA_tide_data_remote.php", {id: id}, function(json) {
                    options.xAxis.categories = json[0]['data']; //xAxis: {categories: []}
                    options.series[0] = json[1];
                    chart = new Highcharts.Chart(options);
                });
				}
				

//------------------- end Tide series data -------------------------


//-------------- end waveRose --------------------------
	map.on('popupclose', function(e){
      $('#container_wave1').html("Loading...");
	  $('#container_waveRose1').html("Loading...");
	  $('#container_curr').html("Loading...");
	  $('#container_tide').html("Loading...");
    });			
				
  });//end map.on
 //=====================================================				
				
	}); //end L.marker.on('mouseover')
	
	//SID=feature.properties.SID;
	//document.getElementById("show_location").innerHTML = feature.properties.SID;
	//var popup_json2 = L.popup({minWidth : 960,maxHeight : 800}).setContent('<p style="font-size:130%;"><b>水利署(WRA)'+feature.properties.CNname+"("+feature.properties.Latitude+','+feature.properties.Longitude+','+ feature.properties.SID+')' +'</b></p><div id="container_wave1" style="min-width: 800px; height: 600px; margin: 0 auto">Loading...</div><p><div id="container_waveRose1" style="min-width: 500px; height: 500px; margin: 0 auto">Loading...</div>');
	//marker.bindPopup(popup_json2);
	//marker.bindPopup(feature.properties.CNname +"("+feature.properties.Latitude+','+feature.properties.Longitude+')' + '<br/>' + feature.properties.SID+feature.properties.DataType_EN);
  return marker; 
	 // return L.marker(latlng,{icon: buoyIcon});
    } // end pointtolayer
  }  );//.addTo(map);//comment .addTo(map)==>表沒有先加入map ==>initial 會變成 unclick
 var clusters = L.markerClusterGroup();
clusters.addLayer(rodents2);
//map.addLayer(clusters); ////若使用clusters就需要使用markerClusterGroup;一開始不顯示
 controlLayers.addOverlay(clusters, 'WRA_Stations');
 
 //---------------- // toggle-on toggle-off easy button on leaflet---------------------
 var toggle = L.easyButton({
  states: [{
    stateName: 'add-markers',
    icon: 'fa-map-marker fa-2x',
    title: 'WRA Station',
    onClick: function(control) {
	map.setView([22.993013, 120.233937],8);
      map.addLayer(clusters);
      control.state('remove-markers');
    }
  }, {
    icon: 'fa-undo fa-2x',
    stateName: 'remove-markers',
    onClick: function(control) {
	map.setView([22.993013, 120.233937],8);
      map.removeLayer(clusters);
      control.state('add-markers');
    },
    title: 'remove WRA Station'
  }]
});
toggle.addTo(map);
 //---------------------------- end -------------------------------
 
}); 



// end getjson2

// add mesure icon
	L.control.scale(baseMaps).addTo(map);
L.control.polylineMeasure(baseMaps).addTo(map);  	


//=================== insert watermark 浮水印===========================
L.Control.Watermark = L.Control.extend({
    onAdd: function(map) {
        var img = L.DomUtil.create('img');

        img.src = 'images/tori_little_icon.png';
        img.style.width = '154px';

        return img;
    },

    onRemove: function(map) {
        // Nothing to do here
    }
});

L.control.watermark = function(opts) {
    return new L.Control.Watermark(opts);
}

L.control.watermark({ position: 'bottomright' }).addTo(map);

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
 //-----------


//================= testing easy button 
var stateChangingButton = L.easyButton({
    states: [{
            stateName: 'zoom-to-Penghu',        // name the state
            icon:      'fa-tree',               // and define its properties
            title:     'zoom to a Penghu',      // like its title
            onClick: function(btn, map) {       // and its callback
                map.setView([23.5624, 119.57521 ],10);
                btn.state('zoom-to-orchard-island');    // change state on click!
            }
        }, {
            stateName: 'zoom-to-orchard-island',
            icon:      'fa-university',
            title:     'zoom to a orchard',
            onClick: function(btn, map) {
                map.setView([22.04617, 121.54314],12);
                btn.state('zoom-to-Penghu');
            }
    }]
});

stateChangingButton.addTo(map);
//=============================================
/* 

        L.easyButton('fa-pencil', function(){
        classes.addLayer({
            click: onClick
        });
    }).addTo(map);

    function onClick(e) {
        map.removeLayer(classes);
    } */
  </script>

</body>
</html>