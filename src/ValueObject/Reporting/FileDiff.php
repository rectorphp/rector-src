<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject\Reporting;

use Nette\Utils\Strings;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\EasyParallel\Contract\SerializableInterface;

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
     * @var string
     */
    private const KEY_RELATIVE_FILE_PATH = 'relative_file_path';

    /**
     * @var string
     */
    private const KEY_DIFF = 'diff';

    /**
     * @var string
     */
    private const KEY_DIFF_CONSOLE_FORMATTED = 'diff_console_formatted';

    /**
     * @var string
     */
    private const KEY_RECTORS_WITH_LINE_CHANGES = 'rectors_with_line_changes';

    /**
     * @param RectorWithLineChange[] $rectorsWithLineChanges
     */
    public function __construct(
        private string $relativeFilePath,
        private string $diff,
        private string $diffConsoleFormatted,
        private array $rectorsWithLineChanges = []
    ) {
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
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            self::KEY_RELATIVE_FILE_PATH => $this->relativeFilePath,
            self::KEY_DIFF => $this->diff,
            self::KEY_DIFF_CONSOLE_FORMATTED => $this->diffConsoleFormatted,
            self::KEY_RECTORS_WITH_LINE_CHANGES => $this->rectorsWithLineChanges,
        ];
    }

    /**
     * @param array<string, mixed> $json
     */
    public static function decode(array $json): SerializableInterface
    {
        return new self(
            $json[self::KEY_RELATIVE_FILE_PATH],
            $json[self::KEY_DIFF],
            $json[self::KEY_DIFF_CONSOLE_FORMATTED],
            $json[self::KEY_RECTORS_WITH_LINE_CHANGES],
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
