<?php

declare(strict_types=1);

use Rector\Php74\Rector\ArrayDimFetch\CurlyToSquareBracketArrayStringRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CurlyToSquareBracketArrayStringRector::class);
};
