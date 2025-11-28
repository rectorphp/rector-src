<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\Node as AstNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessVoidReturnFromDocblockOnVoidMagicMethodsRector\RemoveUselessVoidReturnFromDocblockVoidMagicMethodsRectorTest
 */
final class RemoveUselessVoidReturnFromDocblockVoidMagicMethodsRector extends AbstractRector
{
    public function __construct(
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove useless @return void docblock from magic methods __construct, __destruct, and __clone',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return void
     */
    public function __construct() {}

    /**
     * @return void
     */
    public function __destruct() {}

    /**
     * @return void
     */
    public function __clone() {}
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct() {}

    public function __destruct() {}

    public function __clone() {}
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $magicMethodNames = ['__construct', '__destruct', '__clone'];

        $methodName = $this->getName($node);

        if (! in_array($methodName, $magicMethodNames, true)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $returnTagValueNode = $phpDocInfo->getReturnTagValue();

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable(
            $phpDocInfo->getPhpDocNode(),
            '',
            function (AstNode $astNode) use ($returnTagValueNode): ?int {
                if (! $astNode instanceof PhpDocTagNode) {
                    return null;
                }

                if ($astNode->value === $returnTagValueNode) {
                    return PhpDocNodeTraverser::NODE_REMOVE;
                }

                return null;
            }
        );

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        return $node;
    }
}
