<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Rules;

class AttributeRule extends AttributeRuleAbstract {

	/** @var string[] */
	private $allowedValues = [];

	public function allowValue(string $value) {
		$this->allowedValues[] = $value;

		return $this;
	}

	public function isValueAllowed(string $value): bool {
		if (!$this->allowed) {
			return false;
		}
		if (!$this->allowedValues) {
			return true;
		}

		return in_array($value, $this->allowedValues);
	}

	public function allowValues(array $values) {
		foreach ($values as $value) {
			$this->allowValue($value);
		}

		return $this;
	}

}
