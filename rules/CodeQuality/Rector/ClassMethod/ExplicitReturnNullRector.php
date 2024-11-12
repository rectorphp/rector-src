<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PHPStan\Type\NullType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\ExplicitReturnNullRectorTest
 */
final class ExplicitReturnNullRector extends AbstractRector
{
    public function __construct(
        private readonly SilentVoidResolver $silentVoidResolver,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly TypeFactory $typeFactory,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly ReturnTypeInferer $returnTypeInferer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add explicit return null to method/function that returns a value, but missed main return',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return string|void
     */
    public function run(int $number)
    {
        if ($number > 50) {
            return 'yes';
        }
    }
}
CODE_SAMPLE

                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return string|null
     */
    public function run(int $number)
    {
        if ($number > 50) {
            return 'yes';
        }

        return null;
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // known return type, nothing to improve
        if ($node->returnType instanceof Node) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        $returnType = $this->returnTypeInferer->inferFunctionLike($node);
        if (! $returnType instanceof UnionType) {
            return null;
        }

        $hasChanged = false;
        $this->traverseNodesWithCallable((array) $node->stmts, static function (Node $node) use (
            &$hasChanged
        ): int|null|Return_ {
            if ($node instanceof Class_ || $node instanceof Function_ || $node instanceof Closure) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($node instanceof Return_ && ! $node->expr instanceof Expr) {
                $hasChanged = true;
                $node->expr = new ConstFetch(new Name('null'));
                return $node;
            }

            return null;
        });

        if (! $this->silentVoidResolver->hasSilentVoid($node)) {
            if ($hasChanged) {
                $this->transformDocUnionVoidToUnionNull($node);
                return $node;
            }

            return null;
        }

        $node->stmts[] = new Return_(new ConstFetch(new Name('null')));

        $this->transformDocUnionVoidToUnionNull($node);

        return $node;
    }

    private function transformDocUnionVoidToUnionNull(ClassMethod|Function_ $node): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $returnType = $phpDocInfo->getReturnType();
        if (! $returnType instanceof UnionType) {
            return;
        }

        $newTypes = [];
        $hasChanged = false;
        foreach ($returnType->getTypes() as $type) {
            if ($type->isVoid()->yes()) {
                $type = new NullType();
                $hasChanged = true;
            }

            $newTypes[] = $type;
        }

        if (! $hasChanged) {
            return;
        }

        $type = $this->typeFactory->createMixedPassedOrUnionTypeAndKeepConstant($newTypes);
        if (! $type instanceof UnionType) {
            return;
        }

        $this->phpDocTypeChanger->changeReturnType($node, $phpDocInfo, $type);
    }
}
