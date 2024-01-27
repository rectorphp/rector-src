<?php

declare(strict_types=1);

namespace Rector\Console\Formatter;

use Nette\Utils\Strings;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Inspired by @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/src/Differ/DiffConsoleFormatter.php to be
 * used as standalone class, without need to require whole package by Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * @see \Rector\Tests\Console\Formatter\ColorConsoleDiffFormatterTest
 */
final readonly class ColorConsoleDiffFormatter
{
    /**
     * @var string
     * @see https://regex101.com/r/ovLMDF/1
     */
    private const PLUS_START_REGEX = '#^(\+.*)#';

    /**
     * @var string
     * @see https://regex101.com/r/xwywpa/1
     */
    private const MINUT_START_REGEX = '#^(\-.*)#';

    /**
     * @var string
     * @see https://regex101.com/r/CMlwa8/1
     */
    private const AT_START_REGEX = '#^(@.*)#';

    /**
     * @var string
     * @see https://regex101.com/r/qduj2O/2
     */
    private const NEWLINES_REGEX = "#\n#";

    private string $template;

    public function __construct()
    {
        $this->template = sprintf(
            "<comment>    ---------- begin diff ----------</comment>%s%%s%s<comment>    ----------- end diff -----------</comment>\n" ,
            "\n",
            "\n"
        );
    }

    public function format(string $diff): string
    {
        $formatted = $this->formatWithTemplate($diff, $this->template);

        if ($formatted === "---------- begin diff ----------\n----------- end diff -----------") {
            return '';
        }

        // as OS based on formatting
        return str_replace("\n", PHP_EOL, $formatted);
    }

    private function formatWithTemplate(string $diff, string $template): string
    {
        $diff = str_replace("\r\n", "\n", $diff);
        $escapedDiff = OutputFormatter::escape(rtrim($diff));

        $escapedDiffLines = Strings::split($escapedDiff, self::NEWLINES_REGEX);

        // remove description of added + remove; obvious on diffs
        foreach ($escapedDiffLines as $key => $escapedDiffLine) {
            if ($escapedDiffLine === '--- Original') {
                unset($escapedDiffLines[$key]);
            }

            if ($escapedDiffLine === '+++ New') {
                unset($escapedDiffLines[$key]);
            }
        }

        $coloredLines = array_map(function (string $string): string {
            $string = $this->makePlusLinesGreen($string);
            $string = $this->makeMinusLinesRed($string);
            $string = $this->makeAtNoteCyan($string);

            if ($string === ' ') {
                return '';
            }

            return $string;
        }, $escapedDiffLines);

        return sprintf($template, implode("\n", $coloredLines));
    }

    private function makePlusLinesGreen(string $string): string
    {
        return Strings::replace($string, self::PLUS_START_REGEX, '<fg=green>$1</fg=green>');
    }

    private function makeMinusLinesRed(string $string): string
    {
        return Strings::replace($string, self::MINUT_START_REGEX, '<fg=red>$1</fg=red>');
    }

    private function makeAtNoteCyan(string $string): string
    {
        return Strings::replace($string, self::AT_START_REGEX, '<fg=cyan>$1</fg=cyan>');
    }
}
