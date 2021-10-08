<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation\EmptyConfigurableRectorChecker;

use Rector\Core\Validation\EmptyConfigurableRectorChecker;
use Rector\Privatization\Rector\Class_\ChangeLocalPropertyToVariableRector;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * Not configurable will be passed
 */
final class NotConfigurableRectorTest extends AbstractTestCase
{
    private EmptyConfigurableRectorChecker $validator;

    private PrivatesAccessor $privateAccessor;

    protected function setUp(): void
    {
        $this->bootFromConfigFileInfos([new SmartFileInfo(__DIR__ . '/config/not_configurable.php')]);
        $this->validator = $this->getService(EmptyConfigurableRectorChecker::class);
        $this->privateAccessor = $this->getService(PrivatesAccessor::class);
    }

    public function test(): void
    {
        $this->validator->check([$this->getService(ChangeLocalPropertyToVariableRector::class)]);
        $countEmptyConfigurableRectors = $this->privateAccessor->getPrivateProperty($this->validator, 'emptyConfigurableRectors');
        $this->assertCount(0, $countEmptyConfigurableRectors);
    }
}
