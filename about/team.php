<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	$about = new about('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$result = $about->list_about(2);
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
			<div class="juice_body">
				<div class="u-1-5"></div>
				<div class="u-3-5 team_body">
					<div class="u-1-1">
						<h1 class="title">關於團隊</h1>
						<h2 class="title">成員介紹</h2>
					</div>
					<div class="u-1-1">
<?php
	$i = 0;
	foreach ($result as $tmp) {
?>
							<div class="retractable"><?php echo $tmp['nickname']; ?></div>
							<div id="introduction_<?php echo $i; ?>">
								<blockquote>
									<div>
<?php
		echo $tmp['content'];
?>
									</div>
								</blockquote>
							</div>
<?php
		$i++;
	}
?>
					</div>
				</div>
				<div class="u-1-5"></div>
			</div>
<?php display_footer($prefix); ?>
		<script>
			$(document).ready(function(){
				$('div[id*="introduction"]').each(function(){
					$(this).hide();
				});
				
				$('.retractable').click(function(){
					$('.retractable_hover').attr('class', 'retractable');
					$(this).attr('class', 'retractable retractable_hover');
					$(this).next().stop(true);
					$(this).next().toggle(300);
				});
			});
		</script>
	</body>
</html>