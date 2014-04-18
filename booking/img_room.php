<?php
require("config.inc.php");
$stmt = $db->prepare("select Background from Room WHERE ID=?");
$stmt->bindParam(1, $_GET['room']);
$stmt->execute();
$stmt->bindColumn(1, $lob, PDO::PARAM_LOB);
$stmt->fetch(PDO::FETCH_BOUND);

header("Content-Type: image");
echo($lob);
?>