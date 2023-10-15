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

        /**
         * For testing skip remove use statement part of rename during auto import
         * the rename annotation is allowed only on specific symfony assert, doctrine, and serializer
         *
         * @see https://github.com/rectorphp/rector-src/blob/d55a35bcdede830d3927de1c11e0f7f0d12ee9e4/packages/BetterPhpDocParser/PhpDocManipulator/PhpDocClassRenamer.php#L36-L38s
         * @see https://github.com/rectorphp/rector-symfony/issues/535#issuecomment-1762822651
         */
        'Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted' => 'Symfony\Component\Security\Http\Attribute\IsGranted',
    ]);
};
