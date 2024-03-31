<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    public function hasThing(string $thing): bool
    {
        return !empty($this->syncFields[$thing]);
    }
}
