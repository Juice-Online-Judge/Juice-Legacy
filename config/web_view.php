<?php
	function display_css_link($prefix) {
?>
		<link rel="stylesheet" href="<?php echo $prefix.'scripts/css/pure.css' ?>">
		<link rel="stylesheet" href="<?php echo $prefix.'scripts/css/juice.css' ?>">
<?php
	}
	
	function display_scripts_link() {
?>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="http://yui.yahooapis.com/3.17.2/build/yui/yui-min.js"></script>
<?php
	}
	
	function display_navigation($prefix) {
?>
		<header>
			<a href="<?php echo $prefix.'index.php' ?>"><div class="juice-logo"></div></a>
			<div class="juice-menu-head">
				<nav id="demo-horizontal-menu">
					<ul id="std-menu-items">
<?php
		if (permission_check('login')) {
?>
						<li><a href="<?php echo $prefix.'index.php' ?>">首頁</a></li>
						<li>
							<a href="#">主選單</a>
							<ul>
								<li><a href="<?php echo $prefix.'others/solve_status.php' ?>">解題動態</a></li>
								<li class="pure-menu-separator"></li>
								<li><a href="<?php echo $prefix.'course/course_list.php' ?>">課程教學</a></li>
								<li class="pure-menu-separator"></li>
								<li>
									<a href="#">闖關競賽</a>
									<ul>
										<li><a href="#">排 行 榜</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li>
							<a href="<?php echo $prefix.'user/member.php' ?>">會員中心</a>
							<ul>
							</ul>
						</li>
<?php
			if (permission_check('admin_groups')) {
?>
						<li>
							<a href="#">Juice</a>
							<ul>
<?php
				if (permission_check('admin_groups_lesson')) {
?>
								<li>
									<a href="#">課　　程</a>
									<ul>
										<li><a href="<?php echo $prefix.'juice/lesson/lesson_list.php'; ?>">課程列表</a></li>
										<li class="pure-menu-separator"></li>
										<li><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php'; ?>">新增課程</a></li>
										<li class="pure-menu-separator"></li>
										<li><a href="<?php echo $prefix.'juice/lesson/lesson_image_refine.php'; ?>">新增圖片</a></li>
									</ul>
								</li>
<?php
				}
				if (true/*$_SESSION['admin_group'] > 6*/) {
?>
								<li class="pure-menu-separator"></li>
								<li>
									<a href="#">會　　員</a>
									<ul>
										<li><a href="<?php echo $prefix.'juice/account/account_list.php'; ?>">帳號列表</a></li>
										<li class="pure-menu-separator"></li>
										<li><a href="<?php echo $prefix.'juice/account/account_modify.php'; ?>">帳號管理</a></li>
									</ul>
								</li>
<?php
				}
				if (true/*$_SESSION['admin_group'] > 9*/) {
?>
								<li class="pure-menu-separator"></li>
								<li>
									<a href="#">網　　站</a>
									<ul>
										<li><a href="<?php echo $prefix.'juice/web/announcement_add.php'; ?>">新增公告</a></li>
										<li class="pure-menu-separator"></li>
										<li><a href="<?php echo $prefix.'juice/web/announcement_modify.php'; ?>">公告管理</a></li>
										<li class="pure-menu-separator"></li>
										<li><a href="<?php echo $prefix.'juice/web/web.php'; ?>">網站管理</a></li>
									</ul>
								</li>
<?php
				}
?>
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
						<li><a href="<?php echo $prefix.'user/register.php' ?>">註冊</a></li>
<?php
		}
?>
					</ul>
				</nav>
			</div>
			<script>
				YUI({classNamePrefix:"pure"}).use("gallery-sm-menu",function(a){var b=new a.Menu({container:"#demo-horizontal-menu",sourceNode:"#std-menu-items",orientation:"horizontal",hideOnOutsideClick:false,hideOnClick:true});b.render();b.show();});
			</script>
		</header>
<?php
	}
	
	function display_footer($prefix) {
?>
		<footer>
			<div>
				<span><a href="<?php echo $prefix.'about/index.php' ?>">關於本站</a></span>
				<span><a href="<?php echo $prefix.'about/team.php' ?>">團隊介紹</a></span>
				<span><a href="#">意見回饋</a></span>
			</div>
			<div>
				<span>Copyright © 2014 Juice All rights reserved.</span>
			</div>
		</footer>
<?php
	}
	/*
		<script>
			$(document).ready(function(){
			});
		</script>
	*/
?>