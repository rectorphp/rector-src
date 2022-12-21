<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\TypeMapper\UnionTypeMapper;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Property\NullifyUnionNullableRector\NullifyUnionNullableRectorTest
 */
final class NullifyUnionNullableRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly UnionTypeMapper $unionTypeMapper
    ) {
    }

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
            return $this->processNullablePropertyParamType($node);
        }

        return $this->processNullableFunctionLikeReturnType($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::UNION_TYPES;
    }

    private function resolveNullableType(?Node $node): ?NullableType
    {
        if (! $node instanceof Node) {
            return null;
        }

        if (! $node instanceof UnionType) {
            return null;
        }

        $nullableType = $this->unionTypeMapper->resolveTypeWithNullablePHPParserUnionType($node);
        if (! $nullableType instanceof NullableType) {
            return null;
        }

        return $nullableType;
    }

    private function processNullablePropertyParamType(Property|Param $node): null|Property|Param
    {
        $nullableType = $this->resolveNullableType($node->type);

        if (! $nullableType instanceof NullableType) {
            return null;
        }

        $node->type = $nullableType;
        return $node;
    }

    private function processNullableFunctionLikeReturnType(
        ClassMethod|Closure|Function_|ArrowFunction $functionLike
    ): ?FunctionLike {
        $nullableType = $this->resolveNullableType($functionLike->returnType);

        if (! $nullableType instanceof NullableType) {
            return null;
        }

        $functionLike->returnType = $nullableType;
        return $functionLike;
    }
}
