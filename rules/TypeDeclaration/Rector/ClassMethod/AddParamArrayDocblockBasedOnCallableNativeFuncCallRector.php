<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\AddParamArrayDocblockBasedOnCallableNativeFuncCallRectorTest
 */
final class AddParamArrayDocblockBasedOnCallableNativeFuncCallRector extends AbstractRector
{
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

    public function refactor(): null|ClassMethod|Function_
    {
        return null;
    }
}
