<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!permission_check('admin_groups_lesson')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	if (isset($_POST['unit']) and isset($_FILES['file'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_upload_image']) and $_COOKIE['verify_code_upload_image'] == $_POST['verify_code']) {   
			if ($_FILES['file']['error'] != 0) {
				$message = 'Please check the image that you have uploaded.';
			} else {
				$image = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
				$result = json_decode($image->add_image($_POST['unit'], $_FILES['file']));
				if (isset($result->{'error'})) {
					$message = $result->{'error'};
				} else {
					$message = 'Uploaded success!';
				}
			}
		} else {
			$message = '上傳頁面已失效';
		}
	}
	
	$verify_code = verify_code();
	set_cookie('verify_code_upload_image', $verify_code, 600);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>新增圖片</title>
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
		<script src="<?php echo $prefix.'scripts/js/jquery.center.min.js' ?>"></script>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
<?php
	if (isset($message)) {
		echo <<<EOD
				<div class="warning t-center">
						<h3>$message</h3>
				</div>\n
EOD;
	}
?>
			<div id="upload_image-r" style="position:relative;">
				<div class="m-center" style="width:500px;">
					<form name="upload_image" id="upload_image" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="unit">單元：</label>
								<select id="unit" name="unit" required>								
<?php
	$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	$result = $lesson->list_lesson();
	for ($i = (count($result) - 1); $i >= 0; $i--) {
?>
								<option value="<?php echo $result[$i]['lesson_key']; ?>"><?php echo $result[$i]['lesson_unit']; ?></option>
<?php
	}
?>
								</select>
							</div>
							<div class="pure-control-group">
								<label for="file">圖片：</label>
								<input type="file" id="file" name="file" accept="image/*" required>
							</div>
							<div style="display:hidden;">
								<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_upload_image']; ?>" hidden readonly autocomplete="off" required>
							</div>
							<div class="pure-control-group t-center">
								<button type="submit" id="submit" class="pure-button pure-button-primary">上傳</button>
							</div>
						</fieldset>	
					</form>
				</div>
			</div>
		</div>
<?php display_footer($prefix); ?>
	<script>
		$(document).ready(function(){
			$('#upload_image-r').center({against:'parent'});
		});
	</script>
	</body>
</html>