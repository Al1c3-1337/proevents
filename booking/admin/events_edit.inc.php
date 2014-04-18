<?PHP
if (!defined('OK')) die();
if ($_POST['save'] != 1) {
if (isset($_GET['event'])) {
$stmt = $db->prepare("select Name, Description, Start, End, Visible, Room, Price from Event WHERE ID=?");
$stmt->bindParam(1, $_GET['event']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<div class="row">
<form role="form" action="?page=events_edit&event=<?echo($_GET['event']);?>" method="post">
  <div class="form-group">
    <label for="eventName">Event name</label>
    <input type="text" class="form-control" id="eventName" name="eventName" placeholder="Enter event name" value="<?echo($row['Name']);?>" required>
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" rows="5" id="description" name="description" required><?echo($row['Description']);?></textarea>
  </div>
  <div class="form-group">
    <label for="price">Price per seat</label>
	<div class="input-group">
  <span class="input-group-addon">EUR</span>
  <input type="text" class="form-control" id="price" name="price" placeholder="0,0" value="<?echo($row['Price']);?>" required>
</div>
    
  </div>
  <div class="form-group">
  <label for="dateStart">Start date</label>
  <div class='input-group date' id='datetimepicker1'>
	
    <input type='text' class="form-control" id="dateStart" required/>
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
    </span>
  </div>
  </div>
  <div class="form-group">
  <label for="dateEnd">End date</label>
  <div class='input-group date' id='datetimepicker2'>
	
    <input type='text' class="form-control" id="dateEnd" required/>
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
    </span>
  </div>
  </div>
  <div class="form-group">
    <label>
      <input type="checkbox" name="visible" <?echo(ord($row['Visible']) == "1" ? 'checked="checked"' : "");?>> Event visible
    </label>
  </div>
  <div class="form-group">
  <label for="room">Room</label>
  <div class="input-group btn-group">
							<span class="input-group-addon"><b class="glyphicon glyphicon-list-alt"></b></span>
							<select id="room" name="room" required>
							<?
							foreach ($db->query("SELECT Room.ID,Room.Name from Room") as $row2) {
							?>
								<option value="<?echo($row2['ID']);?>" <?echo($row['Room'] == $row2['ID'] ? 'selected="selected"' : "");?>><?echo($row2['Name']);?></option>
							<?
							}
							?>
							</select>
						</div>
  </div>
  <div class="form-group">
  <label for="hyperlink">Link</label>
  <a href="http://www.angelteichanlage.de/booking/?event=<?echo($_GET['event']);?>">http://www.angelteichanlage.de/booking/?event=<?echo($_GET['event']);?></a>
  </div>
  <button type="submit" class="btn btn-default">Save</button>
  <input type="hidden" name="save" value="1">
  <input type="hidden" name="dateStart" id="dateStartV">
  <input type="hidden" name="dateEnd" id="dateEndV">
</form>
</div>
<script type="text/javascript">
            $(function () {
                $("#datetimepicker1").datetimepicker({
                    language: 'de'
                });
                $("#datetimepicker2").datetimepicker({
                    language: 'de'
                });
            $("#datetimepicker1").data("DateTimePicker").setDate("<?echo($row['Start']);?>");
			$("#datetimepicker2").data("DateTimePicker").setDate("<?echo($row['End']);?>");
			$("#dateStartV").val($("#datetimepicker1").data("DateTimePicker").getDate());
			$("#dateEndV").val($("#datetimepicker2").data("DateTimePicker").getDate());
			$("#datetimepicker1").on("change.dp",function (e) {
			   $("#datetimepicker2").data("DateTimePicker").setStartDate($("#datetimepicker1").data("DateTimePicker").getDate());
			   $("#dateStartV").val($("#datetimepicker1").data("DateTimePicker").getDate());
            });
			$("#datetimepicker2").on("change.dp",function (e) {
			   $("#dateEndV").val($("#datetimepicker2").data("DateTimePicker").getDate());
            });
			$('#room').multiselect();
			});
        </script>
<?
}
} else {
$result=0;
if ($_POST['eventName'] != "" && $_POST['description'] != "" && $_POST['dateStart'] != "" && $_POST['dateEnd'] != "" && isset($_POST['room']) & isset($_GET['event'])) 
{
$visible = isset($_POST['Visible']) ? 1 : 0;
$dateS = DateTime::createFromFormat('D M d Y H:i:s T', $_POST['dateStart']);
$dateE = DateTime::createFromFormat('D M d Y H:i:s T', $_POST['dateEnd']);
$stmt = $db->prepare("update Event SET Name=?, Description=?, Start=?, End=?, Visible=?, Room=?, Price=? WHERE ID=?");
$stmt->bindParam(1, $_POST['eventName']);
$stmt->bindParam(2, $_POST['description']);
$stmt->bindParam(3, $dateS->format('Y-m-d H:i:s'));
$stmt->bindParam(4, $dateE->format('Y-m-d H:i:s'));
$stmt->bindParam(5, $visible);
$stmt->bindParam(6, $_POST['room']);
$stmt->bindParam(7, $_POST['price']);
$stmt->bindParam(8, $_GET['event']);
$stmt->execute();
$result=1;
}
if ($result==1) {
?>
<div class="row">
Event saved successfully!
</div>
<div class="row">
<a class="btn btn-default" href="?page=events">Back</a>
</div>
<?
} else {


?>
<div class="row">
Error while saving event.
</div>
<div class="row">
<a class="btn btn-default" href="?page=events_edit?event=<?echo($_GET['event']);?>">Retry</a>
</div>
<?
}
}
?>