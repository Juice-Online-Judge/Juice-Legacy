<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	$about = new about('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$result = $about->show_groups(1);
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
			<div class="pure-g">
				<div class="pure-u-1-5"></div>
				<div class="pure-u-3-5">
					<div>
						<h1 class="title">關於團隊</h1>
					</div>
					<div>
						<div>
							<div>
								<h2 class="title">Product Manager</h2>
							</div>
							<div>
<?php
	/*
		groups : 0 -> PM, 1 -> administration, 2 -> lesson, 3 -> website
	*/
	foreach ($result as $tmp) {
		if ($tmp['groups'] == 0) {
?>
								<div>
									<div><?php echo $tmp['user']; ?></div>
									<div><?php echo $tmp['content']; ?></div>
								</div>
<?php
		}
	}
?>
							</div>
						</div>
						<div>
							<div>
								<h2 class="title">行政部</h2>
							</div>
							<div>
<?php
	foreach ($result as $tmp) {
		if ($tmp['groups'] == 1) {
?>
								<div>
									<div><?php echo $tmp['user']; ?></div>
									<div><?php echo $tmp['content']; ?></div>
								</div>
<?php
		}
	}
?>
							</div>
						</div>
						<div>
							<div>
								<h2 class="title">課程部</h2>
							</div>
							<div>
<?php
	foreach ($result as $tmp) {
		if ($tmp['groups'] == 2) {
?>
								<div>
									<div><?php echo $tmp['user']; ?></div>
									<div><?php echo $tmp['content']; ?></div>
								</div>
<?php
		}
	}
?>
							</div>
						</div>
						<div>
							<div>
								<h2 class="title">網頁部</h2>
							</div>
							<div>
<?php
	foreach ($result as $tmp) {
		if ($tmp['groups'] == 3) {
?>
								<div>
									<div><?php echo $tmp['user']; ?></div>
									<div><?php echo $tmp['content']; ?></div>
								</div>
<?php
		}
	}
?>
							</div>
						</div>
					</div>
				</div>
				<div class="pure-u-1-5"></div>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){
			});
		</script>
	</body>
</html>