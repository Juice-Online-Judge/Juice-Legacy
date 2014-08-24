<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	} else if (!isset($_GET['unit'])) {
		header("Location: ".$prefix."course/course_list.php");
		exit();
	}
	
	$course = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$result = $course->lesson_unit_to_key($_GET['unit']);
	print_r($result);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>單元一</title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div>
				<div id="course_content">
					<div id="course_unit">
					</div>
					<div id="course_title">
					</div>
					<div id="course_goal">
					</div>
					<div id="course_content">
					</div>
				</div>
				<div id="course_example">
				</div>
				<div id="course_practice">
					<div>
					</div>
				</div>
				<div id="course_implement">
					<div>
					</div>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){
			});
		</script>
	</body>
</html>