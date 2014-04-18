<?PHP
require("../config.inc.php");
session_start();
define("OK",TRUE);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ProEvents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="admin.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
	<script src="../js/bootbox.min.js"></script>
	<link rel="stylesheet" href="../css/dropPin.css" type="text/css" />
	<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css" type="text/css" />
	<link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css" />
	<script src="../js/dropPin.js"></script>
	<script src="../js/moment.min.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
	<script src="../js/bootstrap-multiselect.js"></script>
	<script src="../js/jquery.json-2.4.min.js"></script>
  </head>
  <body>
  
<?
if (!isset($_SESSION["admin"])) {
require ("login.inc.php");
} else {
if (!isset($_GET["page"])) $_GET["page"] = "bookings";
?>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <a class="navbar-brand" href="#">ProEvents</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
    <ul class="nav navbar-nav">
      <li <? if(substr($_GET["page"],0,8) == "bookings") {?> class="active"<?}?>><a href="?page=bookings">Bookings</a></li>
      <li <? if(substr($_GET["page"],0,6) == "events") {?> class="active"<?}?>><a href="?page=events">Events</a></li>
      <li <? if(substr($_GET["page"],0,5) == "rooms") {?> class="active"<?}?>><a href="?page=rooms">Rooms</a></li>
	  <li <? if(substr($_GET["page"],0,10) == "blacklists") {?> class="active"<?}?>><a href="?page=blacklists">Blacklists</a></li>
    </ul>
</nav>
<div class="container">
<?
$allowed_pages=array("bookings","bookings_details","bookings_add","events","events_add","events_edit","rooms","rooms_add","rooms_edit","blacklists");
if (in_array($_GET['page'],$allowed_pages)) require ($_GET['page'].".inc.php");
?>
</div>
</body>
</html>
<?
}
?>