<?php

declare(strict_types=1);

namespace Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver;

use Rector\ChangesReporting\Annotation\RectorsChangelogResolver;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\FileSystem\FilePathHelper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver\Source\RectorWithChangelog;
use Rector\Tests\ChangesReporting\Annotation\AppliedRectorsChangelogResolver\Source\RectorWithOutChangelog;

final class RectorsChangelogResolverTest extends AbstractLazyTestCase
{
    private RectorsChangelogResolver $rectorsChangelogResolver;

    private FilePathHelper $filePathHelper;

    protected function setUp(): void
    {
        $this->rectorsChangelogResolver = $this->make(RectorsChangelogResolver::class);
        $this->filePathHelper = $this->make(FilePathHelper::class);
    }

    public function test(): void
    {
        $fileDiff = $this->createFileDiff();

        $rectorsChangelogs = $this->rectorsChangelogResolver->resolve($fileDiff->getRectorClasses());

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
