<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Field;

final class SkipPartiallyQualifiedNew
{
    public function run(Field\TextField $textField): Source\Query\Parameter
    {
        new Field\TextField();

        return new Source\Query\Parameter();
    }
}
