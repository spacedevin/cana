<?php

/**
 * SQLite3 result set
 *
 * @date		2012.04.03
 * @author		Devin Smith <devin@cana.la>
 *
 */

class Cana_Db_SQLite3_Result extends Cana_Db_Result {
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

	public function fetch($class = null, $params = []) {
		if (!is_object($this->res())) {
			// honestly i dont know how u would get here but it hapens in my old code (lots...)
			debug_print_backtrace();
			exit;
		}

		if ($class) {
			return new $class($this->res()->fetchArray(SQLITE3_ASSOC));
		} else {
			$i = $this->res()->fetchArray(SQLITE3_ASSOC);
			return $i ? (object)$i : false;
		}
	}
}