<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Core\Rector\AbstractRector;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\RemoveNullTagValueNodeRectorTest
 */
final class RemoveNullTagValueNodeRector extends AbstractRector
{
    public function __construct(
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove @var/@param/@return null docblock',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return null
     */
    public function foo()
    {
        return null;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return null
     */
    public function foo()
    {
        return null;
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
        return [ClassMethod::class, Function_::class, Expression::class, Property::class];
    }

    private function isNull(VarTagValueNode|ParamTagValueNode|ReturnTagValueNode $tag): bool
    {
        return $tag->type instanceof IdentifierTypeNode
            && $tag->type->__toString() === 'null'
            && $tag->description === '';
    }

    private function removeParamNullTag(PhpDocInfo $phpDocInfo, array $paramNames): void
    {
        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable(
            $phpDocInfo->getPhpDocNode(),
            '',
            static function (Node $docNode) use ($paramNames) : ?int {
                if (! $docNode instanceof PhpDocTagNode) {
                    return null;
                }

                if (! $docNode->value instanceof ParamTagValueNode) {
                    return null;
                }

                if (in_array($docNode->value->parameterName , $paramNames, true)) {
                    return PhpDocNodeTraverser::NODE_REMOVE;
                }
            });
    }

    /**
     * @param ClassMethod|Function_|Expression|Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Expression || $node instanceof Property) {
            $phpdocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
            $varTagValueNode = $phpdocInfo->getVarTagValueNode();

            if ($varTagValueNode instanceof VarTagValueNode && $this->isNull($varTagValueNode)) {

                $phpdocInfo->removeByType(VarTagValueNode::class);
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

                return $node;
            }
        }

        $phpdocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $removedParamNames = [];

        foreach ($node->params as $param) {
            $paramName = $this->getName($param);
            $paramTagValueNode = $phpdocInfo->getParamTagValueByName($paramName);

            if ($paramTagValueNode instanceof VarTagValueNode && $this->isNull($paramTagValueNode)) {
                $removedParamNames[] = $paramTagValueNode->parameterName;
            }
        }

        $hasRemoved = false;
        if ($removedParamNames !== []) {
            $this->removeParamNullTag($phpdocInfo, $removedParamNames);
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

            $hasRemoved = true;
        }

        $returnTagValueNode = $phpdocInfo->getReturnTagValue();
        if ($returnTagValueNode instanceof ReturnTagValueNode && $this->isNull($returnTagValueNode)) {
            $phpdocInfo->removeByType(ReturnTagValueNode::class);
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

            $hasRemoved = true;
        }

        if (! $hasRemoved) {
            return null;
        }

        return $node;
    }
}
