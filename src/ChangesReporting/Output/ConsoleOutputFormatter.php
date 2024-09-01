<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output;

use Nette\Utils\Strings;
use PHPStan\File\ParentDirectoryRelativePathHelper;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\ProcessResult;
use Rector\ValueObject\Reporting\FileDiff;
use ReflectionClass;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class ConsoleOutputFormatter implements OutputFormatterInterface
{
    /**
     * @var string
     */
    public const NAME = 'console';

    /**
     * @var string
     * @see https://regex101.com/r/q8I66g/1
     */
    private const ON_LINE_REGEX = '# on line #';

    public function __construct(
        private SymfonyStyle $symfonyStyle,
    ) {
    }

    public function report(ProcessResult $processResult, Configuration $configuration): void
    {
        if ($configuration->shouldShowDiffs()) {
            $this->reportFileDiffs($processResult->getFileDiffs(), $configuration->isReportingWithRealPath());
        }

        $this->reportErrors($processResult->getSystemErrors(), $configuration->isReportingWithRealPath());

        if ($processResult->getSystemErrors() !== []) {
            return;
        }

        // to keep space between progress bar and success message
        if ($configuration->shouldShowProgressBar() && $processResult->getFileDiffs() === []) {
            $this->symfonyStyle->newLine();
        }

        $message = $this->createSuccessMessage($processResult, $configuration);
        $this->symfonyStyle->success($message);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param FileDiff[] $fileDiffs
     */
    private function reportFileDiffs(array $fileDiffs, bool $absoluteFilePath): void
    {
        if (count($fileDiffs) <= 0) {
            return;
        }

        // normalize
        ksort($fileDiffs);
        $message = sprintf('%d file%s with changes', count($fileDiffs), count($fileDiffs) === 1 ? '' : 's');

        $this->symfonyStyle->title($message);

        $i = 0;
        foreach ($fileDiffs as $fileDiff) {
            $filePath = $absoluteFilePath
                ? ($fileDiff->getAbsoluteFilePath() ?? '')
                : $fileDiff->getRelativeFilePath();

            // append line number for faster file jump in diff
            $firstLineNumber = $fileDiff->getFirstLineNumber();
            if ($firstLineNumber !== null) {
                $filePath .= ':' . $firstLineNumber;
            }

            $filePathWithUrl = $this->addEditorUrl(
                $filePath,
                $fileDiff->getAbsoluteFilePath(),
                $fileDiff->getRelativeFilePath(),
                (string) $fileDiff->getFirstLineNumber()
            );

            $message = sprintf('<options=bold>%d) %s</>', ++$i, $filePathWithUrl);

            $this->symfonyStyle->writeln($message);
            $this->symfonyStyle->newLine();
            $this->symfonyStyle->writeln($fileDiff->getDiffConsoleFormatted());

            if ($fileDiff->getRectorChanges() !== []) {
                $relativePathHelper = new ParentDirectoryRelativePathHelper(getcwd());
                $this->symfonyStyle->writeln('<options=underscore>Applied rules:</>');
                $appliedRules = [];
                foreach ($fileDiff->getRectorClasses() as $rectorClass) {
                    $appliedRuleName = (string) Strings::after($rectorClass, '\\', -1);
                    $reflectionClass = new ReflectionClass($rectorClass);
                    $absolutePath = $reflectionClass->getFileName();
                    if ($absolutePath !== false) {
                        $relativePath = $relativePathHelper->getRelativePath($absolutePath);
                        $appliedRuleNameWithUrl = $this->addEditorUrl(
                            $appliedRuleName,
                            $absolutePath,
                            $relativePath,
                            '0'
                        );
                        $appliedRules[] = $appliedRuleNameWithUrl;
                    } else {
                        $appliedRules[] = $appliedRuleName;
                    }
                }

                $this->symfonyStyle->listing($appliedRules);
                $this->symfonyStyle->newLine();
            }
        }
    }

    /**
     * @param SystemError[] $errors
     */
    private function reportErrors(array $errors, bool $absoluteFilePath): void
    {
        foreach ($errors as $error) {
            $errorMessage = $error->getMessage();
            $errorMessage = $this->normalizePathsToRelativeWithLine($errorMessage);

            $filePath = $absoluteFilePath ? $error->getAbsoluteFilePath() : $error->getRelativeFilePath();

            $message = sprintf(
                'Could not process %s%s, due to: %s"%s".',
                $filePath !== null ? '"' . $filePath . '" file' : 'some files',
                $error->getRectorClass() !== null ? ' by "' . $error->getRectorClass() . '"' : '',
                PHP_EOL,
                $errorMessage
            );

            if ($error->getLine() !== null) {
                $message .= ' On line: ' . $error->getLine();
            }

            $this->symfonyStyle->error($message);
        }
    }

    private function normalizePathsToRelativeWithLine(string $errorMessage): string
    {
        $regex = '#' . preg_quote(getcwd(), '#') . '/#';
        $errorMessage = Strings::replace($errorMessage, $regex);
        return Strings::replace($errorMessage, self::ON_LINE_REGEX);
    }

    private function createSuccessMessage(ProcessResult $processResult, Configuration $configuration): string
    {
        $changeCount = count($processResult->getFileDiffs());

        if ($changeCount === 0) {
            return 'Rector is done!';
        }

        return sprintf(
            '%d file%s %s by Rector',
            $changeCount,
            $changeCount > 1 ? 's' : '',
            $configuration->isDryRun() ? 'would have been changed (dry-run)' : ($changeCount === 1 ? 'has' : 'have') . ' been changed'
        );
    }

    private function addEditorUrl(
        string $filePath,
        ?string $absoluteFilePath,
        ?string $relativeFilePath,
        ?string $lineNumber,
    ): string {
        $editorUrl = SimpleParameterProvider::provideStringParameter(Option::EDITOR_URL, '');
        if ($editorUrl !== '') {
            $editorUrl = str_replace(
                ['%file%', '%relFile%', '%line%'],
                [$absoluteFilePath, $relativeFilePath, $lineNumber],
                $editorUrl,
            );
            $filePath = '<href=' . OutputFormatter::escape($editorUrl) . '>' . $filePath . '</>';
        }

        return $filePath;
    }
}
