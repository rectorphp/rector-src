<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector;

return RectorConfig::configure()->withRules([StrictArrayParamDimFetchRector::class]);
