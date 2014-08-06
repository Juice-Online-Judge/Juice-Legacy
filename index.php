<!DOCTYPE html>
<html>
	<head>
		<meta charset= "UTF-8">
		<title>首頁</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	</head>
	<style type="text/css">
		div {
			border: 1px black dashed;
		}
		.header-list {
			decoration: none;
			display:inline;
			margin: 10px;
			float: right;
		}
		.dropdown-menu {
			decoration: none;
			<!-- display:none; -->
		}
		.btn-list {
			decoration: none;
			display: block;
		}
		.individual-side {
			background-color: blue;
			width: 30%;
			height: 1000px;
			float: left;
		}
		.overview-side {
			background-color: red;
			width: 69%;
			height: 1000px;
			float: right;
		}
		header,footer {
			height: 80px;
			clear: both;
		}
		.header,.footer {
			background-color: green;
			clear: both;
		}
	</style>
	<body>
		<div class="header">
			<header>
				<nav>
					<ul>
						<li class="header-list">
							<a href="">帳號管理</a>
							<ul class="dropdown-menu">
								<li class="btn-list"><a href="">1</a></li>
								<li class="btn-list"><a href="">2</a></li>
								<li class="btn-list"><a href="">3</a></li>
							</ul>
						</li>
						<li class="header-list">
							<a href="">主選單</a>
							<ul class="dropdown-menu">
								<li class="btn-list"><a href="">1</a></li>
								<li class="btn-list"><a href="">2</a></li>
								<li class="btn-list"><a href="">3</a></li>
							</ul>
						</li>
						<li class="header-list"><a href="">關於我們</a></li>
						<li class="header-list"><a href="">建議</a></li>
					</ul>
				</nav>
			</header>
		</div>
		<div class="individual-side"><h1>individual-side</h1></div>
		<div class="overview-side"><h1>overview-side</h1></div>
		<div class="footer">
			<footer>
				<h1>footer</h1>
			</footer>
		</div>
	</body>
</html>