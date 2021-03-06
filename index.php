<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker2" ).datepicker();
  });
  </script>
</head>
<body>
 
  <form method="get">
  <table  >
    <tr>
      <td > 
        Start: <input type="text" id="datepicker" name="begin-date" />
      </td>
      <td>
        End: <input type="text" id="datepicker2" name="end-date"/>
      </td>
      <td>
        Zip Code: <input type="text" name="zip-code"/>
      </td>
    </tr>
      <tr style="text-align:center;">
      <td>
        <input type="radio" name="datasource" value="noaa">NOAA Data<br>
        <input type="radio" name="datasource" value="fio">Forecast.io Data
      </td>
      <td colspan="2">
        <input type="submit">
      </td>
    </tr>
  </table>
  </form>

</body>
</html>


<?php
  include('forecast.io-php-api/lib/forecast.io.php');
  
$lookup_zip = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $_GET["zip-code"] . '&sensor=false'));
$latitude = $lookup_zip->results[0]->geometry->location->lat;
$longitude = $lookup_zip->results[0]->geometry->location->lng;

echo 'Showing weather from ' . $_GET['begin-date'] . ' to ' . $_GET['end-date'] . ' in ' . $lookup_zip->results[0]->address_components[1]->long_name . '.';
date_default_timezone_set('UTC');
$begin_date = strtotime($_GET["begin-date"]) + 1;
$end_date = strtotime($_GET["end-date"]) + 1;
echo "<br />";

$api_key = 'd5cc645c8a6f195462b8a38684648963';
  
if($begin_date >= $end_date){
echo "Error: Please select an end date after the begin date.";
}else{
  

echo '<table style="text-align:center;">
<tr>
<td style="padding:5px;">Date</td>
<td style="padding:5px;">Low Temperature</td>
<td style="padding:5px;">High Temperature</td>
</tr>';

if($_GET['datasource']=='fio'){
  for($i=$begin_date;$i<=$end_date;$i+=86400){
    $forecast = new ForecastIO($api_key);
    $condition = $forecast->getHistoricalConditions($latitude, $longitude, $i);
    echo 
    '<tr>
      <td>' 
        . date("F d, Y",$i) . 
      '</td>
      <td>' . $condition->getMinTemperature() . '</td>
      <td>' . $condition->getMaxTemperature() . '</td>
    </tr>';
  }
} else if($_GET['datasource']=='noaa') {
  echo date('Y-m-d',  $begin_date);
  $xml = new SimpleXMLElement(file_get_contents('http://graphical.weather.gov/xml/sample_products/browser_interface/ndfdXMLclient.php?zipCodeList=' . $_GET["zip-code"] . '&product=time-series&begin=' . date('Y-m-d', $begin_date) . 'T00:00:00&end='.date('Y-m-d', $end_date).'T00:00:00&maxt=maxt'));
  foreach($xml->data->parameters->temperature->value as $val){
    
      '<tr>
      <td>' 
        . date("F d, Y") . 
      '</td>
      <td>' . '</td>
      <td>' . $val . '</td>
    </tr>';

    }

  }
}
function c_to_f($c){
  return($c * 9 / 5 + 32);
}
?>