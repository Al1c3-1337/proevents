<?PHP
if (!defined('OK')) die();
if (!isset($_GET['event'])) die();
$stmt = $db->prepare("select Name, Description, Start, End, Visible, Room, Price from Event WHERE ID=?");
$stmt->bindParam(1, $_GET['event']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $db->prepare("select Seat,X,Y from Seat WHERE Room=?");
$stmt->bindParam(1, $row['Room']);
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$seats_total = $stmt->rowCount();
$stmt = $db->prepare("select Booking_Seat.Seat FROM Booking_Seat INNER JOIN Booking ON Booking_Seat.Booking = Booking.ID WHERE Booking.Event=? GROUP BY Booking_Seat.Seat ");
$stmt->bindParam(1, $_GET['event']);
$stmt->execute();
$row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$seats_taken = $stmt->rowCount();

$stmt = $db->prepare("select Name, Width, Height from Room WHERE ID=?");
$stmt->bindParam(1, $row['Room']);
$stmt->execute();
$row4 = $stmt->fetch(PDO::FETCH_ASSOC);
$seatsbooked = array();
foreach($row3 as $seat) 
{
$seatsbooked[] = $seat['Seat'];
}
foreach($row2 as $map)
	{
		$mapPins[] = array(
				"id" => $map['Seat'],
				"xcoord" => $map['X'],
				"ycoord" => $map['Y'],
				"enabled" => !in_array($map['Seat'],$seatsbooked),
				"selected" => false
				);
	}
	if (!isset($_POST['add']) || $_POST['add'] != 1) {
?>
	<script type="text/javascript">
	$(document).ready(function() {

	    $('#map').dropPin('showPins',{
			  	fixedHeight:<?echo($row4['Height']);?>,
			  	fixedWidth:<?echo($row4['Width']);?>,
			  	cursor: '',
				pin: '../img/MarkerGreen.png',
				redPin: '../img/MarkerRed.png',
				yellowPin: '../img/MarkerYellow.png',
				backgroundImage: '../img_room.php?room=<?echo($row['Room']);?>',
				xoffset : 12,
				yoffset : 12,
				pinDataSet: <?php echo '{"markers": '.json_encode($mapPins).'}' ;?>,
				enabled: "true"
		});

	});
	</script>
<div class="row">
	<form role="form" action="?page=bookings_add&event=<?echo($_GET['event']);?>" method="post">
  <div class="form-group">
    <label for="firstname">Vorname</label>
    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Vorname" required>
  </div>
  <div class="form-group">
    <label for="lastname">Name</label>
    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Name" required>
  </div>
  <div class="form-group">
    <label for="mail">E-Mail</label>
    <input type="text" class="form-control" id="mail" name="mail" placeholder="E-Mail" required>
  </div>
  <div class="form-group">
    <label for="mobile">Mobil</label>
    <input type="text" class="form-control" id="mobile" name="mobile" required>
  </div>
  <div class="form-group">
    <div id="map"></div>
  </div>
  <button type="submit" class="btn btn-default">Add</button>
  <input type="hidden" name="add" value="1">
</form>
	</div>
	
<?
} else {
$mapDataX = json_decode($_POST['mapdata']);
$mapData = $mapDataX->markers;
$cancelbooking = false;
$seatsbooknew = array();
try {
foreach ($mapData as $pinData) {
if ($pinData->selected == '1') {
$seatsbooknew[] = $pinData->id;
if (in_array($pinData->id,$seatsbooked)) $cancelbooking=true;
}
}
if (count($seatsbooknew) == 0) $cancelbooking = true;
}
catch (Exception $ex) {
$cancelbooking = true;
}
if (!$cancelbooking) {
try {
$db->beginTransaction();
$stmt = $db->prepare("insert into Booking (Event, Name, Firstname, Mail, Mobile, IP) VALUES (?,?,?,?,?,'local')");
$stmt->bindParam(1, $_GET['event']);
$stmt->bindParam(2, $_POST['lastname']);
$stmt->bindParam(3, $_POST['firstname']);
$stmt->bindParam(4, $_POST['mail']);
$stmt->bindParam(5, $_POST['mobile']);
$stmt->execute();
$bookid = $db->lastInsertId();

foreach ($seatsbooknew as $snew) {
$stmt = $db->prepare("insert into Booking_Seat (Booking, Seat) VALUES (?,?)");
$stmt->bindParam(1, $bookid);
$stmt->bindParam(2, $snew);
$stmt->execute();
}
 $stmt = $db->prepare("delete from Token WHERE Mobile=?");
$stmt->bindParam(1, $_SESSION['mobile']);
$stmt->execute();
$db->commit();
} catch(PDOException $ex) {
$db->rollBack();
$cancelbooking=true;
}
}
if (!$cancelbooking) {
?>
<div class="row">
Booking added successfully!
</div>
<div class="row">
<a class="btn btn-default" href="?page=bookings_details&event=<?echo($_GET['event']);?>">Back</a>
</div>
<?
} else {
?>
<div class="row">
Error while adding booking.
</div>
<div class="row">
<a class="btn btn-default" href="?page=bookings_add&event=<?echo($_GET['event']);?>">Retry</a>
</div>
<?
}
}
?>