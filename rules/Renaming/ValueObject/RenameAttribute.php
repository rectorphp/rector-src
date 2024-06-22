<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

/**
 * @api
 */
final readonly class RenameAttribute
{
    public function __construct(
        private string $oldAttribute,
        private string $newAttribute
    ) {
    }

    public function getOldAttribute(): string
    {
        return $this->oldAttribute;
    }

    public function getNewAttribute(): string
    {
        return $this->newAttribute;
    }
}
