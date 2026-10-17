<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\Tests\Issues\ChangeSwitchTernary\Source\AnotherExpressionRector;

return RectorConfig::configure()
    ->withRules(
        [ChangeSwitchToMatchRector::class, AnotherExpressionRector::class]
    );
