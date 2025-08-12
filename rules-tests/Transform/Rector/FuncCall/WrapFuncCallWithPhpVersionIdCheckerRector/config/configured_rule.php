<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\ValueObject\WrapFuncCallWithPhpVersionIdChecker;
use Rector\Transform\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector;

return RectorConfig::configure()
    ->withConfiguredRule(
        WrapFuncCallWithPhpVersionIdCheckerRector::class,
        [new WrapFuncCallWithPhpVersionIdChecker('no_op_function', 80500)]
    );
