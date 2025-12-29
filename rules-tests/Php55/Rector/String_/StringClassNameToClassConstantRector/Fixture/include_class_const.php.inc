<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class IncludeClassConst
{
    const SKIP_TYPES = [
        'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\SomeUser'
    ];
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class IncludeClassConst
{
    const SKIP_TYPES = [
        \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\SomeUser::class
    ];
}

?>
