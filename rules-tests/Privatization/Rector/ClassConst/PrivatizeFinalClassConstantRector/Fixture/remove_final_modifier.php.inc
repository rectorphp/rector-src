<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

final class RemoveFinalModifier
{
    final protected const VALUE = 'value';
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

final class RemoveFinalModifier
{
    private const VALUE = 'value';
}

?>
