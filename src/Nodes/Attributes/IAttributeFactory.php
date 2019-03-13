<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes\Attributes;

use DOMAttr;
use WebChemistry\Purifier\Rules\AttributeRuleAbstract;

interface IAttributeFactory {

	public function create(DOMAttr $attr, ?AttributeRuleAbstract $rule): AttributeAbstract;

}
