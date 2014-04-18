<?PHP
require("config.inc.php");
session_start();
define("OK",TRUE);
setlocale(LC_ALL, 'de_DE');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ProEvents</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.8, user-scalable=no">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootbox.min.js"></script>
	<link rel="stylesheet" href="css/dropPin.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap-formhelpers.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="js/dropPin.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/bootstrap-datetimepicker.min.js"></script>
	<script src="js/bootstrap-multiselect.js"></script>
	<script src="js/bootstrap-formhelpers.min.js"></script>
	<script src="js/jquery.json-2.4.min.js"></script>
  </head>
  <body>
  
<?
if (!isset($_GET["page"])) $_GET["page"] = "wizard1";
?>
<div class="container">
<?
$allowed_pages=array();
if (!isset($_SESSION['tokenok'])) {
$allowed_pages=array("wizard1","wizard2");
} else {
$allowed_pages=array("wizard1","wizard2","wizard3","wizard4");
}
if (in_array($_GET['page'],$allowed_pages)) require ($_GET['page'].".inc.php");
?>
</div>
</body>
</html>
<?

?>