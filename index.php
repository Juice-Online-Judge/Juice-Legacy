<?php
	if (!isset($prefix)) {
		$prefix = './';
	}
	require_once $prefix.'config/web_preprocess.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>首頁</title>
		<link type="text/css" href="<?php echo $prefix.'scripts/css/pure.css' ?>" rel="stylesheet">
		<link type="text/css" href="<?php echo $prefix.'scripts/css/index.css' ?>" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	</head>
	<body>
		<header>
			<div class="pure-menu pure-menu-open pure-menu-horizontal juice-menu-head">
				<a href="<?php echo $prefix.'index.php' ?>"><img src="<?php echo $prefix.'images/logo.png' ?>" width="200" height="100"></a>
				<ul>
					<li><a href="#">建議</a></li>
					<li><a href="#">關於我們</a></li>
					<li>
						<a href="#">主選單</a>
						<ul>
						</ul>
					</li>
					<li>
						<a href="#">帳號管理</a>
						<ul>
						</ul>
					</li>
				</ul>
			</div>
		</header>
		<div class="pure-g">
			<div class="pure-u-1-3">
				<p style="text-align:center; height:1000px;">I</p>
			</div>
			<div class="pure-u-2-3">
				<p style="text-align:center; height:1000px;">II</p>
			</div>
		</div>
		<footer>
			<div>
				<p>Web Create by Juice / Copyright © 2014</p>
			</div>
		</footer>
	</body>
</html>