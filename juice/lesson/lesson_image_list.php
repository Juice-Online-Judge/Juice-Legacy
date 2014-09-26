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
	
	$lesson = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	if (isset($_GET['key']) and isset($_POST['image_key'])) {
		$lesson->delete_image($_GET['key'], $_POST['image_key']);
	}
	$result = $lesson->list_lesson_image($_GET['key']);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>圖片列表</title>
		<!--<link rel="icon" href="" type="image/x-icon">-->
<?php display_css_link($prefix); ?>
<?php display_scripts_link(); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div id="main">
			<div class="pure-g">
				<div class="pure-u-1-1">
					<div id="lesson_list" class="t-center">
						<div>
							<h1 class="title">圖片列表</h1>
						</div>
						<div style="width:100%;">
							<table class="pure-table pure-table-bordered m-center">
								<thead>
									<tr class="t-center">
										<th>#</th>
										<th>圖　　片</th>
										<th>連　　結</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
<?php
	if (!empty($result)) {
		$i = 1;
		foreach ($result as $tmp) {
?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><a href="<?php echo $prefix.'others/show_imgages.php?key='.$_GET['key'].'&image_key='.$tmp['image_key']; ?>" target="_blank"><img src="<?php echo $prefix.'others/show_imgages.php?key='.$_GET['key'].'&image_key='.$tmp['image_key']; ?>" style="max-width:480px;"></a></td>
										<td><input type="text" value="<?php echo 'http://'.WEB_DOMAIN_NAME.'/freedom/juice/others/show_imgages.php?key='.$_GET['key'].'&image_key='.$tmp['image_key']; ?>"></td>
										<td>
											<form name="delete_img" id="delete_img" action="<?php echo $_SERVER['PHP_SELF'].'?key='.$_GET['key']; ?>" method="POST" class="pure-form pure-form-aligned">
												<div style="display:hidden;">
													<input type="text" name="image_key" id="image_key" value="<?php echo $tmp['image_key']; ?>" hidden readonly autocomplete="off" required>
												</div>
												<div class="pure-control-group t-center">
													<button type="submit" id="submit" class="pure-button pure-button-primary">刪除</button>
												</div>
											</form>
										</td>
									</tr>
<?php
		}
	}
?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php display_footer($prefix); ?>
		<script>
			$(document).ready(function(){
				$("input[type='text']").on("click", function () {
					$(this).select();
				});
			});
		</script>
	</body>
</html>