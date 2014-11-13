<?php
	function display_link($type) {
		$web_root_path = WEB_ROOT_DIR;
		$web_error_page = WEB_ERROR_PAGE;
		switch ($type) {
			case 'css':
				echo <<<END
		<link type="image/x-icon" rel="icon" href="{$web_root_path}images/icon.ico">
		<link type="text/css" rel="stylesheet" href="{$web_root_path}scripts/css/pure.css">
		<link type="text/css" rel="stylesheet" href="{$web_root_path}scripts/css/juice.css">\n
END;
				break;
			case 'js':
				echo <<<END
		<noscript><meta http-equiv="refresh" content="0; URL={$web_error_page}?message=為了獲得更加的體驗，請開起JavaScript&no_transfer=1"></noscript>
		<script src="{$web_root_path}scripts/js/jquery.min.js"></script>
		<script src="{$web_root_path}scripts/js/yui-min.js"></script>\n
END;
				break;
			default :
				break;
		}
	}
	
	function display_navigation($prefix) {
?>
		<header>
			<a href="<?php echo $prefix.'index.php' ?>"><div class="juice_logo"></div></a>
			<div class="juice_menu_head">
				<nav id="demo-horizontal-menu">
					<ul id="std-menu-items">
<?php
		if (permission_check('login')) {
?>
						<li><a href="<?php echo $prefix.'index.php' ?>">首頁</a></li>
						<li>
							<a href="#">主選單</a>
							<ul>
								<li><a href="<?php echo $prefix.'course/course_list.php' ?>">課程教學</a></li>
								<li><a href="<?php echo $prefix.'user/solve_status.php' ?>">解題動態</a></li>
							</ul>
						</li>
						<li><a href="<?php echo $prefix.'user/member.php' ?>"><img src="<?php echo $prefix.'others/api/apiShowProfileImage.php?uid='.$_SESSION['uid']; ?>" height="22" width="22" style="vertical-align: text-top;"></img> <?php echo $_SESSION['nickname']; ?></a></li>
<?php
			if (permission_check('admin_groups')) {
?>
						<li>
							<a href="#">Juice</a>
							<ul>
<?php
				if (true/*permission_check('admin_groups_lesson')*/) {
?>
								<li>
									<a href="#">課　　程</a>
									<ul>
										<li><a href="<?php echo $prefix.'juice/lesson/lesson_list.php'; ?>">課程列表</a></li>
										<li><a href="<?php echo $prefix.'juice/lesson/lesson_refine.php'; ?>">新增課程</a></li>
										<li><a href="<?php echo $prefix.'juice/lesson/lesson_image_refine.php'; ?>">新增圖片</a></li>
									</ul>
								</li>
<?php
				}
				if (true/*permission_check('admin_groups_root')*/) {
?>
								<li>
									<a href="#">會　　員</a>
									<ul>
										<li><a href="<?php echo $prefix.'juice/account/account_list.php'; ?>">帳號列表</a></li>
										<li><a href="<?php echo $prefix.'juice/account/account_modify.php'; ?>">帳號管理</a></li>
									</ul>
								</li>
<?php
				}
				if (true/*permission_check('admin_groups_root')*/) {
?>
								<li>
									<a href="#">網　　站</a>
									<ul>
										<li><a href="<?php echo $prefix.'juice/web/ann_refine.php'; ?>">新增公告</a></li>
										<li><a href="<?php echo $prefix.'juice/web/ann_refine.php'; ?>">公告管理</a></li>
										<li><a href="<?php echo $prefix.'juice/web/web.php'; ?>">網站管理</a></li>
									</ul>
								</li>
<?php
				}
?>
								<li>
									<a href="#">其　　他</a>
									<ul>
										<li><a href="<?php echo $prefix.'juice/about/about_refine.php'; ?>">團隊介紹修改</a></li>
									</ul>
								</li>
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
				YUI({classNamePrefix:"pure"}).use("gallery-sm-menu",function(a){var b=new a.Menu({container:"#demo-horizontal-menu",sourceNode:"#std-menu-items",orientation:"horizontal",hideOnOutsideClick:false,hideOnClick:false});b.render();b.show();});
			</script>
		</header>
<?php
	}
	
	function display_footer($prefix) {
?>
		<div id="judge_notification">
			
		</div>
		<div id="go_to_top">
			<img src="<?php echo $prefix.'images/go_to_top.png' ?>">
		</div>
		<footer>
			<div>
				<span><a class="link" href="<?php echo $prefix.'about/index.php' ?>">>關於本站</a></span>
				<span><a class="link" href="<?php echo $prefix.'about/team.php' ?>">>團隊介紹</a></span>
				<span><a class="link" href="http://goo.gl/forms/BahuJGxMMB" target="_blank">>意見回饋</a></span>
			</div>
			<div>
				<span>Copyright © 2014 Juice All rights reserved.</span>
			</div>
		</footer>
		<script>
			$(document).ready(function(){
				$("#go_to_top").hide();
				
				$(window).scroll(function(){
					($(this).scrollTop() > 0) ? $("#go_to_top").fadeIn(300) : $("#go_to_top").stop(true).fadeOut(300);
				});
				
				$("#go_to_top").click(function(){
					$("html, body").animate({
						scrollTop:0
					}, 350);
				});
			});
		</script>
<?php
	}
?>