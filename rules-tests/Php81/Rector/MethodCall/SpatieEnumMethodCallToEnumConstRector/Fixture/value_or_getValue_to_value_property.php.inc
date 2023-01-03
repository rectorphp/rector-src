<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class ValueOrGetValueToValueProperty
{
    public function run($value)
    {
        $archivedName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::archived()->getValue();
        $draftName = SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::draft()->value;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class ValueOrGetValueToValueProperty
{
    public function run($value)
    {
        $archivedName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::ARCHIVED->value;
        $draftName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::DRAFT->value;
    }
}

?>
