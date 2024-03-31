<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    function has(): bool
    {
        return !empty($this->syncFields);
    }

    function hasNot(): bool
    {
        return empty($this->syncFields);
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
        return (bool) $this->syncFields;
    }

    function hasNot(): bool
    {
        return !$this->syncFields;
    }
}
?>
