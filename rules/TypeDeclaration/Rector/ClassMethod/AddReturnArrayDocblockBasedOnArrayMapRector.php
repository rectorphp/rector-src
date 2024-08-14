<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\AddReturnArrayDocblockBasedOnArrayMapRectorTest
 */
final class AddReturnArrayDocblockBasedOnArrayMapRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ReturnAnalyzer $returnAnalyzer,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly TypeFactory $typeFactory,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add @return array docblock based on array_map() return strict type',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function getItems(array $items)
    {
        return array_map(function ($item): int {
            return $item->id;
        }, $items);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return int[]
     */
    public function getItems(array $items)
    {
        return array_map(function ($item): int {
            return $item->id;
        }, $items);
    }
}
CODE_SAMPLE
                )],
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): null|Function_|ClassMethod
    {
        $returnsScoped = $this->betterNodeFinder->findReturnsScoped($node);

        if ($this->hasNonArrayReturnType($node)) {
            return null;
        }

        // nothing to return? skip it
        if ($returnsScoped === []) {
            return null;
        }

        // only returns with expr and no void
        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($node, $returnsScoped)) {
            return null;
        }

        $closureReturnTypes = [];

        foreach ($returnsScoped as $returnScoped) {
            if (! $returnScoped->expr instanceof FuncCall) {
                return null;
            }

            $arrayMapClosure = $this->matchArrayMapClosure($returnScoped->expr);
            if (! $arrayMapClosure instanceof FunctionLike) {
                return null;
            }

            if (! $arrayMapClosure->returnType instanceof Node) {
                return null;
            }

            $closureReturnTypes[] = $this->staticTypeMapper->mapPhpParserNodePHPStanType($arrayMapClosure->returnType);
        }

        $returnType = $this->typeFactory->createMixedPassedOrUnionType($closureReturnTypes);
        $arrayType = new ArrayType(new MixedType(), $returnType);

        $functionLikePhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $hasChanged = $this->phpDocTypeChanger->changeReturnType($node, $functionLikePhpDocInfo, $arrayType);
        if ($hasChanged) {
            return null;
        }

        return $node;
    }

    private function hasNonArrayReturnType(ClassMethod|Function_ $functionLike): bool
    {
        if (! $functionLike->returnType instanceof Identifier) {
            return false;
        }

        return $functionLike->returnType->toLowerString() !== 'array';
    }

    private function matchArrayMapClosure(FuncCall $funcCall): Closure|ArrowFunction|null
    {
        if (! $this->isName($funcCall, 'array_map')) {
            return null;
        }

        if ($funcCall->isFirstClassCallable()) {
            return null;
        }

        // lets infer strict array_map() type
        $firstArg = $funcCall->getArgs()[0];
        if (! $firstArg->value instanceof Closure && ! $firstArg->value instanceof ArrowFunction) {
            return null;
        }

        return $firstArg->value;
    }
}
