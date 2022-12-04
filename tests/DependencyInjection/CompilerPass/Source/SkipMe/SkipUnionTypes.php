<?php

declare(strict_types=1);

namespace Rector\Core\Tests\DependencyInjection\CompilerPass\Source\SkipMe;

use Rector\Core\Tests\DependencyInjection\CompilerPass\Source\Contract\FirstCollectedInterface;
use Rector\Core\Tests\DependencyInjection\CompilerPass\Source\Contract\SecondCollectedInterface;

final class SkipUnionTypes
{
    public function __construct(
        public FirstCollectedInterface|SecondCollectedInterface $collectedInterface
    ) {
    }
}
