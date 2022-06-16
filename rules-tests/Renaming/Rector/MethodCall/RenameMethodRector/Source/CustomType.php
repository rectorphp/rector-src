<?php declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source;

final class CustomType
{
    public function notify(): int
    {
        return 5;
    }
}
