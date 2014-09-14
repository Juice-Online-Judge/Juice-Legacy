<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$result = $lesson->list_lesson();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>課程列表</title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div class="pure-g">
				<div class="pure-u-1-1">
					<div id="lesson_list" class="t-center">
						<div>
							<h1 class="title">課程列表</h1>
						</div>
						<div style="width:100%;">
							<table class="pure-table m-center">
								<thead>
									<tr class="t-center">
										<th>單　　元</th>
										<th>難　　度</th>
										<th>標　　題</th>
									</tr>
								</thead>
								<tbody>
<?php
	if (!empty($result)) {
		$lesson_level_name = array('初階', '中階', '高階', '終階');
		foreach ($result as $tmp) {
?>
									<tr>
										<td><?php echo $tmp['lesson_unit']; ?></td>
										<td><?php echo $lesson_level_name[$tmp['lesson_level']-1]; ?></td>
										<td>
											<a href="http://crux.coder.tw/freedom/juice/course/course.php?unit=<?php echo $tmp['lesson_unit']; ?>"><?php echo $tmp['lesson_title']; ?></a>
										</td>
									</tr>
<?php
		}
	}
?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
	</body>
</html>