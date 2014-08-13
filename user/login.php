<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (isset($_SESSION['uid'])) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	if (!isset($_COOKIE['verify_code_login'])) {
		setcookie("verify_code_login", verify_code(), $current_time + 600, "/", WEB_DOMAIN_NAME);
	}
	
	if (isset($_POST['username']) and isset($_POST['passward'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_login']) and $_COOKIE['verify_code_login'] == $_POST['verify_code']) {
			$remember = (isset($_POST['remember'])) ? 1 : 0;
			$login = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $login->login($_POST['username'], $_POST['passward'], $remember);
			if ($message === true) {
				setcookie("verify_code_login", '', $current_time - 600, "/", WEB_DOMAIN_NAME);
				header("Location: ".$prefix."index.php");
				exit();
			}
		} else {
			$message = '登入頁面已失效，請重新登入';
		}
		setcookie("verify_code_login", verify_code(), $current_time + 600, "/", WEB_DOMAIN_NAME);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>登入</title>
		<link type="text/css" href="<?php echo $prefix.'scripts/css/pure.css' ?>" rel="stylesheet">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/index.css' ?>" rel="stylesheet">
<?php display_scripts_link(); ?>
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div class="login-area">
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
				<h1>登入</h1>
				<form name="login" id="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<fieldset>
						<div class="login-area-content">
							<label for="username">帳號：</label>
							<input type="text" name="username" id="username" autocomplete="off" required>
						</div>
						<div class="login-area-content">
							<label for="passward">密碼：</label>
							<input type="password" name="passward" id="password" autocomplete="off" required>
						</div>
						<div class="login-area-content">
							<label for="remember">記住我</label>
							<input type="checkbox" name="remember" id="remember" value="1">
						</div>
						<div class="login-area-content">
							<input type="text" name="verify_code" id="verify_code" value="<?php echo $_COOKIE['verify_code_login']; ?>" hidden readonly autocomplete="off" required>
						</div>
						<div class="login-area-content">
							<button type="submit" id="submit">登入</button>
						</div>
					</fieldset>	
				</form>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){$("#login").submit(function(){$("#submit").attr("disabled",true);$("#password").val(new jsSHA($("#password").val(),"TEXT").getHash("SHA-512","HEX",2048));});});
		</script>
	</body>
</html>