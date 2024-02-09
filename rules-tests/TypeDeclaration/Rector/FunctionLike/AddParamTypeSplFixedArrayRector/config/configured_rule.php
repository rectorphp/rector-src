<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;

return RectorConfig::configure()->withRules([AddParamTypeSplFixedArrayRector::class]);
