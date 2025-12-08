<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final readonly class SkipParamUsedAsInvokable {
    public function __construct(private string $name, private object $object) {}
    public function render(callable $formatter): string
    {
        return $formatter($this->name, $this->object);
    }
}

$obj = new SkipParamUsedAsInvokable('name', new \stdClass());
$obj->render(fn(string $label, object $object): string => ucfirst($label));
