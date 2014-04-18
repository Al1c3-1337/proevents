<?PHP
if (!defined('OK')) die();
?>

<div class="row">
<table class="table">
<tr><th>Name</th><th>Seats</th></tr>
<?
foreach ($db->query("SELECT Room.ID, Room.Name, COUNT(Seat.ID) As Seats FROM Room LEFT JOIN Seat ON Seat.Room = Room.ID GROUP BY Room.ID") as $row) {
?><tr><td><?echo($row['Name']);?></td><td><?echo($row['Seats']);?></td><td><a href="?page=rooms_edit&room=<?echo($row['ID']);?>"><span class="glyphicon glyphicon-edit"></span></a></td><td><a href="#" data-row="<?echo($row['ID']);?>" class="deleterow"><span class="glyphicon glyphicon-floppy-remove"></span></a></td></tr>
<?
}
?>
</table>
</div>
<div class="row">
<a class="btn btn-default" href="?page=rooms_add">Add Room</a>
</div>
<script>
        $(".deleterow").click(function(e) {
		row = $(this).data('row');
            bootbox.confirm("Are you sure?", function(result) {
 if (result === true) 
 {
	$.get( "action.php?a=delroom&i="+ row, function( data ) {
	location.reload();
});
 }
});
        });
</script>
