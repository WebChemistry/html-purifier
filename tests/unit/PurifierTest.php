<?php

use Codeception\Test\Unit;
use WebChemistry\Purifier\Purifier;
use WebChemistry\Purifier\Rules\AttributeRule;
use WebChemistry\Purifier\Rules\AttributeStyleRule;
use WebChemistry\Purifier\Rules\ElementRule;
use WebChemistry\Purifier\Rules\Rules;

class PurifierTest extends Unit {


	protected function _before() {
	}

	protected function _after() {
	}

	public function testAttributes() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('p')
				->addAttributeRuleMulti(
					AttributeRule::create('class')
						->allowValue('foo')
				)
		);
		$this->assertSame('<p class="foo">Bar</p>', (new Purifier($rules))->purify('<p class="foo">Bar</p>'));
	}

	public function testClassAttributes() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('p')
				->addAttributeRuleMulti(
					AttributeRule::create('class')
						->allowValue('foo')
				)
		);
		$this->assertSame('<p class="foo">Bar</p>', (new Purifier($rules))->purify('<p class=" bar    ssss  foo">Bar</p>'));
	}

	public function testNotAllowed() {
		$this->assertSame('Foo', (new Purifier(new Rules()))->purify('<p>Foo</p>'));
	}

	public function testNestedNotAllowed() {
		$this->assertSame('Name: bar foo', (new Purifier(new Rules()))->purify('<div>Name: <strong>bar <i>foo</i></strong></div>'));
	}

	public function testNestedDiscard() {
		$rules = new Rules();
		$rules->add(
			ElementRule::create('i')
				->discard()
		);
		$this->assertSame('Name: bar ', (new Purifier($rules))->purify('<div>Name: <strong>bar <i>foo</i></strong></div>'));
	}

	public function testNestedAllowed() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('i'),
			ElementRule::create('div'),
			ElementRule::create('strong'),
		);
		$this->assertSame('<div>Name: <strong>bar <i>foo</i></strong></div>', (new Purifier($rules))->purify('<div>Name: <strong>bar <i>foo</i></strong></div>'));
	}

	public function testNestedAttributes() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('i'),
			ElementRule::create('div'),
			ElementRule::create('strong')
				->addAttributeRule(
					AttributeRule::create('class')
				)
		);
		$this->assertSame('<div>Name: <strong class="nested">bar <i>foo</i></strong></div>', (new Purifier($rules))->purify('<div class="bar">Name: <strong class="nested">bar <i>foo</i></strong></div>'));
	}

	public function testNestedAttributes2() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('i'),
			ElementRule::create('div'),
			ElementRule::create('strong')
				->addAttributeRule(
					AttributeRule::create('class')
						->allowValue('nested2')
				)
		);
		$this->assertSame('<div>Name: <strong>bar <i>foo</i></strong></div>', (new Purifier($rules))->purify('<div class="bar">Name: <strong class="nested">bar <i>foo</i></strong></div>'));
	}

	public function testDiscard() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('script')->discard()
		);
		$this->assertSame('', (new Purifier($rules))->purify('<script>call();</script>'));
	}

	public function testStyle() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('p')
				->addAttributeRule(
					AttributeStyleRule::create()
						->addRule('color', ['red'])
				)
		);

		$this->assertSame('<p style="color: red;">foo</p>', (new Purifier($rules))->purify('<p style="color: red">foo</p>'));
	}

	public function testNoAllowedStyleValue() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('p')
				->addAttributeRule(
					AttributeStyleRule::create()
						->addRule('color', ['blue'])
				)
		);

		$this->assertSame('<p>foo</p>', (new Purifier($rules))->purify('<p style="color: red">foo</p>'));
	}

	public function testCaseInsensitiveStyle() {
		$rules = new Rules();
		$rules->addMulti(
			ElementRule::create('p')
				->addAttributeRule(
					AttributeStyleRule::create()
						->addRule('color', ['#fff'])
				)
		);

		$this->assertSame('<p style="color: #FFF;">foo</p>', (new Purifier($rules))->purify('<p style="CoLoR: #FFF">foo</p>'));
	}

	public function testPurifier() {
		$rules = new Rules();

		$rules->addMulti(
			ElementRule::create('i'),
			ElementRule::create('div'),
			ElementRule::create('strong')
				->addAttributeRule(
					AttributeRule::create('class')
						->allowValue('italic')
				),
			ElementRule::create('script')
				->discard(),
			ElementRule::create('span')
				->addAttributeRule(
					AttributeStyleRule::create()
						->addRule('color', ['white', '#fff', '#fffff'])
				)
		);

		$purifier = new Purifier($rules);

		$html = $purifier->purify('
<div>
Lorem ipsum <strong class="italic h2">is simply</strong>	
<script>alert("dummy text!")</script>
<span style="color: #FFF;font-weight: bold">white</span> and <span style="color: red">red</span>
<br>
</div>');

		$this->assertSame('
<div>
Lorem ipsum <strong class="italic">is simply</strong>	
<span style="color: #FFF;">white</span> and <span>red</span>
</div>', $html);
	}

}