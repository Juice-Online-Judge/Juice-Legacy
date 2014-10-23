<?php
	if (isset($_GET['key']) and isset($_GET['is_implement'])) {
		if (!isset($prefix)) {
			$prefix = '../';
		}
		require_once $prefix.'config/web_preprocess.php';
	
		if (!permission_check('login')) {
			header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
			exit();
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>解題動態</title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $prefix.'scripts/css/tomorrow-night-bright.css'; ?>">
<?php display_scripts_link(); ?>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.2/highlight.min.js"></script>
	</head>
	<body>
		<div class="pure-u-1-1">
			<div id="code" class="language-C"></div>
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
		<script>
			function ipm_pt_query(key, is_implement) {
				$.post(
					'<?php echo $prefix.'others/api/apiGetSolveStatus.php'; ?>',
					{
						key:key,
						is_implement:is_implement
					},
					function (data) {
						$('#data_switch').empty();
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
								content += '<td class="' + obj[i].result + '">' + obj[i].result + '</td>';
								content += '<td>' + obj[i].memory_usage + '</td>';
								content += '<td>' + obj[i].time_usage + '</td>';
								content += '<td onClick="lesson_code_query(\'' + obj[i].key + '\', \'' + key + '\');">Code</td>';
								content += '<td>' + obj[i].time + '</td>';
								content += '</tr>';
							}
						}
						$('#data_switch').append(content);
					}
				);
			}
			
			function lesson_code_query(code_key, ipm_pt_key) {
				$.post(
					'<?php echo $prefix.'others/api/apiGetSolveCode.php'; ?>',
					{
						type:'lesson',
						code_key:code_key,
						ipm_pt_key:ipm_pt_key
					},
					function (data) {
						$('#code').empty();
						var obj = JSON.parse(data);
						if (typeof obj.error != 'undefined') {
							var content = '<span class="warning">There is something wrong when loading the data.</span>';
						} else if (typeof obj.empty != 'undefined') {
							var content = '<span>No data</span>';
						} else {
							var content = '<pre><code>' + obj.code + '</code></pre>';
						}
						$('#code').append(content);
						hljs.initHighlighting();
					}
				);
			}
			
			$(window).load(function(){
				ipm_pt_query('<?php echo $_GET['key']; ?>', <?php echo $_GET['is_implement']; ?>);
			});
		</script>
	</body>
</html>
<?php
	} else {
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		exit();
	}
?>