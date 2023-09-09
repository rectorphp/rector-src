<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output;

use Nette\Utils\Json;
use Rector\ChangesReporting\Annotation\RectorsChangelogResolver;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Parallel\ValueObject\Bridge;

final class JsonOutputFormatter implements OutputFormatterInterface
{
    /**
     * @var string
     */
    public const NAME = 'json';

    public function __construct(
        private readonly RectorsChangelogResolver $rectorsChangelogResolver
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function report(ProcessResult $processResult, Configuration $configuration): void
    {
        $errorsJson = [
            'totals' => [
                'changed_files' => count($processResult->getFileDiffs()),
            ],
        ];

        $fileDiffs = $processResult->getFileDiffs();
        ksort($fileDiffs);
        foreach ($fileDiffs as $fileDiff) {
            $relativeFilePath = $fileDiff->getRelativeFilePath();

            $appliedRectorsWithChangelog = $this->rectorsChangelogResolver->resolve($fileDiff->getRectorClasses());

            $errorsJson[Bridge::FILE_DIFFS][] = [
                'file' => $relativeFilePath,
                'diff' => $fileDiff->getDiff(),
                'applied_rectors' => $fileDiff->getRectorClasses(),
                'applied_rectors_with_changelog' => $appliedRectorsWithChangelog,
            ];

            // for Rector CI
            $errorsJson['changed_files'][] = $relativeFilePath;
        }

        $systemErrors = $processResult->getSystemErrors();
        $errorsJson['totals']['errors'] = count($systemErrors);

        $errorsData = $this->createErrorsData($systemErrors);
        if ($errorsData !== []) {
            $errorsJson['errors'] = $errorsData;
        }

        $json = Json::encode($errorsJson, Json::PRETTY);
        echo $json . PHP_EOL;
    }

    /**
     * @param SystemError[] $errors
     * @return mixed[]
     */
    private function createErrorsData(array $errors): array
    {
        $errorsData = [];

        foreach ($errors as $error) {
            $errorDataJson = [
                'message' => $error->getMessage(),
                'file' => $error->getRelativeFilePath(),
            ];

            if ($error->getRectorClass() !== null) {
                $errorDataJson['caused_by'] = $error->getRectorClass();
            }

            if ($error->getLine() !== null) {
                $errorDataJson['line'] = $error->getLine();
            }

            $errorsData[] = $errorDataJson;
        }

        return $errorsData;
    }
}
