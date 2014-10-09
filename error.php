<?php
	if (isset($_GET["message"])) {
		
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
<?php if (!isset($_GET['no_transfer'])) { ?>
		<meta http-equiv="refresh" content="2;url=http://crux.coder.tw/freedom/juice/index.php">
<?php } ?>
		<title>Juice</title>
	</head>
	<body>
		<div>
			<h1><?php echo $_GET["message"]; ?></h1>
		</div>
	</body>
</html>
<?php
	} else {
		header("Location: http://crux.coder.tw/freedom/juice/index.php");
	}
?>