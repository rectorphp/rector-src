<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SymplifyQuoteEscapeRector::class]);
