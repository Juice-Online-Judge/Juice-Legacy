<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
		page_check('lesson_list');
	
	$lesson = new lesson();
	if (isset($_POST['lesson_key']) and isset($_POST['lesson_visible'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_lesson_list']) and $_COOKIE['verify_code_lesson_list'] == $_POST['verify_code']) {
			$message = $lesson->change_lesson_visible($_POST['lesson_key'], $_POST['lesson_visible']);
			if ($message === true) {
				$message = '切換成功';
			}
		} else {
			$message = '頁面已失效';
		}
	} else if (isset($_POST['lesson_key']) and isset($_POST['del_lesson'])) {
		if (isset($_POST['verify_code']) and isset($_COOKIE['verify_code_lesson_list']) and $_COOKIE['verify_code_lesson_list'] == $_POST['verify_code']) {
			$message = $lesson->delete_lesson($_POST['lesson_key']);
			if ($message === true) {
				$message = '刪除成功';
			}
		} else {
			$message = '頁面已失效';
		}
	}
	$result = $lesson->list_lesson();
	
	$verify_code = verify_code();
	set_cookie('verify_code_lesson_list', $verify_code, 600);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>課程列表</title>
		<link rel="icon" href="" type="image/x-icon">
<?php display_link('css'); ?>
<?php display_link('js'); ?>
	</head>
	<body>
<?php display_navigation($prefix); ?>
		<div class="juice_body">
				<div class="pure-u-1-1">
					<div id="lesson_list">
						<div>
							<h1 class="title t-center">課程列表</h1>
						</div>
<?php
	if (isset($message)) {
		echo <<<EOD
						<div class="u-1-1 warning">
							<h3>$message</h3>
						</div>\n
EOD;
	}
?>
						<div style="width:100%;">
							<table class="pure-table pure-table-bordered m-center t-center">
								<thead>
									<tr>
										<th>單　　元</th>
										<th>難　　度</th>
										<th>標　　題</th>
										<th>小試身手</th>
										<th>動 動 腦</th>
										<th>公　　開</th>
										<th>課程修改</th>
										<th>圖片列表</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
<?php
	if (!empty($result)) {
		$lesson_level_name = array('初階', '中階', '高階', '終階');
		foreach ($result as $tmp) {
?>
									<tr>
										<td><?php echo $tmp['lesson_unit']; ?></td>
										<td><?php echo $lesson_level_name[$tmp['lesson_level']-1]; ?></td>
										<td><?php echo $tmp['lesson_title']; ?></td>
										<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?practice=1&key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">更新</button></a></td>
										<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?implement=1&key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">更新</button></a></td>
										<td>
											<span style="display:inline-block;"><?php echo ($tmp['lesson_is_visible']) ? '是' : '否'; ?>　</span>
											<form name="change_lesson_visible" id="change_lesson_visible" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline-block;">
												<div>
													<input type="text" name="lesson_key" id="lesson_key" value="<?php echo $tmp['lesson_key']; ?>" hidden readonly autocomplete="off" required>
													<input type="text" name="lesson_visible" id="lesson_visible" value="<?php echo $tmp['lesson_is_visible']; ?>" hidden readonly autocomplete="off" required>
													<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_lesson_list']; ?>" hidden readonly autocomplete="off" required>
													<button type="submit" class="pure-button pure-button-primary">切換</button>
												</div>
											</form>
										</td>
										<td><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php?key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">更新</button></a></td>
										<td><a href="<?php echo $prefix.'juice/lesson/lesson_image_list.php?key='.$tmp['lesson_key']; ?>"><button class="pure-button pure-button-primary">前往</button></a></td>
										<td>
											<form name="delete_lesson" id="delete_lesson" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onSubmit="return del_confirm();">
												<div>
													<input type="text" name="lesson_key" id="lesson_key" value="<?php echo $tmp['lesson_key']; ?>" hidden readonly autocomplete="off" required>
													<input type="text" name="del_lesson" id="del_lesson" value="1" hidden readonly autocomplete="off" required>
													<input type="text" name="verify_code" id="verify_code" value="<?php echo (isset($verify_code)) ? $verify_code : $_COOKIE['verify_code_lesson_list']; ?>" hidden readonly autocomplete="off" required>
													<button type="submit" class="pure-button pure-button-primary">刪除</button>
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
<?php display_footer($prefix); ?>
		<script>
			function del_confirm() {
				if (confirm('確定刪除？')) {
					return true;
				} else {
					return false;
				}
			}
		</script>
	</body>
</html>