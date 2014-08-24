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
	$error = false;
	
	$course = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$course_key = $course->lesson_unit_to_key($_GET['unit']);
	if ($course_key === false) {
		$error = true;
		$message = 'Invalid unit.';
	} else {
		$result = $course->get_lesson_content($course_key['lesson_key']);
	}
	$verify_code = verify_code();
	set_cookie('verify_code_course', $verify_code, 3600);
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
<?php if ($error) { ?>
			<div>
				<h2 class="warning center"><?php echo $message; ?></h2>
			</div>
<?php } else { ?>
			<div>
				<div id="course_content">
					<div id="course_unit">
						單元 <?php echo $result['lesson_unit']; ?>
					</div>
					<div id="course_title">
						<?php echo $result['lesson_title']; ?>
					</div>
					<div id="course_goal">
						<?php echo $result['lesson_goal']; ?>
					</div>
					<div id="course_content">
						<?php echo $result['lesson_content']; ?>
					</div>
				</div>
				<div id="course_example">
					<?php echo $result['lesson_example']; ?>
				</div>
				<div id="course_practice">
<?php
		$i = 1;
		foreach ($result['practice'] as $tmp) {
?>
					<div>
						<div>
							第 <?php echo $i; ?> 題
						</div>
						<div>
							<?php echo $tmp['practice_content']; ?>
						</div>
					</div>
<?php
			$i++;
		}
?>
				</div>
				<div id="course_implement">
<?php
		$i = 1;
		foreach ($result['implement'] as $tmp) {
?>
					<div>
						<div>
							第 <?php echo $i; ?> 題
						</div>
						<div>
							Time Limit : <?php echo $tmp['time_limit']; ?>
						</div>
						<div>
							Memory Limit : <?php echo $tmp['memory_limit']; ?>
						</div>
						<div>
							File Limit : <?php echo $tmp['file_limit']; ?>
						</div>
						<div>
							題目：
							<?php echo $tmp['implement_content']; ?>
						</div>
						<div>
							<form name="implement_<?php echo $i; ?>" id="implement_<?php echo $i; ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
								<fieldset>
									<div class="pure-control-group">
										<label for="code_<?php echo $i; ?>">Code:</label>
										<textarea name="code_<?php echo $i; ?>" id="code_<?php echo $i; ?>" required></textarea>
									</div>
									<div style="display:hidden;">
										<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_course']; ?>" hidden readonly autocomplete="off" required>
									</div>
									<div class="pure-control-group t-center">
										<button type="submit" id="submit" class="pure-button pure-button-primary">繳交</button>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
<?php
			$i++;
		}
?>
				</div>
			</div>
<?php } ?>
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){
			});
		</script>
	</body>
</html>