<?php
	class account extends db_connect {
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			parent::__construct($db_type, $db_host, $db_name, $db_username, $db_password);
		}
		
		public function __destruct() {
			parent::__destruct();
		}
		
		public function register($username, $pw, $pw_check, $email, $nickname) {
			$pw = hash('sha512', $pw);
			$pw_check = hash('sha512', $pw_check);
			$nickname = htmlspecialchars($nickname, ENT_QUOTES);
			
			if (!preg_match("/^\w{5,32}$/", $username)) {
				$result =  '帳號格式不符';
			} else if (strcmp($pw, $pw_check) != 0) {
				$result =   '密碼與密碼確認不符';
			} else if (!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email) or strlen($email) > 128) {
				$result =   '信箱格式不符';
			} else if (($length = mb_strlen($nickname, 'UTF-8')) < 5 or $length > 16) {
				$result =   '暱稱格式不符';
			} else {
				$sql = "SELECT `id` FROM `account` WHERE `username` = :username";
				$params = array(
					':username' => $username
				);
				$this->query($sql, $params);
				if ($this->rowCount() >= 1) {
					$result = '此帳號已被使用';
				} else {
					$this->closeCursor();
					$sql = "SELECT `uid` FROM `user_data` WHERE `email` = :email OR `nickname` = :nickname";
					$params = array(
						':email' => $email,
						':nickname' => $nickname
					);
					$this->query($sql, $params);
					if ($this->rowCount() >= 1) {
						$result = '信箱或暱稱已被使用';
					} else {
						$this->closeCursor();
						$sql = "INSERT INTO `account` (`username`, `password`, `account_create_time`, `account_create_ip`) ";
						$sql .= "VALUES (:username, :password, :account_create_time, :account_create_ip)";
						$params = array(
							':username' => $username,
							':password' => $pw,
							':account_create_time' => $this->current_time,
							':account_create_ip' => $this->ip
						);
						$this->query($sql, $params);
						$insert_id = $this->lastInsertId();
						if ($this->rowCount() != 1 or $insert_id == 0) {
							$this->log('Error', 'INSERT the account data failed. User username : '.$username);
							$result = '更新資料時發生錯誤，請稍後再試';
						} else {
							$this->closeCursor();
							$sql = "INSERT INTO `user_data` (`uid`, `email`, `nickname`) ";
							$sql .= "VALUES (:uid, :email, :nickname)";
							$params = array(
								':uid' => $insert_id,
								':email' => $email,
								':nickname' => $nickname
							);
							$this->query($sql, $params);
							if ($this->rowCount() != 1) {
								$this->log('Error', 'INSERT the user data failed. User uid : '.$insert_id);
								$result = '更新資料時發生錯誤，請稍後再試';
							} else {
								$result = true;
							}
						}
					}
				}
				$this->closeCursor();
			}
			return $result;
		}
		
		public function update_pw($old_pw, $new_pw, $new_pw_check) {
			$old_pw = hash('sha512', $old_pw);
			$new_pw = hash('sha512', $new_pw);
			$new_pw_check = hash('sha512', $new_pw_check);
			
			if (strcmp($new_pw, $new_pw_check) != 0) {
				$result = '新密碼與新密碼確認不符';
			} else {
				$sql = "UPDATE `account` SET `password` = :new_password WHERE `id` = :uid AND `password` = :old_password";
				$params = array(
					':new_password' => $new_pw,
					':old_password' => $old_pw,
					':uid' => $_SESSION['uid']
				);
				$this->query($sql, $params);
				if ($this->rowCount() != 1) {
					$result = '原密碼不符';
				} else {
					$result = true;
				}
				$this->closeCursor();
			}
			return $result;
		}
		
		public function update_info($email, $nickname) {
			$nickname = htmlspecialchars($nickname, ENT_QUOTES);
			
			if (!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email) or strlen($email) > 128) {
				$result = '信箱格式不符';
			} else if (($length = mb_strlen($nickname, 'UTF-8')) < 5 or $length > 16) {
				$result = '暱稱格式不符';
			} else {
				$sql = "SELECT `uid` FROM `user_data` WHERE (`email` = :email OR `nickname` = :nickname) AND `uid` != :uid";
				$params = array(
					':email' => $email,
					':nickname' => $nickname,
					':uid' => $_SESSION['uid']
				);
				$this->query($sql, $params);
				if ($this->rowCount() >= 1) {
					$result = '信箱或暱稱已被使用';
				} else {
					$this->closeCursor();
					$sql = "UPDATE `user_data` SET `email` = :email, `nickname` = :nickname, `last_update_time` = :last_update_time, `last_update_ip` = :last_update_ip WHERE `uid` = :uid";
					$params = array(
						':email' => $email,
						':nickname' => $nickname,
						':last_update_time' => $this->current_time,
						':last_update_ip' => $this->ip,
						':uid' => $_SESSION['uid']
					);
					$this->query($sql, $params);
					if ($this->rowCount() != 1) {
						$this->log('Error', 'Update the user data failed. User uid : '.$_SESSION['uid']);
						$result = '更新資料時發生錯誤，請稍後再試';
					} else {
						$result = true;
					}
				}
				$this->closeCursor();
			}
			return $result;
		}
		
		public function login($username, $password, $remember) {
			$sql = "SELECT `id`, `allow_login` FROM `account` WHERE `username` = :username AND `password` = :password";
			$params = array(
				':username' => $username,
				':password' => hash('sha512', $password)
			);
			$this->query($sql, $params);
			if ($this->rowCount() != 1) {
				$result = '帳號或密碼錯誤';
			} else {
				$result = $this->fetch();
				if ($result['allow_login'] == false) {
					$result = '此帳號目前不允許登入';
				} else {
					$_SESSION['uid'] = $result['id'];
					$this->closeCursor();
					$user_data = $this->get_user_data();
					if ($user_data === false) {
						session_unset();
						session_regenerate_id(true);
						$this->log('Error', 'The user data does not exist. User username : '.$username);
						$result = '讀取資料時發生錯誤，請稍後再試';
					} else {
						$_SESSION['nickname'] = $user_data['nickname'];
						$_SESSION['group_id'] = $user_data['group_id'];
						$_SESSION['admin_group'] = $user_data['admin_group'];
						$sql = "UPDATE `account` SET `last_login_time` = :last_login_time, `last_login_ip` = :last_login_ip WHERE `id` = :uid";
						$params = array(
							':last_login_time' => $this->current_time,
							':last_login_ip' => $this->ip,
							':uid' => $_SESSION['uid']
						);
						$this->query($sql, $params);
						if ($this->rowCount() != 1) {
							$this->log('Error', 'Update the account information failed. User uid : '.$_SESSION['uid']);
						}
						$this->closeCursor();
						if ($remember == 1) {
							$rem_user = hash('sha1', $username);
							$rem_verify = hash_key('sha1');
							$rem_time_end = $this->current_time + 2592000;
							$sql = "INSERT INTO `web_login_log` (`uid`, `remember_username`, `remember_verify`, `remember_time_start`, `remember_time_end`, `login_ip`, `login_time`) ";
							$sql .= "VALUES (:uid, :remember_username, :remember_verify, :remember_time_start, :remember_time_end, :login_ip, :login_time)";
							$params = array(
								':uid' => $_SESSION['uid'],
								':remember_username' => $rem_user,
								':remember_verify' => $rem_verify,
								':remember_time_start' => $this->current_time,
								':remember_time_end' => $rem_time_end,
								':login_ip' => $this->ip,
								':login_time' => $this->current_time
							);
							set_cookie('rem_user', $rem_user, $rem_time_end);
							set_cookie('rem_verify', $rem_verify, $rem_time_end);
						} else {
							$sql = "INSERT INTO `web_login_log` (`uid`, `login_ip`, `login_time`) VALUES (:uid, :login_ip, :login_time) ";
							$params = array(
								':uid' => $_SESSION['uid'],
								':login_ip' => $this->ip,
								':login_time' => $this->current_time
							);
						}
						$this->query($sql, $params);
						if ($this->rowCount() != 1) {
							$this->log('Error', 'Update the web_login_log information failed. User uid : '.$_SESSION['uid']);
						}
						$result = true;
					}
				}
			}
			$this->closeCursor();
			return $result;
		}
		
		public function check_login() {
			if (isset($_COOKIE['rem_user']) and isset($_COOKIE['rem_verify']) and !isset($_SESSION['uid'])) {
				$sql = "SELECT `uid` FROM `web_login_log` WHERE `remember_username` = :rem_user AND `remember_verify` = :rem_verify ORDER BY `id` DESC LIMIT 1";
				$params = array(
					':rem_user' => $_COOKIE['rem_user'],
					':rem_verify' => $_COOKIE['rem_verify']
				);
				$this->query($sql, $params);
				if ($this->rowCount() == 1) {
					$result = $this->fetch();
					$_SESSION['uid'] = $result['uid'];
					$this->closeCursor();
					$result = $this->get_user_data();
					if ($result === false) {
						session_unset();
						session_regenerate_id(true);
					} else {
						$_SESSION['nickname'] = $result['nickname'];
						$_SESSION['group_id'] = $result['group_id'];
						$_SESSION['admin_group'] = $result['admin_group'];
					}
				}
			}
		}
		
		public function logout() {
			if (isset($_SESSION['uid'])) {
				$sql = "UPDATE `web_login_log` SET `remember_time_end` = :remember_time_end WHERE `uid` = :uid AND `remember_time_end` >= :current_time";
				$params = array(
					':remember_time_end' => $this->current_time,
					':current_time' => $this->current_time,
					':uid' => $_SESSION['uid']
				);
				$this->query($sql, $params);
				$this->closeCursor();
			}
			del_cookie('rem_user');
			del_cookie('rem_verify');
			session_unset();
			session_regenerate_id(true);
		}
		
		public function get_user_data() {
			$sql = "SELECT `email`, `nickname`, `group_id`, `admin_group` FROM `user_data` WHERE `uid` = :uid";
			$params = array(
				':uid' => $_SESSION['uid']
			);
			$this->query($sql, $params);
			$result = ($this->rowCount() != 1) ? false : $this->fetch();
			$this->closeCursor();
			return $result;
		}
	}
?>