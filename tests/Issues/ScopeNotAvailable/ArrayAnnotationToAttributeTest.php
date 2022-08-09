<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ArrayAnnotationToAttribute;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ArrayAnnotationToAttributeTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureArrayAnnotationToAttribute');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/array_annotation_to_attribute.php';
    }
}
