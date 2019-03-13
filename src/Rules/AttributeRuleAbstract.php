<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Rules;

abstract class AttributeRuleAbstract {

	/** @var string */
	protected $name;

	/** @var bool */
	public $allowed = true;

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function disallow() {
		$this->allowed = false;

		return $this;
	}

	public function allow() {
		$this->allowed = true;

		return $this;
	}

	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return static
	 */
	public static function create(string $name) {
		return new static($name);
	}

}
