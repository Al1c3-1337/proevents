<?php
require("../config.inc.php");
session_start();
define("OK",TRUE);
$result = 0;
if (isset($_SESSION["admin"])) {
switch($_GET['a']) 
{
case 'delroom':
if (is_numeric($_GET['i'])) {
$stmt = $db->prepare("DELETE FROM Room WHERE ID=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$affected_rows = $stmt->rowCount();
$stmt = $db->prepare("DELETE FROM Seat WHERE Room=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$affected_rows += $stmt->rowCount();

$stmt = $db->prepare("SELECT ID FROM Event WHERE Room=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$rowER = $stmt->fetch(PDO::FETCH_ASSOC);
if ($stmt->rowCount() > 0) {

$stmt = $db->prepare("DELETE FROM Event WHERE ID=?");
$stmt->bindValue(1, $rowER['ID']);
$stmt->execute();

$stmt = $db->prepare("SELECT ID FROM Booking WHERE Event=?");
$stmt->bindValue(1, $rowER['ID']);
$stmt->execute();
$rowERX = $stmt->fetch(PDO::FETCH_ASSOC);
if ($stmt->rowCount() > 0) {
$stmt = $db->prepare("DELETE FROM Booking WHERE ID=?");
$stmt->bindValue(1, $rowERX['ID']);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM Booking_Seat WHERE Booking=?");
$stmt->bindValue(1, $rowERX['ID']);
$stmt->execute();
}
}
$result = 1;
}
echo (json_encode(array('result' => $result, 'rows' => $affected_rows)));
break;
case 'delbooking':
if (is_numeric($_GET['i'])) {
$stmt = $db->prepare("DELETE FROM Booking WHERE ID=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM Booking_Seat WHERE Booking=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$result=1;
}
echo (json_encode(array('result' => $result)));
break;
case 'delblmail':
if (is_numeric($_GET['i'])) {
$stmt = $db->prepare("DELETE FROM Blacklist_Mail WHERE ID=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
case 'delblmobile':
if (is_numeric($_GET['i'])) {
$stmt = $db->prepare("DELETE FROM Blacklist_Mobile WHERE ID=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
case 'delblname':
if (is_numeric($_GET['i'])) {
$stmt = $db->prepare("DELETE FROM Blacklist_Name WHERE ID=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
case 'addblmail':
if (isset($_GET['mail'])) {
$stmt = $db->prepare("INSERT INTO Blacklist_Mail(Mail) VALUES(?)");
$stmt->bindValue(1, strtolower($_GET['mail']));
$stmt->execute();
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
case 'addblmobile':
if (isset($_GET['mobile'])) {
$stmt = $db->prepare("INSERT INTO Blacklist_Mobile(Mobile) VALUES(?)");
$stmt->bindValue(1, strtolower($_GET['mobile']));
$stmt->execute();
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
case 'addblname':
if (isset($_GET['name']) && isset($_GET['firstname'])) {
$stmt = $db->prepare("INSERT INTO Blacklist_Name(Name,Firstname) VALUES(?,?)");
$stmt->bindValue(1, strtolower($_GET['name']));
$stmt->bindValue(2, strtolower($_GET['firstname']));
$stmt->execute();
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
case 'delevent':
if (is_numeric($_GET['i'])) {
$stmt = $db->prepare("DELETE FROM Event WHERE ID=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();

$stmt = $db->prepare("SELECT ID FROM Booking WHERE Event=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$rowER = $stmt->fetch(PDO::FETCH_ASSOC);
if ($stmt->rowCount() > 0) {
$stmt = $db->prepare("DELETE FROM Booking WHERE ID=?");
$stmt->bindValue(1, $rowER['ID']);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM Booking_Seat WHERE Booking=?");
$stmt->bindValue(1, $rowER['ID']);
$stmt->execute();
}
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
case 'tgleventvisible':
if (is_numeric($_GET['i'])) {
$stmt = $db->prepare("UPDATE Event SET Visible = NOT Visible WHERE ID=?");
$stmt->bindValue(1, $_GET['i']);
$stmt->execute();
$result = 1;
}
echo (json_encode(array('result' => $result)));
break;
}
}
?>