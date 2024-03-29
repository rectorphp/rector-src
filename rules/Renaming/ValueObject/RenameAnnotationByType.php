<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Renaming\Contract\RenameAnnotationInterface;
use Rector\Validation\RectorAssert;

final readonly class RenameAnnotationByType implements RenameAnnotationInterface
{
    public function __construct(
        private string $type,
        private string $oldAnnotation,
        private string $newAnnotation
    ) {
        RectorAssert::className($type);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->type);
    }

    public function getOldAnnotation(): string
    {
        return $this->oldAnnotation;
    }

    public function getNewAnnotation(): string
    {
        return $this->newAnnotation;
    }
}
