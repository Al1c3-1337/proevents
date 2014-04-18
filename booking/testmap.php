<?PHP
require('config.inc.php');

$stmt = $db->prepare("select Name, Width, Height from Room WHERE ID=?");
$stmt->bindParam(1, $_GET['room']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


$stmt = $db->prepare("select Seat,X,Y from Seat WHERE Room=?");
$stmt->bindParam(1, $_GET['room']);
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($row2 as $map)
	{
		$mapPins[] = array(
				"id" => $map['Seat'],
				"xcoord" => $map['X'],
				"ycoord" => $map['Y'],
				"enabled" => "false"
				);
	}
?>
<HTML>
  <head>
    <title>ProEvents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
	<script src="js/dropPin.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/bootstrap-datetimepicker.min.js"></script>
	<script src="js/bootstrap-multiselect.js"></script>
  </head>
<Body>
<script type="text/javascript">
	$(document).ready(function() {

	    $('#map').dropPin('showPins',{
			  	fixedHeight:<?echo($row['Height']);?>,
			  	fixedWidth:<?echo($row['Width']);?>,
			  	cursor: '',
				pin: 'img/MarkerGreen.png',
				redPin: 'img/MarkerRed.png',
				yellowPin: 'img/MarkerYellow.png',
				backgroundImage: 'img_room.php?room=<?echo($_GET['room']);?>',
				xoffset : 12,
				yoffset : 12<? if(!$_GET['clear'] == 1) {?>,
				pinDataSet: <?php echo '{"markers": '.json_encode($mapPins).'}' ;?>
				<?}?>
		});

	});
	</script>
	<div id="map"></div>
	</Body>
	</HTML>