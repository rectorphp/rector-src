<?php

declare(strict_types=1);

namespace Rector\Core\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static ApplicationPhase REFACTORING()
 * @method static ApplicationPhase PRINT_SKIP()
 * @method static ApplicationPhase PRINT()
 * @method static ApplicationPhase POST_RECTORS()
 * @method static ApplicationPhase PARSING()
 */
final class ApplicationPhase extends Enum
{
    /**
     * @var string
     */
    private const REFACTORING = 'refactoring';

    /**
     * @var string
     */
    private const PRINT_SKIP = 'printing skipped due error';

    /**
     * @var string
     */
    private const PRINT = 'print';

    /**
     * @var string
     */
    private const POST_RECTORS = 'post rectors';

    /**
     * @var string
     */
    private const PARSING = 'parsing';
}
