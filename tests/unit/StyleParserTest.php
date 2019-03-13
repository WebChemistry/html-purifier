<?php

use WebChemistry\Purifier\Parsers\StyleParser;

class StyleParserTest extends \Codeception\Test\Unit {

	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	// tests
	public function testBasic() {
		$expects = [
			'color' => 'red',
		];

		$this->assertSame($expects, StyleParser::parse('color: red'));
		$this->assertSame($expects, StyleParser::parse('color: red '));
		$this->assertSame($expects, StyleParser::parse('color: red;'));
		$this->assertSame($expects, StyleParser::parse('color: red ;'));
		$this->assertSame($expects, StyleParser::parse('  color : red ;'));
	}

	public function testMulti(): void {
		$expects = [
			'color' => '#fff',
			'font-weight' => 'bold',
		];
		$this->assertSame($expects, StyleParser::parse('color: #fff; font-weight: bold'));
		$this->assertSame($expects, StyleParser::parse('color: #fff ;;; font-weight: bold;;'));
	}

	public function testImportant(): void {
		$expects = [
			'color' => '#fff !important',
			'font-weight' => 'bold',
		];
		$this->assertSame($expects, StyleParser::parse('color: #fff !important; font-weight: bold'));
	}
}