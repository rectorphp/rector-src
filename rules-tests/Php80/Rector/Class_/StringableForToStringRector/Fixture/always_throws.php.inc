<?php

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

final class DemoFile implements \Stringable
{
    public function __toString(): string
    {
        throw new \Exception('Not implemented');
    }
}
?>
