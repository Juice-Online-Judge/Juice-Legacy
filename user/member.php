<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	/*
	if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['password_check']) and isset($_POST['second_password']) and isset($_POST['nickname']) and isset($_POST['email'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_member']) and $_COOKIE['verify_code_member'] == $_POST['verify_code']) {
			$register = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $register->register($_POST['username'], $_POST['password'], $_POST['password_check'], $_POST['second_password'], $_POST['email'], $_POST['nickname']);
			if ($message === true) {
				setcookie("verify_code_member", '', $current_time - 600, '/', '', false, true);
				header("Location: ".$prefix."user/login.php");
				exit();
			}
		} else {
			$message = '註冊頁面已失效';
		}
	}*/
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
				<div class="shadow m-center" style="width:500px;">
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
						<div class="title t-center">
							<h2>密碼更改</h2>
						</div>
						<div>
							<form name="change_pw" id="change_pw" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
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
										<button type="submit" id="submit_cpw" class="pure-button pure-button-primary">修改</button>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div>
						<div class="title t-center">
							<h2>資料更改</h2>
						</div>
						<div>
							<form name="change_info" id="change_info" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
								<fieldset>
									<div class="pure-control-group">
										<label for="nickname">暱稱:</label>
										<input type="text" name="nickname" id="nickname" pattern="^.{5,16}$" autocomplete="off">
									</div>
									<div class="pure-control-group">
										<label for="email">信箱:</label>
										<input type="email" name="email" id="email" maxlength="128" autocomplete="off">
									</div>
									<div style="display:hidden;">
										<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_member']; ?>" hidden readonly autocomplete="off" required>
									</div>
									<div class="pure-control-group t-center">
										<button type="submit" id="submit_cif" class="pure-button pure-button-primary">修改</button>
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
				$("#change_pw").submit(function(){
					$("#submit_cpw").attr("disabled",true);
					$(":password").each(function(){
						$(this).val(new jsSHA($(this).val(),"TEXT").getHash("SHA-512","HEX",2048));
					});
				});
				
				$('#member').center({against:'parent'});
			});
		</script>
	</body>
</html>