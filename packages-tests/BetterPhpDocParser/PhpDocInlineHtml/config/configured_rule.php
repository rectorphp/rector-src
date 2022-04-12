<?php

declare(strict_types=1);

use Rector\Tests\BetterPhpDocParser\PhpDocInlineHtml\Source\InlineHtmlRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(InlineHtmlRector::class);
};
