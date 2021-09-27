<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\VariadicPlaceholder;

final class ArgsAnalyzer
{
    /**
     * @param Arg[]|VariadicPlaceholder[] $args
     */
    public function isArgInstanceInArgsPosition(array $args, int $position): bool
    {
        if (! isset($args[$position])) {
            return false;
        }

        return $args[$position] instanceof Arg;
    }
}
