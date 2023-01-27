<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\ReturnArrayNodeAfterInlineHTMLStmt\Source\ArrayAddAfterInlineHTMLRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ArrayAddAfterInlineHTMLRector::class);
};
