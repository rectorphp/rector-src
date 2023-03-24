<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output\Gitlab;

use Nette\Utils\Strings;
use Rector\ChangesReporting\Annotation\RectorsChangelogResolver;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\ChangesReporting\Output\Gitlab\CodeQuality\Line;
use Rector\ChangesReporting\Output\Gitlab\CodeQuality\Location;
use Rector\ChangesReporting\Output\Gitlab\CodeQuality\Report;
use Rector\ChangesReporting\Output\Gitlab\CodeQuality\Severity;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Violet\StreamingJsonEncoder\BufferJsonEncoder;

final class GitlabOutputFormatter implements OutputFormatterInterface
{
    /**
     * @var string
     */
    public const NAME = 'gitlab';

    public function __construct(
        private readonly RectorsChangelogResolver $rectorsChangelogResolver,
        private readonly Severity $errorsSeverity = Severity::CRITICAL,
        private readonly Severity $diffSeverity = Severity::BLOCKER,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function report(ProcessResult $processResult, Configuration $configuration): void
    {
        $bufferJsonEncoder = new BufferJsonEncoder(function () use ($processResult) {
            foreach ($processResult->getErrors() as $systemError) {
                $path = $systemError->getRelativeFilePath();
                $beginLine = $systemError->getLine();

                yield new Report(
                    $systemError->getMessage(),
                    $this->errorsSeverity,
                    $path !== null && $beginLine !== null ? new Location($path, new Line($beginLine)) : null
                );
            }

            foreach ($processResult->getFileDiffs() as $fileDiff) {
                $beginLine = $fileDiff->getFirstLineNumber();

                $description = <<<"TEXT"
                Applied rules:
                    %s
                TEXT;

                $description = sprintf($description, implode("\n", $this->createRectorChangelogLines($fileDiff)));


                yield new Report(
                    $description,
                    $this->diffSeverity,
                    new Location($fileDiff->getRelativeFilePath(), $beginLine ? new Line($beginLine) : null)
                );
            }
        });

        foreach ($bufferJsonEncoder as $error) {
            echo $error;
        }
    }



    /**
     * @return string[]
     */
    private function createRectorChangelogLines(FileDiff $fileDiff): array
    {
        $rectorsChangelogs = $this->rectorsChangelogResolver->resolveIncludingMissing($fileDiff->getRectorClasses());

        $rectorsChangelogsLines = [];
        foreach ($rectorsChangelogs as $rectorClass => $changelog) {
            $rectorShortClass = (string) Strings::after($rectorClass, '\\', -1);
            $rectorsChangelogsLines[] = $changelog === null ? $rectorShortClass : $rectorShortClass . ' (' . $changelog . ')';
        }

        return $rectorsChangelogsLines;
    }
}
