<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source;

/**
 * @method array fetchData()
 */
final class MagicMethodApiClient
{
    /**
     * @deprecated use fetchData() instead
     */
    public function getData(): array
    {
        return [];
    }

    public function __call(string $name, array $arguments): array
    {
        return [];
    }
}
