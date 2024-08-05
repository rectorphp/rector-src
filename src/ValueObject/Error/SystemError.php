<?php

declare(strict_types=1);

namespace Rector\ValueObject\Error;

use Rector\Parallel\ValueObject\BridgeItem;
use Symplify\EasyParallel\Contract\SerializableInterface;

final readonly class SystemError implements SerializableInterface
{
    public function __construct(
        private string $message,
        private string|null $relativeFilePath = null,
        private int|null $line = null,
        private string|null $rectorClass = null
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

    public function getAbsoluteFilePath(): ?string
    {
        return $this->relativeFilePath ? (\realpath($this->relativeFilePath) ?: null) : null;
    }

    /**
     * @return array{
     *     message: string,
     *     relative_file_path: string|null,
     *     absolute_file_path: string|null,
     *     line: int|null,
     *     rector_class: string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            BridgeItem::MESSAGE => $this->message,
            BridgeItem::RELATIVE_FILE_PATH => $this->relativeFilePath,
            BridgeItem::ABSOLUTE_FILE_PATH => $this->getAbsoluteFilePath(),
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
