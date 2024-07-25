<?php

declare(strict_types=1);

namespace Rector\ValueObject\Reporting;

use Nette\Utils\Strings;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Contract\Rector\RectorInterface;
use Rector\Parallel\ValueObject\BridgeItem;
use Symplify\EasyParallel\Contract\SerializableInterface;
use Webmozart\Assert\Assert;

final readonly class FileDiff implements SerializableInterface
{
    /**
     * @var string
     * @se https://regex101.com/r/AUPIX4/1
     */
    private const FIRST_LINE_REGEX = '#@@(.*?)(?<' . self::FIRST_LINE_KEY . '>\d+)(.*?)@@#';

    /**
     * @var string
     */
    private const FIRST_LINE_KEY = 'first_line';

    /**
     * @param RectorWithLineChange[] $rectorsWithLineChanges
     */
    public function __construct(
        private string $relativeFilePath,
        private string $diff,
        private string $diffConsoleFormatted,
        private array $rectorsWithLineChanges = []
    ) {
        Assert::allIsInstanceOf($rectorsWithLineChanges, RectorWithLineChange::class);
    }

    public function getDiff(): string
    {
        return $this->diff;
    }

    public function getDiffConsoleFormatted(): string
    {
        return $this->diffConsoleFormatted;
    }

    public function getRelativeFilePath(): string
    {
        return $this->relativeFilePath;
    }

    public function getAbsoluteFilePath(): ?string
    {
        return \realpath($this->relativeFilePath) ?: null;
    }

    /**
     * @return RectorWithLineChange[]
     */
    public function getRectorChanges(): array
    {
        return $this->rectorsWithLineChanges;
    }

    /**
     * @return string[]
     */
    public function getRectorShortClasses(): array
    {
        $rectorShortClasses = [];
        foreach ($this->getRectorClasses() as $rectorClass) {
            $rectorShortClasses[] = (string) Strings::after($rectorClass, '\\', -1);
        }

        return $rectorShortClasses;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    public function getRectorClasses(): array
    {
        $rectorClasses = [];

        foreach ($this->rectorsWithLineChanges as $rectorWithLineChange) {
            $rectorClasses[] = $rectorWithLineChange->getRectorClass();
        }

        return $this->sortClasses($rectorClasses);
    }

    public function getFirstLineNumber(): ?int
    {
        $match = Strings::match($this->diff, self::FIRST_LINE_REGEX);

        // probably some error in diff
        if (! isset($match[self::FIRST_LINE_KEY])) {
            return null;
        }

        return (int) $match[self::FIRST_LINE_KEY] - 1;
    }

    /**
     * @return array{relative_file_path: string, diff: string, diff_console_formatted: string, rectors_with_line_changes: RectorWithLineChange[]}
     */
    public function jsonSerialize(): array
    {
        return [
            BridgeItem::RELATIVE_FILE_PATH => $this->relativeFilePath,
            BridgeItem::DIFF => $this->diff,
            BridgeItem::DIFF_CONSOLE_FORMATTED => $this->diffConsoleFormatted,
            BridgeItem::RECTORS_WITH_LINE_CHANGES => $this->rectorsWithLineChanges,
        ];
    }

    /**
     * @param array<string, mixed> $json
     */
    public static function decode(array $json): self
    {
        $rectorWithLineChanges = [];

        foreach ($json[BridgeItem::RECTORS_WITH_LINE_CHANGES] as $rectorWithLineChangesJson) {
            $rectorWithLineChanges[] = RectorWithLineChange::decode($rectorWithLineChangesJson);
        }

        return new self(
            $json[BridgeItem::RELATIVE_FILE_PATH],
            $json[BridgeItem::DIFF],
            $json[BridgeItem::DIFF_CONSOLE_FORMATTED],
            $rectorWithLineChanges,
        );
    }

    /**
     * @template TType as object
     * @param array<class-string<TType>> $rectorClasses
     * @return array<class-string<TType>>
     */
    private function sortClasses(array $rectorClasses): array
    {
        $rectorClasses = array_unique($rectorClasses);
        sort($rectorClasses);

        return $rectorClasses;
    }
}
