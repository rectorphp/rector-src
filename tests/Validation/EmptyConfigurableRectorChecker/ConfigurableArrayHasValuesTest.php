<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation\EmptyConfigurableRectorChecker;

use Rector\Core\Validation\EmptyConfigurableRectorChecker;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * array configurable with has values config will be passed
 */
final class ConfigurableArrayHasValuesTest extends AbstractTestCase
{
    private EmptyConfigurableRectorChecker $validator;

    private PrivatesAccessor $privateAccessor;

    protected function setUp(): void
    {
        $this->bootFromConfigFileInfos([new SmartFileInfo(__DIR__ . '/config/configurable_array_has_values.php')]);
        $this->validator = $this->getService(EmptyConfigurableRectorChecker::class);
        $this->privateAccessor = $this->getService(PrivatesAccessor::class);
    }

    public function test(): void
    {
        $this->validator->check([$this->getService(AnnotationToAttributeRector::class)]);
        $countEmptyConfigurableRectors = $this->privateAccessor->getPrivateProperty($this->validator, 'emptyConfigurableRectors');
        $this->assertCount(0, $countEmptyConfigurableRectors);
    }
}
