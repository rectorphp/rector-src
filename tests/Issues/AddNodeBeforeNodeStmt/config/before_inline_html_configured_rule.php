<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\AddNodeBeforeNodeStmt\Source\AddBeforeInlineHTMLRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddBeforeInlineHTMLRector::class);
};
