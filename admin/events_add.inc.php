<?PHP
if (!defined('OK')) die();
if ($_POST['add'] != 1) {
?>
<div class="row">
<form role="form" action="?page=events_add" method="post">
  <div class="form-group">
    <label for="eventName">Event name</label>
    <input type="text" class="form-control" id="eventName" name="eventName" placeholder="Enter event name" required>
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" rows="5" id="description" name="description" required></textarea>
  </div>
  <div class="form-group">
    <label for="price">Price per seat</label>
	<div class="input-group">
  <span class="input-group-addon">EUR</span>
  <input type="text" class="form-control" id="price" name="price" placeholder="0,0" required>
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
      <input type="checkbox" name="visible"> Event visible
    </label>
  </div>
  <div class="form-group">
  <label for="room">Room</label>
  <div class="input-group btn-group">
							<span class="input-group-addon"><b class="glyphicon glyphicon-list-alt"></b></span>
							<select id="room" name="room" required>
							<?
							foreach ($db->query("SELECT Room.ID,Room.Name from Room") as $row) {
							?>
								<option value="<?echo($row['ID']);?>"><?echo($row['Name']);?></option>
							<?
							}
							?>
							</select>
						</div>
  </div>
  <button type="submit" class="btn btn-default">Add</button>
  <input type="hidden" name="add" value="1">
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
} else {
$result=0;
if ($_POST['eventName'] != "" && $_POST['description'] != "" && $_POST['dateStart'] != "" && $_POST['dateEnd'] != "" && isset($_POST['room'])) 
{
$visible = isset($_POST['Visible']) ? 1 : 0;
$dateS = DateTime::createFromFormat('D M d Y H:i:s T', $_POST['dateStart']);
$dateE = DateTime::createFromFormat('D M d Y H:i:s T', $_POST['dateEnd']);
$stmt = $db->prepare("insert into Event (Name, Description, Start, End, Visible, Room, Price) values (?,?,?,?,?,?,?)");
$stmt->bindParam(1, $_POST['eventName']);
$stmt->bindParam(2, $_POST['description']);
$stmt->bindParam(3, $dateS->format('Y-m-d H:i:s'));
$stmt->bindParam(4, $dateE->format('Y-m-d H:i:s'));
$stmt->bindParam(5, $visible);
$stmt->bindParam(6, $_POST['room']);
$stmt->bindParam(7, $_POST['price']);
$stmt->execute();
$result=1;

}
if ($result==1) {
?>
<div class="row">
Event added successfully!
</div>
<div class="row">
<a class="btn btn-default" href="?page=events">Back</a>
</div>
<?
} else {


?>
<div class="row">
Error while adding event.
</div>
<div class="row">
<a class="btn btn-default" href="?page=events_add">Retry</a>
</div>
<?
}
}
?>