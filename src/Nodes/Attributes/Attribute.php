<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes\Attributes;

use WebChemistry\Purifier\Rules\AttributeRule;

/**
 * @property-read AttributeRule $rule
 */
class Attribute extends AttributeAbstract {

	/** @var string[] */
	private $values = [];

	protected function setup(): void {
		if (!$this->rule || !$this->rule->allowed) {
			return;
		}
		$this->values = array_filter(explode(' ', $this->value));

		$this->values = array_filter($this->values, function (string $value): bool {
			return $this->rule->isValueAllowed($value);
		});
	}

	public function getValue(): string {
		if (!$this->values) {
			return '';
		}

		return implode(' ', $this->values);
	}

}
