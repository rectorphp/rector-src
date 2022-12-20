<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class FullyQualifiedClassName
{
    public function run($value)
    {
        $archived = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::archived();
        $published = SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::published();

        $archivedValue = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::archived()->getValue();
        $archivedName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::archived()->getName();

        $draftValue = SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::draft()->value;
        $draftName = SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::draft()->name;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Fixture;

use \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

final class FullyQualifiedClassName
{
    public function run($value)
    {
        $archived = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::ARCHIVED;
        $published = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::PUBLISHED;

        $archivedValue = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::ARCHIVED->value;
        $archivedName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::ARCHIVED->name;

        $draftValue = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::DRAFT->value;
        $draftName = \Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source\StatusEnum::DRAFT->name;
    }
}

?>
