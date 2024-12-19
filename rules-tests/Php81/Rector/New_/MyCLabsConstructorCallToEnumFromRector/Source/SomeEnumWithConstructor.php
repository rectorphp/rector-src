<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Source;

use MyCLabs\Enum\Enum;

/**
 * @method SomeEnumWithConstructor VALUE()
 */
final class SomeEnumWithConstructor extends Enum
{
    const VALUE = 'value';

    public function __construct($value)
    {
        //some logic
        parent::__construct($value);
    }
}
