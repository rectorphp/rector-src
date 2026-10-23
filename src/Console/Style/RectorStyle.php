<?php

declare(strict_types=1);

namespace Rector\Console\Style;

use Entropy\Console\ConsoleTable\ConsoleTable;
use Entropy\Console\Enum\Color;
use Entropy\Console\Output\OutputColorizer;
use Entropy\Console\Output\OutputPrinter;
use Entropy\Console\Output\ProgressBar;

/**
 * Entropy-backed console style, replacing the former symfony/console SymfonyStyle.
 */
final class RectorStyle
{
    public const int VERBOSITY_QUIET = 16;

    public const int VERBOSITY_NORMAL = 32;

    public const int VERBOSITY_VERBOSE = 64;

    public const int VERBOSITY_DEBUG = 256;

    private int $verbosity = self::VERBOSITY_NORMAL;

    private bool $isRecording = false;

    /**
     * @var string[]
     */
    private array $recordedMessages = [];

    public function __construct(
        private readonly OutputPrinter $outputPrinter,
        private readonly OutputColorizer $outputColorizer,
        private readonly ProgressBar $progressBar,
    ) {
    }

    public function setVerbosity(int $verbosity): void
    {
        $this->verbosity = $verbosity;
    }

    /**
     * Collect emitted messages in memory, so tests can assert on them even though the
     * underlying OutputPrinter stays silent during a PHPUnit run.
     *
     * @api used in tests
     */
    public function startRecording(): void
    {
        $this->isRecording = true;
        $this->recordedMessages = [];
    }

    /**
     * @api used in tests
     */
    public function getRecordedOutput(): string
    {
        return implode("\n", $this->recordedMessages);
    }

    public function isVerbose(): bool
    {
        return $this->verbosity >= self::VERBOSITY_VERBOSE;
    }

    public function isDebug(): bool
    {
        return $this->verbosity >= self::VERBOSITY_DEBUG;
    }

    public function writeln(string $message): void
    {
        $this->record($message);

        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->writeln($this->normalizeTags($message));
    }

    public function newLine(int $count = 1): void
    {
        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->newline($count);
    }

    public function title(string $message): void
    {
        $this->record($message);

        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->title($message);
    }

    public function section(string $message): void
    {
        $this->record($message);

        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->section($message);
    }

    public function success(string $message): void
    {
        $this->record($message);

        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->success($message);
    }

    public function warning(string $message): void
    {
        $this->record($message);

        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->warning($message);
    }

    public function note(string $message): void
    {
        $this->record($message);

        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->writeln($this->normalizeTags($message));
    }

    public function error(string $message): void
    {
        $this->record($message);

        // errors are shown even in quiet mode, so failures are never swallowed
        $this->outputPrinter->error($message);
    }

    /**
     * @param string[] $items
     */
    public function listing(array $items): void
    {
        foreach ($items as $item) {
            $this->record($item);
        }

        if ($this->isQuiet()) {
            return;
        }

        $this->outputPrinter->listing($items);
    }

    /**
     * @param string[] $headers
     * @param array<array<string>> $rows
     */
    public function table(array $headers, array $rows): void
    {
        foreach ($rows as $row) {
            $this->record(implode(' ', $row));
        }

        if ($this->isQuiet()) {
            return;
        }

        $consoleTable = new ConsoleTable($this->outputPrinter);
        $consoleTable->render($headers, $rows);
    }

    public function ask(string $question, ?string $default = null): ?string
    {
        return $this->outputPrinter->ask($question, $default);
    }

    public function getErrorStyle(): self
    {
        return $this;
    }

    public function progressStart(int $max): void
    {
        if ($this->isQuiet()) {
            return;
        }

        $this->progressBar->start($max);
    }

    public function progressAdvance(int $step = 1): void
    {
        if ($this->isQuiet()) {
            return;
        }

        $this->progressBar->advance($step);
    }

    private function isQuiet(): bool
    {
        return $this->verbosity <= self::VERBOSITY_QUIET;
    }

    private function record(string $message): void
    {
        if (! $this->isRecording) {
            return;
        }

        $this->recordedMessages[] = $message;
    }

    /**
     * Translate the former symfony/console tags still emitted by the reporters into
     * the smaller tag vocabulary understood by Entropy's OutputColorizer.
     */
    private function normalizeTags(string $text): string
    {
        $text = (string) preg_replace('#<comment>(.*?)</comment>#su', '<fg=yellow>$1</>', $text);
        $text = (string) preg_replace('#<info>(.*?)</info>#su', '<fg=green>$1</>', $text);

        // grey is not handled by OutputColorizer::colorize(), apply it directly
        $text = (string) preg_replace_callback(
            '#<fg=grey>(.*?)</>#su',
            fn (array $matches): string => $this->outputColorizer->color($matches[1], Color::GREY),
            $text
        );

        // normalize explicit closing tags (e.g. </fg=green>) to the generic closing tag
        return (string) preg_replace('#</fg=[a-z]+>#', '</>', $text);
    }
}
