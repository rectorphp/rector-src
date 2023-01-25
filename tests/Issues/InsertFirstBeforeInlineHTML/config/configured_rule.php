<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\InsertFirstBeforeInlineHTML\Source\InsertBeforeInlineHTMLRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(InsertBeforeInlineHTMLRector::class);
};
