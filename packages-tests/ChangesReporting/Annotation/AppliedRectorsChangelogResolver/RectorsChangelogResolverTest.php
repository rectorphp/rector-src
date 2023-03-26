<?php

declare(strict_types=1);

namespace Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver;

use Rector\ChangesReporting\Annotation\RectorsChangelogResolver;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver\Source\RectorWithChangelog;
use Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver\Source\RectorWithOutChangelog;

final class RectorsChangelogResolverTest extends AbstractTestCase
{
    private RectorsChangelogResolver $rectorsChangelogResolver;

    private FileDiff $fileDiff;

    private FilePathHelper $filePathHelper;

    protected function setUp(): void
    {
        $this->boot();
        $this->rectorsChangelogResolver = $this->getService(RectorsChangelogResolver::class);
        $this->filePathHelper = $this->getService(FilePathHelper::class);

        $this->fileDiff = $this->createFileDiff();
    }

    public function test(): void
    {
        $rectorsChangelogs = $this->rectorsChangelogResolver->resolve($this->fileDiff->getRectorClasses());

        $expectedRectorsChangelogs = [
            RectorWithChangelog::class => 'https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md',
        ];
        $this->assertSame($expectedRectorsChangelogs, $rectorsChangelogs);
    }

    private function createFileDiff(): FileDiff
    {
        // This is by intention to test the array_unique functionality
        $rectorWithLineChanges = [
            new RectorWithLineChange(RectorWithChangelog::class, 1),
            new RectorWithLineChange(RectorWithChangelog::class, 1),
            new RectorWithLineChange(RectorWithOutChangelog::class, 1),
        ];

        $relativeFilePath = $this->filePathHelper->relativePath(__FILE__);
        return new FileDiff($relativeFilePath, 'foo', 'foo', $rectorWithLineChanges);
    }
}
