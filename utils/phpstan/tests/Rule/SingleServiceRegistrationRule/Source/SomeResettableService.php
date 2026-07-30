<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule\Source;

use Rector\Contract\DependencyInjection\ResettableInterface;

final class SomeResettableService implements ResettableInterface
{
    public function reset(): void
    {
    }
}
