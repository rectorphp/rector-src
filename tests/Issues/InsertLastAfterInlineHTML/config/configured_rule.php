<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\InsertLastAfterInlineHTML\Source\InsertAfterInlineHTMLRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(InsertAfterInlineHTMLRector::class);
};
