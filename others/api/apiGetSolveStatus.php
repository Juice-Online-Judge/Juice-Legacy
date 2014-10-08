<?php
	if (!isset($prefix)) {
		$prefix = '../../';
	}
	require_once $prefix.'config/web_preprocess.php';
	
	if (!permission_check('login')) {
		$result = array(
			'error' => 'Permission denied.'
		);
	} else {
		if (!isset($_POST['key']) or !isset($_POST['is_implement'])) {
			$result = array(
				'error' => 'Wrong argument.'
			);
		} else {
			$judge_result = array(
				-1 => 'Judging',
				1 => 'AC',
				2 => 'CE',
				3 => 'WA',
				4 => 'TLE',
				5 => 'MLE',
				6 => 'RE'
			);
			$get_data = new lesson('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
			$temp = $get_data->list_user_lesson_record($_POST['is_implement'], $_POST['key']);
			if (empty($temp)) {
				$result = array(
					'empty' => true
				);
			} else {
				foreach ($temp as $name => $v) {
					foreach ($temp[$name] as $key => $value) {
						switch ($key) {
							case 'code_key':
								$temp[$name]['key'] = $value;
								unset($temp[$name][$key]);
								break;
							case 'result':
								$temp[$name][$key] = $judge_result[$value];
								break;
							case 'memory_usage':
								if ($vaule == null) {
									$temp[$name][$key] = '-';
								}
								break;
							case 'time_usage':
								if ($vaule == null) {
									$temp[$name][$key] = '-';
								}
								break;
							case 'user_code':
								unset($temp[$name][$key]);
								break;
							case 'submit_time':
								$temp[$name]['time'] = data('Y-m-d H:i:s', $value);
								unset($temp[$name][$key]);
								break;
						}
					}
				}
				$result = $temp;
			}
		}
	}
	
	echo json_encode($result);
?>