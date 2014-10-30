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
	if (isset($_POST['nickname']) and isset($_POST['email'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_member']) and $_COOKIE['verify_code_member'] == $_POST['verify_code']) {
			$message = $account->update_info($_POST['email'], $_POST['nickname']);
			if ($message === true) {
				$message = '資料更新成功';
			}
		} else {
			$message = '頁面已失效';
		}
	} else if (isset($_POST['old_pw']) and isset($_POST['new_pw']) and isset($_POST['new_pw_check'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_member']) and $_COOKIE['verify_code_member'] == $_POST['verify_code']) {
			$message = $account->update_pw($_POST['old_pw'], $_POST['new_pw'], $_POST['new_pw_check']);
			if ($message === true) {
				$message = '密碼更新成功';
			}
		} else {
			$message = '頁面已失效';
		}
	} else if (isset($_FILES['file'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_member']) and $_COOKIE['verify_code_member'] == $_POST['verify_code']) {
			if ($_FILES['file']['error'] != 0) {
				$message = '圖片損毀，請嘗試重新上傳';
			} else {
				$message = $account->update_profile_picture($_FILES['file']);
				if ($message === true) {
					$message = '更新成功';
				}
			}
		} else {
			$message = '頁面已失效';
		}
	} else {
		$user_info = $account->get_user_data();
		
		$verify_code = verify_code();
		set_cookie('verify_code_member', $verify_code, 600);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>會員中心</title>
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $prefix.'scripts/css/member.css'; ?>">
		<script src="<?php echo $prefix.'scripts/js/jquery.center.min.js' ?>"></script>
		<script src="<?php echo $prefix.'scripts/js/sha-512.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="member" class="juice_body">
			<div class="u-1-12"></div>
			<div class="u-5-6">
				<div class="member_space shadow">
					<div class="title u-1-3">
						<h2>大頭貼更改</h2>
					</div>
					<div class="title u-1-3">
						<h2>資料更改</h2>
					</div>
					<div class="title u-1-3">
						<h2>密碼更改</h2>
					</div>
<?php
	if (isset($message) and isset($_FILES['file'])) {
		echo <<<EOD
					<div class="u-1-3 warning">
							<h3>$message</h3>
					</div>
					<div class="u-2-3"></div>
EOD;
	}
?>
<?php
	if (isset($message) and isset($_POST['nickname'])) {
		echo <<<EOD
					<div class="u-1-3"></div>
					<div class="u-1-3 warning">
							<h3>$message</h3>
					</div>
					<div class="u-1-3"></div>
EOD;
	}
?>
<?php
	if (isset($message) and isset($_POST['old_pw'])) {
		echo <<<EOD
					<div class="u-2-3"></div>
					<div class="u-1-3 warning">
							<h3>$message</h3>
					</div>
EOD;
	}
?>
					<div class="u-1-3">
						<form name="update_profile_picture" id="update_profile_picture" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
							<fieldset>
								<div>
									<img src="<?php echo $prefix.'others/api/apiShowProfileImage.php?uid='.$_SESSION['uid']; ?>" height="128px" width="128px">
								</div>
								<div>
									<input type="file" id="file" name="file" accept="image/*" required>
								</div>
								<div>
									<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_member']; ?>" hidden readonly autocomplete="off" required>
									<button type="submit" class="pure-button pure-button-primary">上傳</button>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="u-1-3">
						<form name="update_info" id="update_info" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
							<fieldset>
								<div>
									<span>學號：</span>
									<span><?php echo $user_info['std_id']; ?></span>
								</div>
								<div>
									<label for="nickname">暱稱：</label>
									<input type="text" name="nickname" id="nickname" value="<?php echo $user_info['nickname']; ?>" pattern="^.{5,16}$" autocomplete="off" required>
								</div>
								<div>
									<label for="email">信箱：</label>
									<input type="email" name="email" id="email" value="<?php echo $user_info['email']; ?>" maxlength="128" autocomplete="off" required>
								</div>
								<div>
									<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_member']; ?>" hidden readonly autocomplete="off" required>
									<button type="submit" class="pure-button pure-button-primary">修改</button>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="u-1-3">
						<form name="update_pw" id="update_pw" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
							<fieldset>
								<div>
									<label for="old_pw">原　密　碼：</label>
									<input type="password" name="old_pw" id="old_pw" autocomplete="off" required>
								</div>
								<div>
									<label for="new_pw">新　密　碼：</label>
									<input type="password" name="new_pw" id="new_pw" autocomplete="off" required>
								</div>
								<div>
									<label for="new_pw_check">新密碼確認：</label>
									<input type="password" name="new_pw_check" id="new_pw_check" autocomplete="off" required>
								</div>
								<div>
									<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_member']; ?>" hidden readonly autocomplete="off" required>
									<button type="submit" class="pure-button pure-button-primary">修改</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="u-1-12"></div>
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
			});
			
			$(window).load(function(){
				$('#member').center({against:'parent'});
			});
		</script>
	</body>
</html>