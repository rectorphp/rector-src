<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    public function getArray()
    {
        return [
            'yes' => !empty($this->syncFields),
            'not' => empty($this->syncFields),
            !empty($this->syncFields) => 'a',
            empty($this->syncFields) => 'b',
        ];
    }
}
?>
-----
<?php
namespace Rector\Tests\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRectorTest\Fixture;

class SomeClass
{
    public array $syncFields;

    public function getArray()
    {
        return [
            'yes' => (bool) $this->syncFields,
            'not' => !$this->syncFields,
            (bool) $this->syncFields => 'a',
            !$this->syncFields => 'b',
        ];
    }
}
?>
