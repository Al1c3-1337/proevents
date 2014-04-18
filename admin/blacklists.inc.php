<?PHP
if (!defined('OK')) die();
?>
<div class="row">
<h2>E-Mail Blacklist</h2>
<table class="table">
<tr><th>E-Mail</th></tr>
<?
foreach ($db->query("SELECT ID,Mail from Blacklist_Mail") as $row) {
?><tr><td><?echo($row['Mail']);?></td><td><a href="#" data-row="<?echo($row['ID']);?>" class="deletemail"><span class="glyphicon glyphicon-floppy-remove"></span></a></td></tr>
<?
}
?>
</table>
<button class="btn btn-default" id="addmail">Add E-Mail</button>
</div>

<div class="row">
<h2>Mobile Blacklist</h2>
<table class="table">
<tr><th>Mobile #</th></tr>
<?
foreach ($db->query("SELECT ID,Mobile from Blacklist_Mobile") as $row) {
?><tr><td><?echo($row['Mobile']);?></td><td><a href="#" data-row="<?echo($row['ID']);?>" class="deletemobile"><span class="glyphicon glyphicon-floppy-remove"></span></a></td></tr>
<?
}
?>
</table>
<button class="btn btn-default" id="addmobile">Add Mobile #</button>
</div>

<div class="row">
<h2>Name Blacklist</h2>
<table class="table">
<tr><th>Name</th><th>Firstname</th></tr>
<?
foreach ($db->query("SELECT ID,Name,Firstname from Blacklist_Name") as $row) {
?><tr><td><?echo($row['Name']);?></td><td><?echo($row['Firstname']);?></td><td><a href="#" data-row="<?echo($row['ID']);?>" class="deletename"><span class="glyphicon glyphicon-floppy-remove"></span></a></td></tr>
<?
}
?>
</table>
<button class="btn btn-default" id="addname">Add Name</button>
</div>

<script>
        $(".deletemail").click(function(e) {
		row = $(this).data('row');
            bootbox.confirm("Are you sure?", function(result) {
 if (result === true) 
 {
	$.get( "action.php?a=delblmail&i="+ row, function( data ) {
	location.reload();
});
 }
});
        });

        $(".deletemobile").click(function(e) {
		row = $(this).data('row');
            bootbox.confirm("Are you sure?", function(result) {
 if (result === true) 
 {
	$.get( "action.php?a=delblmobile&i="+ row, function( data ) {
	location.reload();
});
 }
});
        });
		$(".deletename").click(function(e) {
		row = $(this).data('row');
            bootbox.confirm("Are you sure?", function(result) {
 if (result === true) 
 {
	$.get( "action.php?a=delblname&i="+ row, function( data ) {
	location.reload();
});
 }
});
        });
		
		$("#addmail").click(function(e) {
		row = $(this).data('row');
            bootbox.prompt("Which E-Mail do you want to blacklist?", function(result) {
 if (result != null) 
 {
	$.get( "action.php?a=addblmail&mail="+ result, function( data ) {
	location.reload();
});
 }
});
        });
		
		$("#addmobile").click(function(e) {
		row = $(this).data('row');
            bootbox.prompt("Which Mobile # do you want to blacklist?", function(result) {
 if (result != null) 
 {
	$.get( "action.php?a=addblmobile&mobile="+ result, function( data ) {
	location.reload();
});
 }
});
        });
		
		$("#addname").click(function(e) {
		row = $(this).data('row');
            bootbox.prompt("Please enter the family name", function(result) {
 if (result != null) 
 {
	bootbox.prompt("Please enter the first name", function(result2) {
 if (result2 != null) 
 {
	$.get( "action.php?a=addblname&name="+ result + "&firstname=" + result2, function( data ) {
	location.reload();
});
}});
 }});
        });
</script>