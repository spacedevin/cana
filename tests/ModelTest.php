<?php

class ModelTest extends Cana_Test {

	public function setUp() {
		$this->useOb = true; // for debug use
	}

	public function testModelBasic() {
		$_REQUEST['__url'] = 'home';
		$this->ob();

		$test = new App_Test;

		$this->assertTrue($test->test());
	}

	public function testModelShort() {
		$_REQUEST['__url'] = 'home';
		$this->ob();

		$test = new Test;

		$this->assertTrue($test->test());
	}
}
