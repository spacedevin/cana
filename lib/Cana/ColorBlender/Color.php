<?php

/**
 * Color object for color blender
 * 
 * @author		Devin Smith <devin@cana.la>
 * @date		2009.09.17
 *
 */


class Cana_ColorBlender_Color extends Cana_Model {
	var $r;
	var $g;
	var $b;
	var $hex;

	public function __construct($r,$g,$b,$hex='') {
		if ($hex) {
			$this->r = hexdec(substr($hex,0,2));
			$this->g = hexdec(substr($hex,2,2));
			$this->b = hexdec(substr($hex,4,2));
		} else {
			$this->r = sprintf("%03d", $r);
			$this->g = sprintf("%03d", $g);
			$this->b = sprintf("%03d", $b);
		}
		$this->hex = dechex($this->r).dechex($this->g).dechex($this->b);
	}
}
