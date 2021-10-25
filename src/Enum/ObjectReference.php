<?php

declare(strict_types=1);

namespace Rector\Core\Enum;

use MyCLabs\Enum\Enum;

final class ObjectReference extends Enum
{
    /**
     * @var string
     */
    public const SELF = 'self';

    /**
     * @var string
     */
    public const PARENT = 'parent';

    /**
     * @var string
     */
    public const STATIC = 'static';

    /**
     * @var string[]
     */
    public const REFERENCES = [self::STATIC, self::PARENT, self::SELF];
}
