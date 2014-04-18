<?PHP
if (!defined('OK')) die();
require('lib/sms.inc.php');
function printMobileNr($mobile) {
return preg_replace("/^0(\d{3,3})(\d*)/", "+49 $1 $2", $mobile);
}
function validateMail($mail) {
return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($mail));
}
if($_SESSION['tokenok'] == true) {
$stmt = $db->prepare("select Name, Description, Start, End, Visible, Room, Price from Event WHERE ID=?");
$stmt->bindParam(1, $_SESSION['event']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_SESSION['event']) && ord($row['Visible']) == "1") {
$stmt = $db->prepare("select Seat,X,Y from Seat WHERE Room=?");
$stmt->bindParam(1, $row['Room']);
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$seats_total = $stmt->rowCount();

$stmt = $db->prepare("select Booking_Seat.Seat FROM Booking_Seat INNER JOIN Booking ON Booking_Seat.Booking = Booking.ID WHERE Booking.Event=? GROUP BY Booking_Seat.Seat ");
$stmt->bindParam(1, $_SESSION['event']);
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
				"selected" => in_array($map['Seat'],$_SESSION['map'])
				);
	}
if(!isset($_POST['book'])) {

?>
	<script type="text/javascript">
	$(document).ready(function() {

	    $('#map').dropPin('showPins',{
			  	fixedHeight:<?echo($row4['Height']);?>,
			  	fixedWidth:<?echo($row4['Width']);?>,
			  	cursor: '',
				pin: 'img/MarkerGreen.png',
				redPin: 'img/MarkerRed.png',
				yellowPin: 'img/MarkerYellow.png',
				backgroundImage: 'img_room.php?room=<?echo($row['Room']);?>',
				xoffset : 12,
				yoffset : 12,
				pinDataSet: <?php echo '{"markers": '.json_encode($mapPins).'}' ;?>,
				enabled: "false"
		});

	});
	</script>
<div class="row">
<h1>Reservierung</h1>
<div class="row">
<div class="col-xs-8">
	<form role="form" action="?page=wizard3&event=<?echo($_SESSION['event']);?>" method="post">
   <div class="form-group">
        <label for="eventname">Veranstaltung</label>
            <p id="eventname" ><?echo($row['Name']);?></p>
   </div>
    <div class="form-group">
        <label for="date">Datum</label>
            <p id="date" ><?echo(strftime("%d.%m.%Y %H:%M",DateTime::createFromFormat("Y-m-d H:i:s", $row['Start'])->getTimestamp()));?></p>
    </div>
    <div class="form-group">
        <label for="price">Preis / Platz</label>
            <p id="price" >EUR <?echo($row['Price']);?></p>
    </div>
    <div class="form-group">
        <label for="location">Ort</label>
            <p id="location" ><?echo($row4['Name']);?></p>
    </div>
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
    <input type="text" class="form-control" id="mobile" disabled="disabled" value="<?echo(printMobileNr($_SESSION['mobile']));?>">
  </div>
  <div class="form-group">
    <div id="map"></div>
  </div>
  <button type="submit" class="btn btn-success btn-lg btn-block">Verbindlich reservieren</button>
  <input type="hidden" name="book" value="1">
</form>
	</div>
	</div>
	</div>

<?
} else {
if (isset($_SESSION['map']) && isset($_SESSION['event']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['mail']) && $_POST['firstname'] != "" && $_POST['lastname'] != "" && $_POST['mail'] != "" && validateMail($_POST['mail'])){
$blacklisted = false;

$stmt = $db->prepare("select ID from Blacklist_Mail WHERE Mail=?");
$stmt->bindParam(1, strtolower($_POST['mail']));
$stmt->execute();
$blacklisted = $blacklisted || ($stmt->rowCount() > 0);

$stmt = $db->prepare("select ID from Blacklist_Name WHERE Name=? AND Firstname=?");
$stmt->bindParam(1, strtolower($_POST['lastname']));
$stmt->bindParam(2, strtolower($_POST['firstname']));
$stmt->execute();
$blacklisted = $blacklisted || ($stmt->rowCount() > 0);
if (!$blacklisted) {
$cancelbooking = false;
try {
$db->beginTransaction();
$stmt = $db->prepare("insert into Booking (Event, Name, Firstname, Mail, Mobile, IP) VALUES (?,?,?,?,?,?)");
$stmt->bindParam(1, $_SESSION['event']);
$stmt->bindParam(2, $_POST['lastname']);
$stmt->bindParam(3, $_POST['firstname']);
$stmt->bindParam(4, $_POST['mail']);
$stmt->bindParam(5, $_SESSION['mobile']);
$stmt->bindParam(6, $_SERVER['REMOTE_ADDR']);
$stmt->execute();
$bookid = $db->lastInsertId();

foreach ($_SESSION['map'] as $snew) {
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


if (!$cancelbooking) {
sendConfirmation($_POST['mail'],$_POST['lastname'],$_POST['firstname'],$row['Name'],$row4['Name'],strftime("%d.%m.%Y %H:%M",DateTime::createFromFormat("Y-m-d H:i:s", $row['Start'])->getTimestamp()),strftime("%d.%m.%Y %H:%M",DateTime::createFromFormat("Y-m-d H:i:s", $row['End'])->getTimestamp()),$_SESSION['map'],$_SESSION['mobile']);
unset($_SESSION['tokenok']);
unset($_SESSION['mobile']);
unset($_SESSION['map']);
unset($_SESSION['event']);
?>
<div class="jumbotron">
<h1>Reservierung</h1>
<p>Vielen Dank f&uuml;r Deine Reservierung! Die Reservierungsbest&auml;tigung geht Dir per E-Mail zu.</p>
</div>
<?

} else {
?>
<div class="jumbotron">
<h1>Reservierung</h1>
<p>Die Reservierung ist leider fehlgeschlagen. Bitte versuche es erneut</p>
<a href="?page=wizard3&event=<?echo($_SESSION['event']);?>" class="btn btn-success">OK</a>
</div>
<?
}
} else {
?>
<div class="jumbotron">
<h1>Reservierung</h1>
<div class="alert alert-danger">Du wurdest wegen missbr&auml;uchlicher Verwendung des Systems gesperrt!</div>
<br>
</div>
<?
}
}
}
}
}
?>