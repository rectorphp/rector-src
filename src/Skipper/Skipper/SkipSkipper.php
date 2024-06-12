<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use Rector\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher;
use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Util\NewLineSplitter;

final readonly class SkipSkipper
{
    private const RECTOR_IGNORE_NEXT_LINE_TAG = '@rector-ignore-next-line';

    private const RECTOR_IGNORE_TAG = '@rector-ignore';

    public function __construct(
        private FileInfoMatcher $fileInfoMatcher,
        private ClassAnnotationMatcher $classAnnotationMatcher
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

    public function doesMatchComments(string $rectorClass, Node $node): bool
    {
        $comments = $node->getComments();
        if ($comments === []) {
            return false;
        }
        foreach ($comments as $comment) {
            $commentLines = NewLineSplitter::split($comment->getText());
            foreach ($commentLines as $commentLine) {
                if (str_contains($commentLine, self::RECTOR_IGNORE_NEXT_LINE_TAG)) {
                    return true;
                }
                if ($this->isCurrentRuleInExcludedRulesInCommentLine($rectorClass, $commentLine, $node)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function isCurrentRuleInExcludedRulesInCommentLine(
        string $currentRuleName,
        string $commentLine,
        Node $node
    ): bool {
        $ignorePosition = strpos($commentLine, self::RECTOR_IGNORE_TAG);
        if ($ignorePosition !== false) {
            $restOfLine = substr($commentLine, $ignorePosition + strlen(self::RECTOR_IGNORE_TAG));
            $restOfLine = str_replace('*/', '', $restOfLine);
            $excludedRules = explode(',', $restOfLine);
            foreach ($excludedRules as $excludedRule) {
                $excludedRuleFullName = $this->classAnnotationMatcher->resolveTagFullyQualifiedName(
                    trim($excludedRule),
                    $node
                );
                if ($excludedRuleFullName === $currentRuleName) {
                    return true;
                }
            }
        }
        return false;
    }
}
