<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector\Source;

use Spatie\Enum\Enum;

/**
 * @method static self draft()
 * @method static self published()
 * @method static self archived()
 */
class StatusEnum extends Enum
{
}
