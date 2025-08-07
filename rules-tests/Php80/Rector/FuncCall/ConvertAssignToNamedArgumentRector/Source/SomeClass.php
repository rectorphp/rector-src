<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Source;

class SomeClass
{
    public static function staticMethod(string $a, string $b): void {}
    public function process(string $data, array $options = []): void {}
}

