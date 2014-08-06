<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>課程新增</title>
		<link rel="icon" href="" type="image/x-icon">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/main.css'; ?>" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="http://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
	</head>
	<body>
		<div class="header">
			<header>
				<div style="height: 40px;">
				</div>
				<nav>
					<ul>
						<li class="header-list"><a href="<?php echo $prefix.'index.php'; ?>">首頁</a></li>
						<li class="header-list"><a href="<?php echo $prefix.'juice/index.php'; ?>">後台</a></li>
						<li class="header-list">
							<a href="#">課程</a>
							<ul class="dropdown-menu">
								<li class="btn"><a href="<?php echo $prefix.'juice/lesson/add.php'; ?>">新增課程</a></li>
								<li class="btn"><a href="<?php echo $prefix.'juice/lesson/modify.php'; ?>">修改課程</a></li>
							</ul>
						</li>
						<li class="header-list">
							<a href="#">帳號</a>
							<ul class="dropdown-menu">
								<li class="btn"><a href="<?php echo $prefix.'juice/account/list.php'; ?>">帳號列表</a></li>
								<li class="btn"><a href="<?php echo $prefix.'juice/account/manager.php'; ?>">管理帳號</a></li>
							</ul>
						</li>
						<li class="header-list">
							<a href="#">網站</a>
							<ul class="dropdown-menu">
								<li class="btn"><a href="<?php echo $prefix.'juice/web/announcement.php'; ?>">公告管理</a></li>
							</ul>
						</li>
						<li class="header-list"><a href="<?php echo $prefix.'user/logout.php'; ?>">登出</a></li>
					</ul>
				</nav>
			</header>
		</div>
		<div style="width:800px;margin: 10px auto;">
			<div>
				<form name="add_lesson" id="add_lesson" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<fieldset>
						<div class="lesson_add_header">
							<label for="unit">單　　元：</label>
							<input type="text" id="unit" name="unit" maxlength="2" pattern="^\d{1,2}$" autocomplete="off" required>
						</div>
						<div class="lesson_add_header">
							<label for="level">難　　度：</label>
							<select name="level" id="level" required>
								<option value="0">初階</option>
								<option value="1">中階</option>
								<option value="2">高階</option>
								<option value="3">終階</option>
							</select>
						</div>
						<div class="lesson_add_header">
							<label for="title">標　　題：</label>
							<input type="text" id="title" name="title" maxlength="128" autocomplete="off" required>
						</div>
						<div style="max-width:768px;">
							<label class="lesson_add_title" for="goal">學習目標：</label>
							<textarea class="ckeditor" name="goal" id="goal" required></textarea>
						</div>
						<div style="max-width:768px;">
							<label class="lesson_add_title" for="content">課程內容：</label>
							<textarea class="ckeditor" name="content" id="content" required></textarea>
						</div>
						<div style="max-width:768px;">
							<label class="lesson_add_title" for="example">範　　例：</label>
							<textarea class="ckeditor" name="example" id="example" required></textarea>
						</div>
						<div style="max-width:768px;">
							<label class="lesson_add_title" for="practice">填空練習：</label>
							<textarea class="ckeditor" name="practice" id="practice" required></textarea>
						</div>
						<div style="max-width:768px;">
							<label class="lesson_add_title" for="implement">動 動 腦：</label>
							<textarea class="ckeditor" name="implement" id="implement" required></textarea>
						</div>
						<div style="margin:10px;">
							<button type="submit" id="submit">新增</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<div class="footer">
			<footer>
				Web Create by : Juice / Copyright © 2014
			</footer> 
		</div>
	</body>
</html>
