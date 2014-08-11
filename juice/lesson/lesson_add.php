<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!isset($_COOKIE['verify_code_add_lesson'])) {
		setcookie("verify_code_add_lesson", verify_code(), $current_time + 1800, "/", WEB_DOMAIN_NAME);
	}
	
	if (isset($_POST['unit']) and isset($_POST['level']) and isset($_POST['title']) and isset($_POST['goal']) and isset($_POST['content']) and isset($_POST['example']) and isset($_POST['practice']) and isset($_POST['implement'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_add_lesson']) and $_COOKIE['verify_code_add_lesson'] == $_POST['verify_code']) {
			$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $lesson->add_lesson($_POST['username'], $_POST['passward'], $remember);
			if ($message === true) {
				setcookie("verify_code_add_lesson", '', $current_time - 600, "/", WEB_DOMAIN_NAME);
				header("Location: ".$prefix."index.php");
				exit();
			}
		} else {
			$message = '登入頁面已失效，請重新登入';
		}
		setcookie("verify_code_add_lesson", verify_code(), $current_time + 1800, "/", WEB_DOMAIN_NAME);
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
				<h3><?php echo $message; ?></h3>
			</div>
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
						<br>
						<div>
							<div class="juice-lesson-contents">
								<label for="goal">學習目標：</label>
								<textarea class="ckeditor" name="goal" id="goal" required></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="content">課程內容：</label>
								<textarea class="ckeditor" name="content" id="content" required></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="example">範　　例：</label>
								<textarea class="ckeditor" name="example" id="example" required></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="practice">填空練習：</label>
								<textarea class="ckeditor" name="practice" id="practice" required></textarea>
							</div>
							<br>
							<hr>
							<br>
							<div class="juice-lesson-contents">
								<label for="implement">動 動 腦：</label>
								<textarea class="ckeditor" name="implement" id="implement" value="test" required></textarea>
							</div>
							<div>
								<input type="text" name="verify_code" id="verify_code" value="<?php echo $_COOKIE['verify_code_add_lesson']; ?>" hidden readonly autocomplete="off" required>
								<input type="text" name="key" id="key" value="" hidden readonly autocomplete="off">
							</div>
						</div>
						<br>
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
