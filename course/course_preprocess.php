<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	foreach ($_POST as $key => $value) {
		if (strpos($key, "implement_a") !== false) {
			if (isset($_POST['implement_key']) {
				$judge = new judge('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
				$result = $judge->code_submit('lesson_implement', $value, $_POST['implement_key']);
				if (!isset($result['error'])) {
					$outputFile = '/dev/null';
					$command = 'ruby '.$prefix.'juice_judge.rb ';
					$command .= $result['key'].' '.$result['table'].' '.$_POST['implement_key'];
					shell_exec(sprintf('%s > %s 2>&1 & echo $!', $command, $outputFile));
					echo 'OK';
				}
			}
			break;
		} else if (strpos($key, "practice_a") !== false) {
			if (isset($_POST['practice_key']) {
				
			}
			break;
		}
	}
?>