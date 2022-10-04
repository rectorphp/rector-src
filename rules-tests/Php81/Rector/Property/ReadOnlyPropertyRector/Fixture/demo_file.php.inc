<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class DemoFile
{
    public function __construct(private string $value)
    {
		$this->value = trim($value);
    }
}

?>
