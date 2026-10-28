<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Field;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Field\TextField;

final class SkipPartiallyQualifiedAlreadyImportedClass
{
    public function run(): TextField
    {
        return new Field\TextField();
    }
}
