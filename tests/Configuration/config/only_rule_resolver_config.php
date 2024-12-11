<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\Tests\Configuration\Source\RemoveDoubleAssignRector as RemoveDoubleAssignRectorTest;

return RectorConfig::configure()
    ->withRules(
        [RemoveDoubleAssignRector::class, RemoveDoubleAssignRectorTest::class, RemoveUnusedPrivateMethodRector::class]
    );
