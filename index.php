<!DOCTYPE html>
<html lang = "en">
	<head>
		<title>ניהול קבצים</title>
		<meta charset = "utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel = "stylesheet" type="text/css" href="admin/css/bootstrap.css" />
		<link rel = "stylesheet" type="text/css" href="admin/css/style.css" />
	</head>
<body>
<?php 
	require_once 'environment.php';
	?>
	<nav class="navbar navbar-default navbar-fixed-top" style="background-color:<?php 	echo $_ENV["ENV_COLOR"];	?>;">
		<div class="container-fluid">
			<label class="navbar-brand" id="title">ניהול קבצים</label>
		</div>
	</nav>
	<?php include 'login.php'?>
	<div id = "footer">
		<label class = "footer-title"><?php echo date("Y", strtotime("+8 HOURS"))?></label>
	</div>
</body>
</html>