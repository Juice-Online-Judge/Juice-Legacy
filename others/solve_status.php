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
					<div id="status_profile" class="blankblock">
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
								<div id="status_option_table">
<?php
		$last_unit = -1;
		foreach ($list_implement as $temp) {
			if ($temp['lesson_id'] != $last_unit) {
				$i = 1;
				$last_unit = $temp['lesson_id'];
			}
?>
									<div id="status_option" onClick="implement_query('<?php echo $temp['implement_key']; ?>', 1);"><?php echo '單元 '.$temp['lesson_unit'].' - 第 '.$i.' 題'?></div>
<?php
			$i++;
		}
?>
								</div>
								<div>
								</div>
							</div>
							<div id="temp">
							</div>
						</div>
					</div>
				</div>
				<div class="pure-u-1-1">
					<div id="show_status" class="blankblock">
						<table class="pure-table pure-table-bordered m-center t-center" style="width:100%;">
							<thead>
								<tr>
									<th style="width:10%;">#</th>
									<th style="width:10%;">Result</th>
									<th style="width:10%;">Memory Usage</th>
									<th style="width:10%;">Time Usage</th>
									<th style="width:10%;"></th>
								</tr>
							</thead>
							<tbody id="data_switch">
							</tbody>
						</table>
					</div>
				</div>
	<?php } ?>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			var submenu = ['course', 'temp'];
			
			function displacement(value) {
				$("#data_switch").empty();
				var offset = (value) * (-100);
				$('#content_float').stop(true);
				$('#content_float').animate({
					marginLeft: offset+'%'
				}, 500);
				$('#content_float').animate({
					/*height: $('#'+course_submenu[value]).height()*/
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
						$("#data_switch").empty();
						var obj = JSON.parse(data);
						if (typeof obj.error != 'undefined') {
							var content = '<div class="warning t-center">There is something wrong when loading the data.</div>';
						} else if (typeof obj.empty != 'undefined') {
							var content = '<div class="t-center">No data</div>';
						} else {
							var content = '';
							for (var i = 0; i < obj.length; i++) {
								content += '<tr>';
								content += '<td style="width:10%">' + (i + 1) + '</td>';
								content += '<td style="width:10%">' + obj[i].result + '</td>';
								content += '<td style="width:10%">' + obj[i].memory_usage + '</td>';
								content += '<td style="width:10%">' + obj[i].time_usage + '</td>';
								content += '<td style="width:10%">Code</td>';
								content += '</tr>';
							}
						}
						$("#data_switch").append(content);
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