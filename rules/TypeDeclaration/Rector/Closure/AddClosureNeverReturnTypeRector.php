<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Closure;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PHPStan\Analyser\Scope;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\TypeDeclaration\NodeManipulator\AddNeverReturnType;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureNeverReturnTypeRector\AddClosureNeverReturnTypeRectorTest
 */
final class AddClosureNeverReturnTypeRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly AddNeverReturnType $addNeverReturnType
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add "never" return-type for closure that never return anything', [
            new CodeSample(
                <<<'CODE_SAMPLE'
function () {
    throw new InvalidException();
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
function (): never {
    throw new InvalidException();
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

    /**
     * @param Closure $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        return $this->addNeverReturnType->add($node, $scope);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NEVER_TYPE;
    }
}
