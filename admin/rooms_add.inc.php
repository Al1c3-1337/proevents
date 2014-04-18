<?PHP
if (!defined('OK')) die();
if ($_POST['add'] != 1) {
?>
<div class="row">
<form role="form" action="?page=rooms_add" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="roomName">Room name</label>
    <input type="text" class="form-control" id="roomName" name="room" placeholder="Enter room name">
  </div>
  <div class="form-group">
    <label for="background">Background for seating map</label>
    <input type="file" id="background" name="background">
  </div>
  <button type="submit" class="btn btn-default">Add</button>
  <input type="hidden" name="add" value="1">
</form>
</div>
<?
} else {
$result=0;
if ($_POST['room'] != "" && $_FILES['background'] != null) 
{
$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
$detectedType = exif_imagetype($_FILES['background']['tmp_name']);
if (in_array($detectedType, $allowedTypes)) {
$size = getimagesize($_FILES['background']['tmp_name']);
$filePointer = fopen($_FILES['background']['tmp_name'], 'rb');
$stmt = $db->prepare("insert into Room (Name, Background, Width, Height) values (?,?,?,?)");
$stmt->bindParam(1, $_POST['room']);
$stmt->bindParam(2, $filePointer, PDO::PARAM_LOB);
$stmt->bindParam(3, $size[0]);
$stmt->bindParam(4, $size[1]);
$stmt->execute();
$result=1;
 }
}
if ($result==1) {
?>
<div class="row">
Room added successfully!
</div>
<div class="row">
<a class="btn btn-default" href="?page=rooms">Back</a>
</div>
<?
} else {
?>
<div class="row">
Error while adding room.
</div>
<div class="row">
<a class="btn btn-default" href="?page=rooms_add">Retry</a>
</div>
<?
}
}
?>