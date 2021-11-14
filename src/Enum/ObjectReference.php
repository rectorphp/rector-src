<?php

declare(strict_types=1);

namespace Rector\Core\Enum;

/**
 * @see https://github.com/marc-mabe/php-enum
 */
final class ObjectReference extends \MabeEnum\Enum
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
}
