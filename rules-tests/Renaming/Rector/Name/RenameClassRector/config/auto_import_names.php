<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\FirstNamespace\SomeServiceClass as SomeServiceClassFirstNamespace;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\SecondNamespace\SomeServiceClass;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();
    $rectorConfig->removeUnusedImports();

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        OldClass::class => NewClass::class,
        SomeServiceClassFirstNamespace::class => SomeServiceClass::class,
        'Storage' => 'Illuminate\Support\Facades\Storage',
        'Queue' => 'Illuminate\Support\Facades\Queue',

        // test same last name with alias
        'Project\PedBook\Manual\Stc\TableMedicine' => 'Project\PedBook\Manual\Stc\TableDisease',
        'Project\PedBook\Auto\Obj\TableMedicine' => 'Project\PedBook\Auto\Obj\TableDisease',
        'Project\PedBook\Auto\Stc\TableMedicine' => 'Project\PedBook\Auto\Stc\TableDisease',
    ]);
};
