<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (isset($_SESSION['uid'])) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	if (isset($_POST['username']) and isset($_POST['passward'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_login']) and $_COOKIE['verify_code_login'] == $_POST['verify_code']) {
			$remember = (isset($_POST['remember'])) ? 1 : 0;
			$login = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$message = $login->login($_POST['username'], $_POST['passward'], $remember);
			if ($message === true) {
				setcookie("verify_code_login", '', $current_time - 600, '/', '', false, true);
				header("Location: ".$prefix."index.php");
				exit();
			}
		} else {
			$message = '登入頁面已失效，請重新登入';
		}
	}
	$verify_code = verify_code();
	setcookie("verify_code_login", $verify_code, $current_time + 600, '/', '', false, true);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>登入</title>
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="<?php echo $prefix.'scripts/js/jquery.center.min.js' ?>"></script>
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div class="pure-g fullheight">
				<div class="pure-u-2-3 fullheight">
					<p>Introduction</p>
				</div>
				<div class="pure-u-1-3 fullheight">
					<div id="test2" style="position:relative;">
<?php
	if (isset($message)) {
		echo <<<EOD
						<div class="warning center">
								<h3>$message</h3>
						</div>\n
EOD;
	}
?>
						<div>
							<form name="login" id="login" class="pure-form pure-form-aligned" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
								<fieldset>
									<div class="pure-control-group">
										<label for="username">帳號：</label>
										<input type="text" name="username" id="username" autocomplete="off" required>
									</div>
									<div class="pure-control-group">
										<label for="passward">密碼：</label>
										<input type="password" name="passward" id="password" autocomplete="off" required>
									</div>
									<div class="pure-control-group">
										<label for="remember">記住我</label>
										<input type="checkbox" name="remember" id="remember" value="1">
									</div>
									<div class="pure-controls">
										<button type="submit" id="submit" class="pure-button pure-button-primary">登入</button>
									</div>
								</fieldset>	
								<div class="pure-control-group">
									<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_login']; ?>" hidden readonly autocomplete="off" required>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){$("#login").submit(function(){$("#submit").attr("disabled",true);$("#password").val(new jsSHA($("#password").val(),"TEXT").getHash("SHA-512","HEX",2048));});});
			$(document).ready(function(){
				$('#test2').center({against:'parent'});
			});
		</script>
	</body>
</html>