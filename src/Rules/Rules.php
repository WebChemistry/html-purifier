<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Rules;

class Rules {

	/** @var ElementRule[] */
	protected $elementRules = [];

	public function add(ElementRule $rule) {
		$this->elementRules[$rule->getElement()] = $rule;

		return $this;
	}

	public function addMulti(...$rules) {
		foreach ($rules as $rule) {
			$this->add($rule);
		}

		return $this;
	}

	public function get(string $element): ?ElementRule {
		return $this->elementRules[$element] ?? null;
	}

}
