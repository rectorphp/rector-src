<?php

declare(strict_types=1);

namespace Rector\Core\Enum;

final class ApplicationPhase extends \MabeEnum\Enum
{
    /**
     * @var string
     */
    public const REFACTORING = 'refactoring';

    /**
     * @var string
     */
    public const PRINT_SKIP = 'printing skipped due error';

    /**
     * @var string
     */
    public const PRINT = 'print';

    /**
     * @var string
     */
    public const POST_RECTORS = 'post rectors';

    /**
     * @var string
     */
    public const PARSING = 'parsing';
}
