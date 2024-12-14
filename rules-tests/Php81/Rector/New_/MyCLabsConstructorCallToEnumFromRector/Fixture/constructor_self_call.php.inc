<?php

namespace Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture;

use MyCLabs\Enum\Enum;

class EnumWithSelfCreation extends Enum
{
    private const VALUE = 'value';

    public static function newSelf(): self
    {
        return new self('value');
    }

    public static function newStatic(): static
    {
        return new static('value');
    }
}
?>
-----
<?php

namespace Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture;

use MyCLabs\Enum\Enum;

class EnumWithSelfCreation extends Enum
{
    private const VALUE = 'value';

    public static function newSelf(): self
    {
        return \Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture\EnumWithSelfCreation::from('value');
    }

    public static function newStatic(): static
    {
        return \Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture\EnumWithSelfCreation::from('value');
    }
}
?>
