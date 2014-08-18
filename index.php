<?php
	if (!isset($prefix)) {
		$prefix = './';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!isset($_SESSION['uid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>首頁</title>
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div class="pure-g">
				<div class="pure-u-1-3">
				</div>
				<div class="pure-u-2-3">
				</div>
			</div>
		</div>
<?php display_footer(); ?>
	</body>
</html>