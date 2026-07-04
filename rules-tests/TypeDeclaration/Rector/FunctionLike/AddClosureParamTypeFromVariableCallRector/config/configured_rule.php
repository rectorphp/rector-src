<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector;

return RectorConfig::configure()
    ->withRules([AddClosureParamTypeFromVariableCallRector::class]);
