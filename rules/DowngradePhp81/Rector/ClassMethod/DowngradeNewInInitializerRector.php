<?php

declare(strict_types=1);

namespace Rector\DowngradePhp81\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\UnionType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/new_in_initializers
 *
 * @see \Rector\Tests\DowngradePhp81\Rector\ClassMethod\DowngradeNewInInitializerRector\DowngradeNewInInitializerRectorTest
 */
final class DowngradeNewInInitializerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace New in initializers', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(
        private Logger $logger = new NullLogger,
    ) {
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(
        private ?Logger $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger;
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $stmts = [];
        foreach ($node->params as $param) {
            if (! $param->default instanceof New_) {
                continue;
            }

            $propertyFetch = new PropertyFetch(new Variable('this'), $param->var->name);
            $coalesce = new Coalesce($param->var, $param->default);
            $stmts[] = new Expression(new Assign($propertyFetch, $coalesce));

            $param->default = $this->nodeFactory->createNull();
            if ($param->type !== null) {
                $param->type = $this->ensureNullableType($param->type);
            }
        }

        $node->stmts = array_merge($stmts, $node->stmts ?? []);

        return $node;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        if (! $this->isName($classMethod, MethodName::CONSTRUCT)) {
            return true;
        }

        foreach ($classMethod->params as $param) {
            if ($param->default instanceof New_ && ! $param->type instanceof IntersectionType) {
                return false;
            }
        }

        return true;
    }

    private function ensureNullableType(Name|Identifier|ComplexType $type): NullableType|UnionType
    {
        if ($type instanceof NullableType) {
            return $type;
        }

        if (! $type instanceof ComplexType) {
            return new NullableType($type);
        }
        
        if ($type instanceof UnionType) {
            if (! $this->hasNull($type)) {
                $type->types[] = new Name('null');
            }

            return $type;
        }

        throw new ShouldNotHappenException();
    }

    private function hasNull(UnionType $unionType): bool
    {
        foreach ($unionType->types as $type) {
            if ($type->toLowerString() === 'null') {
                return true;
            }
        }

        return false;
    }
}
