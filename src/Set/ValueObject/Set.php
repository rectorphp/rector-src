<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

use Rector\Set\Contract\SetInterface;

/**
 * @api used by extensions
 */
final readonly class Set implements SetInterface
{
    public function __construct(
        private string $groupName,
        private string $setName,
        private string $setFilePath
    ) {
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getSetName(): string
    {
        return $this->setName;
    }

    public function getSetFilePath(): string
    {
        return $this->setFilePath;
    }
}
