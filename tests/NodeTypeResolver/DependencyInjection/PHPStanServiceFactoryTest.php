<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\DependencyInjection;

use PHPStan\Parser\Parser;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PHPStanServiceFactoryTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $phpStanServicesFactory = $this->make(PHPStanServicesFactory::class);

        $phpstanParser = $phpStanServicesFactory->createPHPStanParser();
        $this->assertInstanceOf(Parser::class, $phpstanParser);
    }
}
