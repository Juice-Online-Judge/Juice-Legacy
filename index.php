<?php
	if (!isset($prefix)) {
		$prefix = './';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	page_check('index');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>Juice - <?php echo $_SESSION['nickname']; ?></title>
<?php display_link('css'); ?>
<?php display_link('js'); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div class="juice_body">
			<div class="u-1-3">
			</div>
			<div class="u-2-3">
			</div>
		</div>
<?php display_footer($prefix); ?>
	</body>
</html>