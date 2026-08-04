<?php
declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source;

final class SomeObjectWithArrayArgument
{
    public function __construct(
        public string|array|SomeExternalObject|null $argument
    ) {
    }

    public function withArray(array $argument): void
    {
    }

    public function withCallable(callable $argument): void
    {
    }

    public static function staticWithArray(array $argument): void
    {
    }
}
