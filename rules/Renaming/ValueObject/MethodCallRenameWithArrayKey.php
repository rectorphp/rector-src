<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Renaming\Contract\MethodCallRenameInterface;
use Rector\Validation\RectorAssert;

final readonly class MethodCallRenameWithArrayKey implements MethodCallRenameInterface
{
    public function __construct(
        private string $class,
        private string $oldMethod,
        private string $newMethod,
        private mixed $arrayKey
    ) {
        RectorAssert::className($class);
        RectorAssert::methodName($oldMethod);
        RectorAssert::methodName($newMethod);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getOldMethod(): string
    {
        return $this->oldMethod;
    }

    public function getNewMethod(): string
    {
        return $this->newMethod;
    }

    /**
     * @return mixed
     */
    public function getArrayKey()
    {
        return $this->arrayKey;
    }
}
