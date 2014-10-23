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
		<link type="text/css" rel="stylesheet" href="<?php echo $prefix.'scripts/css/tomorrow-night-bright.css'; ?>">
<?php display_scripts_link(); ?>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.2/highlight.min.js"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
			<div class="flexblock" style="max-width:1024px; margin:0 auto; padding:0 0 1em 0;">
<?php if ($error) { ?>
				<div>
					<h2 class="warning t-center"><?php echo $message; ?></h2>
				</div>
<?php } else { ?>
				<div class="pure-u-1-1">
					<ul class="course_list">
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
					<div class="course_body">
						<blockquote>
							<div id="course_unit">
								<span style="font-size:32px"><strong>單元 <?php echo $result['lesson_unit']; ?></strong></span>
							</div>
							<div id="course_title">
								<span style="font-size:32px"><strong><?php echo $result['lesson_title']; ?></strong></span>
							</div>
						</blockquote>
						<hr>
						<div id="course_float">
							<div id="course_introduction">
								<div id="course_goal">
									<blockquote>
<?php
		echo $result['lesson_goal'];
?>
									</blockquote>
								</div>
								<div id="course_content">
									<blockquote>
<?php
		echo $result['lesson_content'];
?>
									</blockquote>
								</div>
							</div>
							<div id="course_example">
								<blockquote>
<?php
		echo $result['lesson_example'];
?>
								</blockquote>
							</div>
							<div id="course_practice">
								<blockquote>
<?php
		$i = 1;
		$practice_key = array();
		foreach ($result['practice'] as $tmp) {
			array_push($practice_key, $tmp['practice_key']);
?>
									<div>
										<div>
											<span style="font-size:28px">第 <?php echo $i; ?> 題</span>
										</div>
										<div>
<?php
		echo $tmp['practice_content'];
?>
										</div>
									</div>
<?php
			$i++;
		}
?>
								<blockquote>
							</div>
							<div id="course_implement">
								<blockquote>
<?php
		$i = 1;
		foreach ($result['implement'] as $tmp) {
			if ($i > 1) {
?>
									<hr>
									<br>
<?php
			}
?>
									<div>
										<div>
											<span style="font-size:28px">第 <?php echo $i; ?> 題</span>
										</div>
										<div>
<?php
			echo $tmp['implement_content'];
?>
										</div>
										<div style="text-align:center;">
											<span>時間限制 : <?php echo $tmp['time_limit']; ?> 秒</span>
										</div>
										<div>
											<form name="implement_<?php echo $i; ?>" id="implement_<?php echo $i; ?>" action="<?php echo $prefix.'course/course_preprocess.php'; ?>" method="POST" target="_blank" class="pure-form pure-form-aligned">
												<fieldset>
													<div class="pure-control-group">
														<textarea name="implement_a<?php echo $i; ?>" id="implement_a<?php echo $i; ?>" rows="30" cols="105" required></textarea>
													</div>
													<div style="display:hidden;">
														<input type="text" name="implement_key" id="implement_key" value="<?php echo $tmp['implement_key']; ?>" hidden readonly autocomplete="off" required>
													</div>
													<div style="display:hidden;">
														<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_course']; ?>" hidden readonly autocomplete="off" required>
													</div>
													<div class="pure-control-group t-center">
														<button type="submit" id="submit" class="pure-button pure-button-primary">送出</button>
													</div>
												</fieldset>
											</form>
										</div>
									</div>
<?php
			$i++;
		}
?>
								<blockquote>
							</div>
						</div>
					</div>
				</div>
<?php } ?>
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
<?php
	if (!$error and !empty($practice_key)) {
		$i = 1;
		foreach ($practice_key as $tmp) {
?>
				
				$('#practice_q<?php echo $i; ?>').append('<div><input type="text" name="practice_key" value="<?php echo $tmp; ?>" hidden readonly autocomplete="off" required></div>');
<?php
			$i++;
		}
	}
?>
			});
			
<?php
	$course_submenu = array(
		'course_introduction' => 0,
		'course_example' => 1,
		'course_practice' => 2,
		'course_implement' => 3
	);
	$type = (isset($_GET['type']) and in_array(($_GET['type']), $course_submenu)) ? $course_submenu[$_GET['type']] : 0;
?>
			$(window).load(function(){
				displacement(<?php echo $type; ?>);
			});
		</script>
	</body>
</html>