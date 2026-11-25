<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

#[\Attribute('Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnClassNameInAttribute')]
final class OwnClassNameInAttribute
{
    public const NAME = 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnClassNameInAttribute';

    #[\Attribute('Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnClassNameInAttribute')]
    public function run($value = 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OwnClassNameInAttribute')
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

#[\Attribute(self::class)]
final class OwnClassNameInAttribute
{
    public const NAME = self::class;

    #[\Attribute(self::class)]
    public function run($value = self::class)
    {
    }
}

?>
