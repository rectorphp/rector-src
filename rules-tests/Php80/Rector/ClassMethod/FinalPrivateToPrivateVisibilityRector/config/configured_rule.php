<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\ClassMethod\FinalPrivateToPrivateVisibilityRector;

return RectorConfig::configure()->withRules([FinalPrivateToPrivateVisibilityRector::class]);
