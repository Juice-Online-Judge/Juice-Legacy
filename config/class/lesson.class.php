<?php
	class lesson extends db_connect {
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			parent::__construct($db_type, $db_host, $db_name, $db_username, $db_password);
		}
		
		public function __destruct() {
			parent::__destruct();
		}
		
		public function list_lesson(array $filter = array()) {
			$sql = "SELECT `lesson_key`, `lesson_unit`, `lesson_level`, `lesson_title`, `lesson_is_visible` FROM `lesson` WHERE `lesson_is_delete` = :lesson_is_delete ORDER BY `lesson_unit` ASC";
			$params = array(
				':lesson_is_delete' => false
			);
			if (!empty($filter)) {
				if (isset($filter['level'])) {
					$sql .= " AND `lesson_level` = :lesson_level";
					$params[':lesson_level'] = $filter['level'];
				}
			}
			$this->query($sql, $params);
			return $this->fetchAll();
		}
		
		public function get_lesson_content($key) {
			$sql = "SELECT `lesson_unit`, `lesson_level`, `lesson_title`, `lesson_goal`, `lesson_content`, `lesson_example`, `lesson_practice`, `lesson_implement`, `lesson_is_visible` FROM `lesson` WHERE `lesson_key` = :lesson_key AND `lesson_is_delete` = :lesson_is_delete";
			$params = array(
				':lesson_key' => $key,
				':lesson_is_delete' => false
			);
			$this->query($sql, $params);
			return $this->fetch();
		}
		
		public function add_lesson($unit, $level, $title, $goal, $content, $example) {
			$key = hash_key('sha1');
			$title = htmlspecialchars($title, ENT_QUOTES);
			$goal = htmlspecialchars($goal, ENT_QUOTES);
			$content = htmlspecialchars($content, ENT_QUOTES);
			$example = htmlspecialchars($example, ENT_QUOTES);
			if (!preg_match("/^\d{1,2}$/", $unit)) {
				$result = array(
					'error' => 'Invalid unit.'
				);
			} else if (!preg_match("/^[1-4]{1}$/", $level)) {
				$result = array(
					'error' => 'Invalid level.'
				);
			} else if (($length = mb_strlen($title, 'UTF-8')) == 0 or $length > 128) {
				$result = array(
					'error' => 'Invalid title.'
				);
			} else {
				if (mb_strlen($goal, 'UTF-8') == 0) {
					$goal = null;
				}
				if (mb_strlen($content, 'UTF-8') == 0) {
					$content = null;
				}
				if (mb_strlen($example, 'UTF-8') == 0) {
					$example = null;
				}
				$lesson_id = $this->get_lesson_id($content['key']);
				if (isset($lesson_id['id'])) {
					$result = array(
						'error' => 'The unit is already exists.'
					);
				} else {
					$sql = "INSERT INTO `lesson` (`lesson_key`, `lesson_unit`, `lesson_level`, `lesson_title`, `lesson_goal`, `lesson_content`, `lesson_example`, `lesson_create_user`, `lesson_create_time`) ";
					$sql .= "VALUES (:lesson_key, :lesson_unit, :lesson_level, :lesson_title, :lesson_goal, :lesson_content, :lesson_example, :lesson_create_user, :lesson_create_time)";
					$params = array(
						':lesson_key' => $key,
						':lesson_unit' => $unit,
						':lesson_level' => $level,
						':lesson_title' => $title,
						':lesson_goal' => $goal,
						':lesson_content' => $content,
						':lesson_example' => $example,
						':lesson_create_user' => $_SESSION['uid'],
						':lesson_create_time' => $this->current_time
					);
					$this->query($sql, $params);
					$insert_id = $this->lastInsertId();
					if ($this->rowCount() != 1 or $insert_id == 0) {
						$result = array(
							'error' => 'There is something wrong when updating the data.'
						);
					} else {
						$this->closeCursor();
						$sql = "UPDATE `lesson` SET `lesson_practice` = :lesson_practice AND `lesson_implement` = :lesson_implement WHERE `lesson_key` = :lesson_key";
						$params = array(
							':lesson_practice' => $insert_id,
							':lesson_implement' => $insert_id,
							':lesson_key' => $key
						);
						$this->query($sql, $params);
						if ($this->rowCount() != 1) {
							$result = array(
								'error' => 'There is something wrong when updating the data.'
							);
						} else {
							$result = array(
								'key' => $key
							);
						}
					}
				}
				$this->closeCursor();
			}
			return json_encode($result);
		}
		
		public function update_lesson($type, array $content = array()) {
			if (!empty($content)) {
				$lesson_id = $this->get_lesson_id($content['key']);
				if (isset($lesson_id['error'])) {
					$result = array(
						'error' => 'The unit does not exist.'
					);
				} else {
					switch ($type) {
						case 'lesson':
							if (!preg_match("/^[1-4]{1}$/", $content['level'])) {
								$result = array(
									'error' => 'Invalid level.'
								);
							} else if (($length = mb_strlen($content['title'], 'UTF-8')) == 0 or $length > 128) {
								$result = array(
									'error' => 'Invalid title.'
								);
							} else {
								if (mb_strlen($content['goal'], 'UTF-8') == 0) {
									$content['goal'] = null;
								}
								if (mb_strlen($content['content'], 'UTF-8') == 0) {
									$content['content'] = null;
								}
								if (mb_strlen($content['example'], 'UTF-8') == 0) {
									$content['example'] = null;
								}
								$sql = "UPDATE `lesson` SET `lesson_level` = :lesson_level, `lesson_title` = :lesson_title, `lesson_goal` = :lesson_goal, `lesson_content` = :lesson_content, `lesson_example` = :lesson_example, ";
								$sql .= "`lesson_last_update_user` = :lesson_last_update_user, `lesson_last_update_time` = :lesson_last_update_time WHERE `lesson_key` = :lesson_key";
								$params = array(
									':lesson_level' => $content['level'],
									':lesson_title' => $content['title'],
									':lesson_goal' => $content['goal'],
									':lesson_content' => $content['content'],
									':lesson_example' => $content['example'],
									':lesson_last_update_user' => $_SESSION['uid'],
									':lesson_last_update_time' => $this->current_time,
									':lesson_key' => $key
								);
								$this->query($sql, $params);
								if ($this->rowCount() != 1) {
									$result = array(
										'error' => 'There is something wrong when updating the data.'
									);
								} else {
									$result = array(
										'updated' => true
									);
								}
							}
							break;
						case 'practice':
							if ($content['action'] == 'add') {
								$sql = "INSERT INTO `lesson_practice` (`lesson_id`, `practice_content`) VALUES ";
								foreach ($_POST as $key => $value) {
									if (stripos($key, 'practice') !== false) {
										$tmp[] = "(:lesson_id, :".$key.")";
										$params[':'.$key] = $value;
									}
								}
								$sql .= implode(", ", $tmp);
								$params[':lesson_id'] = $lesson_id;
							} else {
								$sql = "UPDATE `lesson_practice` SET ";
								foreach ($_POST as $key => $value) {
									if (stripos($key, 'practice') !== false) {
										$tmp[] = "`practice_content` = :".$key."";
										$params[':'.$key] = $value;
									}
								}
								$sql .= implode(", ", $tmp);
								$params[':lesson_id'] = $lesson_id;
							}
							break;
						case 'implement':
							break;
						default :
							$result = array(
								'error' => 'Invalid type.'
							);
							break;
					}
				}
				$this->closeCursor();
			} else {
				$result = array(
					'error' => 'Empty content.'
				);
			}
			return json_encode($result);
		}
		
		public function change_lesson_visible($key, $type) {
			if (!preg_match("/^[0-1]{1}$/", $type)) {
				return 'Invalid type.';
			} else {
				$type = ($type) ? true : false;
				$sql = "UPDATE `lesson` SET `lesson_is_visible` = :lesson_is_visible WHERE `lesson_key` = :lesson_key";
				$params = array(
					':lesson_is_visible' => $type,
					':lesson_key' => $key
				);
				$this->query($sql, $params);
				if ($this->rowCount() != 1) {
					$this->closeCursor();
					return 'There is something wrong when updating the data.';
				} else {
					$this->closeCursor();
					return true;
				}
			}
		}
		
		public function delete_lesson($key) {
			$sql = "UPDATE `lesson` SET `lesson_is_delete` = :lesson_is_delete WHERE `lesson_key` = :lesson_key";
			$params = array(
				':lesson_is_delete' => true,
				':lesson_key' => $key
			);
			$this->query($sql, $params);
			if ($this->rowCount() != 1) {
				$this->closeCursor();
				return 'There is something wrong when updating the data.';
			} else {
				$this->closeCursor();
				return true;
			}
		}
		
		public function show_image($key, $image_id) {
			$lesson_id = $this->get_lesson_id($key);
			if (isset($lesson_id['error'])) {
				$result = array(
					'error' => 'The unit does not exist.'
				);
			} else {
				$sql = "SELECT `image_type`, `image_width`, `image_height`, `image_data` FROM `lesson_image` WHERE `id` = :image_id AND `lesson_id` = :lesson_id AND `image_is_delete` = :image_is_delete";
				$params = array(
					':image_id' => $image_id,
					':lesson_id' => $lesson_id['id'],
					':image_is_delete' => false
				);
				$this->query($sql, $params);
				return $this->fetch();
			}
		}
		
		public function list_lesson_image($key) {
			$lesson_id = $this->get_lesson_id($key);
			if (isset($lesson_id['error'])) {
				$result = array(
					'error' => 'The unit does not exist.'
				);
			} else {
				$sql = "SELECT `id` FROM `lesson_image` WHERE `lesson_id` = :lesson_id AND `image_is_used` = :image_is_used AND `image_is_delete` = :image_is_delete";
				$params = array(
					':lesson_id' => $lesson_id['id'],
					':image_is_used' => true,
					':image_is_delete' => false
				);
				$this->query($sql, $params);
				return $this->fetchAll();
			}
		}
		
		public function list_unused_image($key) {
			$lesson_id = $this->get_lesson_id($key);
			if (isset($lesson_id['error'])) {
				$result = array(
					'error' => 'The unit does not exist.'
				);
			} else {
				$sql = "SELECT `id` FROM `lesson_image` WHERE `lesson_id` = :lesson_id AND `image_is_used` = :image_is_used AND `image_is_delete` = :image_is_delete";
				$params = array(
					':lesson_id' => $lesson_id['id'],
					':image_is_used' => false,
					':image_is_delete' => false
				);
				$this->query($sql, $params);
				return $this->fetchAll();
			}
		}
		
		public function add_image($key, $image) {
			if (($image_data = getimagesize($image["tmp_name"])) === false) {
				$result = array(
					'error' => 'Please check the image that you have uploaded.'
				);
			} else if ($image['size'] == 0 or $image['size'] >= 16777215) {
				$result = array(
					'error' => 'Please check the image that you have uploaded.'
				);
			} else {
				$lesson_id = $this->get_lesson_id($key);
				if (isset($lesson_id['error'])) {
					$result = array(
						'error' => 'The unit does not exist.'
					);
				} else {
					$sql = "INSERT INTO `lesson_image` (`lesson_id`, `image_type`, `image_size`, `image_width`, `image_height`, `image_data`) VALUES (:lesson_id, :image_type, :image_size, :image_width, :image_height, :image_data)";
					$params = array(
						array(':lesson_id', $lesson_id['id'], 'PARAM_INT'),
						array(':image_type', $image_data['mime'], 'PARAM_STR'),
						array(':image_size', $image['size'], 'PARAM_INT'),
						array(':image_width', $image_data['0'], 'PARAM_INT'),
						array(':image_height', $image_data['1'], 'PARAM_INT'),
						array(':image_data', file_get_contents($image['tmp_name']), 'PARAM_LOB')
					);
					$this->prepare($sql);
					$this->bindParam($params);
					$this->execute();
					$insert_id = $this->lastInsertId();
					if ($this->rowCount() != 1) {
						$result = array(
							'error' => 'There is something wrong when updating the data.'
						);
					} else {
						$result = array(
							'id' => $insert_id
						);
					}
					$this->closeCursor();
				}
			}
			return json_encode($result);
		}
		
		public function delete_image($key, $image_id) {
			$lesson_id = $this->get_lesson_id($key);
			if (isset($lesson_id['error'])) {
				$result = array(
					'error' => 'The unit does not exist.'
				);
			} else {
				$sql = "UPDATE `lesson_image` SET `image_is_delete` = :image_is_delete WHERE `id` = :image_id AND `lesson_id` = :lesson_id";
				$params = array(
					':image_is_delete' => true,
					':image_id' => $image_id,
					':lesson_id' => $lesson_id['id']
				);
				$this->query($sql, $params);
				if ($this->rowCount() != 1) {
					$result = array(
						'error' => 'There is something wrong when updating the data.'
					);
				} else {
					$result = array(
						'id' => $lesson_id['id']
					);
				}
				$this->closeCursor();
			}
			return json_encode($result);
		}
		
		public function get_lesson_id($key) {
			$sql = "SELECT `id` FROM `lesson` WHERE `lesson_key` = :lesson_key";
			$params = array(
				':lesson_key' => $key
			);
			$this->query($sql, $params);
			if ($this->rowCount() != 1) {
				$result = array(
					'error' => 'The unit does not exist.'
				);
			} else {
				$lesson_id = $this->fetch();
				$this->closeCursor();
				$result = array(
					'id' => $lesson_id['id']
				);
			}
			return $result;
		}
	}
?>