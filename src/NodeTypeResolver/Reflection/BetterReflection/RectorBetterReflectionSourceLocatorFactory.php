<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Reflection\BetterReflection;

use PHPStan\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use PHPStan\BetterReflection\SourceLocator\Type\MemoizingSourceLocator;
use PHPStan\Reflection\BetterReflection\BetterReflectionSourceLocatorFactory;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocator\IntermediateSourceLocator;

/**
 * @api used on phpstan config factory
 */
final class RectorBetterReflectionSourceLocatorFactory
{
    public function __construct(
        private readonly BetterReflectionSourceLocatorFactory $betterReflectionSourceLocatorFactory,
        private readonly IntermediateSourceLocator $intermediateSourceLocator
    ) {
    }

    public function create(): MemoizingSourceLocator
    {
        $phpStanSourceLocator = $this->betterReflectionSourceLocatorFactory->create();

        // make PHPStan first source locator, so we avoid parsing every single file - huge performance hit!
        $aggregateSourceLocator = new AggregateSourceLocator([$phpStanSourceLocator, $this->intermediateSourceLocator]);

        // important for cache, but should rebuild for tests
        return new MemoizingSourceLocator($aggregateSourceLocator);
    }
}
