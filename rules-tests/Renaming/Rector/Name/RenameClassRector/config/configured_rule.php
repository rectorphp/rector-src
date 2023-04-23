<?php

declare(strict_types=1);

use Acme\Bar\DoNotUpdateExistingTargetNamespace;
use Manual\Twig\TwigFilter;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture\DuplicatedClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\AbstractManualExtension;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\Contract\FirstInterface;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\Contract\SecondInterface;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\Contract\ThirdInterface;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClassWithTypo;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\SomeFinalClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\SomeNonFinalClass;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->removeUnusedImports();

    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            'FqnizeNamespaced' => 'Abc\FqnizeNamespaced',
            OldClass::class => NewClass::class,
            OldClassWithTypo::class => NewClassWithoutTypo::class,
            'DateTime' => 'DateTimeInterface',
            'Countable' => 'stdClass',
            Manual_Twig_Filter::class => TwigFilter::class,
            'Twig_AbstractManualExtension' => AbstractManualExtension::class,
            'Twig_Extension_Sandbox' => 'Twig\Extension\SandboxExtension',
            // Renaming class itself and its namespace
            'MyNamespace\MylegacyClass' => 'MyNewNamespace\MyNewClass',
            'MyNamespace\MyTrait' => 'MyNewNamespace\MyNewTrait',
            'MyNamespace\MyInterface' => 'MyNewNamespace\MyNewInterface',
            'MyOldClass' => 'MyNamespace\MyNewClass2',
            'AnotherMyOldClass' => 'AnotherMyNewClass',
            'MyNamespace\AnotherMyClass' => 'MyNewClassWithoutNamespace',
            // test duplicated class - @see https://github.com/rectorphp/rector/issues/1438
            'Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture\SingularClass' => DuplicatedClass::class,
            // test duplicated class - @see https://github.com/rectorphp/rector/issues/5389
            FirstInterface::class => ThirdInterface::class,
            SecondInterface::class => ThirdInterface::class,
            \Acme\Foo\DoNotUpdateExistingTargetNamespace::class => DoNotUpdateExistingTargetNamespace::class,
            SomeNonFinalClass::class => SomeFinalClass::class,
            'Foo\Bar' => 'Foo\Bar\BarInterface',
            'Doctrine\DBAL\DBALException' => 'Doctrine\DBAL\Exception',
            'Bar' => 'BarInterface',
            'App' => 'Illuminate\Support\Facades\App',
            'NotExistsClass' => 'NewClass',
        ]);
};
