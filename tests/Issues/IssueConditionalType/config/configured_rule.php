<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector;

return RectorConfig::configure()->withRules([DowngradeMixedTypeDeclarationRector::class]);
