<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	$account = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	if (isset($_POST['second_pw']) and isset($_POST['new_pw']) and isset($_POST['new_pw_check'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_member']) and $_COOKIE['verify_code_member'] == $_POST['verify_code']) {
			$message = $account->update_pw($_POST['second_pw'], $_POST['new_pw'], $_POST['new_pw_check']);
			if ($message === true) {
				$message = '密碼更新成功';
			}
		} else {
			$message = '頁面已失效';
		}
	} else if (isset($_POST['nickname']) and isset($_POST['email'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_member']) and $_COOKIE['verify_code_member'] == $_POST['verify_code']) {
			$message = $account->update_info($_POST['email'], $_POST['nickname']);
			if ($message === true) {
				$message = '資料更新成功';
			}
		} else {
			$message = '頁面已失效';
		}
	}
	$user_info = $account->get_user_data();
	
	$verify_code = verify_code();
	set_cookie('verify_code_member', $verify_code, 600);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>會員中心</title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="<?php echo $prefix.'scripts/js/jquery.center.min.js' ?>"></script>
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div id="member" style="position:relative;">
				<div class="pure-g m-center" style="width:1024px">
<?php
	if (isset($message)) {
		echo <<<EOD
					<div class="pure-u-1-1 warning t-center">
							<h3>$message</h3>
					</div>\n
EOD;
	}
?>
					<div class="pure-u-1-2">
						<div class="title t-center">
							<h2>密碼更改</h2>
						</div>
						<div>
							<form name="update_pw" id="update_pw" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
								<fieldset>
									<div class="pure-control-group">
										<label for="second_pw">第二組密碼：</label>
										<input type="password" name="second_pw" id="second_pw" autocomplete="off" required>
									</div>
									<div class="pure-control-group">
										<label for="new_pw">新密碼：</label>
										<input type="password" name="new_pw" id="new_pw" autocomplete="off" required>
									</div>
									<div class="pure-control-group">
										<label for="new_pw_check">新密碼確認：</label>
										<input type="password" name="new_pw_check" id="new_pw_check" autocomplete="off" required>
									</div>
									<div style="display:hidden;">
										<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_member']; ?>" hidden readonly autocomplete="off" required>
									</div>
									<div class="pure-control-group t-center">
										<input type="submit" value="修改" class="pure-button pure-button-primary">
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="pure-u-1-2">
						<div class="title t-center">
							<h2>資料更改</h2>
						</div>
						<div>
							<form name="update_info" id="update_info" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
								<fieldset>
									<div class="pure-control-group">
										<label for="nickname">暱稱:</label>
										<input type="text" name="nickname" id="nickname" value="<?php echo $user_info['nickname']; ?>" pattern="^.{5,16}$" autocomplete="off" required>
									</div>
									<div class="pure-control-group">
										<label for="email">信箱:</label>
										<input type="email" name="email" id="email" value="<?php echo $user_info['email']; ?>" maxlength="128" autocomplete="off" required>
									</div>
									<div style="display:hidden;">
										<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_member']; ?>" hidden readonly autocomplete="off" required>
									</div>
									<div class="pure-control-group t-center">
										<input type="submit" value="修改" class="pure-button pure-button-primary">
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php display_footer($prefix); ?>
		<script>
			$(document).ready(function(){
				$('#update_pw').submit(function(){
					$(':submit').attr('disabled', true);
					$(':password').each(function(){
						$(this).val(new jsSHA($(this).val(), 'TEXT').getHash('SHA-512', 'HEX', 2048));
					});
				});
				
				$('#member').center({against:'parent'});
			});
		</script>
	</body>
</html>