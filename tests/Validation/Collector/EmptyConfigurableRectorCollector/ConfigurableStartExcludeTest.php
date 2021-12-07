<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation\Collector\EmptyConfigurableRectorCollector;

use Rector\Core\Validation\Collector\EmptyConfigurableRectorCollector;
use Rector\Testing\PHPUnit\AbstractTestCase;

/**
 * For configurable with exclude start, eg:
 *      private array $excludeMethods = [];
 * is allowed to pass, as it will use default fallback as is
 */
final class ConfigurableStartExcludeTest extends AbstractTestCase
{
    private EmptyConfigurableRectorCollector $collector;

    protected function setUp(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/config/configurable_start_exclude.php']);
        $this->collector = $this->getService(EmptyConfigurableRectorCollector::class);
    }

    public function test(): void
    {
        $emptyConfigurableRectors = $this->collector->resolveEmptyConfigurableRectorClasses();
        $this->assertCount(0, $emptyConfigurableRectors);
    }
}
