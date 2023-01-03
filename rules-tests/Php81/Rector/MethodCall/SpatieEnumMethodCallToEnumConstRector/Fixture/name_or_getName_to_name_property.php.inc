<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class NameOrGetNameToNameProperty
{
    public function run($value)
    {
        $archivedName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::archived()->getName();
        $draftName = SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::draft()->name;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class NameOrGetNameToNameProperty
{
    public function run($value)
    {
        $archivedName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::ARCHIVED->name;
        $draftName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::DRAFT->name;
    }
}

?>
