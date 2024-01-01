<?php

declare(strict_types=1);

namespace Rector\ValueObject\Error;

use Rector\Parallel\ValueObject\BridgeItem;
use Symplify\EasyParallel\Contract\SerializableInterface;

final class SystemError implements SerializableInterface
{
    public function __construct(
        private readonly string $message,
        private readonly string|null $relativeFilePath = null,
        private readonly int|null $line = null,
        private readonly string|null $rectorClass = null
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFile(): string|null
    {
        return $this->relativeFilePath;
    }

    public function getLine(): int|null
    {
        return $this->line;
    }

    public function getRelativeFilePath(): ?string
    {
        return $this->relativeFilePath;
    }

    /**
     * @return array{message: string, relative_file_path: string|null, line: int|null, rector_class: string|null}
     */
    public function jsonSerialize(): array
    {
        return [
            BridgeItem::MESSAGE => $this->message,
            BridgeItem::RELATIVE_FILE_PATH => $this->relativeFilePath,
            BridgeItem::LINE => $this->line,
            BridgeItem::RECTOR_CLASS => $this->rectorClass,
        ];
    }

    /**
     * @param mixed[] $json
     */
    public static function decode(array $json): self
    {
        return new self(
            $json[BridgeItem::MESSAGE],
            $json[BridgeItem::RELATIVE_FILE_PATH],
            $json[BridgeItem::LINE],
            $json[BridgeItem::RECTOR_CLASS]
        );
    }

    public function getRectorClass(): ?string
    {
        return $this->rectorClass;
    }
}
