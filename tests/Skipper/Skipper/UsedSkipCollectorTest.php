<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\Skipper\Skipper;
use Rector\Skipper\Skipper\UsedSkipCollector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;

final class UsedSkipCollectorTest extends AbstractLazyTestCase
{
    /**
     * Uniquely named on purpose, so the shared collector is never polluted by another test.
     *
     * @var string
     */
    private const UNUSED_SKIP_MARKER = '*/UniqueUnusedSkipMarker/*';

    private Skipper $skipper;

    private UsedSkipCollector $usedSkipCollector;

    protected function setUp(): void
    {
        parent::setUp();

        SimpleParameterProvider::setParameter(Option::SKIP, [
            // path skip that will be matched
            __DIR__ . '/Fixture/SomeSkippedPath',

            // path skip that is never matched
            self::UNUSED_SKIP_MARKER,

            // class skip that will be matched
            FifthElement::class,
        ]);

        $this->skipper = $this->make(Skipper::class);
        $this->usedSkipCollector = $this->make(UsedSkipCollector::class);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    public function testCollectsOnlyMatchedSkips(): void
    {
        $this->skipper->shouldSkipFilePath('tests/Skipper/Skipper/Fixture/SomeSkippedPath/any.txt');
        $this->skipper->shouldSkipElement(FifthElement::class);

        $usedSkips = $this->usedSkipCollector->provide();

        // matched skips are collected
        $this->assertContains(FifthElement::class, $usedSkips);
        $this->assertNotEmpty(array_filter(
            $usedSkips,
            static fn (string $usedSkip): bool => str_ends_with($usedSkip, 'SomeSkippedPath')
        ));

        // unmatched skip is never collected
        $this->assertNotContains(self::UNUSED_SKIP_MARKER, $usedSkips);
    }
}
