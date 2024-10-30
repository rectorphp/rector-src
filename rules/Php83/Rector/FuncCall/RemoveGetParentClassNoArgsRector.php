<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\FuncCall;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php83\Rector\FuncCall\RemoveGetParentClassNoArgsRector\RemoveGetParentClassNoArgsRectorTest
 */
final class RemoveGetParentClassNoArgsRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        $r = new RuleDefinition(
            'Replace calls to get_parent_class() without arguments with parent::class constant',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class Example extends StdClass {
    public function doWork() {
        return get_parent_class();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class Example extends StdClass {
    public function doWork() {
        return parent::class;
    }
}
CODE_SAMPLE
                ),
            ]
        );

        return $r;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\FuncCall::class];
    }

    /**
     * @param Node\Expr\FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if (! ($this->isName($node, 'get_parent_class'))) {
            return null;
        }

        if (count($node->getArgs()) !== 0) {
            return null;
        }

        return new Node\Expr\ClassConstFetch(new Node\Name(['parent']), new Node\VarLikeIdentifier('class'));
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_GET_CLASS_WITHOUT_ARGS;
    }
}
