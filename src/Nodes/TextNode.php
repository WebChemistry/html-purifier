<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes;

final class TextNode extends Node {

	/** @var string */
	protected $content;

	public function __construct(string $content) {
		$this->content = $content;
	}

	public function toString(?string $inside = null): string {
		return $this->content;
	}

}
