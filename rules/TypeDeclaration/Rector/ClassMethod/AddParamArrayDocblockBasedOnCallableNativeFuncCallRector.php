<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\AddParamArrayDocblockBasedOnCallableNativeFuncCallRectorTest
 */
final class AddParamArrayDocblockBasedOnCallableNativeFuncCallRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add param array docblock based on callable native function call', [
            new CodeSample(
                <<<'CODE_SAMPLE'
function process(array $items): void
{
	array_walk($items, function (stdClass $item) {
		echo $item->value;
    });
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
/**
 * @param stdClass[] $items
 */
function process(array $items): void
{
	array_walk($items, function (stdClass $item) {
		echo $item->value;
    });
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): null|ClassMethod|Function_
    {
        if ($node->params === []) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $variableNamesWithArrayType = $this->collectVariableNamesWithArrayType($node, $phpDocInfo);

        if ($variableNamesWithArrayType === []) {
            return null;
        }

        $hasChanged = false;
        $this->traverseNodesWithCallable(
            $node->stmts,
            function (Node $subNode) use (
                $variableNamesWithArrayType,
                $phpDocInfo,
                $node,
                &$hasChanged
            ): Node|null|int {
                if ($subNode instanceof Class_ || $subNode instanceof Function_) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (! $subNode instanceof FuncCall) {
                    return null;
                }

                if (! $this->isName($subNode, 'array_walk')) {
                    return null;
                }

                if ($subNode->isFirstClassCallable()) {
                    return null;
                }

                $args = $subNode->getArgs();
                if ($this->argsAnalyzer->hasNamedArg($args)) {
                    return null;
                }

                if (count($args) < 2) {
                    return null;
                }

                $firstArgValue = $args[0]->value;
                if (! $firstArgValue instanceof Variable) {
                    return null;
                }

                // defined on param provided
                if (! $this->isNames($firstArgValue, $variableNamesWithArrayType)) {
                    return null;
                }

                $firstArgValueType = $this->nodeTypeResolver->getType($firstArgValue);

                // type changed, eg: by reassign
                if (! $firstArgValueType->isArray()->yes()) {
                    return null;
                }

                $secondArgValue = $args[1]->value;

                if (! $secondArgValue instanceof ArrowFunction && ! $secondArgValue instanceof Closure) {
                    return null;
                }

                if (count($secondArgValue->params) !== 1) {
                    return null;
                }

                // not typed
                if (! $secondArgValue->params[0]->type instanceof Node) {
                    return null;
                }

                $paramToUpdate = null;
                foreach ($node->params as $param) {
                    if ($this->isName($param, $this->getName($firstArgValue))) {
                        $paramToUpdate = $param;
                        break;
                    }
                }

                if (! $paramToUpdate instanceof Param) {
                    return null;
                }

                $paramType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($secondArgValue->params[0]->type);
                $this->phpDocTypeChanger->changeParamType(
                    $node,
                    $phpDocInfo,
                    new ArrayType(new MixedType(), $paramType),
                    $paramToUpdate,
                    $this->getName($paramToUpdate)
                );
                $hasChanged = true;

                return $subNode;
            }
        );

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @return string[]
     */
    private function collectVariableNamesWithArrayType(ClassMethod|Function_ $node, PhpDocInfo $phpDocInfo): array
    {
        $variableNamesWithArrayType = [];

        foreach ($node->params as $param) {
            if (! $param->type instanceof Identifier) {
                continue;
            }

            if ($param->type->toString() !== 'array') {
                continue;
            }

            if (! $param->var instanceof Variable) {
                continue;
            }

            $paramName = $this->getName($param);
            $paramTag = $phpDocInfo->getParamTagValueByName($paramName);
            if ($paramTag instanceof ParamTagValueNode) {
                continue;
            }

            $variableNamesWithArrayType[] = $paramName;
        }

        return $variableNamesWithArrayType;
    }
}
