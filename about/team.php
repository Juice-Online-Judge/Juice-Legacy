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
		<title>關於團隊</title>
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
			<div id="main">
				<div>
					<div>
						<h1 class="title">關於團隊</h1>
					</div>
					<div>
						<div>
							<div>
								<h2 class="title">Product Manager</h2>
							</div>
							<div>
							</div>
						</div>
						<div>
							<div>
								<h2 class="title">行政部</h2>
							</div>
							<div>
							</div>
						</div>
						<div>
							<div>
								<h2 class="title">課程部</h2>
							</div>
							<div>
							</div>
						</div>
						<div>
							<div>
								<h2 class="title">網頁部</h2>
							</div>
							<div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){
			});
		</script>
	</body>
</html>