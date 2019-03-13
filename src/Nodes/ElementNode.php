<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes;

use WebChemistry\Purifier\Nodes\Attributes\Attribute;
use WebChemistry\Purifier\Rules\ElementRule;

class ElementNode extends Node {

	/** @var string[] */
	protected static $selfClosingTags = [
		'area',
		'base',
		'br',
		'col',
		'command',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',
	];

	/** @var string */
	protected $name;

	/** @var ElementRule */
	private $rule;

	/** @var Attribute[] */
	private $attributes;

	public function __construct(string $name, array $attributes, ElementRule $rule) {
		$this->name = $name;
		$this->rule = $rule;
		$this->attributes = $attributes;
	}

	protected function createAttributes(): string {
		$attributes = '';
		foreach ($this->attributes as $attribute) {
			$string = $attribute->getValue();
			if ($string) {
				$attributes .= $attribute->getName() . "=\"$string\" ";
			}
		}

		if ($attributes) {
			$attributes = substr($attributes, 0, -1);
		}

		return $attributes;
	}

	public function toString(?string $inside = null): string {
		if ($this->rule->discard) {
			return '';
		}
		if (!$this->rule->allowed) {
			return (string) $inside;
		}
		$attrs = $this->createAttributes();
		$attrs = $attrs ? ' ' . $attrs : $attrs;
		if (in_array($this->name, self::$selfClosingTags)) {
			return "<{$this->name}{$attrs}>";
		}
		return "<{$this->name}{$attrs}>$inside</{$this->name}>";
	}

}
