<?php
/**
 * Db result set
 *
 * @date		2012.03.27
 * @author		Devin Smith <devin@cana.la>
 *
 */

class Cana_Db_Result extends Cana_Iterator {

	private $_db;
	private $_res;

	public function __construct($res, $db) {
		$this->_res = $res;
		$this->_db = $db;
	}

	// do not pass this the mysql result!
	public function fetch($class = null, $params = []) {
		if (!is_object($this->res())) {
			// honestly i dont know how u would get here but it hapens in my old code (lots...)
			debug_print_backtrace();
			exit;
		}
		if ($class) {
			return $this->res()->fetch_object($class, $params);

		} else {
			return $this->res()->fetch_object();
		}
	}
	
	public function db() {
		return $this->_db;
	}
	
	public function numRows() {
		return $this->db()->affected_rows;
		/*
		if (!isset($this->_num_rows)) {
			$this->_num_rows = $this->db()->affected_rows;
			// $num = count($this->fetch_all());
			// $this->data_seek(0);
		}
		return $this->_num_rows;
		*/
	}

	public function res() {
		return $this->_res;
	}
	
	public function __call($name, $arguments) {
		if (method_exists($this->res(),$name)) {
			return (new ReflectionMethod($this->res(), $name))->invokeArgs($this->res(), $arguments);
		} else {
			return (new ReflectionMethod(parent, $name))->invokeArgs(parent, $arguments);
		}
	}

	public function &__get($name) {
		echo $name;
		if (property_exists($this->res(),$name)) {
			return $this->res()->{$name};
		} else {
			return parent::__get($name);
		}
	}
}