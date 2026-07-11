<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector;

return RectorConfig::configure()
    ->withRules([MergePhpstanDocTagIntoNativeRector::class]);
