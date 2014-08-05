<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>新增課程</title>
		<link rel="icon" href="" type="image/x-icon">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	</head>
	<body>
		<div>
			<header>
				<nav>
					<ul>
						<li><a href="<?php echo $prefix.'index.php'; ?>">首頁</a></li>
						<li><a href="<?php echo $prefix.'juice/index.php'; ?>">後台</a></li>
						<li>
							<a href="#">課程</a>
							<ul>
								<li><a href="<?php echo $prefix.'juice/lesson/add.php'; ?>">新增課程</a></li>
								<li><a href="<?php echo $prefix.'juice/lesson/modify.php'; ?>">修改課程</a></li>
							</ul>
						</li>
						<li>
							<a href="#">帳號</a>
							<ul>
								<li><a href="<?php echo $prefix.'juice/account/list.php'; ?>">帳號列表</a></li>
								<li><a href="<?php echo $prefix.'juice/account/manager.php'; ?>">管理帳號</a></li>
							</ul>
						</li>
						<li>
							<a href="#">網站</a>
							<ul>
								<li><a href="<?php echo $prefix.'juice/web/announcement.php'; ?>">公告管理</a></li>
							</ul>
						</li>
						<li><a href="<?php echo $prefix.'user/logout.php'; ?>">登出</a></li>
					</ul>
				</nav>
			</header>
		</div>
		<div>
			<div>
				<form name="add_lesson" id="add_lesson" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<fieldset>
						<div>
							<label for="unit">單　　元：</label>
							<input type="text" id="unit" name="unit" maxlength="2" pattern="^\d{1,2}$" autocomplete="off" required>
						</div>
						<div>
							<label for="level">難　　度：</label>
							<select name="level" id="level" required>
								<option value="0">初階</option>
								<option value="1">中階</option>
								<option value="2">高階</option>
								<option value="3">終階</option>
							</select>
						</div>
						<div>
							<label for="title">標　　題：</label>
							<input type="text" id="title" name="title" maxlength="128" autocomplete="off" required>
						</div>
						<div>
							<label for="goal">學習目標：</label>
							<textarea name="goal" id="goal" rows="8" cols="60" required>
							</textarea>
						</div>
						<div>
							<label for="content">課程內容：</label>
							<textarea name="content" id="content" rows="8" cols="60" required>
							</textarea>
						</div>
						<div>
							<label for="example">範　　例：</label>
							<textarea name="example" id="example" rows="8" cols="60" required>
							</textarea>
						</div>
						<div>
							<label for="practice">填空練習：</label>
							<textarea name="practice" id="practice" rows="8" cols="60" required>
							</textarea>
						</div>
						<div>
							<label for="implement">動 動 腦：</label>
							<textarea name="implement" id="implement" rows="8" cols="60" required>
							</textarea>
						</div>
						<div>
							<button type="submit" id="submit">新增</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<div>
			<footer>
				Web Create by : Juice / Copyright © 2014
			</footer> 
		</div>
	</body>
</html>