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
		if (!isset($_POST['type'])) {
			$result = array(
				'error' => 'Wrong argument.'
			);
		} else {
			switch ($_POST['type']) {
				case 'lesson':
					if (!isset($_POST['code_key']) or !isset($_POST['ipm_pt_key'])) {
						$result = array(
							'error' => 'Wrong argument.'
						);
					} else {
						$get_data = new lesson();
						$temp = $get_data->get_lesson_code($_POST['code_key'], $_POST['ipm_pt_key']);
						if (empty($temp)) {
							$result = array(
								'empty' => true
							);
						} else {
							$temp['code'] = htmlspecialchars($temp['user_code'], ENT_QUOTES);
							unset($temp['user_code']);
							$result = $temp;
						}
					}
					break;
				default :
					$result = array(
						'error' => 'Wrong argument.'
					);
					break;
			}
		}
	}
	
	echo json_encode($result);
?>