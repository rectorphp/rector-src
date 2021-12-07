<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation\Collector\EmptyConfigurableRectorCollector;

use Rector\Core\Validation\Collector\EmptyConfigurableRectorCollector;
use Rector\Testing\PHPUnit\AbstractTestCase;

/**
 * array configurable with missing values config will show warning
 */
final class ConfigurableReservedFnFunctionTest extends AbstractTestCase
{
    private EmptyConfigurableRectorCollector $collector;

    protected function setUp(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/config/configurable_reserved_fn_function.php']);
        $this->collector = $this->getService(EmptyConfigurableRectorCollector::class);
    }

    public function test(): void
    {
        $emptyConfigurableRectors = $this->collector->resolveEmptyConfigurableRectorClasses();
        $this->assertCount(0, $emptyConfigurableRectors);
    }
}
