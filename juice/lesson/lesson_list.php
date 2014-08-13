<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$result = $lesson->list_lesson();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>課程列表</title>
		<link rel="icon" href="" type="image/x-icon">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/pure.css' ?>" rel="stylesheet">
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div>
				<div>
					<h3>課程列表</h3>
				</div>
				<div>
					<table class="pure-table">
						<thead>
							<tr>
								<th>單元</th>
								<th>難度</th>
								<th>標題</th>
								<th>公開</th>
								<th></th>
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
								<td><?php echo $tmp['lesson_title']; ?></td>
								<td><?php echo ($tmp['lesson_is_visible']) ? '是' : '否'; ?></td>
								<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?key='.$tmp['lesson_key']; ?>">修改</a></td>
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
<?php display_footer(); ?>
	</body>
</html>