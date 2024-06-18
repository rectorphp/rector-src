<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\FuncCall\HebrevcToNl2brHebrevRector;

return RectorConfig::configure()
    ->withRules([HebrevcToNl2brHebrevRector::class]);
