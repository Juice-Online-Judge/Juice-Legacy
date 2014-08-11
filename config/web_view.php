<?php
	function display_head($prefix, $page_title, $page_icon, $page_css, $page_js) {
		echo <<<EOD
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>$page_title</title>
		<link rel="icon" href="$prefix$page_icon" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="http://crux.coder.tw/scripts/css/pure-min.css">\n
EOD;
		foreach($page_css as $page_css) {
			echo <<<EOD
		<link rel="stylesheet" type="text/css" href="$prefix$page_css">\n
EOD;
		}
		echo <<<EOD
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
		<script src="http://crux.coder.tw/scripts/js/yui-min.js"></script>\n
EOD;
		foreach($page_js as $page_js) {
			echo <<<EOD
		<script type="text/javascript" src="$prefix$page_js"></script>\n
EOD;
		}
		echo <<<EOD
	</head>\n
EOD;
	}
	
	function display_user_navigation($prefix) {
?>
		<header>
			<div class="pure-menu pure-menu-open pure-menu-horizontal juice-menu-head">
				<a href="<?php echo $prefix.'index.php' ?>"><img src="<?php echo $prefix.'images/logo.png' ?>" width="200" height="100"></a>
				<ul>
					<li><a href="<?php echo $prefix.'index.php' ?>">首頁</a></li>
					<li>
						<a href="#">主選單</a>
						<ul>
						</ul>
					</li>
<?php
		if (isset($_SESSION['uid'])) {
?>
					<li>
						<a href="#">帳號管理</a>
						<ul>
						</ul>
					</li>
<?php
		}
?>
					<li><a href="#">建議</a></li>
					<li><a href="#">關於我們</a></li>
<?php
		if (!isset($_SESSION['uid'])) {
?>
					<li><a href="<?php echo $prefix.'user/login.php' ?>">登入</a></li>
<?php
		}
?>
				</ul>
			</div>
		</header>
<?php
	}
	
	function display_footer() {
		echo <<<EOD
			<div id="id_footer">
				<footer>
					Web Created by：Freedom / Copyright © 2014
				</footer> 
			</div>
		</div>\n
EOD;
?>
		<script>
			$(document).ready(function(){$(document).bind("contextmenu",function(event){return false})});
		</script>
<?php
	}
	/*
		$(document).ready(function() {
			$(document).bind("contextmenu",function(event){
				return false;
			});
		});
	*/
?>