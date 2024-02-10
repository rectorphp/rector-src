<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php56\Rector\FuncCall\PowToExpRector;

return RectorConfig::configure()
    ->withRules([PowToExpRector::class]);
