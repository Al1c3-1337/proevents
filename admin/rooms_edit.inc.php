<?PHP
if (!defined('OK')) die();
if ($_POST['save'] != 1) {
if (isset($_GET['room'])) {
$stmt = $db->prepare("select Name, Width, Height from Room WHERE ID=?");
$stmt->bindParam(1, $_GET['room']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("select Seat,X,Y from Seat WHERE Room=?");
$stmt->bindParam(1, $_GET['room']);
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($row2 as $map)
	{
		$mapPins[] = array(
				"id" => $map['Seat'],
				"xcoord" => $map['X'],
				"ycoord" => $map['Y']
				);
	}
?>

<script type="text/javascript">
	$(document).ready(function() {

	    $('#map').dropPin('dropMulti',{
			  	fixedHeight:<?echo($row['Height']);?>,
			  	fixedWidth:<?echo($row['Width']);?>,
			  	cursor: 'crosshair',
				pin: '../img/MarkerGreen.png',
				backgroundImage: '../img_room.php?room=<?echo($_GET['room']);?>',
				xoffset : 12,
				yoffset : 12<? if(!$_GET['clear'] == 1) {?>,
				pinDataSet: <?php echo '{"markers": '.json_encode($mapPins).'}' ;?>
				<?}?>
		});

	});
	</script>

<div class="row">
<form role="form" action="?page=rooms_edit&room=<?echo($_GET['room']);?>" method="post">
  <div class="form-group">
    <label for="roomName">Room name</label>
    <input type="text" class="form-control" id="roomName" name="roomname" placeholder="Enter room name" value="<?echo($row['Name']);?>">
  </div>
  <div class="form-group">
    <div id="map"></div><a href="?page=rooms_edit&room=<?echo($_GET['room']);?>&clear=1" class="btn btn-danger" id="delseats">Clear</a>
  </div>
  <button type="submit" class="btn btn-default">Save</button>
  <input type="hidden" name="save" value="1">
  <input type="hidden" name="room" value="<?echo($_GET['room']);?>">
</form>
</div>
<?
}
} else {
$result = 1;
try {
$db->beginTransaction();
$stmt = $db->prepare("update Room Set Name=? WHERE ID=?");
$stmt->bindParam(1, $_POST['roomname']);
$stmt->bindParam(2, $_GET['room']);
$stmt->execute();
$stmt = $db->prepare("Delete from Seat WHERE Room=?");
$stmt->bindParam(1, $_GET['room']);
$stmt->execute();
for ($i = 0; $i < count($_POST['hiddenpin']); $i++) 
{
$coords = explode("#", $_POST['hiddenpin'][$i]);
	$stmt = $db->prepare("Insert Into Seat (Room, Seat, X, Y) VALUES (?,?,?,?)");
$stmt->bindParam(1, $_GET['room']);
$stmt->bindParam(2, $i);
$stmt->bindParam(3, $coords[0]);
$stmt->bindParam(4, $coords[1]);
$stmt->execute();
}
$db->commit();
} catch(PDOException $ex) {
$db->rollBack();
$result = 0;
}
if ($result==1) {
?>
<div class="row">
Room saved successfully!
</div>
<div class="row">
<a class="btn btn-default" href="?page=rooms">Back</a>
</div>
<?
} else {
?>
<div class="row">
Error while saving room.
</div>
<div class="row">
<a class="btn btn-default" href="?page=rooms_edit&room=<?echo($_GET['room']);?>">Retry</a>
</div>
<?
}
}
?>
