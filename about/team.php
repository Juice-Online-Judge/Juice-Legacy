<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	$about = new about();
	$result = $about->list_about(2);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>關於團隊</title>
<?php display_link('css'); ?>
<?php display_link('js'); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
			<div class="juice_body">
				<div class="u-1-5"></div>
				<div class="u-3-5 team_body">
					<div class="u-1-1">
						<h2 class="title">團隊介紹</h2>
					</div>
					<div class="u-1-1">
<?php
	$i = 0;
	foreach ($result as $tmp) {
?>
							<div class="team_button"><?php echo $tmp['nickname']; ?></div>
							<div class="team_content" id="introduction_<?php echo $i; ?>">
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
				
				$('.team_button').click(function(){
					$(this).next().stop(true);
					$(this).next().toggle(300);
					$('.team_button_hover').next().toggle(300);
					$('.team_button_hover').attr('class','team_button');
					$(this).attr('class','team_button_hover');
				});
			});
		</script>
	</body>
</html>