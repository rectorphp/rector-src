<?php

declare(strict_types=1);

namespace Rector\Tests\Application;

use Rector\Application\RectorRegistry;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Application\Source\SkippableRector;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;

final class RectorRegistryTest extends AbstractLazyTestCase
{
    private RectorRegistry $rectorRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        SimpleParameterProvider::setParameter(Option::SKIP, [
            SkippableRector::class => ['*/skipped_directory/*'],
            FifthElement::class => ['*/skipped_directory/*'],
        ]);

        $this->rectorRegistry = $this->make(RectorRegistry::class);
        $this->rectorRegistry->refreshRectors([new FifthElement()]);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    public function testForPathSkipsRectorForSkippedPath(): void
    {
        $rectorsForPath = $this->rectorRegistry->forPath(__DIR__ . '/skipped_directory/some_file.php');

        $this->assertSame([], $rectorsForPath);
    }

    public function testForPathKeepsRectorForOtherPath(): void
    {
        $rectorsForPath = $this->rectorRegistry->forPath(__DIR__ . '/some_file.php');

        $this->assertCount(1, $rectorsForPath);
        $this->assertInstanceOf(SkippableRector::class, $rectorsForPath[0]);
        $this->assertInstanceOf(FifthElement::class, $rectorsForPath[0]);
    }
}
