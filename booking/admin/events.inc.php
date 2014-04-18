<?PHP
if (!defined('OK')) die();
?>

<div class="row">
<table class="table">
<tr><th>Name</th><th>Visible</th><th>Room</th><th>Starts</th><th>Ends</th></tr>
<?
foreach ($db->query("SELECT Event.ID,Event.Name,Event.Start,Event.End,Event.Visible, Room.Name AS Room, Count(Booking.ID) AS Bookings FROM Event LEFT JOIN Room ON  Room.ID = Event.Room LEFT JOIN Booking ON Booking.Event = Event.ID GROUP BY Event.ID") as $row) {
?><tr><td><?echo($row['Name']);?></td><td><a class="tgleventvisible" href="#" data-row="<?echo($row['ID']);?>"><?echo(ord($row['Visible']) == "1" ? "Yes" : "No");?></a></td><td><?echo($row['Room']);?></td><td><?echo($row['Start']);?></td><td><?echo($row['End']);?></td><td><?if ($row['Bookings'] == 0) {?><a href="?page=events_edit&event=<?echo($row['ID']);?>"><span class="glyphicon glyphicon-edit"></span></a><?}?></td><td><a href="#" data-row="<?echo($row['ID']);?>" class="deleterow"><span class="glyphicon glyphicon-floppy-remove"></span></a></td></tr>
<?
}
?>
</table>
</div>
<div class="row">
<a class="btn btn-default" href="?page=events_add">Add Event</a>
</div>
<script>
        $(".deleterow").click(function(e) {
		row = $(this).data('row');
            bootbox.confirm("Are you sure?", function(result) {
 if (result === true) 
 {
	$.get( "action.php?a=delevent&i="+ row, function( data ) {
	location.reload();
});
 }
});
        });
		$(".tgleventvisible").click(function(e) {
		row = $(this).data('row');
        $.get( "action.php?a=tgleventvisible&i="+ row, function( data ) {
	    location.reload();
		});
		});
</script>
