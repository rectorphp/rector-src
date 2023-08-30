<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper;

use Illuminate\Container\RewindableGenerator;
use PHPStan\Reflection\BetterReflection\SourceLocator\FileNodesFetcher;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class SkipperRectorRuleTest extends AbstractLazyTestCase
{
    protected function setUp(): void
    {
        // reset to make skip free and invoke resolving
        self::$rectorConfig = null;
    }

    protected function tearDown(): void
    {
        // cleanup configuration
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    public function testRemovingServiceFromContainer(): void
    {
        // register 2 rules, but one is skipped
        $this->bootFromConfigFiles([__DIR__ . '/config/single_skipped_rule_config.php']);

        $rectorConfig = self::getContainer();

        // to invoke before resolving
        $rectorConfig->make(FileNodesFetcher::class);

        // here 1 rule should be removed and 1 should remain
        /** @var RewindableGenerator<int, RectorInterface> $rectorsIterator */
        $rectorsIterator = $rectorConfig->tagged(RectorInterface::class);
        $this->assertCount(1, $rectorsIterator);

        $rectors = iterator_to_array($rectorsIterator->getIterator());
        $this->assertInstanceOf(RemoveUnusedPromotedPropertyRector::class, $rectors[0]);
    }
}
