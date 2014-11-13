<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (false/*!permission_check('admin_groups')*/) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	$about = new about('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	if (isset($_POST['nickname']) and isset($_POST['content']) and isset($_POST['action'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_about_refine']) and $_COOKIE['verify_code_about_refine'] == $_POST['verify_code']) {
			$data['nickname'] = $_POST['nickname'];
			$data['content'] = $_POST['content'];
			$data['type'] = 2;
			$message = $about->update_about($_POST['action'], $data);
			if ($message === true) {
				del_cookie('verify_code_about_refine');
				unset($message);
			}
		} else {
			$message = '頁面已失效';
		}
	}
	
	$is_add = false;
	$about_content = $about->get_about_content(2);
	if (empty($about_content)) {
		$is_add = true;
	}
	$verify_code = verify_code();
	set_cookie('verify_code_about_refine', $verify_code, 1200);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo ($is_add) ? '新增介紹' : '修改介紹'; ?></title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="<?php echo WEB_ROOT_DIR; ?>scripts/ckeditor/ckeditor.js"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
			<div class="juice_body">
				<div class="pure-u-1-8"></div>
				<div class="pure-u-3-4">
<?php
	if (isset($message)) {
		echo <<<EOD
					<div class="warning t-center">
							<h3>$message</h3>
					</div>\n
EOD;
	}
?>
					<div>
						<form name="about_refine" id="about_refine" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
							<fieldset>
								<div>
									<label for="nickname">暱稱：</label>
									<input type="text" id="nickname" name="nickname" value="<?php echo ($is_add) ? '' : $about_content['nickname']; ?>" maxlength="16" autocomplete="off" required>
								</div>
								<div>
									<label for="content"><h3>介紹：</h3></label>
									<textarea class="ckeditor" name="content" id="content" required>
<?php
		echo ($is_add) ? '' : $about_content['content'];
?>
									</textarea>
								</div>
								<div>
									<input type="text" name="action" id="action" value="<?php echo ($is_add) ? 'add' : 'update'; ?>" hidden readonly autocomplete="off">
									<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_about_refine']; ?>" hidden readonly autocomplete="off" required>
								</div>
								<div>
									<div class="pure-control-group t-center">
										<button type="submit" name="submit" id="submit" class="pure-button pure-button-primary"><?php echo ($is_add) ? '新增' : '修改'; ?></button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div class="pure-u-1-8"></div>
			</div>
<?php display_footer($prefix); ?>
	</body>
</html>