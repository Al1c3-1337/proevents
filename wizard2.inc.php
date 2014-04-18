<?PHP
if (!defined('OK')) die();
require('lib/sms.inc.php');
function generateRandomString($length = 5) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function validateMobileNr($mobile) {
return preg_match("/^01[5-7]\d{8,11}$/", $mobile);
}
function formatMobileNr($mobile) {
$mobile = preg_replace("/[^0-9]/", "", $mobile);
$mobile = preg_replace("/^(?:0|\+49)?([0-9]*)$/","0$1", $mobile);
return $mobile;
}
$evid = $_GET['event'];
if (isset($_SESSION['event'])) $evid = $_SESSION['event'];

$stmt = $db->prepare("select Name, Description, Start, End, Visible, Room, Price from Event WHERE ID=?");
$stmt->bindParam(1, $evid);
$stmt->execute();
$rowXYZ = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($evid) && ord($rowXYZ['Visible']) == "1") {
$stmt = $db->prepare("select Seat,X,Y from Seat WHERE Room=?");
$stmt->bindParam(1, $row['Room']);
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$seats_total = $stmt->rowCount();

$stmt = $db->prepare("select Booking_Seat.Seat FROM Booking_Seat INNER JOIN Booking ON Booking_Seat.Booking = Booking.ID WHERE Booking.Event=? GROUP BY Booking_Seat.Seat ");
$stmt->bindParam(1, $evid);
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


if (isset($_SESSION['tokenok'])) 
{
?>
<script type="text/javascript">
$(document).ready(function() {
location.replace("?page=wizard3");
})
</script>
Redirecting...
<?
} else {
$mobileok = false;
if (isset($_SESSION['mobile'])) {
$mobilenr = $_SESSION['mobile'];
$mobileok = true;
} else {
$mobilenr = formatMobileNr($_POST['mobile']);
$mobileok = validateMobileNr($mobilenr);
}
if($mobileok == false) {
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
$_SESSION['map'] = $seatsbooknew;
$_SESSION['event'] = $_GET['event'];
?>
<div class="jumbotron">
<h1>Reservierung</h1>
<?if(isset($_POST['mobile'])){?><div class="alert alert-danger">Du hast eine ung&uuml;ltige Handynummer angegeben!</div><?}?>
<p>Gib bitte deine Handynummer ein! Du erh&auml;ltst <strong>kostenlos</strong> einen Code zur &Uuml;berpr&uuml;fung deiner Handynummer!</p>
<form action="?page=wizard2" method="post" role="form">
<div class="form-group">
<div class="input-group input-group-lg">
<input name="mobile" type="text" data-format="dddd dddddddddd" class="form-control bfh-phone" placeholder="0175 123456" required>
</div>
</div>
<button class="btn btn-lg btn-success btn-block pull-right" type="submit">Anfordern</button>
</form><br>
</div>
<?
}
} else {
$_SESSION['mobile'] = $mobilenr;
if (!isset($_POST['token'])) {
$stmt = $db->prepare("select ID from Blacklist_Mobile WHERE Mobile=?");
$stmt->bindParam(1, $_SESSION['mobile']);
$stmt->execute();
$blacklisted = ($stmt->rowCount() > 0);
if (!$blacklisted) {
$stmt = $db->prepare("select Timestamp from Token WHERE Mobile=?");
$stmt->bindParam(1, $_SESSION['mobile']);
$stmt->execute();
$newtoken = false;
$errX = false;
if ($stmt->rowCount() > 0) {
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['Timestamp'] +1800 < time()) {
 $stmt = $db->prepare("delete from Token WHERE Mobile=?");
$stmt->bindParam(1, $_SESSION['mobile']);
$stmt->execute();
 $newtoken = true;
 
 }
} else {
$newtoken=true;
}
if ($newtoken == true) 
{
$token = generateRandomString();
$stmt = $db->prepare("INSERT INTO Token (Mobile,Token,Timestamp) VALUES(?,?,?)");
$stmt->bindParam(1, $_SESSION['mobile']);
$stmt->bindParam(2, $token);
$stmt->bindParam(3, time());
$stmt->execute();
if (!sendToken($_SESSION['mobile'],$token)) 
{
// Error
$errX = true;
}
}
?>
<div class="jumbotron">
<h1>Reservierung</h1>
<?if(!$newtoken){?><div class="alert alert-warning">Du hast vor kurzem schon einen Code angefordert. Bitte gib diesen ein oder warte ca. 30 min um einen neuen anzufordern!</div><?}?>
<p>Gib bitte den Code aus der SMS ein!</p>
<form action="?page=wizard2" method="post" role="form">
<div class="form-group">
<div class="input-group input-group-lg">
<input name="token" type="text" class="form-control" placeholder="CODE" required>
</div>
</div>
<button class="btn btn-lg btn-success btn-block pull-right" type="submit">&Uuml;berpr&uuml;fen</button>
</form><br>
</div>
<?
} else {
// Blacklisted
?>
<div class="jumbotron">
<h1>Reservierung</h1>
<div class="alert alert-danger">Du wurdest wegen missbr&auml;uchlicher Verwendung des Systems gesperrt!</div>
<br>
</div>
<?
}
} else {
// Check Token
$stmt = $db->prepare("select Token from Token WHERE Mobile=?");
$stmt->bindParam(1, $_SESSION['mobile']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (strtoupper($_POST['token']) == $row['Token']) 
{
$_SESSION['tokenok'] = true;
?>
<script type="text/javascript">
$(document).ready(function() {
location.replace("?page=wizard3");
})
</script>
Redirecting...
<?
} else {
?>
<div class="jumbotron">
<h1>Reservierung</h1>
<div class="alert alert-danger">Du hast den falschen Code eingegeben. Bitte gib den richtigen Code ein oder warte ca. 30 min um einen neuen anzufordern!</div>
<p>Gib bitte den Code aus der SMS ein!</p>
<form action="?page=wizard2" method="post" role="form">
<div class="form-group">
<div class="input-group input-group-lg">
<input name="token" type="text" class="form-control" placeholder="CODE" required>
</div>
</div>
<button class="btn btn-lg btn-success btn-block pull-right" type="submit">&Uuml;berpr&uuml;fen</button>
</form><br>
</div>
<?
}
}
}
}
}
?>