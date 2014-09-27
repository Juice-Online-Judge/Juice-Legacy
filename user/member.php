<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	
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
				<div class="m-center" style="width:500px">
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
										<button type="submit" name="submit_cpw" class="pure-button pure-button-primary">修改</button>
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
							<form name="update_info" id="update_info" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
								<fieldset>
									<div class="pure-control-group">
										<label for="nickname">暱稱:</label>
										<input type="text" name="nickname" id="nickname" pattern="^.{5,16}$" autocomplete="off" required>
									</div>
									<div class="pure-control-group">
										<label for="email">信箱:</label>
										<input type="email" name="email" id="email" maxlength="128" autocomplete="off" required>
									</div>
									<div style="display:hidden;">
										<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_member']; ?>" hidden readonly autocomplete="off" required>
									</div>
									<div class="pure-control-group t-center">
										<button type="submit" name="submit_cif" class="pure-button pure-button-primary">修改</button>
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
				$(':submit').click(function(){
					$(':submit').attr('disabled', true);
				});
				
				$('#update_pw').submit(function(){
					$(':password').each(function(){
						$(this).val(new jsSHA($(this).val(), 'TEXT').getHash('SHA-512', 'HEX', 2048));
					});
				});
				
				$('#member').center({against:'parent'});
			});
		</script>
	</body>
</html>