<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Closure;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\NodeManipulator\AddUnionReturnType;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector\AddClosureUnionReturnTypeRectorTest
 */
final class AddClosureUnionReturnTypeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly AddUnionReturnType $addUnionReturnType
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add union return type on closure', [
            new CodeSample(
                <<<'CODE_SAMPLE'
function () {
    if (rand(0, 1)) {
        return 1;
    }

    return 'one';
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
function (): int|string {
    if (rand(0, 1)) {
        return 1;
    }

    return 'one';
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Closure::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULLABLE_TYPE;
    }

    /**
     * @param Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->returnType instanceof Node) {
            return null;
        }

        return $this->addUnionReturnType->add($node);
    }
}
