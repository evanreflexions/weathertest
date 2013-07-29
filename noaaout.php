<?php
$xml = new SimpleXMLElement(file_get_contents('http://graphical.weather.gov/xml/sample_products/browser_interface/ndfdXMLclient.php?zipCodeList=10510&product=time-series&begin=2004-01-01T00:00:00&end=2015-04-21T00:00:00&maxt=maxt'));
  echo '<pre>';

  foreach($xml->data->parameters->temperature->value as $val){
    echo $val;
    echo '<br />';
  }
  echo '</pre>';
  ?>