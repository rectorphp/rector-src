<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Nette\Utils\FileSystem;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Skipper\Skipper\UsedSkipCollector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * A rule-scoped path skip is marked used for every file whose path it matches, as the rule is
 * skipped once per file.
 *
 * @see RenameClassRector
 */
final class SkipUsedTrackingTest extends AbstractRectorTestCase
{
    public function testMarksSkipUsedForEveryMatchedFile(): void
    {
        $this->doTestFile(__DIR__ . '/FixtureSkipUsedTracking/skip_used_renames_old_class.php.inc');

        // doTestFile() only cleans up the last processed temp file, so remove this one explicitly
        FileSystem::delete(__DIR__ . '/FixtureSkipUsedTracking/skip_used_renames_old_class.php');

        $this->doTestFile(__DIR__ . '/FixtureSkipUsedTracking/skip_unused_no_old_class.php.inc');

        $usedSkipCollector = $this->make(UsedSkipCollector::class);
        $usedSkips = $usedSkipCollector->provide();

        $usedPaths = $usedSkips[RenameClassRector::class] ?? [];

        // both skip masks match a processed file, so both are marked used
        $this->assertContains('*skip_used_renames_old_class*', $usedPaths);
        $this->assertContains('*skip_unused_no_old_class*', $usedPaths);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/skip_used_tracking_configured_rule.php';
    }
}
