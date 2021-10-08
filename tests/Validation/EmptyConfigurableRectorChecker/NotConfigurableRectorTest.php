<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation\EmptyConfigurableRectorChecker;

use Rector\Core\Validation\EmptyConfigurableRectorChecker;
use Rector\Privatization\Rector\Class_\ChangeLocalPropertyToVariableRector;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * Not configurable will be passed
 */
final class NotConfigurableRectorTest extends AbstractTestCase
{
    private EmptyConfigurableRectorChecker $validator;

    protected function setUp(): void
    {
        $this->bootFromConfigFileInfos([new SmartFileInfo(__DIR__ . '/config/not_configurable.php')]);
        $this->validator = $this->getService(EmptyConfigurableRectorChecker::class);
    }

    public function test(): void
    {
        $emptyConfigurableRectors = $this->validator->check(
            [$this->getService(ChangeLocalPropertyToVariableRector::class)]
        );
        $this->assertCount(0, $emptyConfigurableRectors);
    }
}
