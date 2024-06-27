<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\InterfaceAndClass\SomeBasicDateTime;

final class DoNotReplaceInterfaceCall
{
    public function run($dateTime)
    {
        if (! $dateTime instanceof \Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\InterfaceAndClass\SomeBasicDateTimeInterface) {
            $dateTime = SomeBasicDateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        }

        return $dateTime;
    }
}
