<?PHP
if (!defined('OK')) die();
?>

<div class="row">
<table class="table">
<tr><th>Name</th><th>Room</th><th>Bookings</th><th>Starts</th></tr>
<?
foreach ($db->query("SELECT Event.ID,Event.Name,Event.Start,Event.End,Event.Visible, Room.Name AS Room, Count(Booking_Seat.Seat) AS Bookings FROM Event LEFT JOIN Room ON  Room.ID = Event.Room LEFT JOIN Booking ON Booking.Event = Event.ID LEFT JOIN Booking_Seat ON Booking_Seat.Booking = Booking.ID GROUP BY Event.ID ORDER BY Event.Start DESC") as $row) {
?><tr><td><?echo($row['Name']);?></td><td><?echo($row['Room']);?></td><td><?echo($row['Bookings']);?></td><td><?echo($row['Start']);?></td><td><a href="?page=bookings_details&event=<?echo($row['ID']);?>"><span class="glyphicon glyphicon-folder-open"></span></a></td></tr>
<?
}
?>
</table>
</div>

