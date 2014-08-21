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
	$result = $lesson->list_lesson_image($_GET['key']);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>圖片列表</title>
		<link rel="icon" href="" type="image/x-icon">
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
							<table class="pure-table pure-form-aligned m-center">
								<thead>
									<tr class="t-center">
										<th>#</th>
										<th>圖　　片</th>
										<th>連　　結</th>
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
										<td style="max-width:480px;"><img src="<?php echo $prefix.'others/show_imgages.php?key='.$_GET['key'].'&image_key='.$tmp['image_key']; ?>"></td>
										<td><input type="text" value="<?php echo 'http://'.WEB_DOMAIN_NAME.'/freedom/juice/others/show_imgages.php?key='.$_GET['key'].'&image_key='.$tmp['image_key']; ?>"></td>
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
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){
				$("input[type='text']").on("click", function () {
					$(this).select();
				});
			});
		</script>
	</body>
</html>