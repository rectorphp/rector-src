<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp81\Rector\FuncCall\DowngradeFirstClassCallableSyntaxRector;

return RectorConfig::configure()->withRules([DowngradeFirstClassCallableSyntaxRector::class]);
