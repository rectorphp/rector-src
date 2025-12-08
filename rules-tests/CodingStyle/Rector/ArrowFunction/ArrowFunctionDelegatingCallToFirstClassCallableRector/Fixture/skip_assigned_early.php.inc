<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final readonly class SkipAssignedEarly {
    public function __construct(private string $name, private object $object) {}
    public function render(callable $formatter): string
    {
        return $formatter($this->name, $this->object);
    }
}

// callback assigned early, use later, keep as is
$formatter = fn(string $label, object $object): string => ucfirst($label);
echo (new SkipAssignedEarly('name', new \stdClass()))->render($formatter);
