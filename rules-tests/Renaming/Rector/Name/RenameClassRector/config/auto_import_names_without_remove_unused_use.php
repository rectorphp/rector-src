<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Interop\Container\ContainerInterface' => 'Psr\Container\ContainerInterface',
        'DateTime' => 'DateTimeInterface',
        'FqnizeNamespacedImport' => 'Abc\FqnizeNamespacedImport',
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
