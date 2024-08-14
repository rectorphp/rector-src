<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\AddParamArrayDocblockBasedOnCallableNativeFuncCallRectorTest
 */
final class AddParamArrayDocblockBasedOnCallableNativeFuncCallRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory
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
        $variablesWithArrayType = [];

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

            $paramTag = $phpDocInfo->getParamTagValueByName($this->getName($param));
            if (! $paramTag instanceof ParamTagValueNode) {
                continue;
            }

            $variablesWithArrayType[] = $param->var;
        }

        if ($variablesWithArrayType === []) {
            return null;
        }

        return $node;
    }
}
