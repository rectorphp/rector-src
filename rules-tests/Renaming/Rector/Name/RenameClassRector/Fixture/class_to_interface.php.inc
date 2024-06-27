<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\InterfaceAndClass\SomeBasicDateTime;

class ClassToInterface extends SomeBasicDateTime
{
    public function run(\Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\InterfaceAndClass\SomeBasicDateTimeInterface $dateTime): \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\InterfaceAndClass\SomeBasicDateTimeInterface
    {
        $oldClassWithTypo = new SomeBasicDateTime;
    }
}
