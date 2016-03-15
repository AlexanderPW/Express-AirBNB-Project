<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>There has been a new reservation request submitted on the Express Corporate Housing Website.</p>
  <table width="100%">
    <tr>
        <td><strong>Reservation ID: </strong></td>
        <td>'.$reservation['id'].'</td>
    </tr>
    <tr>
        <td><strong>Name: </strong></td>
        <td>'.$reservation['name'].'</td>
    </tr>
    <tr>
        <td><strong>Email: </strong></td>
        <td>'.$reservation['email'].'</td>
    </tr>
    <tr>
        <td><strong>Phone: </strong></td>
        <td>'.$reservation['phone'].'</td>
    </tr>';
if(isset($reservation['url'])) {
    $msg .= '<tr>
            <td><strong>Location: </strong></td>
            <td><a href="'.$reservation['url'].'">'.$reservation['location'].'</a></td>
        </tr>';
} else {
    $msg .= '<tr>
            <td><strong>Location: </strong></td>
            <td>'.$reservation['location'].'</td>
        </tr>';
}
$msg .= '<tr>
        <td><strong>Move-In Date: </strong></td>
        <td>'.$reservation['move_in_date'].'</td>
    </tr>
    <tr>
        <td><strong>Move-Out Date: </strong></td>
        <td>'.$reservation['move_out_date'].'</td>
    </tr>
    <tr>
        <td><strong>Budget: </strong></td>
        <td>'.$reservation['budget'].'</td>
    </tr>
    <tr>
        <td><strong>Number of Guests: </strong></td>
        <td>'.$reservation['total_guests'].'</td>
    </tr>
    <tr>
        <td><strong>Number of Bedrooms: </strong></td>
        <td>'.$reservation['number_of_bedrooms'].'</td>
    </tr>
    <tr>
        <td><strong>Number of Bathrooms: </strong></td>
        <td>'.$reservation['number_of_bathrooms'].'</td>
    </tr>
    <tr>
        <td><strong>Number of Apartments: </strong></td>
        <td>'.$reservation['number_of_apartments'].'</td>
    </tr>
    <tr>
        <td><strong>Requested Housekeeping?: </strong></td>
        <td>'.(isset($reservation['housekeeping']) && $reservation['housekeeping']==1 ? 'Yes' : 'No').'</td>
    </tr>
    <tr>
        <td><strong>Bringing Pets?: </strong></td>
        <td>'.(isset($reservation['pets']) && $reservation['pets']==1 ? 'Yes' : 'No').'</td>
    </tr>
    <tr>
        <td><strong>Government/Military Employee?: </strong></td>
        <td>'.(isset($reservation['government']) && $reservation['government']==1 ? 'Yes' : 'No').'</td>
    </tr>
    <tr>
        <td><strong>Notes: </strong></td>
        <td>'.$reservation['notes'].'</td>
    </tr>
  </table>';
include (APPPATH . '/views/emails/footer.php');
?>