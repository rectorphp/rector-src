<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([FuncGetArgsToVariadicParamRector::class]);
