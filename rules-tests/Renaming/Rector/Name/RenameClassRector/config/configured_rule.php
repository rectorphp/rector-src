<?php

declare(strict_types=1);

use Bar\Foo;
use Acme\Bar\DoNotUpdateExistingTargetNamespace;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture\DuplicatedClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\Contract\FirstInterface;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\Contract\SecondInterface;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\Contract\ThirdInterface;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\InterfaceAndClass\SomeBasicDateTime;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\InterfaceAndClass\SomeBasicDateTimeInterface;
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
            OldClass::class => NewClass::class,
            // interface to class
            SomeBasicDateTime::class =>
            SomeBasicDateTimeInterface::class,

            // test casing
            OldClassWithTypo::class => NewClassWithoutTypo::class,
            'Countable' => 'stdClass',
            'Twig_Extension_Sandbox' => 'Twig\Extension\SandboxExtension',
            // Renaming class itself and its namespace
            'MyNamespace\MylegacyClass' => 'MyNewNamespace\MyNewClass',
            // test duplicated class - @see https://github.com/rectorphp/rector/issues/1438
            'Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture\SingularClass' => DuplicatedClass::class,
            // test duplicated class - @see https://github.com/rectorphp/rector/issues/5389
            FirstInterface::class => ThirdInterface::class,
            SecondInterface::class => ThirdInterface::class,
            \Acme\Foo\DoNotUpdateExistingTargetNamespace::class => DoNotUpdateExistingTargetNamespace::class,
            SomeNonFinalClass::class => SomeFinalClass::class,

            'Doctrine\DBAL\DBALException' => 'Doctrine\DBAL\Exception',
            'NotExistsClass' => 'NewClass',

            foo::class => Foo::class,

            /**
             * This test never renamed as it is annotation @IsGranted
             *
             * Only Assert, Doctrine, and Serializer type annotation that currently supported
             *
             * For @IsGranted, it needs to be changed to Attribute instead
             *
             * @see https://github.com/rectorphp/rector-src/blob/290f2a03d53d0b8da35beb973d724f95a77983cb/tests/Issues/AnnotationToAttributeRenameAutoImport/config/configured_rule.php#L13-L22
             * @see https://github.com/rectorphp/rector-symfony/issues/535
             */
            'Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted' => 'Symfony\Component\Security\Http\Attribute\IsGranted',
        ]);
};
