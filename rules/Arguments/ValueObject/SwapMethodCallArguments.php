<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class SwapMethodCallArguments
{
    /**
     * @param array<int, int> $order
     */
    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private readonly array $order,
    ) {
        RectorAssert::className($class);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array<int, int>
     */
    public function getOrder(): array
    {
        return $this->order;
    }
}
