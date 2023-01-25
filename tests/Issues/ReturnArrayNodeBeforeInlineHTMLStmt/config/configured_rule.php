<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\ReturnArrayNodeBeforeInlineHTMLStmt\Source\ArrayAddBeforeInlineHTMLRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ArrayAddBeforeInlineHTMLRector::class);
};
