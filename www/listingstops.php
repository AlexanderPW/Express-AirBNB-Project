<? 

if(!empty($_REQUEST['listing_item'])){
$lat = $_REQUEST['lat']; 
$lon = $_REQUEST['lon'];


 $url = "http://transit.walkscore.com/transit/search/stops/?wsapikey=0c4f1575f9b47634688d1950ed736fc4";
 $url .= "&lat=".$GLOBALS['lat'];
 $url .= "&lon=".$GLOBALS['lon'];
  $str = @file_get_contents($url); 
  $strp = json_decode($str, true);

$json_req = $strp;

$i = 0;

// NETWORK SEARCH PARAMS

$nsurl = "http://transit.walkscore.com/transit/search/network/?wsapikey=0c4f1575f9b47634688d1950ed736fc4";
$nsurl .= "&lat=".$GLOBALS['lat'];
$nsurl .= "&lon=".$GLOBALS['lon'];
$nsstr = @file_get_contents($nsurl); 
$nsstrp = json_decode($nsstr, true);

$nsjson_req = $nsstrp;

// STOP SEARCH PARAMTS
 function newdistance ($lat1, $lng1, $lat2, $lng2) {

    $earthRadius = 3958.75;

    $dLat = deg2rad($lat2-$lat1);
    $dLng = deg2rad($lng2-$lng1);


    $a = sin($dLat/2) * sin($dLat/2) +
       cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
       sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $dist = $earthRadius * $c;

    // from miles
    $meterConversion = 1609;
    $geopointDistance = $dist;

    return $geopointDistance;
}

function order_by_miles($a, $b) {
    return $a['miles'] > $b['miles'] ? 1 : -1;
}


function unique_multi_array($array, $key) { 
    $temp_array = array(); 
    $i = 0; 
    $key_array = array(); 
    
    foreach($array as $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        } 
        $i++; 
    } 
    return $temp_array; 
} 

function uniqueAssocArray($array, $uniqueKey) {
  if (!is_array($array)) {
    return array();
  }
  $uniqueKeys = array();
  foreach ($array as $key => $item) {
    if (!in_array($item[$uniqueKey], $uniqueKeys)) {
      $uniqueKeys[$item[$uniqueKey]] = $item;
    }
  }
  return $uniqueKeys;
}





foreach ($json_req as $stop => $value) {
if($i < 6){
    $name = $value['name'];
    $stopname = substr($name, 0, 20);
    $distance = round($value['distance'], 2);
    $cat = $value['route_summary'];
    $j = 0;
    foreach ($cat as $key2 => $value2) {
     if($j == 0){
      $cats = $value2['category'];
      $j++;
    }
    }
    $stopdesc = $value['summary_text'];
    if($cats == 'Bus'){
      $blon = $value['lon'];
      $blat = $value['lat'];
  $bus_stops .=  "beachMarker = new google.maps.Marker({position: {lat: $blat, lng: $blon},map: map,icon: busimage, clickable: true});";
  echo "<b>$stopname</b> $distance<b> Miles</b><br>"; }

      $i++;
   } 
} 




// ROUTES ARRAY

foreach ($nsjson_req['routes'] as $routes => $value) {
if ($value['category'] == 'Rail') {
$newroutes[] = array('routename' => $value['name'], 'stop_ids' => $value['stop_ids']);
}
}

// STOPS ARRAY

foreach ($nsjson_req['stops'] as $stops => $svalue) {
        $slat = $svalue['lat'];
        $slon = $svalue['lon'];
        $lat1 = $GLOBALS['lat'];
        $lon1 = $GLOBALS['lon'];
        $miles = round(newdistance($slat, $slon, $lat1, $lon1), 2);
$newstops[] = array('stopname' => $svalue['name'], 'stop_lat' => $svalue['lat'], 'stop_lon' => $svalue['lon'], 'stop_id' => $svalue['id'], 'miles' => $miles);
}

// THE FIRST LOOP CHECK IF EXISTS

foreach ($newroutes as $a => $b) {
$railarr[] ='';
//echo $b['routename'].'<br>';
  $newids = $b['stop_ids'];
  $rail = '';

  foreach ($newids as $c) {

    foreach ($newstops as $d => $e) {
      
      if (($e['stop_id'] == $c) && ($z < 10)){
       
           // echo "<br>". $b['routename']."<br>". $e['stopname']."<br>";
        $railarr[] = array('nstop_id' => $e['stop_id'].'<br>', 'route_name' => $b['routename'].'<br>', 'stop_name' => $e['stopname'], 'miles' => '<br>'.$e['miles'].' <b>Miles</b><br><br>');
       usort($railarr, 'order_by_miles');
       $raildupe = unique_multi_array($railarr, 'nstop_id');
       $rail_stops .=  "beachMarker = new google.maps.Marker({position: {lat: ".$e['stop_lat'].", lng: ".$e['stop_lon']."},map: map,icon: trainimage});";
     
        
      
      } 
      
    }

  }
  
}
$p = 0;
foreach ($raildupe as $k => $v) {
  if($p < 11){
  $rn = $v['route_name'];
  $mi = $v['miles'];
  $sn = $v['stop_name'];
  $rsid = $v['nstop_id'];
  $rail .= "<b>$rn</b> $sn $mi";
$p++;
}
}

  if (!empty($rail))
{
  echo "<br><p><i class='fa fa-train'></i> Rail Stops</p>";
    echo $rail;
 //print_r($newstops);
}





/*
foreach ($nsjson_req['routes'] as $routes => $value) {
  $category = $value['category'];
  $idvalue = $value['stop_ids'][0];
*/








 
   ?>

    
<script>

  var myLatLng = {lat: <?=$lat?>, lng: <?=$lon?>};

  var map = new google.maps.Map(document.getElementById('gmap_canvas'), {
    zoom: 14,
    center: myLatLng
  });

  
  var trainimage = 'http://dev.expresscorporatehousing.com/wp-content/uploads/2016/01/train.png';
  var busimage = 'http://dev.expresscorporatehousing.com/wp-content/uploads/2016/01/bus.png';
 
    <?=$bus_stops?>
    <?=$rail_stops?>
 var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    title: "blahblah",
    zIndex: google.maps.Marker.MAX_ZINDEX + 1
  });

     </script>
   
<?
} 

else {
$lat = $_REQUEST['lat']; 
$lon = $_REQUEST['lon'];
$city = urlencode($_REQUEST['city']) ;
$state = $_REQUEST['state'];


  // $url = "http://transit.walkscore.com/transit/search/stops/?wsapikey=0c4f1575f9b47634688d1950ed736fc4";
 $url = "http://transit.walkscore.com/transit/score/?wsapikey=0c4f1575f9b47634688d1950ed736fc4";
 $url .= "&lat=".$GLOBALS['lat'];
 $url .= "&lon=".$GLOBALS['lon'];
 $url .= "&city=".$GLOBALS['city'];
 $url .= "&state=".$GLOBALS['state'];
  $str = @file_get_contents($url); 
  $strp = json_decode($str, true);

$json_req = $strp;



$i = 0;
foreach ($json_req as $value) {
if($i < 1){
   $summary = $json_req['summary'];
   $desc = $json_req['description'];
   $score = $json_req['transit_score'] ;
   //echo "<b>$desc</b><br>$summary";
   echo "Transit Score: $score<br>$summary";
   $i++;
   } 
}
}

?>

