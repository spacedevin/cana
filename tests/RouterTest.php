<?php

class RouterTest extends Cana_Test {

	public function setUp() {
		$this->useOb = true; // for debug use
	}

	public function testRouterHome() {
		$_REQUEST['__url'] = 'home';
		$this->ob();

		Cana::app()->buildPages();
		Cana::app()->displayPage();

		$check = $this->ob(false);
		$this->assertEquals("LAYOUT\nHOME\n", $check);
	}

	public function testRouterNotHomeFile() {
		$_REQUEST['__url'] = 'nothomefile';
		$this->ob();

		Cana::app()->buildPages();
		Cana::app()->displayPage();

		$check = $this->ob(false);
		$this->assertEquals('NOTHOME-FILE', $check);
	}

	public function testRouterNotHomeFolder() {
		$_REQUEST['__url'] = 'nothomefolder';
		$this->ob();

		Cana::app()->buildPages();
		Cana::app()->displayPage();

		$check = $this->ob(false);
		$this->assertEquals('NOTHOME-FOLDER', $check);
	}

}
