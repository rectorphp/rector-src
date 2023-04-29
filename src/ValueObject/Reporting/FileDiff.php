<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject\Reporting;

use Nette\Utils\Strings;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Parallel\ValueObject\Name;
use Symplify\EasyParallel\Contract\SerializableInterface;
use Webmozart\Assert\Assert;

final class FileDiff implements SerializableInterface
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
        private readonly string $relativeFilePath,
        private readonly string $diff,
        private readonly bool $isConsoleFormatted,
        private readonly array $rectorsWithLineChanges = []
    ) {
        Assert::allIsInstanceOf($rectorsWithLineChanges, RectorWithLineChange::class);
    }

    public function getDiff(): string
    {
        return $this->diff;
    }

    public function isConsoleFormatted(): bool {
        return $this->isConsoleFormatted;
    }

    public function getRelativeFilePath(): string
    {
        return $this->relativeFilePath;
    }

    /**
     * @return RectorWithLineChange[]
     */
    public function getRectorChanges(): array
    {
        return $this->rectorsWithLineChanges;
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
     * @return array{relative_file_path: string, diff: string, is_console_formatted: bool, rectors_with_line_changes: RectorWithLineChange[]}
     */
    public function jsonSerialize(): array
    {
        return [
            Name::RELATIVE_FILE_PATH => $this->relativeFilePath,
            Name::DIFF => $this->diff,
            Name::DIFF_CONSOLE_FORMATTED => $this->isConsoleFormatted,
            Name::RECTORS_WITH_LINE_CHANGES => $this->rectorsWithLineChanges,
        ];
    }

    /**
     * @param array<string, mixed> $json
     */
    public static function decode(array $json): SerializableInterface
    {
        $rectorWithLineChanges = [];

        foreach ($json[Name::RECTORS_WITH_LINE_CHANGES] as $rectorWithLineChangesJson) {
            $rectorWithLineChanges[] = RectorWithLineChange::decode($rectorWithLineChangesJson);
        }

        return new self(
            $json[Name::RELATIVE_FILE_PATH],
            $json[Name::DIFF],
            $json[Name::DIFF_CONSOLE_FORMATTED],
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
