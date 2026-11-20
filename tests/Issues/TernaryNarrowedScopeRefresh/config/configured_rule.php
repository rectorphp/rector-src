<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source\CastFirstArgToIntRector;

return RectorConfig::configure()
    ->withRules([CastFirstArgToIntRector::class, RemoveExtraParametersRector::class]);
