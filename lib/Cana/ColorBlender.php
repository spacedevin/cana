<?php

/**
 * Class to blend a list of colors together
 * 
 * @author		Devin Smith <devin@cana.la>
 * @date		2009.09.17
 *
 */


class Cana_ColorBlender extends Cana_Model {
	
	public function __construct($items = 0, $colors = null) {
		if (!$colors) {
			$this->cellColors = ['FFFFCF', 'E6FFB3', 'CCFFCC', 'B3E6E6', '99CCFF', 'CCCCFF', 'FFCCFF', 'FFE6CC'];
		}
		$this->colorsUsed = $items;
		$this->generateColors();
	}
	
	public function color($index) {
		return $this->cellRowPallet[$index];
	}

	public function generateColors() {
		$toGenerate = ceil(($this->colorsUsed + 1) / count($this->cellColors));
		for ($x = 0; $x < count($this->cellColors); $x++) {
			if (!$this->cellColors[$x+1]) {
				$end = $this->cellColors[0];
			} else {
				$end = $this->cellColors[$x+1];
			}

			$pallete = $this->mixPalette($this->cellColors[$x],$end,$toGenerate);

			if (!$palletes) {
				$palletes = $pallete;

			} elseif ($x == count($this->cellColors)-1) {
				array_shift($pallete);
				array_pop($pallete);
				foreach($pallete as $color) {
					$palletes[] = $color;
				}

			} else {
				array_shift($pallete);
				foreach($pallete as $color) {
					$palletes[] = $color;
				}
			}
		}
	
		foreach ($palletes as $color) {
			$this->cellRowPallet[] = $color->hex;
		}
	}
	
	
	public function mixPalette($startColor, $endColor, $steps=5) {
		$startColor = new Cana_ColorBlender_Color(0,0,0,$startColor);
		$endColor = new Cana_ColorBlender_Color(0,0,0,$endColor);
	
		$palette[0] = new Cana_ColorBlender_Color($startColor->r,$startColor->g,$startColor->b);
		$palette[$steps] = new Cana_ColorBlender_Color($endColor->r,$endColor->g,$endColor->b);

		for ($i = 1; $i < $steps; $i++) {
			$r = ($startColor->r + ((($endColor->r - $startColor->r) / ($steps)) * $i));
			$g = ($startColor->g + ((($endColor->g - $startColor->g) / ($steps)) * $i));
			$b = ($startColor->b + ((($endColor->b - $startColor->b) / ($steps)) * $i));
			$palette[$i] = new Cana_ColorBlender_Color($r,$g,$b);
		}

		ksort($palette);
		return $palette;
	}

}