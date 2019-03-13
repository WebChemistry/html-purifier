<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes\Attributes;

use WebChemistry\Purifier\Rules\AttributeRuleAbstract;

abstract class AttributeAbstract {

	/** @var string */
	protected $name;

	/** @var AttributeRuleAbstract|null */
	protected $rule;

	/** @var string */
	protected $value;

	public function __construct(string $name, string $value, ?AttributeRuleAbstract $rule) {
		$this->name = $name;
		$this->rule = $rule;
		$this->value = $value;

		$this->setup();
	}

	abstract protected function setup(): void;

	public function getName(): string {
		return $this->name;
	}

	abstract public function getValue(): string;

}
