<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	$solve_status = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>解題動態</title>
<?php display_css_link($prefix); ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $prefix.'scripts/css/tomorrow-night-bright.css'; ?>">
		<link type="text/css" rel="stylesheet" href="<?php echo $prefix.'scripts/css/status.css'; ?>">
<?php display_scripts_link(); ?>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.2/highlight.min.js"></script>
		<script src="<?php echo $prefix.'scripts/js/jquery.custom-scrollbar.min.js'; ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div class="juice_body status_body">
			<div class="u-1-1">
				<ul class="status_list">
					<li onClick="displacement(0);">課程</li>
					<li onClick="displacement(1);">闖關</li>
				</ul>
			</div>
			<div class="u-1-24"></div>
			<div class="u-3-24">
				<div class="status_profile"></div>
			</div>
			<div class="u-19-24">
				<div class="status_option_table">
					<div id="content_float">
						<div id="course">
							<div class="u-1-1">
								<h3 class="title t-left">小試身手</h3>
							</div>
							<div class="status_option_part">
<?php
	$list_practice = $solve_status->list_ipm_pt(false);
	$last_unit = -1;
	foreach ($list_practice as $temp) {
		if ($temp['lesson_id'] != $last_unit) {
			$i = 1;
			$last_unit = $temp['lesson_id'];
		}
?>
								<div class="status_option" onClick="ipm_pt_query('<?php echo $temp['practice_key']; ?>', 0, <?php echo $temp['lesson_unit']; ?>);"><?php echo $temp['lesson_unit'].' - '.$i; ?></div>
<?php
		$i++;
	}
?>
							</div>
							<div class="u-1-1">
								<h3 class="title t-left">動動腦</h3>
							</div>
							<div class="status_option_part">
<?php
	$list_implement = $solve_status->list_ipm_pt(true);
	$last_unit = -1;
	foreach ($list_implement as $temp) {
		if ($temp['lesson_id'] != $last_unit) {
			$i = 1;
			$last_unit = $temp['lesson_id'];
		}
?>
								<div	 class="status_option" onClick="ipm_pt_query('<?php echo $temp['implement_key']; ?>', 1, <?php echo $temp['lesson_unit']; ?>);"><?php echo $temp['lesson_unit'].' - '.$i; ?></div>
<?php
		$i++;
	}
?>
							</div>
						</div>
						<div id="challenge">
							<div class="u-1-1">
								<p>Test</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="u-1-24"></div>
			<div class="u-1-24"></div>
			<div class="u-22-24">
				<div class="show_status">
					<table class="show_status_table">
						<thead>
							<tr>
								<th></th>
								<th>#</th>
								<th>Result</th>
								<th>Memory Usage</th>
								<th>Time Usage</th>
								<th>Code</th>
								<th>Time</th>
							</tr>
						</thead>
						<tbody id="data_switch">
						</tbody>
					</table>
				</div>
			</div>
			<div class="u-1-24"></div>
			<div id="code_area">
				<div id="code_background"></div>
				<div id="code_close"></div>
				<div id="code_content" class="language-C"></div>
			</div>
		</div>
		<div id="loading_img" style="position:fixed;top:50%;left:50%;z-index:3;">
			<img src="../images/loading.gif">
		</div>
<?php display_footer($prefix); ?>
		<script>
			var submenu = ['course', 'challenge'];
			
			function displacement(value) {
				$('#data_switch').empty();
				$('#code_content').empty();
				$('.status_option_hover').attr('class', 'status_option');
				if(value == 0) {
					$('#course').show();
					$('#challenge').hide();
				} else {
					$('#challenge').show();
					$('#course').hide();
				}
			}
			
			function ipm_pt_query(key, is_implement, unit) {
				$('#loading_img').show();
				$.post(
					'<?php echo $prefix.'others/api/apiGetSolveStatus.php'; ?>',
					{
						key:key,
						is_implement:is_implement
					},
					function (data) {
						$('#data_switch').empty();
						$('#code_content').empty();
						var obj = JSON.parse(data);
						if (typeof obj.error != 'undefined') {
							var content = '<tr><td colspan="7" class="warning">There is something wrong when loading the data.</td></tr>';
						} else if (typeof obj.empty != 'undefined') {
							var content = '<tr><td colspan="7">No data</td></tr>';
						} else {
							var content = '<tr><td rowspan="' + (obj.length + 1) + '"><a href="<?php echo $prefix.'course/course.php?unit='; ?>' + unit + '&type=' + ((is_implement) ? "course_implement" : "course_practice") + '">LINK</a></td></tr>';
							for (var i = 0; i < obj.length; i++) {
								content += '<tr>';
								content += '<td>' + (obj.length - i) + '</td>';
								content += '<td class="' + obj[i].result + '">' + obj[i].result + '</td>';
								content += '<td>' + obj[i].memory_usage + '</td>';
								content += '<td>' + obj[i].time_usage + '</td>';
								content += '<td class="code_td" onClick="lesson_code_query(\'' + obj[i].key + '\', \'' + key + '\');">Code</td>';
								content += '<td>' + obj[i].time + '</td>';
								content += '</tr>';
							}
						}
						$('#data_switch').append(content);
						$('#loading_img').hide();
					}
				);
			}
			
			function lesson_code_query(code_key, ipm_pt_key) {
				$('#loading_img').show();
				$.post(
					'<?php echo $prefix.'others/api/apiGetSolveCode.php'; ?>',
					{
						type:'lesson',
						code_key:code_key,
						ipm_pt_key:ipm_pt_key
					},
					function (data) {
						$('#code_content').empty();
						var obj = JSON.parse(data);
						if (typeof obj.error != 'undefined') {
							var content = '<span class="warning">There is something wrong when loading the data.</span>';
						} else if (typeof obj.empty != 'undefined') {
							var content = '<span>No data</span>';
						} else {
							var content = '<pre><code>' + obj.code + '</code></pre>';
						}
						$('#code_content').append(content);
						hljs.initHighlighting.called = false;
						hljs.initHighlighting();
						$('#code_area').show();
						$('#loading_img').hide();
					}
				);
			}
			
			$(document).ready(function(){
				$('#code_area').hide();
				$('#loading_img').hide();
				$('#challenge').hide();
				
				$('.status_option').click(function(){
					$('.status_option_hover').attr('class', 'status_option');
					$(this).attr('class', 'status_option status_option_hover');
				});
				
				$('#code_close').click(function(){
					$('#code_area').hide();
				});
				
				$('#code_background').click(function(){
					$('#code_area').hide();
				});
			});
			
<?php if (isset($_GET['key']) and isset($_GET['is_implement'])) { ?>
			$(window).load(function(){
				ipm_pt_query('<?php echo $_GET['key']; ?>', <?php echo $_GET['is_implement']; ?>);
			});
<?php } ?>
		</script>
	</body>
</html>
