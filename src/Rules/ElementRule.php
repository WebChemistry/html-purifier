<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Rules;

class ElementRule {

	/** @var string */
	private $element;

	/** @var bool */
	public $allowed = true;

	/** @var bool */
	public $discard = false;

	/** @var AttributeRule[] */
	protected $attributeRules = [];

	public function __construct(string $element) {
		$this->element = $element;
	}

	public function disallow() {
		$this->allowed = false;

		return $this;
	}

	public function allow() {
		$this->allowed = true;
		$this->discard = false;

		return $this;
	}

	public function discard() {
		$this->discard = true;
		$this->allowed = false;

		return $this;
	}

	public function getElement(): string {
		return $this->element;
	}

	public function addAttributeRule(AttributeRuleAbstract $rule) {
		$this->attributeRules[$rule->getName()] = $rule;

		return $this;
	}

	public function addAttributeRuleMulti(...$rules) {
		foreach ($rules as $rule) {
			$this->addAttributeRule($rule);
		}

		return $this;
	}

	public function getAttributeRule(string $name): ?AttributeRuleAbstract {
		return $this->attributeRules[$name] ?? null;
	}

	public static function create(string $element): self {
		return new self($element);
	}

}
