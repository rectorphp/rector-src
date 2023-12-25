<?php

declare(strict_types=1);

namespace Rector\Utils\Tests\ChangelogGenerator\Changelog;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
use Rector\Utils\ChangelogGenerator\Changelog\ChangelogContentsFactory;

final class ChangelogContentsFactoryTest extends TestCase
{
    private ChangelogContentsFactory $changelogContentsFactory;

    protected function setUp(): void
    {
        $this->changelogContentsFactory = new ChangelogContentsFactory();
    }

    public function test(): void
    {
        $changelogLines = [
            '* Add new rule',
            '* Fix bug',
            '* Fixed another bug',
            '* Enable PHPStan on tests as well + add "unused public" ([#3238](https://github.com/rectorphp/rector-src/pull/3238))',
        ];

        $generatedChangelogContents = $this->changelogContentsFactory->create($changelogLines);
        $printed = str_replace("\r\n", "\n", $generatedChangelogContents);
        $fileContent = str_replace("\r\n", "\n", FileSystem::read(__DIR__ . '/Fixture/generated_changelog.md'));

        $this->assertSame($fileContent, $printed);
    }
}
