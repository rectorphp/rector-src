<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\PostRector\AdditionalPostRector\Source\CommentRemoverPostRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();

    $rectorConfig
        ->postRector(CommentRemoverPostRector::class);
};
