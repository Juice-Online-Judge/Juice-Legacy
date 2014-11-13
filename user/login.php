<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	page_check('user_login');
	
	if (!ALLOW_LOGIN) {
		$page_message = '很抱歉，登入功能關閉中';
	} else {
		if (isset($_POST['username']) and isset($_POST['passward'])) {
			if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_login']) and $_COOKIE['verify_code_login'] == $_POST['verify_code']) {
				$remember = (isset($_POST['remember'])) ? 1 : 0;
				$login = new account();
				$message = $login->login($_POST['username'], $_POST['passward'], $remember);
				if ($message === true) {
					del_cookie('verify_code_login');
					header("Location: ".$prefix."index.php");
					exit();
				}
			} else {
				$message = '登入頁面已失效，請重新登入';
			}
		}
		$verify_code = verify_code();
		set_cookie('verify_code_login', $verify_code, 600);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>Juice</title>
<?php display_link('css'); ?>
<?php display_link('js'); ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $prefix.'scripts/css/login.css'; ?>">
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		
			<div class="juice_body">
				<div class="u-3-5">
				</div>
				<div class="u-2-5">
					<div class="login_space shadow">
						<div class="u-1-1">
							<form name="login" id="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
								<fieldset>
									<div class="u-1-1 title">
										<h2>帳號登入</h2>
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
									<div>
										<label for="username">帳號：</label>
										<input type="text" name="username" id="username" size="25" autocomplete="off" required>
									</div>
									<div>
										<label for="passward">密碼：</label>
										<input type="password" name="passward" id="password" size="25" autocomplete="off" required>
									</div>
									<div>
										<label for="remember">記住我</label>
										<input type="checkbox" name="remember" id="remember" value="1">
										<div style="display:none;">
											<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_login']; ?>" hidden readonly autocomplete="off" required>
										</div>
										<button type="submit" id="submit" class="pure-button pure-button-primary">登入</button>
									</div>
								</fieldset>	
							</form>
						</div>
<?php } ?>
						<div class="u-1-1 login_bulletin">
							<span>1</span>
							<span>2</span>
							<span>3</span>
							<span>4</span>
							<span>5</span>
						</div>
					</div>
				</div>
			</div>
		
<?php display_footer($prefix); ?>
		<script>
			$(document).ready(function(){
				$("#login").submit(function(){
					$("#submit").attr("disabled",true);
					$(":password").each(function(){
						$(this).val(new jsSHA($(this).val(),"TEXT").getHash("SHA-512","HEX",2048));
					});
				});
			});
		</script>
	</body>
</html>