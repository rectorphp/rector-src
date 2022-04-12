<?php

declare(strict_types=1);

use Rector\Renaming\Rector\ClassMethod\RenameAnnotationRector;
use Rector\Renaming\ValueObject\RenameAnnotationByType;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameAnnotationRector::class)
        ->configure([new RenameAnnotationByType('PHPUnit\Framework\TestCase', 'scenario', 'test')]);
};
