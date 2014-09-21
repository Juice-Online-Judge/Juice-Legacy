<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	$error = false;
	
	$solve_status = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$list_implement = $solve_status->list_implement();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>解題動態</title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div class="pure-g">
<?php if ($error) { ?>
				<div>
					<h2 class="warning center"><?php echo $message; ?></h2>
				</div>
<?php } else { ?>
				<div class="pure-u-1-5">
					<div class="blankblock">
					</div>
				</div>
				<div class="pure-u-4-5">
					<ul id="status_list">
						<li onClick="displacement(0);">課程</li>
						<li onClick="displacement(1);">闖關</li>
					</ul>
					<div class="blankblock">
						<div id="content_float">
							<div id="course">
								<div id="implement_list">
<?php
		$last_unit = -1;
		foreach ($list_implement as $temp) {
			if ($temp['lesson_id'] != $last_unit) {
				$i = 1;
				$last_unit = $temp['lesson_id'];
			}
?>
									<div onClick="implement_query('<?php echo $temp['implement_key']; ?>', 1);"><?php echo '單元 '.$temp['lesson_unit'].' - 第 '.$i.' 題'?></div>
<?php
			$i++;
		}
?>
								</div>
								<div id="implement_detail">
									
								</div>
								<div>
								</div>
							</div>
							<div id="temp">
							</div>
						</div>
					</div>
				</div>
	<?php } ?>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			var submenu = ['course', 'temp'];
			
			function displacement(value) {
				var offset = (value) * (-100);
				$('#content_float').stop(true);
				$('#content_float').animate({
					marginLeft: offset+'%'
				}, 500);
				$('#content_float').animate({
					height: $('#'+course_submenu[value]).height()
				}, 300);
			}
			
			function implement_query(key, is_implement) {
				$.post(
					'<?php echo $prefix.'others/api/apiGetSolveStatus.php'; ?>',
					{
						key:key,
						is_implement:is_implement
					},
					function (data) {
						var content = '<table class="pure-table pure-table-bordered m-center">';
						content += '<thead><tr class="t-center"><th>#</th><th>Result</th><th>Memory Usage</th><th>Time Usage</th><th></th></tr></thead>';
						content += '<tbody>';
						for (var i = 0; i < Object.keys(data).length; i++) {
							content += '<tr>';
							content += '<td>' + (i + 1) + '</td>';
							content += '<td>' + data[i].result + '</td>';
							content += '<td>' + data[i].memory_usage + '</td>';
							content += '<td>' + data[i].time_usage + '</td>';
							content += '<td>' + data[i].code_key + '</td>';
							content += '</tr>';
						}
						content += '</tbody></table>';
						$("#implement_detail").empty();
						$("#implement_detail").append(content);
					}
				);
			}
			
			$(document).ready(function(){
				$('#content_float').animate({
					height: $('#course').height()
				}, 300);
			});
		</script>
	</body>
</html>