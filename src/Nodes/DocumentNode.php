<?php declare(strict_types = 1);

namespace WebChemistry\Purifier\Nodes;

class DocumentNode extends Node {

	protected function toString(?string $inside = null): string {
		return $inside;
	}

}
