<?PHP
if (!defined('OK')) die();

$stmt = $db->prepare("select Name, Description, Start, End, Visible, Room, Price from Event WHERE ID=?");
$stmt->bindParam(1, $_GET['event']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_GET['event']) && ord($row['Visible']) == "1") {
$stmt = $db->prepare("select Seat,X,Y from Seat WHERE Room=?");
$stmt->bindParam(1, $row['Room']);
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$seats_total = $stmt->rowCount();

$stmt = $db->prepare("select Booking_Seat.Seat FROM Booking_Seat INNER JOIN Booking ON Booking_Seat.Booking = Booking.ID WHERE Booking.Event=? GROUP BY Booking_Seat.Seat");
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
				selected => false
				);
	}
	
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
				enabled: "true"
		});

	});
	</script>
	
	<div class="row">
<h1>Reservierung</h1>
<div class="row">
<div class="col-xs-8">
	<form role="form" action="?page=wizard2&event=<?echo($_GET['event']);?>" method="post">
   <div class="form-group">
        <label for="eventname">Veranstaltung</label>
            <p id="eventname" ><?echo($row['Name']);?></p>
   </div>
    <div class="form-group">
        <label for="datefrom">Von</label>
            <p id="datefrom" ><?echo(strftime("%d.%m.%Y %H:%M",DateTime::createFromFormat("Y-m-d H:i:s", $row['Start'])->getTimestamp()));?></p>
    </div>
	<div class="form-group">
        <label for="dateto">Bis</label>
            <p id="dateto" ><?echo(strftime("%d.%m.%Y %H:%M",DateTime::createFromFormat("Y-m-d H:i:s", $row['End'])->getTimestamp()));?></p>
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
		<label for="mapinfo">Platzauswahl</label>
        <p id="mapinfo" >Bitte die Pl&auml;tze durch Anklicken der gr&uuml;nen Punkte ausw&auml;hlen (werden dann gelb).</p>
    <div id="map"></div>
  </div>
  <button type="submit" class="btn btn-success btn-lg btn-block">Reservieren</button>
</form>
	</div>
	</div>
	</div>	

<?
}
?>
