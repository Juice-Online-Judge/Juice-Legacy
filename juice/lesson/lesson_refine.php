<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!isset($_COOKIE['verify_code_add_lesson'])) {
		setcookie("verify_code_add_lesson", verify_code(), $current_time + 1800, "/", WEB_DOMAIN_NAME);
	}
	
	if (isset($_GET['key'])) {
		$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
		$lesson_content = $lesson->get_lesson_content($_GET['key']);
		if (empty($lesson_content)) {
			$lesson_content = false;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo (isset($_GET['key'])) ? '修改課程' : '新增課程'; ?></title>
		<link rel="icon" href="" type="image/x-icon">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/pure.css' ?>" rel="stylesheet">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/lesson_add.css' ?>" rel="stylesheet">
<?php display_scripts_link(); ?>
		<script src="http://cdn.ckeditor.com/4.4.3/full/ckeditor.js"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div class="juice-lesson-body">
			<div>
				<h3><?php echo $message; ?></h3>
			</div>
			<div>
				<form name="lesson_refine" id="lesson_refine" action="<?php echo $prefix.'juice/lesson/lesson_handle.php' ?>" method="POST" onSubmit="return false;">
					<fieldset>
						<div>
							<div class="juice-lesson-titles">
								<label for="unit">單元：</label>
								<input type="text" id="unit" name="unit" value="<?php echo ($lesson_content) ? $lesson_content['lesson_unit'] : ''; ?>" maxlength="2" pattern="^\d{1,2}$" autocomplete="off" required>
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
								<input type="text" id="title" name="title" value="<?php echo ($lesson_content) ? $lesson_content['lesson_title'] : ''; ?>" maxlength="128" autocomplete="off" required>
							</div>
						</div>
						<br>
						<div>
							<div class="juice-lesson-contents">
								<label for="goal">學習目標：</label>
								<textarea class="ckeditor" name="goal" id="goal" required><?php echo ($lesson_content) ? $lesson_content['lesson_goal'] : ''; ?></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="content">課程內容：</label>
								<textarea class="ckeditor" name="content" id="content" required><?php echo ($lesson_content) ? $lesson_content['lesson_content'] : ''; ?></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="example">範　　例：</label>
								<textarea class="ckeditor" name="example" id="example" required><?php echo ($lesson_content) ? $lesson_content['lesson_example'] : ''; ?></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="practice">填空練習：</label>
								<textarea class="ckeditor" name="practice" id="practice" required><?php echo ($lesson_content) ? $lesson_content['lesson_practice'] : ''; ?></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="implement">動 動 腦：</label>
								<textarea class="ckeditor" name="implement" id="implement" required><?php echo ($lesson_content) ? $lesson_content['lesson_implement'] : ''; ?></textarea>
							</div>
							<div>
								<input type="text" name="verify_code" id="verify_code" value="<?php echo $_COOKIE['verify_code_add_lesson']; ?>" hidden readonly autocomplete="off" required>
								<input type="text" name="key" id="key" value="<?php echo (isset($_GET['key'])) ? $_GET['key'] : ''; ?>" hidden readonly autocomplete="off">
							</div>
						</div>
						<br>
						<button class="juice-lesson-button" type="submit" name="submit" id="submit">新增</button>
					</fieldset>
				</form>
			</div>
		</div>
		<div>
			<footer>
				Web Create by : Juice / Copyright © 2014
			</footer> 
		</div>
		<script>
			$(document).ready(function() {
				var goal = CKEDITOR.editor.replace('goal');
				var content = CKEDITOR.editor.replace('content');
				var example = CKEDITOR.editor.replace('example');
				var practice = CKEDITOR.editor.replace('practice');
				var implement = CKEDITOR.editor.replace('implement');
				
				$("#lesson_refine").submit(function(){
					$.post(
						'<?php echo $prefix.'juice/lesson/lesson_handle.php' ?>',
						{
							unit:$('#unit').val(),
							level:$('#level').val(),
							title:$('#title').val(),
							goal:goal.getData(),
							content:content.getData(),
							example:example.getData(),
							practice:practice.getData(),
							implement:implement.getData(),
							verify_code:$('#verify_code').val(),
							key:$('#key').val()
						}
					);
					
					return false;
				});
			});
		</script>
	</body>
</html>
