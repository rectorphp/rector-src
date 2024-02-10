<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\Config\RectorConfig;
use Rector\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector;

return RectorConfig::configure()
    ->withRules(
        [LocallyCalledStaticMethodToNonStaticRector::class, ThisCallOnStaticMethodToStaticCallRector::class]
    );
