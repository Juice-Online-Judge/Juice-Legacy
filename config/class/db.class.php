<?php
	class db_connect {
		
		protected $pdo = null;
		
		protected $stmt = null;
		
		public $current_time;
		
		public $ip_client, $ip_forwarded, $ip_remote, $ip;
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			try {
				$this->ip_client = (!empty($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'Unknown';
				$this->ip_forwarded = (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'Unknown';
				$this->ip_remote = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
				$this->ip = ($this->ip_client).','.($this->ip_forwarded).','.($this->ip_remote);
				$this->current_time = time();
				
				$opt  = array(
					PDO::MYSQL_ATTR_FOUND_ROWS => TRUE
				);
				$this->pdo = new PDO($db_type.':host='.$db_host.';dbname='.$db_name.';charset=UTF8', $db_username, $db_password, $opt);
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function __destruct() {
			$this->stmt = null;
			$this->pdo = null;
		}
		
		public function query($sql, array $params = array()) {
			try {
				$this->stmt = $this->pdo->prepare($sql);
				(empty($params)) ? $this->stmt->execute() : $this->stmt->execute($params);
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function prepare($sql) {
			try {
				$this->stmt = $this->pdo->prepare($sql);
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function bindParam($params) {
			try {
				foreach ($params as $tmp) {
					switch ($tmp[2]) {
						case 'PARAM_BOOL':
							$this->stmt->bindParam($tmp[0], $tmp[1], PDO::PARAM_BOOL);
							break;
						case 'PARAM_INT':
							$this->stmt->bindParam($tmp[0], $tmp[1], PDO::PARAM_INT);
							break;
						case 'PARAM_STR':
							$this->stmt->bindParam($tmp[0], $tmp[1], PDO::PARAM_STR);
							break;
						case 'PARAM_LOB':
							$this->stmt->bindParam($tmp[0], $tmp[1], PDO::PARAM_LOB);
							break;
						default :
							$this->stmt->bindParam($tmp[0], $tmp[1]);
							break;
					}
				}
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function execute() {
			try {
				$this->stmt->execute();
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function closeCursor() {
			try {
				$this->stmt->closeCursor();
				$this->stmt = null;
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function lastInsertId($name) {
			try {
				if (!is_string($name)) {
					return $this->pdo->lastInsertId();
				}
				return $this->pdo->lastInsertId($name);
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function rowCount() {
			try {
				return $this->stmt->rowCount();
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function fetch() {
			try {
				return $this->stmt->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function fetchAll() {
			try {
				return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function hash_check($table, $field, $hash) {
			$sql = "SELECT `id` FROM `".$table."` WHERE `".$field."` = :hash LIMIT 1";
			$params = array(
				':hash' => $hash
			);
			$this->query($sql, $params);
			return ($this->rowCount() == 1) ? false : true;
		}
		
		public function stmt_errorCode() {
			try {
				return $this->stmt->errorCode();
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function stmt_errorInfo() {
			try {
				return $this->stmt->errorInfo();
			} catch (PDOException $e) {
				$this->db_error($e->getMessage());
				exit();
			}
		}
		
		public function db_error($msg) {
			if (!DEBUG_MODE) {
				$msg = 'There is something error when connecting to the database.';
			}
			header("Location: ".WEB_ERROR_PAGE."?message=".urlencode($msg));
			exit();
		}
		
		public function log($type, $data) {
			$log_file_name = 'log-'.date("Y-m-d");
			$log_file_path = $_SERVER['DOCUMENT_ROOT']."/log/".$log_file_name;
			if (!file_exists($log_file_path)) {
				if (!($file = fopen($log_file_path, "w+"))) {
					error('There is something wrong when loading the data. Error Code : 8');
					exit();
				}
			} else if (!($file = fopen($log_file_path, "r+"))) {
				error('There is something wrong when loading the data. Error Code : 8');
				exit();
			}
			$log = date("Y-m-d H:i:s")."\t[ ".$type." ]\t".$data.PHP_EOL;
			fwrite($file, $log);
			fclose($file);
		}
	}
?>