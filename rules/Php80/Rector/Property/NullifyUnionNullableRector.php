<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Property\NullifyUnionNullableRector\NullifyUnionNullableRectorTest
 */
final class NullifyUnionNullableRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes already typed Type|null to ?Type',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{

    private null|stdClass $property;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{

    private ?stdClass $property;
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
        return [
            Property::class,
            Param::class,
            ClassMethod::class,
            Closure::class,
            Function_::class,
            ArrowFunction::class,
        ];
    }

    /**
     * @param Property|Param|ClassMethod|Closure|Function_|ArrowFunction $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Property || $node instanceof Param) {
            return $this->processNullableParamPropertyType($node);
        }

        return $this->processNullableFunctionLikeReturnType($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::UNION_TYPES;
    }

    private function resolveNullableType(?Node $node): ?Node
    {
        if (! $node instanceof Node) {
            return null;
        }

        if ($node instanceof NullableType) {
            return null;
        }

        if (! $node instanceof UnionType) {
            return null;
        }

        $types = $node->types;
        if (count($types) > 2) {
            return null;
        }

        $node->types = array_values($node->types);
        $firstType = $node->types[0];
        $secondType = $node->types[1];

        if (! $this->areBothValidNullableType($firstType, $secondType)) {
            return null;
        }

        /** @var Identifier|Name $firstType */
        if ($firstType->toString() === 'null') {
            return $secondType;
        }

        /** @var Identifier|Name $secondType */
        if ($secondType->toString() === 'null') {
            return $firstType;
        }

        return null;
    }

    private function areBothValidNullableType(Node $firstType, Node $secondType): bool
    {
        if ($firstType instanceof Identifier || $firstType instanceof Name) {
            return $secondType instanceof Identifier || $secondType instanceof Name;
        }

        if ($secondType instanceof Identifier || $secondType instanceof Name) {
            return $firstType instanceof Identifier || $firstType instanceof Name;
        }

        return false;
    }

    private function processNullableParamPropertyType(Param|Property $node): null|Param|Property
    {
        $nullableType = $this->resolveNullableType($node->type);

        if (! $nullableType instanceof Node) {
            return null;
        }

        $node->type = new NullableType($nullableType);
        return $node;
    }

    private function processNullableFunctionLikeReturnType(
        ClassMethod|Closure|Function_|ArrowFunction $functionLike
    ): ?FunctionLike {
        $nullableType = $this->resolveNullableType($functionLike->returnType);

        if (! $nullableType instanceof Node) {
            return null;
        }

        $functionLike->returnType = new NullableType($nullableType);
        return $functionLike;
    }
}
