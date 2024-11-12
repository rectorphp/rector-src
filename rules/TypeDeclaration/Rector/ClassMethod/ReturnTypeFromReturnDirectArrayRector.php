<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\ReturnTypeFromReturnDirectArrayRectorTest
 */
final class ReturnTypeFromReturnDirectArrayRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly ReturnTypeInferer $returnTypeInferer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return type from return direct array', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class AddReturnArray
{
    public function getArray()
    {
        return [1, 2, 3];
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class AddReturnArray
{
    public function getArray(): array
    {
        return [1, 2, 3];
    }
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $scope = ScopeFetcher::fetch($node);
        // already has return type, skip
        if ($node->returnType instanceof Node) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return null;
        }

        if (! $this->hasReturnArray($node)) {
            return null;
        }

        $type = $this->returnTypeInferer->inferFunctionLike($node);
        if (! $type->isArray()->yes()) {
            return null;
        }

        $node->returnType = new Identifier('array');

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }

    private function hasReturnArray(ClassMethod|Function_ $functionLike): bool
    {
        $stmts = $functionLike->stmts;

        if (! is_array($stmts)) {
            return false;
        }

        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Return_) {
                continue;
            }

            if (! $stmt->expr instanceof Array_) {
                continue;
            }

            return true;
        }

        return false;
    }
}
