<?php

declare(strict_types=1);

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\MigrateInnerClassReference;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MigrateInnerClassReferenceTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $smartFileInfo): void
    {
        $this->doTestFileInfo($smartFileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureMigrateInnerClassReference');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/migrate_inner_class_reference.php';
    }
}
