<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStan\ScopeFetcher;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeFromTryCatchTypeRector\AddReturnTypeFromTryCatchTypeRectorTest
 */
final class AddReturnTypeFromTryCatchTypeRector extends AbstractRector
{
    public function __construct(
        private TypeComparator $typeComparator,
        private StaticTypeMapper $staticTypeMapper,
    ) {

    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add known type declarations based on first-level try/catch return values',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        try {
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): int
    {
        try {
            return 1;
        } catch (\Exception $e) {
            return 2;
        }
    }
}
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        // better nothing to do
        if ($node->isAbstract()) {
            return null;
        }

        // already known type
        if ($node->returnType instanceof \PhpParser\Node) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        // skip interfaces and traits
        if (! $classReflection->isClass()) {
            return null;
        }

        $tryReturnType = null;
        $catchReturnTypes = [];

        foreach ((array) $node->stmts as $classMethodStmt) {
            if (! $classMethodStmt instanceof Node\Stmt\TryCatch) {
                continue;
            }

            $tryCatch = $classMethodStmt;
            $tryReturnType = $this->matchReturnType($tryCatch);

            foreach ($tryCatch->catches as $catch) {
                $currentCatchType = $this->matchReturnType($catch);

                // each catch must have type
                if (! $currentCatchType instanceof \PHPStan\Type\Type) {
                    return null;
                }

                $catchReturnTypes[] = $currentCatchType;
            }
        }

        if (! $tryReturnType instanceof \PHPStan\Type\Type) {
            return null;
        }

        foreach ($catchReturnTypes as $catchReturnType) {
            if (! $this->typeComparator->areTypesEqual($catchReturnType, $tryReturnType)) {
                return null;
            }
        }

        $returnType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($tryReturnType, TypeKind::RETURN);
        if (! $returnType instanceof \PhpParser\Node) {
            return null;
        }

        $node->returnType = $returnType;
        return $node;
    }

    private function matchReturnType(Node\Stmt\TryCatch|Node\Stmt\Catch_ $tryOrCatch): ?\PHPStan\Type\Type
    {
        foreach ($tryOrCatch->stmts as $stmt) {
            if (! $stmt instanceof Node\Stmt\Return_) {
                continue;
            }

            if (! $stmt->expr instanceof Node\Expr) {
                continue;
            }

            return $this->getType($stmt->expr);
        }

        return null;
    }
}
