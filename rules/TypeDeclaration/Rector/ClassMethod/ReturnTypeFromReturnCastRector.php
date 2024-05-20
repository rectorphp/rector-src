<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\UnionType;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\ReturnTypeFromReturnCastRectorTest
 */
final class ReturnTypeFromReturnCastRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly ReturnTypeInferer $returnTypeInferer,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return type to function like with return cast', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function action($param)
    {
        try {
            return (array) $param;
        } catch (Exception $exception) {
            // some logging
            throw $exception;
        }
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function action($param): array
    {
        try {
            return (array) $param;
        } catch (Exception $exception) {
            // some logging
            throw $exception;
        }
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
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node->returnType !== null) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return null;
        }

        $hasNonCastReturn = (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
            $node,
            static fn (Node $subNode): bool => $subNode instanceof Return_ && ! $subNode->expr instanceof Cast
        );

        if ($hasNonCastReturn) {
            return null;
        }

        $returnType = $this->returnTypeInferer->inferFunctionLike($node);
        if ($returnType instanceof UnionType) {
            return null;
        }

        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
        if (! $returnTypeNode instanceof Identifier) {
            return null;
        }

        if (! in_array($returnTypeNode->toString(), ['string', 'int', 'float', 'bool', 'array', 'object'], true)) {
            return null;
        }

        $node->returnType = $returnTypeNode;
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }
}
