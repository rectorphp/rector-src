<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector;

return RectorConfig::configure()->withRules([AddReturnTypeDeclarationFromYieldsRector::class]);
