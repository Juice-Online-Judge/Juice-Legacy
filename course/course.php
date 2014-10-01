<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	} else if (!isset($_GET['unit']) or !preg_match("/^\d+$/", $_GET['unit'])) {
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
		<title>單元 <?php echo $result['lesson_unit']; ?></title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
		<link rel="stylesheet" href="<?php echo $prefix.'scripts/css/tomorrow-night-bright.css'; ?>">
<?php display_scripts_link(); ?>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.2/highlight.min.js"></script>
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
						<li onClick="displacement(0);">課程內容</li>
						<li onClick="displacement(1);">範例觀摩</li>
						<li onClick="displacement(2);">小試身手</li>
						<li onClick="displacement(3);">動 動 腦</li>
						<select id="course_menu">
<?php
	foreach ($course->list_lesson() as $tmp) {
?>
							<option value="<?php echo $tmp['lesson_unit']; ?>"<?php echo ($_GET['unit'] == $tmp['lesson_unit']) ? ' selected' : ''; ?>>單元 <?php echo $tmp['lesson_unit']; ?></option>
<?php
	}
?>	
						</select>
					</ul>
				</div>
				<div class="pure-u-1-1">
					<div id="course_body">
						<div id="course_unit">
							<span>單元 <?php echo $result['lesson_unit']; ?></span>
						</div>
						<div id="course_title">
							<span><?php echo $result['lesson_title']; ?></span>
						</div>
						<div id="course_float">
							<div id="course_introduction">
								<div id="course_goal">
									<blockquote><?php echo $result['lesson_goal']; ?></blockquote>
								</div>
								<div id="course_content">
									<blockquote><?php echo $result['lesson_content']; ?></blockquote>
								</div>
							</div>
							<div id="course_example">
								<blockquote><?php echo $result['lesson_example']; ?></blockquote>
							</div>
							<div id="course_practice">
								<blockquote>
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
								<blockquote>
							</div>
							<div id="course_implement">
<?php
		$i = 1;
		foreach ($result['implement'] as $tmp) {
?>
								<blockquote>
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
											<form name="implement_<?php echo $i; ?>" id="implement_<?php echo $i; ?>" action="<?php echo $prefix.'course/course_preprocess.php'; ?>" method="POST" class="pure-form pure-form-aligned">
												<fieldset>
													<div class="pure-control-group">
														<label for="code_<?php echo $i; ?>">Code:</label>
														<textarea name="implement_a<?php echo $i; ?>" id="implement_a<?php echo $i; ?>" required></textarea>
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
								<blockquote>
<?php
			$i++;
		}
?>
							</div>
						</div>
					</div>
				</div>
	<?php } ?>
			</div>
		</div>
<?php display_footer($prefix); ?>
		<script>
			var course_submenu = ['course_introduction', 'course_example', 'course_practice', 'course_implement'];
			function displacement(value) {
				var offset = (value) * (-100);
				$('#course_float').stop(true);
				$('#course_float').animate({
					marginLeft: offset+'%'
				}, 500);
				$('#course_float').animate({
					height: $('#'+course_submenu[value]).height()
				}, 300);
			}
			
			$(document).ready(function(){
				$('#course_menu').change(function(){
					window.location.replace('http://crux.coder.tw/freedom/juice/course/course.php?unit=' + $('#course_menu').val());
				});
				
				hljs.initHighlightingOnLoad();
			});
			
			$(window).load(function(){
				$('#course_float').animate({
					height: $('#course_introduction').height()
				}, 300);
			});
		</script>
	</body>
</html>