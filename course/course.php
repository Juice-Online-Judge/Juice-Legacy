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
			<div class="pure-g">
<?php if ($error) { ?>
				<div>
					<h2 class="warning center"><?php echo $message; ?></h2>
				</div>
<?php } else { ?>
				<div class="pure-u-1-1">
					<ul id="course_list">
						<li><a href="">學習目標</a></li>
						<li><a href="">範例觀摩</a></li>
						<li><a href="">填空練習</a></li>
						<li><a href="">動 動 腦</a></li>
					</ul>
				</div>
				<div class="pure-u-1-1">
					<div id="course_content">
						<div id="course_unit" class="">
							<span>單元 <?php echo $result['lesson_unit']; ?></span>
						</div>
						<div id="course_title">
							<span><?php echo $result['lesson_title']; ?></span>
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
								<span>第 <?php echo $i; ?> 題</span>
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
								<span>第 <?php echo $i; ?> 題</span>
							</div>
							<div>
								<span>Time Limit : <?php echo $tmp['time_limit']; ?></span>
							</div>
							<div>
								<span>Memory Limit : <?php echo $tmp['memory_limit']; ?></span>
							</div>
							<div>
								<span>File Limit : <?php echo $tmp['file_limit']; ?></span>
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
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){
			});
		</script>
	</body>
</html>