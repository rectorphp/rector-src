<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator\Changelog;

use Nette\Utils\Strings;
use Rector\Utils\ChangelogGenerator\Enum\ChangelogCategory;

final class ChangelogContentsFactory
{
    /**
     * @param array<string, $changelogLines string[]>
     */
    private const FILTER_KEYWORDS_BY_CATEGORY = [
        ChangelogCategory::NEW_FEATURES => ['Add support'],
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
                    if (Strings::contains($changelogLine, $filterKeyword)) {
                        $linesByCategory[$category][] = $changelogLine;
                        continue 3;
                    }
                }
            }
        }

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
