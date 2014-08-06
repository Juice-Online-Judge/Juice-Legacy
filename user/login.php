<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!isset($_COOKIE['verify_code_login'])) {
		setcookie("verify_code_login", verify_code(), $current_time + 600, "/", WEB_DOMAIN_NAME);
	}
	
	if (isset($_POST['username']) and isset($_POST['passward'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_login']) and $_COOKIE['verify_code_login'] == $_POST['verify_code']) {
			$remember = (isset($_POST['remember'])) ? 1 : 0;
			$login = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $login->login($_POST['username'], $_POST['passward'], $remember);
			if ($message === true) {
				header("Location: ".$prefix."index.php");
				exit();
			}
		} else {
			$message = '登入頁面已失效，請重新登入';
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>登入</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	</head>
	<body>
		<div>
			<h1>登入</h1>
			<form name="login" id="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<fieldset>
					<div>
						<label for="username">帳號：</label>
						<input type="text" name="username" id="username" autocomplete="off" required>
					</div>
					<div>
						<label for="passward">密碼：</label>
						<input type="password" name="passward" id="password" autocomplete="off" required>
					</div>
					<div>
						<label for="remember">記住我</label>
						<input type="checkbox" name="remember" id="remember" value="1">
					</div>
					<div>
						<input type="text" name="verify_code" id="verify_code" value="<?php echo $_COOKIE['verify_code_login']; ?>" hidden readonly autocomplete="off" required>
					</div>
					<div>
						<button type="submit" id="submit">登入</button>
					</div>
				</fieldset>	
			</form>
		</div>
	</body>
</html>