<?php

/**
 * A markdown parser wrapper
 *
 * @author		Devin Smith <devin@cana.la>
 * @date		2010.06.03
 *
 */


// auto include the markdown parser
$md = new Cana_Markdown_PHPMarkdown;

class Cana_Markdown extends Cana_Model {

	public static function parse($text) {

		// Setup static parser variable.
		static $parser;
		if (!isset($parser)) {
			$parser_class = MARKDOWN_PARSER_CLASS;
			$parser = new $parser_class;
		}
	

		// Transform text using parser.
		$text = $parser->transform($text);
//		$text = str_replace('<code>','<pre class="prettyprint linenums">',trim($text));
		$text = str_replace("\n</code>",'</code>',trim($text));
		$text = str_replace('<pre>','<pre class="prettyprint linenums">',trim($text));

		return $text;
	}

}