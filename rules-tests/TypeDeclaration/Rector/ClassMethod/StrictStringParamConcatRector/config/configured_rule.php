<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector;

return RectorConfig::configure()->withRules([StrictStringParamConcatRector::class]);
