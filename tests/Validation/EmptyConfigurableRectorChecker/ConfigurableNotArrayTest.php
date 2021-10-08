<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Validation\EmptyConfigurableRectorChecker;

use Rector\Core\Validation\EmptyConfigurableRectorChecker;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * Not array configurable, eg:
 *      private bool $classLikeTypeOnly = false
 * is allowed to pass, as it will use default fallback as is
 */
final class ConfigurableNotArrayTest extends AbstractTestCase
{
    private EmptyConfigurableRectorChecker $validator;

    protected function setUp(): void
    {
        $this->bootFromConfigFileInfos([new SmartFileInfo(__DIR__ . '/config/configurable_not_array.php')]);
        $this->validator = $this->getService(EmptyConfigurableRectorChecker::class);
    }

    public function test(): void
    {
        $emptyConfigurableRectors = $this->validator->check([$this->getService(TypedPropertyRector::class)]);
        $this->assertCount(0, $emptyConfigurableRectors);
    }
}
