<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Parsers;

class StyleParser {

	public static function parse(string $code): ?array {
		if (preg_match_all('#\s*(.+?)\s*:\s*(.+?)\s*(?:;+|$)\s*#m', $code, $matches)) {
			return array_combine($matches[1], $matches[2]);
		}

		return null;
	}

}
