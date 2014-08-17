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
	
	if (isset($_GET['key'])) {
		$lesson_check = true;
		$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
		$lesson_content = $lesson->get_lesson_content($_GET['key']);
		if (empty($lesson_content)) {
			$lesson_check = false;
		}
	}
	$verify_code = verify_code();
	setcookie("verify_code_lesson_refine", $verify_code, $current_time + 3600, "/", WEB_DOMAIN_NAME, false, true);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo ($lesson_check) ? '修改課程' : '新增課程'; ?></title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="http://cdn.ckeditor.com/4.4.3/standard-all/ckeditor.js"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div class="juice-lesson-body">
				<div>
					<h3 id="message"></h3>
				</div>
					<div>
					<form name="lesson_refine" id="lesson_refine" action="<?php echo $prefix.'juice/lesson/lesson_handle.php' ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
<?php
	if (isset($_GET['practice'])) {
		$i = 1;
		if ($lesson_check and !empty($lesson_content['practice'])) {
			foreach ($lesson_content['practice'] as $tmp) {
?>
							<div class="pure-control-group">
								<label for="practice">填空練習：</label>
								<textarea class="ckeditor" name="practice_id_<?php echo $i; ?>" id="practice_id_<?php echo $i; ?>" required><?php echo $tmp['practice_content']; ?></textarea>
								<input type="text" name="practice_key_<?php echo $i; ?>" id="practice_key_<?php echo $i; ?>" value="<?php echo $tmp['practice_key']; ?>" hidden readonly autocomplete="off">
								<input type="text" name="practice_action_<?php echo $i; ?>" id="practice_action_<?php echo $i; ?>" value="update" hidden readonly autocomplete="off">
							</div>
<?php
				$i++;
			}
		}
?>
							<div class="pure-control-group">
								<label for="practice">填空練習：</label>
								<textarea class="ckeditor" name="practice_id_<?php echo $i; ?>" id="practice_id_<?php echo $i; ?>" required></textarea>
								<input type="text" name="practice_action_<?php echo $i; ?>" id="practice_action_<?php echo $i; ?>" value="add" hidden readonly autocomplete="off">
							</div>
							<div class="pure-control-group">
								<input type="text" name="total_practice" id="total_practice" value="<?php echo $i-1; ?>" hidden readonly autocomplete="off">
								<input type="text" name="type" id="type" value="practice" hidden readonly autocomplete="off">
							</div>
<?php
	} else if (isset($_GET['implement'])) {
		$i = 1;
		if ($lesson_check and !empty($lesson_content['implement'])) {
			foreach ($lesson_content['implement'] as $tmp) {
?>
							<div class="pure-control-group">
								<label for="implement_timeLimit_<?php echo $i; ?>">時間限制(秒)：</label>
								<input type="text" name="implement_timeLimit_<?php echo $i; ?>" id="implement_timeLimit_<?php echo $i; ?>" value="<?php echo $tmp['time_limit']; ?>" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_memoryLimit_<?php echo $i; ?>">記憶體限制(MB)：</label>
								<input type="text" name="implement_memoryLimit_<?php echo $i; ?>" id="implement_memoryLimit_<?php echo $i; ?>" value="<?php echo $tmp['memory_limit']; ?>" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_fileLimit_<?php echo $i; ?>">開檔限制：</label>
								<input type="text" name="implement_fileLimit_<?php echo $i; ?>" id="implement_fileLimit_<?php echo $i; ?>" value="<?php echo $tmp['file_limit']; ?>" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_mode_<?php echo $i; ?>">較驗模式：</label>
								<input type="text" name="implement_mode_<?php echo $i; ?>" id="implement_mode_<?php echo $i; ?>" value="<?php echo $tmp['mode']; ?>" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_otherLimit_<?php echo $i; ?>">其餘限制：</label>
								<input type="text" name="implement_otherLimit_<?php echo $i; ?>" id="implement_otherLimit_<?php echo $i; ?>" value="<?php echo $tmp['other_limit']; ?>" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement">動 動 腦：</label>
								<textarea class="ckeditor" name="implement_id_<?php echo $i; ?>" id="implement_id_<?php echo $i; ?>" required><?php echo $tmp['implement_content']; ?></textarea>
								<input type="text" name="implement_key_<?php echo $i; ?>" id="implement_key_<?php echo $i; ?>" value="<?php echo $tmp['implement_key']; ?>" hidden readonly autocomplete="off">
								<input type="text" name="implement_action_<?php echo $i; ?>" id="implement_action_<?php echo $i; ?>" value="update" hidden readonly autocomplete="off">
							</div>
<?php
				$i++;
			}
		}
?>
							<div class="pure-control-group">
								<label for="implement_timeLimit_<?php echo $i; ?>">時間限制(秒)：</label>
								<input type="text" name="implement_timeLimit_<?php echo $i; ?>" id="implement_timeLimit_<?php echo $i; ?>" value="" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_memoryLimit_<?php echo $i; ?>">記憶體限制(MB)：</label>
								<input type="text" name="implement_memoryLimit_<?php echo $i; ?>" id="implement_memoryLimit_<?php echo $i; ?>" value="" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_fileLimit_<?php echo $i; ?>">開檔限制：</label>
								<input type="text" name="implement_fileLimit_<?php echo $i; ?>" id="implement_fileLimit_<?php echo $i; ?>" value="" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_mode_<?php echo $i; ?>">較驗模式：</label>
								<input type="text" name="implement_mode_<?php echo $i; ?>" id="implement_mode_<?php echo $i; ?>" value="" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement_otherLimit_<?php echo $i; ?>">其餘限制：</label>
								<input type="text" name="implement_otherLimit_<?php echo $i; ?>" id="implement_otherLimit_<?php echo $i; ?>" value="" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="implement">動 動 腦：</label>
								<textarea class="ckeditor" name="implement_id_<?php echo $i; ?>" id="implement_id_<?php echo $i; ?>" required></textarea>
								<input type="text" name="implement_action_<?php echo $i; ?>" id="implement_action_<?php echo $i; ?>" value="add" hidden readonly autocomplete="off">
							</div>
<?php } else { ?>
							<div>
								<div class="juice-lesson-titles">
									<label for="unit">單元：</label>
									<input type="text" id="unit" name="unit" value="<?php echo ($lesson_check) ? $lesson_content['lesson_unit'] : ''; ?>" maxlength="2" pattern="^\d{1,2}$" autocomplete="off" required>
								</div>
								<div class="juice-lesson-titles">
									<label for="level">難度：</label>
									<select name="level" id="level" required>
										<option value="1"<?php echo ($lesson_content['lesson_level'] == 1) ? 'selected' : ''; ?>>初階</option>
										<option value="2"<?php echo ($lesson_content['lesson_level'] == 2) ? 'selected' : ''; ?>>中階</option>
										<option value="3"<?php echo ($lesson_content['lesson_level'] == 3) ? 'selected' : ''; ?>>高階</option>
										<option value="4"<?php echo ($lesson_content['lesson_level'] == 4) ? 'selected' : ''; ?>>終階</option>
									</select>
								</div>
								<div class="juice-lesson-titles">
									<label for="title">標題：</label>
									<input type="text" id="title" name="title" value="<?php echo ($lesson_check) ? $lesson_content['lesson_title'] : ''; ?>" maxlength="128" autocomplete="off" required>
								</div>
							</div>
							<br>
							<div>
								<div class="juice-lesson-contents">
									<label for="goal">學習目標：</label>
									<textarea class="ckeditor" name="goal" id="goal" required><?php echo ($lesson_check) ? $lesson_content['lesson_goal'] : ''; ?></textarea>
								</div>
								<br>
								<hr>
								<br>
								<div class="juice-lesson-contents">
									<label for="content">課程內容：</label>
									<textarea class="ckeditor" name="content" id="content" required><?php echo ($lesson_check) ? $lesson_content['lesson_content'] : ''; ?></textarea>
								</div>
								<br>
								<hr>
								<br>
								<div class="juice-lesson-contents">
									<label for="example">範　　例：</label>
									<textarea class="ckeditor" name="example" id="example" required><?php echo ($lesson_check) ? $lesson_content['lesson_example'] : ''; ?></textarea>
								</div>
							</div>
							<div class="pure-control-group">
								<input type="text" name="type" id="type" value="lesson" hidden readonly autocomplete="off">
								<input type="text" name="action" id="action" value="<?php echo ($lesson_check) ? 'update' : 'add'; ?>" hidden readonly autocomplete="off">
							</div>
<?php } ?>
							<div class="pure-control-group">
								<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_lesson_refine']; ?>" hidden readonly autocomplete="off" required>
								<input type="text" name="key" id="key" value="<?php echo ($lesson_check) ? $_GET['key'] : ''; ?>" hidden readonly autocomplete="off">
							</div>
							<br>
							<div class="pure-controls">
								<button class="juice-lesson-button" type="submit" name="submit" id="submit" class="pure-button pure-button-primary"><?php echo ($lesson_check) ? '修改' : '新增'; ?></button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			/*$(document).ready(function() {
				function auto_update() {
					$.post(
						'<?php echo $prefix.'juice/lesson/lesson_handle.php' ?>',
						{
							unit:$('#unit').val(),
							level:$('#level').val(),
							title:$('#title').val(),
							goal:CKEDITOR.instances.goal.getData(),
							content:CKEDITOR.instances.content.getData(),
							example:CKEDITOR.instances.example.getData(),
							//practice:CKEDITOR.instances.practice.getData(),
							//implement:CKEDITOR.instances.implement.getData(),
							verify_code:$('#verify_code').val(),
							key:$('#key').val()
						},
						function (data) {
							var d = new Date();
							if (typeof data.error != 'undefined') {
								$('#message').text(data.error);
								$('html,body').animate({
									scrollTop:0
								});
							} else if (typeof data.updated != 'undefined') {
								$('#message').text('系統已自動存檔 - ' + d);
							} else if (typeof data.key != 'undefined') {
								$('#message').text('課程已新增 - ' + d);
								$('#submit').text('修改');
								$('#key').val(data.key);
								$("#unit").attr("readonly",true);
								$('html,body').animate({
									scrollTop:0
								});
								setInterval(auto_update, 300000);
							} else {
								$('#message').text('未知的錯誤 - ' + d);
								$('html,body').animate({
									scrollTop:0
								});
							}
						}, 'json'
					);
				}
				
				$("#lesson_refine").submit(function(){
					auto_update();
					return false;
				});*/
				
<?php
	if ($lesson_content) {
?>
				//$("#unit").attr("readonly",true);
				//setInterval(auto_update, 300000);
<?php
	}
?>
			//});
		</script>
	</body>
</html>
