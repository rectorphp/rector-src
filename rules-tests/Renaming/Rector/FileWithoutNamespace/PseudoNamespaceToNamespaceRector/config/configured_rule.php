<?php

declare(strict_types=1);

use Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;
use Rector\Renaming\ValueObject\PseudoNamespaceToNamespace;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PseudoNamespaceToNamespaceRector::class)
        ->configure([
            new PseudoNamespaceToNamespace('PHPUnit_', ['PHPUnit_Framework_MockObject_MockObject']),
            new PseudoNamespaceToNamespace('ChangeMe_', ['KeepMe_']),
            new PseudoNamespaceToNamespace(
                'Rector_Tests_Renaming_Rector_FileWithoutNamespace_PseudoNamespaceToNamespaceRector_Fixture_'
            ),
        ]);
};
