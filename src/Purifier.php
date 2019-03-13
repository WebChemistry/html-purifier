<?php declare(strict_types = 1);

namespace WebChemistry\Purifier;

use DOMAttr;
use DOMCharacterData;
use DOMDocument;
use DOMNode;
use WebChemistry\Purifier\Nodes\Attributes\AttributeFactory;
use WebChemistry\Purifier\Nodes\Attributes\IAttributeFactory;
use WebChemistry\Purifier\Nodes\DocumentNode;
use WebChemistry\Purifier\Nodes\ElementNode;
use WebChemistry\Purifier\Nodes\Node;
use WebChemistry\Purifier\Nodes\TextNode;
use WebChemistry\Purifier\Rules\ElementRule;
use WebChemistry\Purifier\Rules\Rules;

class Purifier {

	private const OPTIONS = LIBXML_NOBLANKS | LIBXML_NOERROR | LIBXML_NOCDATA | LIBXML_HTML_NOIMPLIED | LIBXML_NOENT | LIBXML_HTML_NODEFDTD | LIBXML_COMPACT;

	/** @var Rules */
	protected $rules;

	/** @var IAttributeFactory */
	protected $attributeFactory;

	public function __construct(Rules $rules) {
		$this->rules = $rules;
		$this->attributeFactory = new AttributeFactory();
	}

	public function setAttributeFactory(IAttributeFactory $attributeFactory): void {
		$this->attributeFactory = $attributeFactory;
	}

	public function purify(string $content): string {
		$content = '<div>' . $content . '</div>';

		$dom = new DOMDocument();
		$dom->encoding = 'UTF-8';
		$dom->loadHTML($content, self::OPTIONS);

		$this->processNode($dom->childNodes[0], $domNode = new DocumentNode());


		return (string) $domNode;
	}

	private function processNode(DOMNode $dom, Node $node): void {
		if (!$dom->childNodes) {
			return;
		}
		/** @var DOMNode $child */
		foreach ($dom->childNodes as $child) {
			$newNode = $this->createNode($child);
			if (!$newNode) {
				continue;
			}

			$node->addChild($newNode);
			$this->processNode($child, $newNode);
		}
	}

	protected function getAttributes(DOMNode $node, ElementRule $rule): array {
		if (!$node->hasAttributes()) {
			return [];
		}

		$attrs = [];
		/** @var DOMAttr $attribute */
		foreach ($node->attributes as $attribute) {
			$attrs[] = $this->attributeFactory->create($attribute, $rule->getAttributeRule($attribute->nodeName));
		}

		return $attrs;
	}

	private function createNode(DOMNode $dom): ?Node {
		if ($dom instanceof DOMCharacterData) {
			return new TextNode($dom->nodeValue);
		}

		$rule = $this->rules->get($dom->nodeName) ?? ElementRule::create($dom->nodeName)->disallow();
		switch ($dom->nodeType) {
			case XML_ELEMENT_NODE:
				return new ElementNode(
					$dom->nodeName,
					$this->getAttributes($dom, $rule),
					$rule
				);
			default:
				throw new \Exception();
		}
	}

}
