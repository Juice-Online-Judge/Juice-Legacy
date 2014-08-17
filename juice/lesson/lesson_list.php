<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!isset($_SESSION['uid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!($_SESSION['admin_group'] > 3)) {
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
		<link rel="icon" href="" type="image/x-icon">
<?php display_css_link($prefix); ?>
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
								<th>單　　元</th>
								<th>難　　度</th>
								<th>標　　題</th>
								<th colspan="2">填空練習</th>
								<th colspan="2">動 動 腦</th>
								<th>公　　開</th>
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
								<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?practice=1&add=1&key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">新增</button></a></td>
								<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?practice=1&key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">修改</button></a></td>
								<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?implement=1&add=1&key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">新增</button></a></td>
								<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?implement=1&key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">修改</button></a></td>
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