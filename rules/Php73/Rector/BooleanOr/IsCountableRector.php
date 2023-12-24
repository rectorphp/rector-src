<?php

declare(strict_types=1);

namespace Rector\Php73\Rector\BooleanOr;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Name;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Core\ValueObject\PolyfillPackage;
use Rector\Php71\IsArrayAndDualCheckToAble;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php73\Rector\BinaryOr\IsCountableRector\IsCountableRectorTest
 */
final class IsCountableRector extends AbstractRector implements MinPhpVersionInterface, RelatedPolyfillInterface
{
    public function __construct(
        private readonly IsArrayAndDualCheckToAble $isArrayAndDualCheckToAble,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes is_array + Countable check to is_countable',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
is_array($foo) || $foo instanceof Countable;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
is_countable($foo);
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [BooleanOr::class];
    }

    /**
     * @param BooleanOr $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip()) {
            return null;
        }

        return $this->isArrayAndDualCheckToAble->processBooleanOr($node, 'Countable', 'is_countable');
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::IS_COUNTABLE;
    }

    public function providePolyfillPackage(): string
    {
        return PolyfillPackage::PHP_73;
    }

    private function shouldSkip(): bool
    {
        return ! $this->reflectionProvider->hasFunction(new Name('is_countable'), null);
    }
}
