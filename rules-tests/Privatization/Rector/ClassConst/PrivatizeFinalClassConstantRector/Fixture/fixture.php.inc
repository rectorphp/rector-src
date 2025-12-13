<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

final class Fixture
{
    protected const SOME_CONSTANT = 'some-value';
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

final class Fixture
{
    private const SOME_CONSTANT = 'some-value';
}

?>
