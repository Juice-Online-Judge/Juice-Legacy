<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	page_check('course_preprocess');
	
	foreach ($_POST as $key => $value) {
		if (strpos($key, "implement_a") !== false) {
			/*if (isset($_POST['implement_key'])) {
				$judge = new judge();
				$result = $judge->code_submit('lesson_implement', $value, $_POST['implement_key']);
				if (!isset($result['error'])) {
					$command = 'ruby2.0 '.$prefix.'juice/core/lib/juice_judge.rb ';
					$command .= $result['key'].' '.$result['table'].' '.$_POST['implement_key'];
					$outputFile = '/dev/null';
					shell_exec(sprintf('%s > %s 2>&1 & echo $!', $command, $outputFile));
				}
				header("Location: ".$prefix."user/pre_solve_status.php?key=".$_POST['implement_key']."&is_implement=1");
				exit();
			}*/
			break;
		} else if (strpos($key, 'practice_a') !== false) {
			if (isset($_POST['practice_key'])) {
				$judge = new judge();
				$result = $judge->code_submit('lesson_practice', $value, $_POST['practice_key']);
				if (!isset($result['error'])) {
					$judge->lesson_practice_judge($_POST['practice_key'], $result['key']);
				}
				header("Location: ".$prefix."user/pre_solve_status.php?key=".$_POST['practice_key']."&is_implement=0");
				exit();
			}
			break;
		}
	}
?>