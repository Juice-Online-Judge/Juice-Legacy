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
			$result = $this->fetch();
			$this->closeCursor();
			if (!empty($result)) {
				$sql = "SELECT `practice_key`, `practice_content` FROM `lesson_practice` WHERE `lesson_id` = :lesson_id AND `practice_is_delete` = :practice_is_delete";
				$params = array(
					':lesson_id' => $result['lesson_practice'],
					':practice_is_delete' => false
				);
				$this->query($sql, $params);
				$tmp = $this->fetchAll();
				$result['practice'] = $tmp;
				$this->closeCursor();
				$sql = "SELECT `implement_key`, `implement_content`, `time_limit`, `memory_limit`, `file_limit`, `mode`, `other_limit` FROM `lesson_implement` WHERE `lesson_id` = :lesson_id AND `implement_is_delete` = :implement_is_delete";
				$params = array(
					':lesson_id' => $result['lesson_implement'],
					':implement_is_delete' => false
				);
				$this->query($sql, $params);
				$tmp = $this->fetchAll();
				$result['implement'] = $tmp;
				$this->closeCursor();
			}
			return $result;
		}
		
		public function add_lesson($type, array $content = array()) {
			$key = hash_key('sha1');
			$content['title'] = htmlspecialchars($content['title'], ENT_QUOTES);
			$content['goal'] = htmlspecialchars($content['goal'], ENT_QUOTES);
			$content['content'] = htmlspecialchars($content['content'], ENT_QUOTES);
			$content['example'] = htmlspecialchars($content['example'], ENT_QUOTES);
			if (!preg_match("/^\d{1,2}$/", $content['unit'])) {
				$result = array(
					'error' => 'Invalid unit.'
				);
			} else if (!preg_match("/^[1-4]{1}$/", $content['level'])) {
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
				$sql = "SELECT `id` FROM `lesson` WHERE `lesson_unit` = :lesson_unit";
				$params = array(
					':lesson_unit' => $content['unit']
				);
				$this->query($sql, $params);
				if ($this->rowCount() >= 1) {
					$result = array(
						'error' => 'The unit is already exists.'
					);
				} else {
					$this->closeCursor();
					$sql = "INSERT INTO `lesson` (`lesson_key`, `lesson_unit`, `lesson_level`, `lesson_title`, `lesson_goal`, `lesson_content`, `lesson_example`, `lesson_create_user`, `lesson_create_time`) ";
					$sql .= "VALUES (:lesson_key, :lesson_unit, :lesson_level, :lesson_title, :lesson_goal, :lesson_content, :lesson_example, :lesson_create_user, :lesson_create_time)";
					$params = array(
						':lesson_key' => $key,
						':lesson_unit' => $content['unit'],
						':lesson_level' => $content['level'],
						':lesson_title' => $content['title'],
						':lesson_goal' => $content['goal'],
						':lesson_content' => $content['content'],
						':lesson_example' => $content['example'],
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
						$sql = "UPDATE `lesson` SET `lesson_practice` = :lesson_practice, `lesson_implement` = :lesson_implement WHERE `lesson_key` = :lesson_key";
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
							$content['title'] = htmlspecialchars($content['title'], ENT_QUOTES);
							$content['goal'] = htmlspecialchars($content['goal'], ENT_QUOTES);
							$content['content'] = htmlspecialchars($content['content'], ENT_QUOTES);
							$content['example'] = htmlspecialchars($content['example'], ENT_QUOTES);
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
									':lesson_key' => $content['key']
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
							foreach ($content as $key => $value) {
								if (is_array($value)) {
									$value['content'] = htmlspecialchars($value['content'], ENT_QUOTES);
									if ($value['action'] == 'add') {
										if (mb_strlen($value['content']) > 0) {
											$sql = "INSERT INTO `lesson_practice` (`lesson_id`, `practice_key`, `practice_content`) VALUES ";
											$sql .= "(:lesson_id, :practice_key, :practice_content)";
											$params = array(
												':lesson_id' => $lesson_id['id'],
												':practice_key' => hash_key('md5'),
												':practice_content' => $value['content']
											);
										} else {
											continue;
										}
									} else {
										$sql = "UPDATE `lesson_practice` SET `practice_content` = :practice_content WHERE `practice_key` = :practice_key";
										$params = array(
											':practice_content' => $value['content'],
											':practice_key' => $value['key']
										);
									}
									$this->query($sql, $params);
									if ($this->rowCount() != 1) {
										$result = array(
											'error' => 'There is something wrong when updating the data.'
										);
										$this->closeCursor();
										break 2;
									}
									$this->closeCursor();
								}
							}
							$result = array(
								'updated' => true
							);
							break;
						case 'implement':
							foreach ($content as $key => $value) {
								if (is_array($value)) {
									$value['content'] = htmlspecialchars($value['content'], ENT_QUOTES);
									if ($value['action'] == 'add') {
										if (mb_strlen($value['content']) > 0) {
											$sql = "INSERT INTO `lesson_implement` (`lesson_id`, `implement_key`, `implement_content`, `time_limit`, `memory_limit`, `file_limit`, `mode`, `other_limit`) VALUES ";
											$sql .= "(:lesson_id, :implement_key, :implement_content, :time_limit, :memory_limit, :file_limit, :mode, :other_limit)";
											$params = array(
												':lesson_id' => $lesson_id['id'],
												':implement_key' => hash_key('md5'),
												':implement_content' => $value['content'],
												':time_limit' => $value['time_limit'],
												':memory_limit' => $value['memory_limit'],
												':file_limit' => $value['file_limit'],
												':mode' => $value['mode'],
												':other_limit' => $value['other_limit']
											);
										} else {
											continue;
										}
									} else {
										$sql = "UPDATE `lesson_implement` SET `implement_content` = :implement_content, `time_limit` = :time_limit, `memory_limit` = :memory_limit, ";
										$sql .= "`file_limit` = :file_limit, `mode` = :mode, `other_limit` = :other_limit WHERE `implement_key` = :implement_key";
										$params = array(
											':implement_content' => $value['content'],
											':time_limit' => $value['time_limit'],
											':memory_limit' => $value['memory_limit'],
											':file_limit' => $value['file_limit'],
											':mode' => $value['mode'],
											':other_limit' => htmlspecialchars($value['other_limit'], ENT_QUOTES),
											':implement_key' => $value['key']
										);
									}
									$this->query($sql, $params);
									if ($this->rowCount() != 1) {
										$result = array(
											'error' => 'There is something wrong when updating the data.'
										);
										$this->closeCursor();
										break 2;
									}
									$this->closeCursor();
								}
							}
							$result = array(
								'updated' => true
							);
							break;
						default :
							$result = array(
								'error' => 'Invalid type.'
							);
							break;
					}
				}
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
		
		public function show_image($key, $image_key) {
			$lesson_id = $this->get_lesson_id($key);
			if (isset($lesson_id['error'])) {
				$result = array(
					'error' => 'The unit does not exist.'
				);
			} else {
				$sql = "SELECT `image_type`, `image_width`, `image_height`, `image_data` FROM `lesson_image` WHERE `lesson_id` = :lesson_id AND `image_key` = :image_key AND `image_is_delete` = :image_is_delete";
				$params = array(
					':lesson_id' => $lesson_id['id'],
					':image_key' => $image_key,
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
				$sql = "SELECT `id` FROM `lesson_image` WHERE `lesson_id` = :lesson_id AND `image_is_delete` = :image_is_delete";
				$params = array(
					':lesson_id' => $lesson_id['id'],
					':image_is_delete' => false
				);
				$this->query($sql, $params);
				return $this->fetchAll();
			}
		}
		
		public function add_image($lesson_id, $image) {
			if (($image_data = getimagesize($image["tmp_name"])) === false) {
				$result = array(
					'error' => 'Please check the image that you have uploaded.'
				);
			} else if ($image['size'] == 0 or $image['size'] >= 16777215) {
				$result = array(
					'error' => 'Please check the image that you have uploaded.'
				);
			} else {
				if (!preg_match("/^\d{1,2}$/", $lesson_id)) {
					$result = array(
						'error' => 'Invalid unit.'
					);
				} else {
					$sql = "INSERT INTO `lesson_image` (`lesson_id`, `image_key`, `image_type`, `image_size`, `image_width`, `image_height`, `image_data`) VALUES (:lesson_id, :image_key, :image_type, :image_size, :image_width, :image_height, :image_data)";
					$params = array(
						array(':lesson_id', $lesson_id, 'PARAM_INT'),
						array(':image_key', hash_key('md5'), 'PARAM_STR'),
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
							'result' => true
						);
					}
					$this->closeCursor();
				}
			}
			return json_encode($result);
		}
		
		public function delete_image($key, $image_key) {
			$lesson_id = $this->get_lesson_id($key);
			if (isset($lesson_id['error'])) {
				$result = array(
					'error' => 'The unit does not exist.'
				);
			} else {
				$sql = "UPDATE `lesson_image` SET `image_is_delete` = :image_is_delete WHERE `lesson_id` = :lesson_id AND `image_key` = :image_key";
				$params = array(
					':image_is_delete' => true,
					':lesson_id' => $lesson_id['id'],
					':image_key' => $image_key
				);
				$this->query($sql, $params);
				if ($this->rowCount() != 1) {
					$result = array(
						'error' => 'There is something wrong when updating the data.'
					);
				} else {
					$result = array(
						'result' => true
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
				$result = array(
					'id' => $lesson_id['id']
				);
			}
			$this->closeCursor();
			return $result;
		}
	}
?>