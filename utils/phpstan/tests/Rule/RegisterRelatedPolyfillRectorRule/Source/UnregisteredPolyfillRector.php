<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\RegisterRelatedPolyfillRectorRule\Source;

use Rector\ValueObject\PolyfillPackage;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;

final class UnregisteredPolyfillRector implements RelatedPolyfillInterface
{
    public function providePolyfillPackage(): string
    {
        return PolyfillPackage::PHP_80;
    }
}
