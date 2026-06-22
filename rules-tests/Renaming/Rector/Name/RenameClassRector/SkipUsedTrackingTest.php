<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Nette\Utils\FileSystem;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Skipper\Skipper\UsedSkipCollector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * A class/path skip is only "used" when the rule would actually have changed the skipped file.
 *
 * @see RenameClassRector
 */
final class SkipUsedTrackingTest extends AbstractRectorTestCase
{
    public function testMarksSkipUsedOnlyWhenRuleWouldChangeFile(): void
    {
        // file uses OldClass → rule would rename it → its skip is what prevents the change
        $this->doTestFile(__DIR__ . '/FixtureSkipUsedTracking/skip_used_renames_old_class.php.inc');

        // doTestFile() only cleans up the last processed temp file, so remove this one explicitly
        FileSystem::delete(__DIR__ . '/FixtureSkipUsedTracking/skip_used_renames_old_class.php');

        // file does not use OldClass → rule would not change it → its skip is unnecessary
        $this->doTestFile(__DIR__ . '/FixtureSkipUsedTracking/skip_unused_no_old_class.php.inc');

        $usedSkipCollector = $this->make(UsedSkipCollector::class);
        $usedSkips = $usedSkipCollector->provide();

        $usedPaths = $usedSkips[RenameClassRector::class] ?? [];

        // the skip that actually prevented a rename is marked used
        $this->assertContains('*skip_used_renames_old_class*', $usedPaths);

        // the skip on a file the rule would not have touched is never marked used
        $this->assertNotContains('*skip_unused_no_old_class*', $usedPaths);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/skip_used_tracking_configured_rule.php';
    }
}
