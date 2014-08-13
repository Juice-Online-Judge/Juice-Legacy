<?php
	if (!isset($prefix)) {
		$prefix = './';
	}
	require_once $prefix.'config/web_preprocess.php';
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
					<p style="text-align:center; height:1000px;">I</p>
				</div>
				<div class="pure-u-2-3">
					<p style="text-align:center; height:1000px;">II</p>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
	</body>
</html>