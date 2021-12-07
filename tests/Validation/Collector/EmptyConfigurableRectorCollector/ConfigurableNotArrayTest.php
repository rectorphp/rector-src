<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation\Collector\EmptyConfigurableRectorCollector;

use Rector\Core\Validation\Collector\EmptyConfigurableRectorCollector;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * Not array configurable, eg:
 *      private bool $classLikeTypeOnly = false
 * is allowed to pass, as it will use default fallback as is
 */
final class ConfigurableNotArrayTest extends AbstractTestCase
{
    private EmptyConfigurableRectorCollector $collector;

    protected function setUp(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/config/configurable_not_array.php']);
        $this->collector = $this->getService(EmptyConfigurableRectorCollector::class);
    }

    public function test(): void
    {
        $emptyConfigurableRectors = $this->collector->resolveEmptyConfigurableRectorClasses();
        $this->assertCount(0, $emptyConfigurableRectors);
    }
}