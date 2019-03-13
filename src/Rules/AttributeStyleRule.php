<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Rules;

class AttributeStyleRule extends AttributeRuleAbstract {

	/** @var mixed[] */
	protected $styles = [];

	public function addRule(string $key, array $values = []) {
		$this->styles[strtolower($key)] = array_merge($this->styles[$key] ?? [], $values);

		return $this;
	}

	public function isStyleAllowed(string $key, string $value): bool {
		if (!isset($this->styles[$key])) {
			return false;
		}

		if (!$this->styles[$key]) {
			return true;
		}

		foreach ($this->styles[$key] as $allowed) {
			if ($this->equal($value, $allowed)) {
				return true;
			}
		}

		return false;
	}

	protected function equal(string $op, string $op1): bool {
		$op = trim($op); $op1 = trim($op1);

		return strcasecmp($op, $op1) === 0;
	}

	public static function create(string $name = 'style') {
		return parent::create($name);
	}

}
