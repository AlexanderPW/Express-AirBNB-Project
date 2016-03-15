<?
include (APPPATH . '/views/emails/header.php');
$msg .=
    '<p>Someone you know believes that the following apartment would be an excellent fit for you. Here is their message:.</p>
    <p>'.nl2br($message).'</p>
    <table width="100%">
    <tr>
        <td colspan="2"><a href="'.site_url('listing/'.get_listing_url($listing)).'">Click here to view the apartment.</a></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><img style="margin:10px auto" width="'.IMG_SIZE_WIDTH.'" height="'.IMG_SIZE_HEIGHT.'" src="'.site_url('assets/listings/resized/'.$photo->original_url).'"></a></td>
    </tr>
    <tr>
        <td valign="top"><strong>Address: </strong></td>
        <td valign="top">'.$listing->address.', '.$listing->city.', '.$listing->state.' '.$listing->zipcode.'</td>
    </tr>
    <tr>
        <td valign="top"><strong>Number of Bedrooms: </strong></td>
        <td valign="top">'.intval($listing->bedrooms).'</td>
    </tr>';

if($listing->bathrooms>0) {
    $msg.='<tr>
        <td valign="top"><strong>Number of Bathrooms: </strong></td>
        <td valign="top">'.$listing->bathrooms.'</td>
    </tr>';
}
$msg.=' <tr>
        <td valign="top"><strong>Description: </strong></td>
        <td valign="top">'.$listing->description.'</td>
    </tr>
  </table>';
include (APPPATH . '/views/emails/footer.php');
?>