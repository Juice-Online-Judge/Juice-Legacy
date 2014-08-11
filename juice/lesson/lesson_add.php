<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!isset($_COOKIE['verify_code_add_lesson'])) {
		setcookie("verify_code_add_lesson", verify_code(), $current_time + 600, "/", WEB_DOMAIN_NAME);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>課程新增</title>
		<link rel="icon" href="" type="image/x-icon">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/pure.css' ?>" rel="stylesheet">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/lesson_add.css' ?>" rel="stylesheet">
<?php display_scripts_link(); ?>
		<script src="http://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div class="juice-lesson-body">
			<div>
				<form name="add_lesson" id="add_lesson" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<fieldset>
						<div>
							<div class="juice-lesson-titles">
								<label for="unit">單元：</label>
								<input type="text" id="unit" name="unit" maxlength="2" pattern="^\d{1,2}$" autocomplete="off" required>
							</div>
							<div class="juice-lesson-titles">
								<label for="level">難度：</label>
								<select name="level" id="level" required>
									<option value="0">初階</option>
									<option value="1">中階</option>
									<option value="2">高階</option>
									<option value="3">終階</option>
								</select>
							</div>
							<div class="juice-lesson-titles">
								<label for="title">標題：</label>
								<input type="text" id="title" name="title" maxlength="128" autocomplete="off" required>
							</div>
						</div>
						<div>
							<div class="juice-lesson-contents">
								<label for="goal">學習目標：</label>
								<textarea class="ckeditor" name="goal" id="goal" required></textarea>
							</div>
							<div class="juice-lesson-contents">
								<label for="content">課程內容：</label>
								<textarea class="ckeditor" name="content" id="content" required></textarea>
							</div>
							<div class="juice-lesson-contents">
								<label for="example">範　　例：</label>
								<textarea class="ckeditor" name="example" id="example" required></textarea>
							</div>
							<div class="juice-lesson-contents">
								<label for="practice">填空練習：</label>
								<textarea class="ckeditor" name="practice" id="practice" required></textarea>
							</div>
							<div class="juice-lesson-contents">
								<label for="implement">動 動 腦：</label>
								<textarea class="ckeditor" name="implement" id="implement" required></textarea>
							</div>
							<div>
								<input type="text" name="verify_code" id="verify_code" value="<?php echo $_COOKIE['verify_code_add_lesson']; ?>" hidden readonly autocomplete="off" required>
							</div>
						</div>
						<button class="juice-lesson-button" type="submit" id="submit">新增</button>
					</fieldset>
				</form>
			</div>
		</div>
		<div>
			<footer>
				Web Create by : Juice / Copyright © 2014
			</footer> 
		</div>
	</body>
</html>
