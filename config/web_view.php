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