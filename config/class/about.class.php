<?php
	class about extends db_connect {
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			parent::__construct($db_type, $db_host, $db_name, $db_username, $db_password);
		}
		
		public function __destruct() {
			parent::__destruct();
		}
		
		/*
			type :
				1 -> website
				2 -> member
		*/
		public function list_about($type) {
			$sql = "SELECT `nickname`, `content` FROM `web_about` WHERE `type` = :type";
			$params = array(
				':type' => $type
			);
			$this->query($sql, $params);
			return $this->fetchAll();
		}
		
		public function get_about_content($type) {
			$sql = "SELECT `nickname`, `content` FROM `web_about` WHERE `uid` = :uid";
			$params = array(
				':uid' => $_SESSION['uid']
			);
			$this->query($sql, $params);
			return $this->fetch();
		}
		
		public function update_about($type, array $value = array()) {
			if (empty($value)) {
				$result = '請輸入內容';
			} else {
				$value['nickname'] = htmlspecialchars($value['nickname'], ENT_QUOTES);
				if (($length = mb_strlen($value['nickname'], 'UTF-8')) < 5 or $length > 16) {
					$result = '暱稱格式錯誤';
				} else if (strlen($value['content']) == 0) {
					$result = '請輸入內容';
				} else if (!preg_match("/^[1-2]$/", $value['type'])) {
					$result = '所屬群組錯誤';
				} else {
					switch ($type) {
						case 'add':
							$sql = "INSERT INTO `web_about` (`uid`, `type`, `nickname`, `content`, `last_update_time`, `last_update_ip`) ";
							$sql .= "VALUES (:uid, :type, :nickname, :content, :last_update_time, :last_update_ip)";
							$params = array(
								':uid' => $_SESSION['uid'],
								':type' => $value['type'],
								':nickname' => $value['nickname'],
								':content' => $value['content'],
								':last_update_time' => $this->current_time,
								':last_update_ip' => $this->ip
							);
							break;
						case 'update':
							$sql = "UPDATE `web_about` SET `nickname` = :nickname, `content` = :content, `last_update_time` = :last_update_time, `last_update_ip` = :last_update_ip WHERE `uid` = :uid";
							$params = array(
								':nickname' => $value['nickname'],
								':content' => $value['content'],
								':last_update_time' => $this->current_time,
								':last_update_ip' => $this->ip,
								':uid' => $_SESSION['uid']
							);
							break;
						default :
							$result = '型態錯誤';
							break;
					}
					if (!isset($result)) {
						$this->query($sql, $params);
						if ($this->rowCount() != 1) {
							$result = '更新資料時發生錯誤，請稍後再試';
						} else {
							$result = true;
						}
						$this->closeCursor();
					}
				}
			}
			return $result;
		}
	}
?>