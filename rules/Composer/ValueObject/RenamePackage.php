<?php

declare(strict_types=1);

namespace Rector\Composer\ValueObject;

final class RenamePackage
{
    public function __construct(
        private readonly string $oldPackageName,
        private readonly string $newPackageName
    ) {
    }

    public function getOldPackageName(): string
    {
        return $this->oldPackageName;
    }

    public function getNewPackageName(): string
    {
        return $this->newPackageName;
    }
}
