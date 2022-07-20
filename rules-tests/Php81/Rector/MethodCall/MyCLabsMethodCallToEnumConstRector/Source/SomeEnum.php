<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source;

use MyCLabs\Enum\Enum;

/**
 * @method SomeEnum USED_TO_BE_CONST()
 */
final class SomeEnum extends Enum
{
    const USED_TO_BE_CONST = 'value';
}
