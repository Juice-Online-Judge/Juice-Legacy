<?php
	class account extends db_connect {
		
		public function register($username, $pw, $pw_check, $email, $nickname, $std_id) {
			$pw = hash('sha512', $pw);
			$pw_check = hash('sha512', $pw_check);
			$nickname = htmlspecialchars($nickname, ENT_QUOTES);
			
			if (!preg_match("/^\w{5,32}$/", $username)) {
				$result = '帳號格式不符';
			} else if (strcmp($pw, $pw_check) != 0) {
				$result = '密碼與密碼確認不符';
			} else if (!email_check($email) or strlen($email) > 128) {
				$result = '信箱格式不符';
			} else if (($length = mb_strlen($nickname, 'UTF-8')) < 3 or $length > 16) {
				$result = '暱稱格式不符';
			} else if (!preg_match("/^\d{9}$/", $std_id)) {				$result = '學號格式不符';			} else {
				$sql = "SELECT `id` FROM `account` WHERE `username` = :username";
				$params = array(
					':username' => $username
				);
				$this->query($sql, $params);
				if ($this->rowCount() >= 1) {
					$result = '此帳號已被使用';
				} else {
					$this->closeCursor();
					
					$sql = "SELECT `uid` FROM `user_data` WHERE `email` = :email OR `nickname` = :nickname OR `std_id` = :std_id";
					$params = array(
						':email' => $email,
						':nickname' => $nickname,						':std_id' => $std_id
					);
					$this->query($sql, $params);
					if ($this->rowCount() >= 1) {
						$result = '信箱、暱稱或學號已被使用';
					} else {
						$this->closeCursor();
						
						$sql = "INSERT INTO `account` (`username`, `password`, `register_time`, `register_ip`) ";
						$sql .= "VALUES (:username, :password, :register_time, :register_ip)";
						$params = array(
							':username' => $username,
							':password' => $pw,
							':register_time' => $this->current_time,
							':register_ip' => $this->ip
						);
						$this->query($sql, $params);
						$insert_id = $this->lastInsertId();
						if ($this->rowCount() != 1 or $insert_id == 0) {
							$this->log('Error', 'INSERT the account data failed. User username : '.$username);
							$result = '更新資料時發生錯誤，請稍後再試';
						} else {
							$this->closeCursor();
							
							$sql = "INSERT INTO `user_data` (`uid`, `email`, `nickname`, `std_id`) ";
							$sql .= "VALUES (:uid, :email, :nickname, :std_id)";
							$params = array(
								':uid' => $insert_id,
								':email' => $email,
								':nickname' => $nickname,								':std_id' => $std_id
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
		
		public function update_info($uid, $email, $nickname) {
			$nickname = htmlspecialchars($nickname, ENT_QUOTES);
			
			if (!email_check($email) or strlen($email) > 128) {
				$result = '信箱格式不符';
			} else if (($length = mb_strlen($nickname, 'UTF-8')) < 3 or $length > 16) {
				$result = '暱稱格式不符';
			} else {
				$sql = "SELECT `uid` FROM `user_data` WHERE (`email` = :email OR `nickname` = :nickname) AND `uid` != :uid";
				$params = array(
					':email' => $email,
					':nickname' => $nickname,
					':uid' => $uid
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
						':uid' => $uid
					);
					$this->query($sql, $params);
					if ($this->rowCount() != 1) {
						$this->log('Error', 'Update the user data failed. User uid : '.$uid);
						$result = '更新資料時發生錯誤，請稍後再試';
					} else {
						$_SESSION['nickname'] = $nickname;
						$result = true;
					}
				}
				$this->closeCursor();
			}
			return $result;
		}
		
		public function show_profile_picture($uid) {
			$sql = "SELECT `image_type`, `image_width`, `image_height`, `image_data` FROM `user_profile_picture` WHERE `uid` = :uid AND `image_is_delete` = :image_is_delete";
			$params = array(
				':uid' => $uid,
				':image_is_delete' => false
			);
			$this->query($sql, $params);
			$result = $this->fetch();
			$this->closeCursor();
			
			if (empty($result)) {
				$sql = "SELECT `image_type`, `image_width`, `image_height`, `image_data` FROM `user_profile_picture` WHERE `uid` = :uid AND `image_is_delete` = :image_is_delete";
				$params = array(
					':uid' => 0,
					':image_is_delete' => false
				);
				$this->query($sql, $params);
				$result = $this->fetch();
				$this->closeCursor();
			}
			return $result;
		}
		
		public function update_profile_picture($uid, $image) {
			if (($image_data = getimagesize($image["tmp_name"])) === false) {
				$result = '圖片損毀，請嘗試重新上傳';
			} else if ($image['size'] == 0 or $image['size'] >= 1048575) {
				$result = '圖片大小過大，上限值為 1 MB';
			} else {
				$sql = "UPDATE `user_profile_picture` SET `image_is_delete` = :image_delete WHERE `uid` = :uid AND `image_is_delete` = :image_is_delete";
				$params = array(
					':image_delete' => true,
					':uid' => $uid,
					':image_is_delete' => false
					
				);
				$this->query($sql, $params);
				$this->closeCursor();
				
				$sql = "INSERT INTO `user_profile_picture` (`uid`, `image_type`, `image_size`, `image_width`, `image_height`, `image_data`, `upload_time`, `upload_ip`) VALUES (:uid, :image_type, :image_size, :image_width, :image_height, :image_data, :upload_time, :upload_ip)";
				$params = array(
					array(':uid', $uid, 'PARAM_INT'),
					array(':image_type', $image_data['mime'], 'PARAM_STR'),
					array(':image_size', $image['size'], 'PARAM_INT'),
					array(':image_width', $image_data['0'], 'PARAM_INT'),
					array(':image_height', $image_data['1'], 'PARAM_INT'),
					array(':image_data', file_get_contents($image['tmp_name']), 'PARAM_LOB'),
					array(':upload_time', $this->current_time, 'PARAM_INT'),
					array(':upload_ip', $this->ip, 'PARAM_STR')
				);
				$this->prepare($sql);
				$this->bindParam($params);
				$this->execute();
				if ($this->rowCount() != 1) {
					$result = '更新資料時發生錯誤，請稍後再試';
				} else {
					$result = true;
				}
				$this->closeCursor();
			}
			return $result;
		}
		
		public function delete_image($uid) {
			$sql = "UPDATE `user_profile_picture` SET `image_is_delete` = :image_delete WHERE `uid` = :uid AND `image_is_delete` = :image_is_delete";
			$params = array(
				':image_delete' => true,
				':uid' => $uid,
				':image_is_delete' => false
			);
			$this->query($sql, $params);
			if ($this->rowCount() != 1) {
				$result = '刪除失敗，您尚未上傳過大頭貼';
			} else {
				$result = true;
			}
			$this->closeCursor();
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
					
					$user_data = $this->get_user_data($_SESSION['uid']);
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
					
					$result = $this->get_user_data($_SESSION['uid']);
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
		
		public function get_user_data($uid) {
			$sql = "SELECT `email`, `nickname`, `std_id`, `group_id`, `admin_group` FROM `user_data` WHERE `uid` = :uid";
			$params = array(
				':uid' => $uid
			);
			$this->query($sql, $params);
			$result = ($this->rowCount() != 1) ? false : $this->fetch();
			$this->closeCursor();
			return $result;
		}
	}
?>