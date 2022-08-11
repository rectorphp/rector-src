<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeThrowableTypeDeclarationRector;
use Rector\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ArrowFunctionToAnonymousFunctionRector::class);
    $rectorConfig->rule(DowngradeThrowableTypeDeclarationRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_70);
};
