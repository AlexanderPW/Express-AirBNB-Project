<? 
/*

  function getLocalListings() {
  $address=urlencode($address);
  $url = "http://api.eventful.com/json/events/search?location=".types_render_field('hero-title', array('output', 'raw'))."";
  $url .= "&within=30";
  $url .= "&page_size=8";
  $url .= "&page_number=1";
  $url .= "&image_sizes=perspectivecrop176by124";
  $url .= "&sort_order=popularity";
  $url .= "&app_key=sckR75CWfLQsT59Q";

  $str = @file_get_contents($url); 
  $strp = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str), true);
  return $strp; 
 } 

$json_req = getLocalListings();

foreach($json_req['events']['event'] as $list => $event) {
foreach ($event as $key => $value) {
  $watching = $event['watching_count'];
  $title = $event['title'];
  $img = $event['image']['perspectivecrop176by124']['url'];
  $venue = $event['venue_name'];
}

echo "<div class='col-lg-3 col-md-6' style='padding:10px;overflow:hidden;min-height:200px;'><img class='img-responsive' style='width:100%;height:auto' src='$img'><b>$title</b><br>$venue</div>";
}
*/
?>
<? $cityname = $_REQUEST['cityname']; ?>
  <h2><b>Whats happening in <?= $cityname; ?></h2></b>
<?
function getLocalListings($cat) {
  $address=urlencode($address);
 $url = "http://www.eventbriteapi.com/v3/events/search/?token=WRURYKF7FLK7B5VYWWRN";
 $url .= "&venue.city=".$GLOBALS['cityname'];
 $url .= "&popular=true";
 $url .= "&categories=$cat";
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
    if(!is_null($value['logo']['url'])){$logo = $value['logo']['url']; } else {$logo = 'http://cdn.evbstatic.com/s3-build/perm_001/f8c5fa/django/images/discovery/default_logos/4.png';}
    $color = $value['logo']['edge_color'];
   }
   echo "<div class='col-lg-3 col-md-6' style='padding:10px;overflow:hidden;min-height:200px;'><div style='text-align:center;background-color:$color;width:100%;max-height:200px;overflow:hidden;'><img style='height:200px;' src='$logo'></div><h4 style='display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;height:40px;'>$name</h4></div>";
   $i++;
   } 
}
}

echo "<h3>Food and Drink</h3>";
 getListing($food);
 echo "<h3>Music</h3>";
 getListing($music);
 echo "<h3>Business</h3>";
 getListing($business);
?>