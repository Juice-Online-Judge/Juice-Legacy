<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['password_check']) and isset($_POST['second_password']) and isset($_POST['nickname']) and isset($_POST['email'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_register']) and $_COOKIE['verify_code_register'] == $_POST['verify_code']) {
			$register = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $register->register($_POST['username'], $_POST['password'], $_POST['password_check'], $_POST['second_password'], $_POST['email'], $_POST['nickname']);
			if ($message === true) {
				setcookie("verify_code_register", '', $current_time - 600, '/', '', false, true);
				header("Location: ".$prefix."user/login.php");
				exit();
			}
		} else {
			$message = '註冊頁面已失效';
		}
	}
	$verify_code = verify_code();
	setcookie("verify_code_register", $verify_code, $current_time + 600, '/', '', false, true);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>註冊頁面</title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="<?php echo $prefix.'scripts/js/jquery.center.min.js' ?>"></script>
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div id="register-r" style="position:relative;">
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
					<div class="title t-center">
						<h2>帳號註冊</h2>
					</div>
					<div>
						<form name="register" id="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
							<fieldset>
								<div class="pure-control-group">
									<label for="username">帳號：</label>
									<input type="text" name="username" id="username" pattern="^\w{5,32}$" autocomplete="off" required>
								</div>
								<div class="pure-control-group">
									<label for="password">密碼：</label>
									<input type="password" name="password" id="password" autocomplete="off" required>
								</div>
								<div class="pure-control-group">
									<label for="password_check">密碼確認：</label>
									<input type="password" name="password_check" id="password_check" autocomplete="off" required>
								</div>
								<div class="pure-control-group">
									<label for="second_password">第二組密碼：</label>
									<input type="password" name="second_password" id="second_password" autocomplete="off" required>
								</div>
								<div class="pure-control-group">
									<label for="nickname">暱稱:</label>
									<input type="text" name="nickname" id="nickname" pattern="^.{5,16}$" autocomplete="off" required>
								</div>
								<div class="pure-control-group">
									<label for="email">信箱:</label>
									<input type="email" name="email" id="email" maxlength="128" autocomplete="off" required>
								</div>
								<div style="display:hidden;">
									<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_register']; ?>" hidden readonly autocomplete="off" required>
								</div>
								<div class="pure-control-group t-center">
									<button type="submit" id="submit" class="pure-button pure-button-primary">註冊</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){$("#register").submit(function(){$("#submit").attr("disabled",true);$("#password").val(new jsSHA($("#password").val(),"TEXT").getHash("SHA-512","HEX",2048));$("#password_check").val(new jsSHA($("#password_check").val(),"TEXT").getHash("SHA-512","HEX",2048));$("#second_password").val(new jsSHA($("#second_password").val(),"TEXT").getHash("SHA-512","HEX",2048));});});
			$(document).ready(function(){
				$('#register-r').center({against:'parent'});
			});
		</script>
	</body>
</html>