<?php

declare(strict_types=1);

namespace Rector\Tests\Config;

use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Symfony\Set\TwigSetList;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

#[RunClassInSeparateProcess]
final class RectorConfigTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $rectorConfig = $this->getContainer();

        $rectorConfig->configure()
            ->withSets([TwigSetList::TWIG_134])
            ->withRules([ReturnTypeFromReturnNewRector::class])($rectorConfig);

        // only collect root withRules()
        $this->assertCount(1, SimpleParameterProvider::provideArrayParameter(Option::ROOT_STANDALONE_REGISTERED_RULES));
    }
}
