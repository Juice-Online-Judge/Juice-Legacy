<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>關於本站</title>
<?php display_link('css'); ?>
<?php display_link('js'); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
			<div class="juice_body">
				<div>
					<div>
						<h1 class="title">關於本站</h1>
					</div>
					<div>
					</div>
				</div>
			</div>
<?php display_footer($prefix); ?>
	</body>
</html>