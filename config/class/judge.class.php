<?php
	class judge extends db_connect {
		
		public function code_submit($type, $code, $key) {
			switch ($type) {
				case 'lesson_practice':
					while (true) {
						$hash = hash_key('sha512');
						if (!$this->hash_used_check('user_code_lesson', 'code_key', $hash)) {
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
						$result['table'] = 'user_code_lesson';
					}
					$this->closeCursor();
					break;
				case 'lesson_implement':
					while (true) {
						$hash = hash_key('sha512');
						if (!$this->hash_used_check('user_code_lesson', 'code_key', $hash)) {
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
						$result['table'] = 'user_code_lesson';
					}
					$this->closeCursor();
					break;
				default :
					$result['error'] = 'Invalid type.';
					break;
			}
			return $result;
		}
		
		public function lesson_practice_judge($practice_key, $code_key) {
			$sql = "SELECT `practice_answer` FROM `lesson_practice` WHERE `practice_key` = :practice_key";
			$params = array(
				':practice_key' => $practice_key
			);
			$this->query($sql, $params);
			$practice_answer = $this->fetch();
			$this->closeCursor();
			
			$sql = "SELECT `user_code` FROM `user_code_lesson` WHERE `code_key` = :code_key AND `ipm_pt_key` = :ipm_pt_key";
			$params = array(
				':code_key' => $code_key,
				':ipm_pt_key' => $practice_key
			);
			$this->query($sql, $params);
			$user_answer = $this->fetch();
			$this->closeCursor();
			
			$judge_result = ($practice_answer['practice_answer'] == $user_answer['user_code']) ? 1 : 3; // correct : 1, incorrect : 3
			$sql = "UPDATE `user_code_lesson` SET `result` = :result WHERE `code_key` = :code_key AND `ipm_pt_key` = :ipm_pt_key";
			$params = array(
				':result' => $judge_result,
				':code_key' => $code_key,
				':ipm_pt_key' => $practice_key
			);
			$this->query($sql, $params);
			$this->closeCursor();
		}
	}
?>