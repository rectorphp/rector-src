<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\ConstFetch\RenameConstantRector\Source;

final class ExternalList
{
    public const FIRST = 'first';

    public const SECOND = 'second';

    public const VALUES = [self::FIRST, self::SECOND];
}
