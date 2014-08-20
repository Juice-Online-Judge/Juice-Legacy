<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!permission_check('admin_groups_lesson')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	/*
	if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_login']) and $_COOKIE['verify_code_login'] == $_POST['verify_code']) {
		foreach ($_FILES['files']['name'] as $key => $name) {     
			if ($_FILES['files']['error'][$key] != 0) {
				continue;
			} else {
				
			}
		}
		$image = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
		$message = $image->add_image($_POST['username'], $_POST['passward'], $remember);
		if ($message === true) {
			setcookie("verify_code_login", '', $current_time - 600, '/', '', false, true);
			header("Location: ".$prefix."index.php");
			exit();
		}
	} else {
		$message = '登入頁面已失效，請重新登入';
	}
	*/
	$verify_code = verify_code();
	setcookie("verify_code_upload_image", $verify_code, $current_time + 600, '/', '', false, true);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>新增圖片</title>
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div>
				<div>
					<form name="upload_image" id="upload_image" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="files">圖片：</label>
								<input type="file" id="files" name="files[]" accept="image/*">
							</div>
							<div style="display:hidden;">
								<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_upload_image']; ?>" hidden readonly autocomplete="off" required>
							</div>
							<div class="pure-control-group t-center">
								<button type="submit" id="submit" class="pure-button pure-button-primary">上傳</button>
							</div>
						</fieldset>	
					</form>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
	</body>
</html>