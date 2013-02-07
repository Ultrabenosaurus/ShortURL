<?php

class URL {
	private $mysqli;
	private $domain;
	private $user_ip;
	private $salt = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-_";

	public function __construct($domain = null){
		if(is_null($this->mysqli)){
			$this->mysqli = new mysqli('localhost', 'root', '', 'test');
		}
		$this->create_tables();
		$this->set_domain($domain);
		$this->user_ip = $_SERVER['REMOTE_ADDR'];
	}

	public function __destruct(){
		$this->mysqli->close();
		$this->mysqli = null;
		$this->domain = null;
		$this->user_ip = null;
		$this->salt = null;
	}

	public function add_url($url){
		$exist = $this->check_url($url);
		if($exist){
			return $this->get_domain().$exist;
		}
		$key = $this->url_key();
		$query = $this->mysqli->prepare("INSERT INTO `urls` (`url`, `key`, `ip`) VALUES (?, ?, ?)");
		$query->bind_param('sss', $url, $key, $this->user_ip);
		$query->execute();
		return ($query->insert_id > 0) ? $this->get_domain().$key : false;
	}

	public function get_url($key){
		$query = $this->mysqli->prepare("SELECT `url` FROM `urls` WHERE `key` = ?");
		$query->bind_param('s', $key);
		$query->execute();
		$query->store_result();
		$query->bind_result($url);
		if($query->fetch() === true){
			$query->free_result();
			$this->add_hit($key);
			return $url;
		}
		return false;
	}

	private function create_tables(){
		$urls = $this->mysqli->prepare("CREATE TABLE IF NOT EXISTS `urls` ("
		." `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,"
		." `url` VARCHAR(255) NOT NULL,"
		." `key` VARCHAR(5) NOT NULL,"
		." `ip` VARCHAR(39) NOT NULL,"
		." `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
		." PRIMARY KEY (`id`)"
		." ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
		$urls->execute();
		$urls->store_result();
		$urls->free_result();
		$hits = $this->mysqli->prepare("CREATE TABLE IF NOT EXISTS `hits` ("
		." `key` VARCHAR(5) NOT NULL,"
		." `count` INT(10) NOT NULL,"
		." `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
		." PRIMARY KEY (`key`)"
		." ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
		$hits->execute();
		$hits->store_result();
		$hits->free_result();
	}

	private function set_domain($domain){
		if(!is_null($domain)){
			if(preg_match('/http[s]*:\/\//', $domain) < 1){
				$domain = 'http://'.$domain;
			}
			if(strrpos($domain, '/') !== (strlen($domain)-1)){
				$domain .= '/';
			}
		}
		$this->domain = $domain;
	}

	private function get_domain(){
		return $this->domain;
	}

	private function add_hit($key){
		$query = $this->mysqli->prepare("SELECT `count` FROM `hits` WHERE `key` = ?");
		$query->bind_param('s', $key);
		$query->execute();
		$query->store_result();
		$query->bind_result($count);
		if($query->fetch() === true){
			$query->free_result();
			$update = $this->mysqli->prepare("UPDATE `hits` SET `count` = `count`+1 WHERE `key` = ?");
			$update->bind_param('s', $key);
			$res = $update->execute();
		} else {
			$temp = 1;
			$insert = $this->mysqli->prepare("INSERT INTO `hits` (`key`, `count`) VALUES (?, ?)");
			$insert->bind_param('si', $key, $temp);
			$res = $insert->execute();
		}
		return $res;
	}

	private function url_key(){
		$key = substr(str_shuffle($this->salt), 0, 5);
		$query = $this->mysqli->prepare("SELECT `key` FROM `urls` WHERE `key` = ?");
		$query->bind_param('s', $key);
		$query->execute();
		$query->bind_result($res);
		return ($query->fetch() === true) ? $this->url_key() : $key;
	}

	private function check_url($url){
		$variations = array($url);
		if(preg_match('/http[s]*:\/\//', $url) > 0){
			$variations[] = preg_replace('/http[s]*:\/\//', "", $url);
		} else {
			$variations[] = "http://".$url;
			$variations[] = "https://".$url;
		}
		$variations[] = (strrpos($url, '/') !== (strlen($url)-1)) ? $url.'/' : rtrim($url, '/');
		foreach ($variations as $value) {
			$query = $this->mysqli->prepare("SELECT `key` FROM `urls` WHERE `url` = ?");
			$query->bind_param('s', $value);
			$query->execute();
			$query->store_result();
			$query->bind_result($key);
			if($query->fetch() === true){
				$query->free_result();
				return $key;
			}
		}
		return false;
	}
}

?>