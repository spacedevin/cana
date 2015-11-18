<?php

class Controller_home extends Cana_Controller {
	public function init() {
		c::view()->display('home/index');
	}
}
