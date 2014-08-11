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
	
	function display_navigation($prefix) {
?>
		<header>
			<nav class="pure-menu pure-menu-open pure-menu-horizontal juice-menu-head">
				<a href="<?php echo $prefix.'index.php' ?>"><img src="<?php echo $prefix.'images/logo.png' ?>" width="200" height="100"></a>
				<ul>
					<li><a href="<?php echo $prefix.'index.php' ?>">首頁</a></li>
					<li>
						<a href="#">主選單</a>
						<ul>
						</ul>
					</li>
<?php
		if (true/*isset($_SESSION['uid'])*/) {
?>
					<li>
						<a href="#">會員中心</a>
						<ul>
						</ul>
					</li>
<?php
			if (true/*$_SESSION['admin_group'] > 0*/) {
?>
					<li>
						<a href="#">Juice</a>
						<ul>
<?php
				if (true/*$_SESSION['admin_group'] > 3*/) {
?>
							<li>
								<a href="#">課程</a>
								<ul>
									<li><a href="<?php echo $prefix.'juice/lesson/lesson_list.php'; ?>">課程列表</a></li>
									<li><a href="<?php echo $prefix.'juice/lesson/lesson_add.php'; ?>">新增課程</a></li>
									<li><a href="<?php echo $prefix.'juice/lesson/lesson_modify.php'; ?>">修改課程</a></li>
								</ul>
							</li>
<?php
				}
				if (true/*$_SESSION['admin_group'] > 6*/) {
?>
							<li>
								<a href="#">會員</a>
								<ul>
									<li><a href="<?php echo $prefix.'juice/account/account_list.php'; ?>">帳號列表</a></li>
									<li><a href="<?php echo $prefix.'juice/account/account_modify.php'; ?>">帳號管理</a></li>
								</ul>
							</li>
<?php
				}
				if (true/*$_SESSION['admin_group'] > 9*/) {
?>
							<li>
								<a href="#">網站</a>
								<ul>
									<li><a href="<?php echo $prefix.'juice/web/announcement_add.php'; ?>">新增公告</a></li>
									<li><a href="<?php echo $prefix.'juice/web/announcement_modify.php'; ?>">公告管理</a></li>
									<li><a href="<?php echo $prefix.'juice/web/web.php'; ?>">網站管理</a></li>
								</ul>
							</li>
<?php
				}
?>
							<li><a href="<?php echo $prefix.'user/logout.php'; ?>">登出</a></li>
						</ul>
					</li>
<?php
			}
?>
					<li><a href="<?php echo $prefix.'user/logout.php'; ?>">登出</a></li>
<?php
		} else {
?>
					<li><a href="<?php echo $prefix.'user/login.php' ?>">登入</a></li>
<?php
		}
?>
					<li><a href="#">建議</a></li>
					<li><a href="#">關於我們</a></li>
				</ul>
			</nav>
			<script>
				YUI({classNamePrefix:"pure"}).use("gallery-sm-menu",function(a){var b=new a.Menu({container:"#demo-horizontal-menu",sourceNode:"#std-menu-items",orientation:"horizontal",hideOnOutsideClick:false,hideOnClick:false});b.render();b.show();});
			</script>
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