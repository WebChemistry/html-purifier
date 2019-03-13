<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes\Attributes;

use WebChemistry\Purifier\Parsers\StyleParser;
use WebChemistry\Purifier\Rules\AttributeStyleRule;

/**
 * @property-read AttributeStyleRule $rule
 */
class AttributeStyle extends AttributeAbstract {

	/** @var string[] */
	protected $values = [];

	public function __construct(string $name, string $value, AttributeStyleRule $rule) {
		parent::__construct($name, $value, $rule);
	}

	protected function setup(): void {
		if (!$this->rule || !$this->rule->allowed) {
			return;
		}

		$values = StyleParser::parse($this->value);

		foreach ($values as $key => $value) {
			$this->values[strtolower($key)] = $value;
		}

		$this->values = array_filter($this->values, function (string $value, string $key): bool {
			return $this->rule->isStyleAllowed($key, $value);
		}, ARRAY_FILTER_USE_BOTH);
	}

	public function getValue(): string {
		if (!$this->values) {
			return '';
		}

		$attr = '';
		foreach ($this->values as $key => $value) {
			$attr = "$key: $value;";
		}

		return $attr;
	}

}
