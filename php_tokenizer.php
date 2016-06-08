<?php
/**
 * tokenizer usage
 */
$src = file_get_contents('timeout_handle_use_pcntl.php');

$tokens = token_get_all($src);

$text = '';

foreach ($tokens as $token) {
	if (is_string($token)) {
		$text .= $token;	
	} else {
		list($id, $con) = $token;

		switch ($id) {
			case T_COMMENT:
			case T_ML_COMMENT:
			case T_DOC_COMMENT:
			//case T_WHITESPACE:
				break;
			default:
				$text .= $con;
				break;
		}
	}
}

echo $text;
