<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Type;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersion;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer\StrictScalarReturnTypeAnalyzer;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\ReturnTypeFromStrictScalarReturnExprRectorTest
 */
final class ReturnTypeFromStrictScalarReturnExprRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly StrictScalarReturnTypeAnalyzer $strictScalarReturnTypeAnalyzer,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change return type based on strict scalar returns - string, int, float or bool', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($value)
    {
        if ($value) {
            return 'yes';
        }

        return 'no';
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($value): string
    {
        if ($value) {
            return 'yes';
        }

        return 'no';
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

        $scalarReturnType = $this->strictScalarReturnTypeAnalyzer->matchAlwaysScalarReturnType($node, $scope);
        if (! $scalarReturnType instanceof Type) {
            return null;
        }

        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($scalarReturnType, TypeKind::RETURN);
        if (! $returnTypeNode instanceof Node) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return null;
        }

        $node->returnType = $returnTypeNode;
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }
}
