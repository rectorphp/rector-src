<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\Config\RectorConfig;
use Rector\Tests\Issues\InfiniteLoop\Rector\MethodCall\InfinityLoopRector;

return RectorConfig::configure()
    ->withRules([InfinityLoopRector::class, SimplifyDeMorganBinaryRector::class]);
