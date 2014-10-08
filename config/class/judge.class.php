<?php
	class judge extends db_connect {
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			parent::__construct($db_type, $db_host, $db_name, $db_username, $db_password);
		}
		
		public function __destruct() {
			parent::__destruct();
		}
		
		public function code_submit($type, $code, $key) {
			switch ($type) {
				case 'lesson_practice':
					while (true) {
						$hash = hash_key('sha512');
						if (hash_check('user_code_lesson', 'code_key', $hash)) {
							break;
						}
					}
					$sql = "INSERT INTO `user_code_lesson` (`code_key`, `uid`, `is_implement`, `ipm_pt_key`, `user_code`, `submit_time`, `submit_ip`) VALUES ";
					$sql .= "(:code_key, :uid, :is_implement, :ipm_pt_key, :user_code, :submit_time, :submit_ip)";
					$params = array(
						':code_key' => $hash,
						':uid' => $_SESSION['uid'],
						':is_implement' => false,
						':ipm_pt_key' => $key,
						':user_code' => $code,
						':submit_time' => $this->current_time,
						':submit_ip' => $this->ip
					);
					$this->query($sql, $params);
					if ($this->rowCount() != 1) {
						$result['error'] = 'There is something wrong when updating the data.';
					} else {
						$result['result'] = true;
						$result['key'] = $hash;
						$result['table'] = 'lesson_practice';
					}
					$this->closeCursor();
					break;
				case 'lesson_implement':
					while (true) {
						$hash = hash_key('sha512');
						if ($this->hash_check('user_code_lesson', 'code_key', $hash)) {
							break;
						}
					}
					$sql = "INSERT INTO `user_code_lesson` (`code_key`, `uid`, `is_implement`, `ipm_pt_key`, `user_code`, `submit_time`, `submit_ip`) VALUES ";
					$sql .= "(:code_key, :uid, :is_implement, :ipm_pt_key, :user_code, :submit_time, :submit_ip)";
					$params = array(
						':code_key' => $hash,
						':uid' => $_SESSION['uid'],
						':is_implement' => true,
						':ipm_pt_key' => $key,
						':user_code' => $code,
						':submit_time' => $this->current_time,
						':submit_ip' => $this->ip
					);
					$this->query($sql, $params);
					if ($this->rowCount() != 1) {
						$result['error'] = 'There is something wrong when updating the data.';
					} else {
						$result['result'] = true;
						$result['key'] = $hash;
						$result['table'] = 'lesson_implement';
					}
					$this->closeCursor();
					break;
				default :
					$result['error'] = 'Invalid type.';
					break;
			}
			return $result;
		}
	}
?>