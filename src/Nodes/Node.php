<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes;

abstract class Node {

	/** @var Node[] */
	protected $children = [];

	public function addChild(Node $node) {
		$this->children[] = $node;
	}

	abstract protected function toString(?string $inside = null): string;

	public function __toString() {
		$inside = null;
		foreach ($this->children as $child) {
			$inside .= (string) $child;
		}

		return $this->toString($inside);
	}

}
