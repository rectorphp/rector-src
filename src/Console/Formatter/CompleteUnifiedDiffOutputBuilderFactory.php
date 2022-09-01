<?php

declare(strict_types=1);

namespace Rector\Core\Console\Formatter;

use Rector\Core\Util\Reflection\PrivatesAccessor;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

/**
 * @api
 * Creates @see UnifiedDiffOutputBuilder with "$contextLines = 1000;"
 */
final class CompleteUnifiedDiffOutputBuilderFactory
{
    public function __construct(
        private PrivatesAccessor $privatesAccessor
    ) {
    }

    /**
     * @api
     */
    public function create(): UnifiedDiffOutputBuilder
    {
        $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder('');
        $this->privatesAccessor->setPrivateProperty($unifiedDiffOutputBuilder, 'contextLines', 10000);
        return $unifiedDiffOutputBuilder;
    }
}
