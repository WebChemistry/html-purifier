<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes\Attributes;

use DOMAttr;
use WebChemistry\Purifier\Rules\AttributeRuleAbstract;

class AttributeFactory implements IAttributeFactory {

	public function create(DOMAttr $attr, ?AttributeRuleAbstract $rule): AttributeAbstract {
		if ($attr->nodeName === 'style') {
			return new AttributeStyle($attr->nodeName, $attr->nodeValue, $rule);
		}

		return new Attribute($attr->nodeName, $attr->nodeValue, $rule);
	}

}
