<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!ALLOW_REGISTER) {
		$page_message = '很抱歉，註冊功能關閉中';
	} else if (permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	} else {
		if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['password_check']) and isset($_POST['nickname']) and isset($_POST['email']) and isset($_POST['std_id'])) {
			if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_register']) and $_COOKIE['verify_code_register'] == $_POST['verify_code']) {
				$register = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
				$message = $register->register($_POST['username'], $_POST['password'], $_POST['password_check'], $_POST['email'], $_POST['nickname'], $_POST['std_id']);
				if ($message === true) {
					del_cookie('verify_code_register');
					header("Location: ".$prefix."user/login.php");
					exit();
				}
			} else {
				$message = '註冊頁面已失效';
			}
		}
		$verify_code = verify_code();
		set_cookie('verify_code_register', $verify_code, 600);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>帳號註冊</title>
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div class="juice_body">
			<div class="login_space shadow">
				<div class="u-1-1 title">
					<h2>帳號註冊</h2>
				</div>
<?php
	if (isset($page_message)) {
		echo <<<EOD
				<div class="u-1-1 warning">
					<h3>$page_message</h3>
				</div>\n
EOD;
	} else {
		if (isset($message)) {
			echo <<<EOD
				<div class="u-1-1 warning">
							<h3>$message</h3>
				</div>\n
EOD;
		}
?>
				<div class="u-1-1">
					<form name="register" id="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
						<fieldset>
							<div>
								<label for="username">帳　　號：</label>
								<input type="text" name="username" id="username" size="25" pattern="^\w{5,32}$" placeholder="5 ~ 32 個英文或數字" autocomplete="off" required>
							</div>
							<div>
								<label for="password">密　　碼：</label>
								<input type="password" name="password" id="password" size="25" autocomplete="off" required>
							</div>
							<div>
								<label for="password_check">密碼確認：</label>
								<input type="password" name="password_check" id="password_check" size="25" autocomplete="off" required>
							</div>
							<div>
								<label for="nickname">暱　　稱：</label>
								<input type="text" name="nickname" id="nickname" size="25" pattern="^.{5,16}$" placeholder="5 ~ 16 個字" autocomplete="off" required>
							</div>
							<div>
								<label for="email">信　　箱：</label>
								<input type="email" name="email" id="email" size="25" maxlength="128" autocomplete="off" required>
							</div>
							<div>
								<label for="std_id">學　　號：</label>
								<input type="text" name="std_id" id="std_id" size="25" maxlength="9" pattern="^\d{9}$" placeholder="不可更改，請務必再次確認" autocomplete="off" required>
							</div>
							<div style="display:none;">
								<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_register']; ?>" hidden readonly autocomplete="off" required>
							</div>
							<div>
								<button type="submit" id="submit" class="pure-button pure-button-primary">註冊</button>
							</div>
						</fieldset>
					</form>
				</div>
<?php } ?>
			</div>
		</div>
<?php display_footer($prefix); ?>
		<script>
			$(document).ready(function(){
				$("#register").submit(function(){
					$("#submit").attr("disabled",true);
					$(":password").each(function(){
						$(this).val(new jsSHA($(this).val(),"TEXT").getHash("SHA-512","HEX",2048));
					});
				});
			});
		</script>
	</body>
</html>