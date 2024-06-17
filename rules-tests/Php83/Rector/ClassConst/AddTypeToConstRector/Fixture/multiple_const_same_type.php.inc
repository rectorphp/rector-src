<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class MultipleConstSameType
{
    public const TYPE = 'some_type', OTHER_TYPE = 'some_other_type';
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class MultipleConstSameType
{
    public const string TYPE = 'some_type', OTHER_TYPE = 'some_other_type';
}

?>