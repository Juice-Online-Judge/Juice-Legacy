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
								<h2 class="title">成員介紹</h2>
							</div>
							<div>
<?php
	/*
		groups : 0 -> PM, 1 -> administration, 2 -> lesson, 3 -> system, 4 -> website
	*/
	$i = 0;
	foreach ($result as $tmp) {
?>
								<div>
									<blockquote>
										<div id="member_<?php echo $i; ?>"><?php echo $tmp['user']; ?></div>
										<blockquote>
											<div class="more-less">
												<div id="introduction_<?php echo $i; ?>"><?php echo $tmp['content']; ?></div>
											</div>
										</blockquote>
									</blockquote>
								</div>
<?php
		$i++;
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
			function add_readmore(id,state) {
				id = '#' + id;
				$(id).hide();
				if(state==0)
				{
					var name = $(id).html();
					name.replace(/(<p>|<\/p>)/i, '');
					
					if(name.length >= 40) {
					/*$(id).text().substring(0,40);*/
					$(id).parent().append('<div class="readmore"><p>' + $(id).text().substring(0,40) + ' <a href="#">Readmore...</a></p></div>');
					} else {
					$(id).show();
					/*$(id).parent().append('<div class="readmore"><p><a href="#">Readmore...</a></p></div>');*/
					}
				} else {
					$(id).next().remove;
					$(id).parent().append('<div class="readmore"><p>' + $(id).text().substring(0,40) + ' <a href="#">Readmore...</a></p></div>');
				}
			}
			
			function add_readless(id) {
				id = '#' + id;
				$(id).next().remove();
				$(id).show(300);
				$(id).parent().append('<div class="readless"><p><a href="#">Readless...</a></p></div>');
			}
			
			$(document).ready(function(){
				var state = 0;
				state = $('div[id*="introduction"]').each(function(){
					add_readmore($(this).attr('id'),state);
				});
				
				state = $('.readmore').click(function(){
					add_readless($(this).prev().attr('id'),state);
					return 0;
				});
				
				state = $('.readless').click(function(){
					add_readmore($(this).prev().attr('id'));
					return 1;
				});
			});
		</script>
	</body>
</html>