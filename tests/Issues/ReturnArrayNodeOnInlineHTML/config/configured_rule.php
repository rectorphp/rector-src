<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\ReturnArrayNodeOnInlineHTML\Source\ArrayOnInlineHTMLRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ArrayOnInlineHTMLRector::class);
};
