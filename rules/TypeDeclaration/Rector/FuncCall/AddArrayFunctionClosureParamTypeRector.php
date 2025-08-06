<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Type\Constant\ConstantArrayType;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\AddArrayFunctionClosureParamTypeRectorTest
 */
final class AddArrayFunctionClosureParamTypeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add array_filter() function closure param type, based on passed iterable', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$items = [1, 2, 3];
$result = array_filter($items, fn ($item) => $item > 1);
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
$items = [1, 2, 3];
$result = array_filter($items, fn (int $item) => $item > 1
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'array_filter')) {
            return null;
        }

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $firstArgExpr = $node->getArgs()[1]
            ->value;
        if (! $firstArgExpr instanceof ArrowFunction && ! $firstArgExpr instanceof Closure) {
            return null;
        }

        $arrowFunction = $firstArgExpr;
        $arrowFunctionParam = $arrowFunction->getParams()[0];

        // param is known already
        if ($arrowFunctionParam->type instanceof Node) {
            return null;
        }

        $passedExprType = $this->getType($node->getArgs()[0]->value);
        if ($passedExprType instanceof ConstantArrayType) {
            $singlePassedExprType = $passedExprType->getItemType();

            $paramType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($singlePassedExprType, TypeKind::PARAM);

            if (! $paramType instanceof Node) {
                return null;
            }

            $arrowFunctionParam->type = $paramType;

            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }
}
