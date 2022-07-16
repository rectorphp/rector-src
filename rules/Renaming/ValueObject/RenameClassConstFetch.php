<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;
use Rector\Renaming\Contract\RenameClassConstFetchInterface;

final class RenameClassConstFetch implements RenameClassConstFetchInterface
{
    public function __construct(
        private readonly string $oldClass,
        private readonly string $oldConstant,
        private readonly string $newConstant
    ) {
        RectorAssert::className($oldClass);
    }

    public function getOldObjectType(): ObjectType
    {
        return new ObjectType($this->oldClass);
    }

    public function getOldConstant(): string
    {
        return $this->oldConstant;
    }

    public function getNewConstant(): string
    {
        return $this->newConstant;
    }
}
