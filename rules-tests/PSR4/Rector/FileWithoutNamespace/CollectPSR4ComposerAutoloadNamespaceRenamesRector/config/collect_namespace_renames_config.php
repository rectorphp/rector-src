<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;
use Rector\PSR4\Rector\FileWithoutNamespace\CollectPSR4ComposerAutoloadNamespaceRenamesRector;
use Rector\Tests\PSR4\Rector\FileWithoutNamespace\CollectPSR4ComposerAutoloadNamespaceRenamesRector\Source\DummyPSR4AutoloadWithoutNamespaceMatcher;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $rectorConfig->rule(CollectPSR4ComposerAutoloadNamespaceRenamesRector::class);

    $services->set(DummyPSR4AutoloadWithoutNamespaceMatcher::class);

    $services->alias(PSR4AutoloadNamespaceMatcherInterface::class, DummyPSR4AutoloadWithoutNamespaceMatcher::class);
};
