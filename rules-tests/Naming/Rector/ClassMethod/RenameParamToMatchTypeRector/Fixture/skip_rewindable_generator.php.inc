<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

final class SkipRewindableGenerator
{
    public function __construct(
        RewindableGenerator $items
    ) {
    }
}
