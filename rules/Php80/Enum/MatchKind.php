<?php

declare(strict_types=1);

namespace Rector\Php80\Enum;

final class MatchKind extends Enum
{
    /**
     * @var string
     */
    private const NORMAL = 'normal';

    /**
     * @var string
     */
    private const ASSIGN = 'assign';

    /**
     * @var string
     */
    private const RETURN = 'return';

    /**
     * @var string
     */
    private const THROW = 'throw';
}
