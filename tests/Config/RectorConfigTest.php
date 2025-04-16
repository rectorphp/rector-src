<?php

declare(strict_types=1);

namespace Rector\Tests\Config;

use Rector\Config\RectorConfig;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Symfony\Set\TwigSetList;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

final class RectorConfigTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $rectorConfig = RectorConfig::configure()
            ->withSets([
                TwigSetList::TWIG_134
            ])
            ->withRules([
                ReturnTypeFromReturnNewRector::class
            ]);

        // invoke is needed to get collection of rules
        $rectorConfig->__invoke(new RectorConfig());

        // only collect root withRules()
        $this->assertCount(1, SimpleParameterProvider::provideArrayParameter(Option::ROOT_STANDALONE_REGISTERED_RULES));
    }
}