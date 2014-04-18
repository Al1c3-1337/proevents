<?PHP
if (!defined("OK")) die();
if ($_POST["pass"] != $admin_pass) {
	if (isset($_POST["pass"])) {
	?>
	<div class="alert alert-danger">You entered the wrong password!</div>
	<?
	}
	?>
	<div class="container">
		<form class="form-signin" role="form" method="post">
			<h2 class="form-signin-heading">Please sign in</h2>
			<input name="pass" type="password" class="form-control" placeholder="Password" required>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>
	</div>

<?
} else {
$_SESSION["admin"] = 1;
?>
<body>
<script type="text/javascript">
$(document).ready(function() {
location.reload();
})
</script>
Redirecting...
</body>
<?
}
?>
</body>
</html>
