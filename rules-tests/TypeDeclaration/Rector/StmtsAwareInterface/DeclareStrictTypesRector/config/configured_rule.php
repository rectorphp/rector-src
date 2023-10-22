<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DeclareStrictTypesRector::class);
    $rectorConfig->skip([
        DeclareStrictTypesRector::class => [
            realpath(__DIR__ . '/../Fixture/skipped_by_path.php.inc'),
        ],
    ]);
};
