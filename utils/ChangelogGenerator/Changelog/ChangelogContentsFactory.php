<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator\Changelog;

use Nette\Utils\Strings;
use Rector\Utils\ChangelogGenerator\Enum\ChangelogCategory;

final class ChangelogContentsFactory
{
    /**
     * @var array<string, string[]>
     */
    private const FILTER_KEYWORDS_BY_CATEGORY = [
        ChangelogCategory::NEW_FEATURES => ['Add support', 'Add'],
        ChangelogCategory::SKIPPED => ['Fix wrong reference'],
        ChangelogCategory::BUGFIXES => ['Fixed', 'Fix'],
    ];

    /**
     * @param string[] $changelogLines
     */
    public function create(array $changelogLines): string
    {
        // summarize into "Added Features" and "Bugfixes" groups
        $linesByCategory = [];

        // @todo test this one
        foreach ($changelogLines as $changelogLine) {
            foreach (self::FILTER_KEYWORDS_BY_CATEGORY as $category => $filterKeywords) {
                foreach ($filterKeywords as $filterKeyword) {
                    if (! Strings::contains($changelogLine, $filterKeyword)) {
                        continue;
                    }

                    $linesByCategory[$category][] = $changelogLine;
                    continue 2;
                }
            }
        }

        // remove skipped lines
        unset($linesByCategory[ChangelogCategory::SKIPPED]);

        return $this->generateFileContentsFromGroupedItems($linesByCategory);
    }

    /**
     * @param array<string, string[]> $linesByCategory
     */
    private function generateFileContentsFromGroupedItems(array $linesByCategory): string
    {
        $fileContents = '';

        foreach ($linesByCategory as $category => $lines) {
            $fileContents .= '## ' . $category . PHP_EOL . PHP_EOL;
            foreach ($lines as $line) {
                $fileContents .= $line . PHP_EOL . PHP_EOL;
            }

            // end space
            $fileContents .= PHP_EOL . PHP_EOL;
        }

        return $fileContents;
    }
}
