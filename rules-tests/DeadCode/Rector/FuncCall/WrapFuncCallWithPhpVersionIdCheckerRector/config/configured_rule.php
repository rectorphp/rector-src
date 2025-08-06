<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector;
use Rector\DeadCode\ValueObject\WrapFuncCallWithPhpVersionIdChecker;

return RectorConfig::configure()
    ->withConfiguredRule(
        WrapFuncCallWithPhpVersionIdCheckerRector::class,
        [new WrapFuncCallWithPhpVersionIdChecker('no_op_function', 80500)]
    );
