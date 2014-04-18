<?PHP
if (!defined('OK')) die();
if (!isset($_GET['event'])) die();
$stmt = $db->prepare("SELECT Booking.Name, Booking.Firstname, Booking.Mail, Booking.Mobile, Booking.IP, Booking.ID, (SELECT GROUP_CONCAT((Booking_Seat.Seat + 1) SEPARATOR ', ') FROM Booking_Seat WHERE Booking_Seat.Booking=Booking.ID)  AS Seats FROM Booking WHERE Booking.Event=?");
$stmt->bindParam(1, $_GET['event']);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("select Booking_Seat.Seat FROM Booking_Seat INNER JOIN Booking ON Booking_Seat.Booking = Booking.ID WHERE Booking.Event=? GROUP BY Booking_Seat.Seat");
$stmt->bindParam(1, $_GET['event']);
$stmt->execute();
$row4 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$seats_taken = $stmt->rowCount();

$stmt = $db->prepare("select Name, Description, Start, End, Visible, Room, Price from Event WHERE ID=?");
$stmt->bindParam(1, $_GET['event']);
$stmt->execute();
$row1 = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("select Name, Width, Height from Room WHERE ID=?");
$stmt->bindParam(1, $row1['Room']);
$stmt->execute();
$row2 = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("select Seat,X,Y from Seat WHERE Room=?");
$stmt->bindParam(1, $row1['Room']);
$stmt->execute();
$row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$seatsbooked = array();
foreach($row4 as $seat) 
{
$seatsbooked[] = $seat['Seat'];
}
foreach($row3 as $map)
	{
		$mapPins[] = array(
				"id" => $map['Seat'],
				"xcoord" => $map['X'],
				"ycoord" => $map['Y'],
				"booked" => in_array($map['Seat'],$seatsbooked)
				);
	}
?>

<div class="row">
<table class="table">
<tr><th>Name</th><th>Firstname</th><th>Places</th><th>E-Mail</th><th>Mobile</th><th>IP</th></tr>
<?
foreach ($rows as $row) {
?><tr><td><?echo($row['Name']);?></td><td><?echo($row['Firstname']);?></td><td><?echo($row['Seats']);?></td><td><?echo($row['Mail']);?></td><td><?echo($row['Mobile']);?></td><td><?echo($row['IP']);?></td><td><a href="#" data-row="<?echo($row['ID']);?>" class="delbooking"><span class="glyphicon glyphicon-floppy-remove"></span></a></td></tr>
<?
}
?>
</table>
</div>

<div class="row">
<div id="map"></div>
</div>

<div class="row">
<a class="btn btn-default" href="?page=bookings_add&event=<?echo($_GET['event']);?>">Add Booking</a>
</div>
<script>
        
		$(".delbooking").click(function(e) {
		row = $(this).data('row');
            bootbox.confirm("Are you sure?", function(result) {
 if (result === true) 
 {
	$.get( "action.php?a=delbooking&i="+ row, function( data ) {
	location.reload();
});
 }
});
        });
		$(document).ready(function() {

	    $('#map').dropPin('showPinsBookings',{
			  	fixedHeight:<?echo($row2['Height']);?>,
			  	fixedWidth:<?echo($row2['Width']);?>,
			  	cursor: '',
				pin: '../img/MarkerGreen.png',
				redPin: '../img/MarkerRed.png',
				yellowPin: '../img/MarkerYellow.png',
				backgroundImage: '../img_room.php?room=<?echo($row1['Room']);?>',
				xoffset : 12,
				yoffset : 12,
				pinDataSet: <?php echo '{"markers": '.json_encode($mapPins).'}' ;?>,
				enabled: "true"
		});

	});
		</script>