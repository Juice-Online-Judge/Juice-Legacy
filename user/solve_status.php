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
			<div class="flexblock">
<?php if ($error) { ?>
				<div>
					<h2 class="warning center"><?php echo $message; ?></h2>
				</div>
<?php } else { ?>
				<div class="pure-u-1-5">
					<div class="status_profile" class="blankblock">
					</div>
				</div>
				<div class="pure-u-4-5">
					<ul class="status_list">
						<li onClick="displacement(0);">課程</li>
						<li onClick="displacement(1);">闖關</li>
					</ul>
					<div class="blankblock">
						<div id="content_float">
							<div id="course">
								<div class="status_option_table">
<?php
		$last_unit = -1;
		foreach ($list_implement as $temp) {
			if ($temp['lesson_id'] != $last_unit) {
				if ($last_unit != -1) {
?>
									<br></br>
<?php
				}
				$i = 1;
				$last_unit = $temp['lesson_id'];
			}
?>
									<div class="status_option" onClick="ipm_pt_query('<?php echo $temp['implement_key']; ?>', 1);"><?php echo $temp['lesson_unit'].' - '.$i; ?></div>
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
					<div class="show_status" class="blankblock">
						<table class="pure-table pure-table-bordered m-center t-center" style="width:100%;">
							<thead>
								<tr class="t-center">
									<th style="width:10%;">#</th>
									<th style="width:20%;">Result</th>
									<th style="width:15%;">Memory Usage</th>
									<th style="width:20%;">Time Usage</th>
									<th style="width:15%;">Code</th>
									<th style="width:20%;">Time</th>
								</tr>
							</thead>
							<tbody id="data_switch">
							</tbody>
						</table>
					</div>
				</div>
	<?php } ?>
			</div>
<?php display_footer($prefix); ?>
		<script>
			var submenu = ['course', 'temp'];
			
			function displacement(value) {
				$("#data_switch").empty();
				var offset = (value) * (-100);
				$('#content_float').stop(true);
				$('#content_float').animate({
					marginLeft: offset+'%'
				}, 500);
			}
			
			function ipm_pt_query(key, is_implement) {
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
							var content = '<tr><td colspan="6" class="warning">There is something wrong when loading the data.</td></tr>';
						} else if (typeof obj.empty != 'undefined') {
							var content = '<tr><td colspan="6">No data</td></tr>';
						} else {
							var content = '';
							for (var i = 0; i < obj.length; i++) {
								content += '<tr>';
								content += '<td>' + (obj.length - i) + '</td>';
								content += '<td>' + obj[i].result + '</td>';
								content += '<td>' + obj[i].memory_usage + '</td>';
								content += '<td>' + obj[i].time_usage + '</td>';
								content += '<td onClick="code_query(' + obj[i].key + ', ' + key + ');">Code</td>';
								content += '<td>' + obj[i].time + '</td>';
								content += '</tr>';
							}
						}
						$("#data_switch").append(content);
					}
				);
			}
			
			function code_query(code_key, ipm_pt_key) {
			
			}
		</script>
	</body>
</html>