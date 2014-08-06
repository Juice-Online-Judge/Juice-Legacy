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
		<link type="text/css" href="<?php echo $prefix.'scripts/css/main.css'; ?>" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="header">
			<header>
				<div style="height: 40px;">
				</div>
				<nav>
					<ul>
						<li class="header-list">
							<a href="#">帳號管理</a>
							<ul class="dropdown-menu">
							</ul>
						</li>
						<li class="header-list">
							<a href="#">主選單</a>
							<ul class="dropdown-menu">
							</ul>
						</li>
						<li class="header-list"><a href="#">關於我們</a></li>
						<li class="header-list"><a href="#">建議</a></li>
					</ul>
				</nav>
			</header>
		</div>
		<div class="individual-side"><h1>Individual-side</h1></div>
		<div class="overview-side"><h1>Overview-side</h1></div>
		<div class="footer">
			<footer>
				Web Create by : Juice / Copyright © 2014
			</footer>
		</div>
	</body>
</html>
