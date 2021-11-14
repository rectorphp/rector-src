<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\IntersectionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php81\Rector\FunctionLike\IntersectionTypesRector\IntersectionTypesRectorTest
 */
final class IntersectionTypesRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change docs to intersection types, where possible (properties are covered by TypedPropertyRector (@todo))',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @param string&int $types
     */
    public function process($types)
    {
    }
}
CODE_SAMPLE

                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function process(string&int $types)
    {
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
        return [ArrowFunction::class, Closure::class, ClassMethod::class, Function_::class];
    }

    /**
     * @param ArrowFunction|Closure|ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $this->refactorParamTypes($node, $phpDocInfo);
        // $this->refactorReturnType($node, $phpDocInfo);

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::INTERSECTION_TYPES;
    }

    private function refactorParamTypes(
        ArrowFunction|Closure|ClassMethod|Function_ $functionLike,
        PhpDocInfo $phpDocInfo
    ): void {
        foreach ($functionLike->params as $param) {
            if ($param->type !== null) {
                continue;
            }

            /** @var string $paramName */
            $paramName = $this->getName($param->var);
            $paramType = $phpDocInfo->getParamType($paramName);

            if (! $paramType instanceof IntersectionType) {
                continue;
            }

            $phpParserIntersectionType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $paramType,
                TypeKind::PARAM()
            );

            if (! $phpParserIntersectionType instanceof Node\IntersectionType) {
                continue;
            }

            $param->type = $phpParserIntersectionType;
        }
    }
}
