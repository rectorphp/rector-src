<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\DependencyInjection;

use PHPStan\Parser\Parser;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class PHPStanServiceFactoryTest extends AbstractTestCase
{
    private PHPStanServicesFactory $phpStanServicesFactory;

    protected function setUp(): void
    {
        $this->boot();

        $this->phpStanServicesFactory = $this->getService(PHPStanServicesFactory::class);
    }

    public function test(): void
    {
        $phpstanParser = $this->phpStanServicesFactory->createPHPStanParser();
        $this->assertInstanceOf(Parser::class, $phpstanParser);
    }
}
