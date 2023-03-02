<?php

declare(strict_types=1);

namespace Rector\Utils\ChangelogGenerator\Changelog;

use Rector\Utils\ChangelogGenerator\Enum\ChangelogCategory;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Utils\Tests\ChangelogGenerator\Changelog\ChangelogContentsFactoryTest
 */
final class ChangelogContentsFactory
{
    private $unused;
    /**
     * @var array<string, string[]>
     */
    private const FILTER_KEYWORDS_BY_CATEGORY = [
        ChangelogCategory::SKIPPED => [
            'fix wrong reference',
            'enable phpstan',
            'bump to phpstan',
            'bump composer',
            'cleanup phpstan',
            'compatibility with betterreflection',
            'update to',
            '[automated]',
            '[core]',
        ],
        ChangelogCategory::NEW_FEATURES => ['add', 'added', 'improve'],
        ChangelogCategory::BUGFIXES => ['fixed', 'fix'],
        ChangelogCategory::REMOVED => ['removed', 'deleted', 'remove deprecated', 'remove'],
    ];

    /**
     * @param string[] $changelogLines
     */
    public function create(array $changelogLines): string
    {
        Assert::allString($changelogLines);

        // summarize into "Added Features" and "Bugfixes" groups
        $linesByCategory = [];

        foreach ($changelogLines as $changelogLine) {
            foreach (self::FILTER_KEYWORDS_BY_CATEGORY as $category => $filterKeywords) {
                if (! $this->isKeywordsMatch($filterKeywords, $changelogLine)) {
                    continue;
                }

                $linesByCategory[$category][] = $changelogLine;
                continue 2;
            }

            // fallback to fixed
            $linesByCategory[ChangelogCategory::BUGFIXES][] = $changelogLine;
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

        $lastItemKey = array_key_last($linesByCategory);

        foreach ($linesByCategory as $category => $lines) {
            $fileContents .= PHP_EOL;
            $fileContents .= '## ' . $category . PHP_EOL . PHP_EOL;
            foreach ($lines as $line) {
                $fileContents .= $line . PHP_EOL;
            }

            // end space, only if this is not the last item
            if ($lastItemKey === $category) {
                continue;
            }

            $fileContents .= PHP_EOL . '<br>' . PHP_EOL;
        }

        return ltrim($fileContents);
    }

    /**
     * @param string[] $filterKeywords
     */
    private function isKeywordsMatch(array $filterKeywords, string $changelogLine): bool
    {
        $normalizedChangelogLine = strtolower($changelogLine);

        foreach ($filterKeywords as $filterKeyword) {
            if (\str_contains($normalizedChangelogLine, $filterKeyword)) {
                return true;
            }
        }

        return false;
    }
}
