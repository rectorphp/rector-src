<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Renaming\Contract\RenameClassConstFetchInterface;
use Rector\Validation\RectorAssert;

final readonly class RenameClassConstFetch implements RenameClassConstFetchInterface
{
    public function __construct(
        private string $oldClass,
        private string $oldConstant,
        private string $newConstant
    ) {
        RectorAssert::className($oldClass);
        RectorAssert::constantName($oldConstant);
        RectorAssert::constantName($newConstant);
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
