<?php declare (strict_types = 1);

namespace Tests;

use Contributte\FormsBootstrap\BootstrapUtils;
use Nette\Utils\Html;
use PHPUnit\Framework\TestCase;

class BootstrapUtilsTest extends TestCase
{

	public function testStandardizeClass()
	{
		$html = Html::el('div', ['class' => 'c1 c2']);
		BootstrapUtils::standardizeClass($html);
		$this->assertEquals(['c1', 'c2'], $html->class);
	}
}
