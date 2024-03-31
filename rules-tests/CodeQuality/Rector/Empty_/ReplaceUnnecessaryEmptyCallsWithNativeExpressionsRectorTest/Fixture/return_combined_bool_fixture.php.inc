<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    function has(): bool
    {
        return !empty($this->syncFields) || rand(0, 1);
    }
}
?>
-----
<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    function has(): bool
    {
        return $this->syncFields || rand(0, 1);
    }
}
?>
