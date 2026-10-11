<?php

declare(strict_types=1);

namespace Rector\Tests\Application;

use Rector\Application\RectorRegistry;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;

final class RectorRegistryTest extends AbstractLazyTestCase
{
    private RectorRegistry $rectorRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        SimpleParameterProvider::setParameter(Option::SKIP, [
            FifthElement::class => ['*/skipped_directory/*'],
        ]);

        $this->rectorRegistry = $this->make(RectorRegistry::class);
        $this->rectorRegistry->refreshRectors([new FifthElement()]);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    public function testGetRectors(): void
    {
        $rectors = $this->rectorRegistry->getRectors();

        $this->assertCount(1, $rectors);
        $this->assertInstanceOf(FifthElement::class, $rectors[0]);
    }
}
