<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Field;

final class SkipPartiallyQualifiedDocblock
{
    /**
     * @var Field\TextField
     */
    public $textField;

    /**
     * @var Source\Query\Parameter
     */
    public $parameter;
}
