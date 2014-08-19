<?php
	class about extends db_connect {
		
		public function __construct($db_type, $db_host, $db_name, $db_username, $db_password) {
			parent::__construct($db_type, $db_host, $db_name, $db_username, $db_password);
		}
		
		public function __destruct() {
			parent::__destruct();
		}
		
		/*
			type : 0 -> website, 1 -> member
		*/
		public function show_groups($type) {
			$sql = "SELECT `groups`, `user`, `content` FROM `web_about` WHERE `type` = :type";
			$params = array(
				':type' => $type
			);
			$this->query($sql, $params);
			return $this->fetchAll();
		}
	}
?>