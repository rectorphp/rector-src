<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeVoidTypeDeclarationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DowngradeVoidTypeDeclarationRector::class);
};
