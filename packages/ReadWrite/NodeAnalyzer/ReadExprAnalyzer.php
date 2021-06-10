<?php

declare(strict_types=1);

namespace Rector\ReadWrite\NodeAnalyzer;

use PhpParser\Node\Expr;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\ReadWrite\Contract\ReadNodeAnalyzerInterface;

final class ReadExprAnalyzer
{
    /**
     * @param ReadNodeAnalyzerInterface[] $readNodeAnalyzers
     */
    public function __construct(
        private array $readNodeAnalyzers
    ) {
    }

    /**
     * Is the value read or used for read purpose (at least, not only)
     */
    public function isExprRead(Expr $expr): bool
    {
        foreach ($this->readNodeAnalyzers as $readNodeAnalyzer) {
            if (! $readNodeAnalyzer->supports($expr)) {
                continue;
            }

            return $readNodeAnalyzer->isRead($expr);
        }

        throw new NotImplementedYetException($expr::class);
    }
}
