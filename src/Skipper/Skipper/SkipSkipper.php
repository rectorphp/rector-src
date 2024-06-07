<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Comment;
use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Util\NewLineSplitter;

final readonly class SkipSkipper
{
    private const RECTOR_IGNORE_NEXT_LINE_TAG = '@rector-ignore-next-line';

    private const RECTOR_IGNORE_TAG = '@rector-ignore';

    public function __construct(
        private FileInfoMatcher $fileInfoMatcher
    ) {
    }

    /**
     * @param array<string, string[]|null> $skippedClasses
     */
    public function doesMatchSkip(object | string $checker, string $filePath, array $skippedClasses): bool
    {
        foreach ($skippedClasses as $skippedClass => $skippedFiles) {
            if (! is_a($checker, $skippedClass, true)) {
                continue;
            }

            // skip everywhere
            if (! is_array($skippedFiles)) {
                return true;
            }

            if ($this->fileInfoMatcher->doesFileInfoMatchPatterns($filePath, $skippedFiles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<Comment> $comments
     */
    public function doesMatchComments(object | string $checker, string $filePath, array $comments): bool
    {
        if ($comments === []) {
            return false;
        }
        $currentRuleFullName = is_object($checker) ? $checker::class : $checker;
        $currentRuleName = basename(str_replace('\\', '/', $currentRuleFullName));
        foreach ($comments as $comment) {
            $commentLines = NewLineSplitter::split($comment->getText());
            foreach ($commentLines as $commentLine) {
                if (str_contains($commentLine, self::RECTOR_IGNORE_NEXT_LINE_TAG)) {
                    return true;
                }
                if ($this->isCurrentRuleInExcludedRulesInCommentLine($currentRuleName, $commentLine)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function isCurrentRuleInExcludedRulesInCommentLine(string $currentRuleName, string $commentLine): bool
    {
        $ignorePosition = strpos($commentLine, self::RECTOR_IGNORE_TAG);
        if ($ignorePosition !== false) {
            $restOfLine = substr($commentLine, $ignorePosition + strlen(self::RECTOR_IGNORE_TAG));
            $restOfLine = str_replace('*/', '', $restOfLine);
            $excludedRules = explode(',', $restOfLine);
            foreach ($excludedRules as $excludedRule) {
                $excludedRule = trim($excludedRule);
                if ($excludedRule === $currentRuleName) {
                    return true;
                }
            }
        }
        return false;
    }
}
