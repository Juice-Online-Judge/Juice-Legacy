<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!permission_check('admin_groups_lesson')) {
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
	set_cookie('verify_code_lesson_refine', $verify_code, 1200);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo ($lesson_check) ? '修改課程' : '新增課程'; ?></title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="http://crux.coder.tw/freedom/juice/scripts/ckeditor/ckeditor.js"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
			<div class="juice_body">
				<div class="pure-u-1-8"></div>
				<div class="pure-u-3-4">
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
								<div>
									<div class="pure-control-group">
										<label for="practice" style="text-align:left;width:auto;"><h3>小試身手 第 <?php echo $i; ?> 題：</h3></label>
										<textarea class="ckeditor" name="practice_id_<?php echo $i; ?>" id="practice_id_<?php echo $i; ?>" required>
<?php
				echo $tmp['practice_content'];
?>
										</textarea>
										<input type="text" name="practice_answer_<?php echo $i; ?>" id="practice_answer_<?php echo $i; ?>" value="<?php echo $tmp['practice_answer']; ?>" autocomplete="off" required>
										<input type="text" name="practice_key_<?php echo $i; ?>" id="practice_key_<?php echo $i; ?>" value="<?php echo $tmp['practice_key']; ?>" hidden readonly autocomplete="off">
										<input type="text" name="practice_action_<?php echo $i; ?>" id="practice_action_<?php echo $i; ?>" value="update" hidden readonly autocomplete="off">
									</div>
								</div>
								<br>
								<hr>
<?php
				$i++;
			}
		}
?>
								<div>
									<div class="pure-control-group">
										<label for="practice" style="text-align:left;width:auto;"><h3>小試身手 第 <?php echo $i; ?> 題：</h3></label>
										<textarea class="ckeditor" name="practice_id_<?php echo $i; ?>" id="practice_id_<?php echo $i; ?>" required></textarea>
										<input type="text" name="practice_answer_<?php echo $i; ?>" id="practice_answer_<?php echo $i; ?>" autocomplete="off">
										<input type="text" name="practice_action_<?php echo $i; ?>" id="practice_action_<?php echo $i; ?>" value="add" hidden readonly autocomplete="off">
									</div>
								</div>
								<div>
									<div class="pure-control-group">
										<input type="text" name="total_practice" id="total_practice" value="<?php echo $i-1; ?>" hidden readonly autocomplete="off">
										<input type="text" name="type" id="type" value="practice" hidden readonly autocomplete="off">
									</div>
								</div>
<?php
	} else if (isset($_GET['implement'])) {
		$i = 1;
		if ($lesson_check and !empty($lesson_content['implement'])) {
			foreach ($lesson_content['implement'] as $tmp) {
?>
								<div class="pure-g" style="margin:0 1em 0 1em;">
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_timeLimit_<?php echo $i; ?>">時間限制(秒)：</label>
										<input type="text" name="implement_timeLimit_<?php echo $i; ?>" id="implement_timeLimit_<?php echo $i; ?>" value="<?php echo $tmp['time_limit']; ?>" autocomplete="off" required>
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_memoryLimit_<?php echo $i; ?>">記憶體限制(MB)：</label>
										<input type="text" name="implement_memoryLimit_<?php echo $i; ?>" id="implement_memoryLimit_<?php echo $i; ?>" value="<?php echo $tmp['memory_limit']; ?>" autocomplete="off" required>
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_fileLimit_<?php echo $i; ?>">開檔限制數：</label>
										<input type="text" name="implement_fileLimit_<?php echo $i; ?>" id="implement_fileLimit_<?php echo $i; ?>" value="<?php echo $tmp['file_limit']; ?>" autocomplete="off" required>
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_mode_<?php echo $i; ?>">校驗模式：</label>
										<input type="text" name="implement_mode_<?php echo $i; ?>" id="implement_mode_<?php echo $i; ?>" value="<?php echo $tmp['mode']; ?>" autocomplete="off" required>
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_otherLimit_<?php echo $i; ?>">其餘限制：</label>
										<input type="text" name="implement_otherLimit_<?php echo $i; ?>" id="implement_otherLimit_<?php echo $i; ?>" value="<?php echo $tmp['other_limit']; ?>" autocomplete="off" required>
									</div>
									<div class="pure-control-group pure-u-1-1">
										<label for="implement" style="text-align:left;width:auto;"><h3>動動腦 第 <?php echo $i; ?> 題：</h3></label>
										<textarea class="ckeditor" name="implement_id_<?php echo $i; ?>" id="implement_id_<?php echo $i; ?>" required>
<?php
				echo $tmp['implement_content'];
?>
										</textarea>
										<input type="text" name="implement_key_<?php echo $i; ?>" id="implement_key_<?php echo $i; ?>" value="<?php echo $tmp['implement_key']; ?>" hidden readonly autocomplete="off">
										<input type="text" name="implement_action_<?php echo $i; ?>" id="implement_action_<?php echo $i; ?>" value="update" hidden readonly autocomplete="off">
									</div>
								</div>
								<br>
								<hr>
								<br>
<?php
				$i++;
			}
		}
?>
								<div class="pure-g" style="margin:0 1em 0 1em;">
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_timeLimit_<?php echo $i; ?>">時間限制(秒)：</label>
										<input type="text" name="implement_timeLimit_<?php echo $i; ?>" id="implement_timeLimit_<?php echo $i; ?>" value="" autocomplete="off">
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_memoryLimit_<?php echo $i; ?>">記憶體限制(MB)：</label>
										<input type="text" name="implement_memoryLimit_<?php echo $i; ?>" id="implement_memoryLimit_<?php echo $i; ?>" value="" autocomplete="off">
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_fileLimit_<?php echo $i; ?>">開檔限制數：</label>
										<input type="text" name="implement_fileLimit_<?php echo $i; ?>" id="implement_fileLimit_<?php echo $i; ?>" value="" autocomplete="off">
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_mode_<?php echo $i; ?>">校驗模式：</label>
										<input type="text" name="implement_mode_<?php echo $i; ?>" id="implement_mode_<?php echo $i; ?>" value="" autocomplete="off">
									</div>
									<div class="pure-control-group pure-u-1-3">
										<label style="text-align:left;" for="implement_otherLimit_<?php echo $i; ?>">其餘限制：</label>
										<input type="text" name="implement_otherLimit_<?php echo $i; ?>" id="implement_otherLimit_<?php echo $i; ?>" value="" autocomplete="off">
									</div>
									<div class="pure-control-group pure-u-1-1">
										<label for="implement" style="text-align:left;width:auto;"><h3>動動腦 第 <?php echo $i; ?> 題：</h3></label>
										<textarea class="ckeditor" name="implement_id_<?php echo $i; ?>" id="implement_id_<?php echo $i; ?>" required></textarea>
										<input type="text" name="implement_action_<?php echo $i; ?>" id="implement_action_<?php echo $i; ?>" value="add" hidden readonly autocomplete="off">
									</div>
								</div>
								<div>
									<div class="pure-control-group">
										<input type="text" name="total_implement" id="total_implement" value="<?php echo $i-1; ?>" hidden readonly autocomplete="off">
										<input type="text" name="type" id="type" value="implement" hidden readonly autocomplete="off">
									</div>
								</div>
<?php } else { ?>
								<div>
									<div style="margin:0 1em 0 1em;text-align:right;">
										<div style="display:inline-block;">
											<label for="unit">單元：</label>
											<input type="text" id="unit" name="unit" value="<?php echo ($lesson_check) ? $lesson_content['lesson_unit'] : ''; ?>" size="2" maxlength="2" pattern="^\d{1,2}$"<?php echo ($lesson_check) ? ' readonly' : ''; ?> autocomplete="off" required>
										</div>
										<div style="display:inline-block;">
											<label for="level">難度：</label>
											<select name="level" id="level" required>
												<option value="1"<?php echo ($lesson_content['lesson_level'] == 1) ? 'selected' : ''; ?>>初階</option>
												<option value="2"<?php echo ($lesson_content['lesson_level'] == 2) ? 'selected' : ''; ?>>中階</option>
												<option value="3"<?php echo ($lesson_content['lesson_level'] == 3) ? 'selected' : ''; ?>>高階</option>
												<option value="4"<?php echo ($lesson_content['lesson_level'] == 4) ? 'selected' : ''; ?>>終階</option>
											</select>
										</div>
										<div style="display:inline-block;">
											<label for="title">標題：</label>
											<input type="text" id="title" name="title" value="<?php echo ($lesson_check) ? $lesson_content['lesson_title'] : ''; ?>" size="36" maxlength="128" autocomplete="off" required>
										</div>
									</div>
								</div>
								<br>
								<div>
									<div>
										<label for="goal"><h3>學習目標：</h3></label>
										<textarea class="ckeditor" name="goal" id="goal" required>
<?php
		echo ($lesson_check) ? $lesson_content['lesson_goal'] : '';
?>
										</textarea>
									</div>
									<br>
									<hr>
									<br>
									<div>
										<label for="content"><h3>課程內容：</h3></label>
										<textarea class="ckeditor" name="content" id="content" required>
<?php
		echo ($lesson_check) ? $lesson_content['lesson_content'] : '';
?>
										</textarea>
									</div>
									<br>
									<hr>
									<br>
									<div>
										<label for="example"><h3>範　　例：</h3></label>
										<textarea class="ckeditor" name="example" id="example" required>
<?php
		echo ($lesson_check) ? $lesson_content['lesson_example'] : '';
?>
										</textarea>
									</div>
								</div>
								<div>
									<div class="pure-control-group">
										<input type="text" name="type" id="type" value="lesson" hidden readonly autocomplete="off">
										<input type="text" name="action" id="action" value="<?php echo ($lesson_check) ? 'update' : 'add'; ?>" hidden readonly autocomplete="off">
									</div>
								</div>
<?php } ?>
								<div>
									<div class="pure-control-group">
										<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_lesson_refine']; ?>" hidden readonly autocomplete="off" required>
										<input type="text" name="key" id="key" value="<?php echo ($lesson_check) ? $_GET['key'] : ''; ?>" hidden readonly autocomplete="off">
									</div>
								</div>
								<br>
								<div>
									<div class="pure-control-group t-center">
										<button type="submit" name="submit" id="submit" class="pure-button pure-button-primary"><?php echo ($lesson_check) ? '修改' : '新增'; ?></button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div class="pure-u-1-8"></div>
			</div>
<?php display_footer($prefix); ?>
		<script>
			$(document).ready(function() {
				$(window).on("click scroll",function(){
					$('#main').css('height', ($('.pure-g').height()));
				});
			});
		</script>
	</body>
</html>