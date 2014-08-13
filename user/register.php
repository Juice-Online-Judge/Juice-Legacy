<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (isset($_SESSION['uid'])) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	if (isset($_POST['username']) and isset($_POST['passward']) and isset($_POST['passward_check']) and isset($_POST['second_passward']) and isset($_POST['nickname']) and isset($_POST['email'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_register']) and $_COOKIE['verify_code_register'] == $_POST['verify_code']) {
			$register = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $register->register($_POST['username'], $_POST['passward'], $_POST['passward_check'], $_POST['second_passward'], $_POST['email'], $_POST['nickname']);
			if ($message === true) {
				setcookie("verify_code_register", '', $current_time - 600, "/", WEB_DOMAIN_NAME, false, true);
				header("Location: ".$prefix."user/login.php");
				exit();
			}
		} else {
			$message = '註冊頁面已失效';
		}
	}
	$verify_code = verify_code();
	setcookie("verify_code_register", $verify_code, $current_time + 600, "/", WEB_DOMAIN_NAME, false, true);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>註冊頁面</title>
		<link rel="icon" href="" type="image/x-icon">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/pure.css' ?>" rel="stylesheet">
<?php display_scripts_link(); ?>
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div>
<?php
	if (isset($message)) {
		echo <<<EOD
				<div>
						<h3>$message</h3>
				</div>\n
EOD;
	}
?>
				<div>
				<h1>註冊<?php echo $verify_code.' '.$_COOKIE['verify_code_register'] ;?></h1>
					<form name="register" id="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="username">帳號：</label>
								<input type="text" name="username" id="username" pattern="^\w{5,32}$" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="passward">密碼：</label>
								<input type="password" name="passward" id="password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="passward_check">密碼確認：</label>
								<input type="password" name="passward_check" id="passward_check" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="second_passward">第二組密碼：</label>
								<input type="password" name="second_passward" id="second_passward" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="nickname">暱稱:</label>
								<input type="text" name="nickname" id="nickname" pattern="^.{5,16}$" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="email">信箱:</label>
								<input type="email" name="email" id="email" maxlength="128" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_register']; ?>" hidden readonly autocomplete="off" required>
							</div>
							<div class="pure-controls">
								<button type="submit" id="submit" class="pure-button pure-button-primary">註冊</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){$("#register").submit(function(){$("#submit").attr("disabled",true);$("#password").val(new jsSHA($("#password").val(),"TEXT").getHash("SHA-512","HEX",2048));$("#passward_check").val(new jsSHA($("#passward_check").val(),"TEXT").getHash("SHA-512","HEX",2048));$("#second_passward").val(new jsSHA($("#second_passward").val(),"TEXT").getHash("SHA-512","HEX",2048));});});
		</script>
	</body>
</html>