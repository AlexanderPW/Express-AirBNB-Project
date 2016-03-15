<? 

?>
<? $cityname = $_REQUEST['cityname']; ?>
<?
function getLocalListings($cat) {
  $address=urlencode($address);
 $url = "http://www.eventbriteapi.com/v3/events/search/?token=WRURYKF7FLK7B5VYWWRN";
 $url .= "&venue.city=".urlencode($GLOBALS['cityname']);
 $url .= "&popular=true";
 $url .= "&categories=$cat";
 $url .= "&expand=venue";
  $str = @file_get_contents($url); 
  $strp = json_decode($str, true);
  return $strp; 
 } 

 // LIST OF CATEGORIES

 $business = "101";
 $music = "103";
 $food = "110";

function getListing($data) {
$json_req = getLocalListings($data);

$i = 0;
foreach ($json_req['events'] as $events => $value) {
if($i < 4){
   foreach ($value as $event => $feature) {
    $name = $value['name']['text'];
    $venue = $value['venue']['name'];
      $time = $value['start']['local'];
$timestamp = strtotime($time);
$timeformat = date('l F jS Y \a\t g:ia', $timestamp);
$vurl = $value['url'];
  
    if(!is_null($value['logo']['url'])){$logo = $value['logo']['url']; } else {$logo = 'http://cdn.evbstatic.com/s3-build/perm_001/f8c5fa/django/images/discovery/default_logos/4.png';}
    if(!is_null($value['logo']['edge_color'])) {$color = $value['logo']['edge_color'];} else {$color = '#000000';}
   }
   echo "<div class='col-lg-3 col-md-6 col-sm-6' style='padding:10px;overflow:hidden;min-height:325px;'><div style='text-align:center;background-color:$color;width:100%;max-height:200px;overflow:hidden;'><img style='height:200px;max-width:100%;' src='$logo'></div><h4 style='font-weight:600;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;height:auto;'><a href='$vurl' target='_BLANK'>$name</a></h4><p><b>$venue</b><br>$timeformat</p></div>";
   $i++;
   } 
}
}


 getListing($food);
 /* echo "<h3>Music</h3>";
 getListing($music);
 echo "<h3>Business</h3>";
 getListing($business); */
?>
