<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    public function doConditional(array $stuff): array
    {
        if (empty($this->syncFields) || !empty($stuff)) {
            return $stuff;
        }

        return [];
    }
}
?>
-----
<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    public function doConditional(array $stuff): array
    {
        if (!$this->syncFields || $stuff) {
            return $stuff;
        }

        return [];
    }
}
?>
