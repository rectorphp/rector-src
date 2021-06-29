<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use PhpParser\Node\Expr;
use PHPStan\Type\ObjectType;
use Rector\Arguments\Contract\ReplaceArgumentDefaultValueInterface;

final class ReplaceArgumentDefaultValue implements ReplaceArgumentDefaultValueInterface
{
    /**
     * @param class-string $class
     */
    public function __construct(
        private string $class,
        private string $method,
        private int $position,
        private Expr $valueBefore,
        private Expr $valueAfter
    ) {
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getValueBefore(): Expr
    {
        return $this->valueBefore;
    }

    public function getValueAfter(): Expr
    {
        return $this->valueAfter;
    }
}
