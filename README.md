## Simple html purifier


Usage:

```php
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

$purifier->purify('
	<div>
		Lorem ipsum <strong class="italic h2">is simply</strong>
		
		<script>alert("dummy text!")</script>

		<span style="color: #FFF;font-weight: bold">white</span> and <span style="color: red">red</span>

		<br>
	</div>
');
```

output:

```html
<div>
	Lorem ipsum <strong class="italic">is simply</strong>
	
	<span style="color: #FFF;">white</span> and <span>red</span>
</div>
```
