<?php
	class web extends db_connect {
		
		public function check_restrict_ip() {
			$sql = "SELECT `id` FROM `web_restrict_ip` WHERE `ip` = :ip_r OR `ip` = :ip_f OR `ip` = :ip_c";
			$params = array(
				':ip_r' => $this->ip_remote,
				':ip_f' => $this->ip_forwarded,
				':ip_c' => $this->ip_client
			);
			$this->query($sql, $params);
			if ($this->rowCount() >= 1) {
				return true;
			}
			return false;
		}
	}
?>