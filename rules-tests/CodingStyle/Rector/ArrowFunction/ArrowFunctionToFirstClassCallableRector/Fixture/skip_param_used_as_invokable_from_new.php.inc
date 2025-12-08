<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

final readonly class SkipParamUsedAsInvokableFromNew {
    public function __construct(
        private string $name,
        private object $object,
        callable $formatter
    ) {
        $formatter($this->name, $this->object);
    }
}

$obj = new SkipParamUsedAsInvokableFromNew('name', new \stdClass(), fn(string $label, object $object): string => ucfirst($label));
