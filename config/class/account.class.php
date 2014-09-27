<?php
	class account extends db_connect {
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			parent::__construct($db_type, $db_host, $db_name, $db_username, $db_password);
		}
		
		public function __destruct() {
			parent::__destruct();
		}
		
		public function register($username, $password, $password_check, $pw_secret, $email, $nickname) {
			$password = hash('sha512', $password);
			$password_check = hash('sha512', $password_check);
			$pw_secret = hash('sha512', $pw_secret);
			$nickname = htmlspecialchars($nickname, ENT_QUOTES);
			if (!preg_match("/^\w{5,32}$/", $username)) {
				return 'Invalid username.';
			} else if (strcmp($password, $password_check) != 0) {
				return 'The password and password check do not match.';
			} else if (strcmp($password, $pw_secret) == 0) {
				return 'The password and password secret are equal.';
			} else if (!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email) or strlen($email) > 128) {
				return 'Invalid email address.';
			} else if (($length = mb_strlen($nickname, 'UTF-8')) < 5 or $length > 16) {
				return 'Invalid nickname.';
			} else {
				$sql = "SELECT `id` FROM `account` WHERE `username` = :username";
				$params = array(
					':username' => $username
				);
				$this->query($sql, $params);
				if ($this->rowCount() == 1) {
					$this->closeCursor();
					return 'The username is already exists.';
				} else {
					$this->closeCursor();
					$sql = "SELECT `uid` FROM `user_data` WHERE `email` = :email OR `nickname` = :nickname LIMIT 1";
					$params = array(
						':email' => $email,
						':nickname' => $nickname
					);
					$this->query($sql, $params);
					if ($this->rowCount() == 1) {
						$this->closeCursor();
						return 'The email or nickname are already existed.';
					} else {
						$this->closeCursor();
						$sql = "INSERT INTO `account` (`username`, `password`, `pw_secret`, `account_create_time`, `account_create_ip`) ";
						$sql .= "VALUES (:username, :password, :pw_secret, :account_create_time, :account_create_ip)";
						$params = array(
							':username' => $username,
							':password' => $password,
							':pw_secret' => $pw_secret,
							':account_create_time' => $this->current_time,
							':account_create_ip' => $this->ip
						);
						$this->query($sql, $params);
						$insert_id = $this->lastInsertId();
						if ($this->rowCount() != 1 or $insert_id == 0) {
							$this->closeCursor();
							$this->log('Error', 'INSERT the account data failed. User username : '.$username);
							return 'There is something wrong when updating the data.';
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
								$this->closeCursor();
								$this->log('Error', 'INSERT the user data failed. User uid : '.$insert_id);
								return 'There is something wrong when updating the data.';
							} else {
								$this->closeCursor();
								return true;
							}
						}
					}
				}
			}
		}
		
		public function update_pw($pw_secret, $new_pw, $new_pw_check) {
			$pw_secret = hash('sha512', $pw_secret);
			$new_pw = hash('sha512', $new_pw);
			$new_pw_check = hash('sha512', $new_pw_check);
			
			if (strcmp($new_pw, $new_pw_check) != 0) {
				$result = 'The new password and password check do not match.';
			} else {
				$sql = "UPDATE `account` SET `password` = :password WHERE `id` = :uid AND `pw_secret` = :pw_secret";
				$params = array(
					':password' => $new_pw,
					':uid' => $_SESSION['uid'],
					':pw_secret' => $pw_secret
				);
				$this->query($sql, $params);
				if ($this->rowCount() != 1) {
					$result = 'The second password is incorrect.';
				} else {
					$result = true;
				}
			}
			return $result;
		}
		
		public function update_info($email, $nickname) {
			$nickname = htmlspecialchars($nickname, ENT_QUOTES);
			
			if (!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email) or strlen($email) > 128) {
				$result = 'Invalid email address.';
			} else if (($length = mb_strlen($nickname, 'UTF-8')) < 5 or $length > 16) {
				$result = 'Invalid nickname.';
			} else {
				$sql = "SELECT `uid` FROM `user_data` WHERE `email` = :email OR `nickname` = :nickname AND `uid` != :uid LIMIT 1";
				$params = array(
					':email' => $email,
					':nickname' => $nickname,
					':uid' => $_SESSION['uid']
				);
				$this->query($sql, $params);
				if ($this->rowCount() == 1) {
					$result = 'The email or nickname are already existed.';
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
						$result = 'There is something wrong when updating the data.';
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
				$this->closeCursor();
				return 'Invalid username or password.';
			} else {
				$result = $this->fetch();
				if ($result['allow_login'] == false) {
					$this->closeCursor();
					$this->log('Error', 'The user data does not exist. User username : '.$username);
					return 'This account is not allow to login.';
				} else {
					$_SESSION['uid'] = $result['id'];
					$this->closeCursor();
					$sql = "SELECT `nickname`, `group_id`, `admin_group` FROM `user_data` WHERE `uid` = :uid";
					$params = array(
						':uid' => $_SESSION['uid']
					);
					$this->query($sql, $params);
					if ($this->rowCount() != 1) {
						session_unset();
						session_regenerate_id(true);
						$this->closeCursor();
						return 'There is something wrong when loading the data.';
					} else {
						$result = $this->fetch();
						$_SESSION['nickname'] = $result['nickname'];
						$_SESSION['group_id'] = $result['group_id'];
						$_SESSION['admin_group'] = $result['admin_group'];
						$this->closeCursor();
						$sql = "UPDATE `account` SET `last_login_time` = :last_login_time, `last_login_ip` = :last_login_ip WHERE `id` = :id";
						$params = array(
							':last_login_time' => $this->current_time,
							':last_login_ip' => $this->ip,
							':id' => $_SESSION['uid']
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
							setcookie("rem_user", $rem_user, $rem_time_end, '/', '', false, true);
							setcookie("rem_verify", $rem_verify, $rem_time_end, '/', '', false, true);
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
						$this->closeCursor();
						return true;
					}
				}
			}
		}
		
		public function check_login() {
			if (isset($_COOKIE['rem_user']) and isset($_COOKIE['rem_verify']) and !isset($_SESSION['uid'])) {
				$sql = "SELECT `uid` FROM `web_login_log` WHERE `remember_username` = :remember_username AND `remember_verify` = :remember_verify ORDER BY `id` DESC LIMIT 1";
				$params = array(
					':remember_username' => $_COOKIE['rem_user'],
					':remember_verify' => $_COOKIE['rem_verify']
				);
				$this->query($sql, $params);
				if ($this->rowCount() == 1) {
					$result = $this->fetch();
					$_SESSION['uid'] = $result['uid'];
					$this->closeCursor();
					$sql = "SELECT `nickname`, `group_id`, `admin_group` FROM `user_data` WHERE `uid` = :uid";
					$params = array(
						':uid' => $_SESSION['uid']
					);
					$this->query($sql, $params);
					if ($this->rowCount() != 1) {
						session_unset();
						session_regenerate_id(true);
					} else {
						$result = $this->fetch();
						$_SESSION['nickname'] = $result['nickname'];
						$_SESSION['group_id'] = $result['group_id'];
						$_SESSION['admin_group'] = $result['admin_group'];
					}
					$this->closeCursor();
				}
			}
		}
		
		public function logout() {
			if (isset($_SESSION['uid'])) {
				$sql = "UPDATE `web_login_log` SET `remember_time_end` = :remember_time_end WHERE `uid` = :uid AND `remember_time_end` >= :current_time";
				$params = array(
					':remember_time_end' => $this->current_time,
					':uid' => $_SESSION['uid'],
					':current_time' => $this->current_time
				);
				$this->query($sql, $params);
				$this->closeCursor();
			}
			setcookie("rem_user", "", ($this->current_time - 3600), '/', '', false, true);
			setcookie("rem_verify", "", ($this->current_time - 3600), '/', '', false, true);
			setcookie("verify_code_login", '', ($this->current_time - 3600), '/', '', false, true);
			setcookie("verify_code_register", '', ($this->current_time - 3600), '/', '', false, true);
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