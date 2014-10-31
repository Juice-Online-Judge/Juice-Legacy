<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
		page_check('course_list');
	
	$lesson = new lesson();
	$result = $lesson->list_lesson();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>課程列表</title>
<?php display_link('css'); ?>
<?php display_link('js'); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
			<div class="juice_body">
				<div class="pure-u-1-1">
					<div class="lesson_list">
						<div>
							<h1 class="title">課程列表</h1>
						</div>
						<div class="course_option_table">
<?php
	if (!empty($result)) {
		$lesson_level_name = array('初階', '中階', '高階', '終階');
		foreach ($result as $tmp) {
			if (!$tmp['lesson_is_visible']) {
				continue;
			}
?>
							<div class="course_option" onClick="course_redirect(<?php echo $tmp['lesson_unit']; ?>);">
								<p>單　　元　　<?php echo $tmp['lesson_unit']; ?></p>
								<p><?php echo $lesson_level_name[$tmp['lesson_level']-1]; ?></p>
								<p><?php echo $tmp['lesson_title']; ?></p>
							</div>
<?php
		}
	}
?>
						</div>
					</div>
				</div>
			</div>
<?php display_footer($prefix); ?>
		<script>
			function course_redirect(course_unit) {
				window.location.href = '<?php echo WEB_ROOT_DIR; ?>course/course.php?unit=' + course_unit;
			}
		</script>
	</body>
</html>