<?php

/**
 * Mysql database connectivity
 *
 * @date		2009.09.18
 * @author		Devin Smith <devin@cana.la>
 *
 */

class Cana_Db_MySQL_Db extends mysqli {
	use Cana_Db_Base;

	public function __construct($host, $user, $pass = null, $db = null) {
		if (php_sapi_name() === 'cli') {
			ini_set('mysql.connect_timeout',10);
			ini_set('mysqli.reconnect',1);
		}
		parent::__construct($host, $user, $pass, $db);
	}

	/*
	public function select($db) {		
		$res = @mysql_select_db($db, $this->conn()) or $er = true;
		if (isset($er)) {
			throw new Exception('Unable to select the database');
		}
		return $res;	
	}
	*/

	public function query($query, $cache = true) {
		if (1==2 && $cache && Cana::config()->cache->mysql !== false && $cached = $this->cached($query)) {
			$result = $cached;
			$result->res()->data_seek(0);

		} else {
			if (Cana::config()->cache->mysql !== false) {
				$this->queries($query);
			}
			$ret = parent::query($query);
			if ($er = $this->error) {
				$errno = $this->errno;
				if (php_sapi_name() === 'cli' && $errno == 2006) {
					$this->ping();
					return $this->query($query, $cache);
				} else {
					throw new Cana_Exception_Query(array('message' => $er, 'query' => $query));
				}
			}
			$result = new Cana_Db_MySQL_Result($ret, $this);
			if (Cana::config()->cache->mysql !== false) {
				$this->queries($query, $result);
			}
		}
		return $result;
	}
	
	public function numRows($result) {
		return @mysql_num_rows($result);
	}
	
	public function fetch($result) {
		return $this->fetchObject($result);
	}
	
	public function fetchArray($result) {
		return @mysql_fetch_assoc($result);
	}
	
	public function fetchObject($result) {
		return @mysql_fetch_object($result);
	}
	
	public function escape($var) {
		return $this->real_escape_string($var);
	}
} 