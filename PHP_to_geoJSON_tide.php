<?php
/**
PHP_to_geoJSON_tide.php
 * PHP GeoJSON Constructor, adpated from https://github.com/bmcbride/PHP-Database-GeoJSON
 */
# Connect to MySQL database
//================== connect to database ======================================
//================== connect to database ======================================
ini_set('date.timezone','Asia/Taipei');
//include_once("IHMT_DB.php");
mysql_query("set names 'utf8'");
//include_once("IHMT_DB_in81.php");
include_once("IHMT_DB_remote.php");
//===========================================================================================

$sql1="SELECT * FROM IHMT_TideStations;";
$rt_Temp_data = mysql_query($sql1);$rt_Temp_records=mysql_num_rows($rt_Temp_data);

# Build GeoJSON feature collection array
$geojson = array(
   'type'      => 'FeatureCollection',
   'features'  => array()
);

for($j=0;$j<$rt_Temp_records;$j++)
{
	$SID=mysql_result($rt_Temp_data, $j, "Station_ID");
	$Station_CName=mysql_result($rt_Temp_data, $j, "Station_CName");
	 $Longitude=mysql_result($rt_Temp_data, $j, "Longitude");
	 $Latitude=mysql_result($rt_Temp_data, $j, "Latitude");
	 $DataType_EN=mysql_result($rt_Temp_data, $j, "DataType_EN");
	 $Station_EName=mysql_result($rt_Temp_data, $j, "Station_EName");
	 $ObserverItems=mysql_result($rt_Temp_data, $j, "ObserverItems");
//echo $SID.$Station_CName.$Longitude.$Latitude.$DataType_EN.$Station_EName.$ObserverItems; 
$feature = array(
        'type' => 'Feature', 
		'id' => $SID,
        
        # Pass other attribute columns here
        'properties' => array(
            'name' => $Station_EName,
			'CNname' => $Station_CName,
            'DataType_EN' => $DataType_EN,
            'Longitude' =>$Longitude,
			'Latitude' =>$Latitude,
			'SID' =>$SID
            ),
		'geometry' => array(
            'type' => 'Point',
            # Pass Longitude and Latitude Columns here
            'coordinates' => array($Longitude, $Latitude)
        )
        );
    # Add feature arrays to feature collection array
    array_push($geojson['features'], $feature);
	
}
header('Content-type: application/json');
echo json_encode($geojson, JSON_NUMERIC_CHECK);
?>