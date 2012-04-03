<?php

/**
 * Mysql result set
 *
 * @date		2012.03.27
 * @author		Devin Smith <devin@cana.la>
 *
 */

class Cana_Db_MySQL_Result extends Cana_Db_Result {
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
}

