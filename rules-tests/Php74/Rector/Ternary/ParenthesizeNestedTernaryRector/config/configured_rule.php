<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector;

return RectorConfig::configure()
    ->withRules([ParenthesizeNestedTernaryRector::class]);
