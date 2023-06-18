<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Node;

use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Static_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\Node\Stmt\While_;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\NodeAnalyzer\ExprUsedInNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\RemoveNonExistingVarAnnotationRectorTest
 *
 * @changelog https://github.com/phpstan/phpstan/commit/d17e459fd9b45129c5deafe12bca56f30ea5ee99#diff-9f3541876405623b0d18631259763dc1
 */
final class RemoveNonExistingVarAnnotationRector extends AbstractRector
{
    public function __construct(
        private readonly ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Removes non-existing @var annotations above the code',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function get()
    {
        /** @var Training[] $trainings */
        return $this->getData();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function get()
    {
        return $this->getData();
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
        return [
            Foreach_::class,
            Static_::class,
            Echo_::class,
            Return_::class,
            Expression::class,
            Throw_::class,
            If_::class,
            While_::class,
            Switch_::class,
            Nop::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $varTagValueNode instanceof VarTagValueNode) {
            return null;
        }

        if ($this->isObjectShapePseudoType($varTagValueNode)) {
            return null;
        }

        $variableName = ltrim($varTagValueNode->variableName, '$');

        if ($variableName === '' && $this->isAnnotatableReturn($node)) {
            return null;
        }

        if ($this->hasVariableName($node, $variableName)) {
            return null;
        }

        if ($this->isUsedInNextNodeWithExtractPreviouslyCalled($node, $variableName)) {
            return null;
        }

        $comments = $node->getComments();
        if (isset($comments[1])) {
            // skip edge case with double comment, as impossible to resolve by PHPStan doc parser
            return null;
        }

        $phpDocInfo->removeByType(VarTagValueNode::class);
        return $node;
    }

    private function isUsedInNextNodeWithExtractPreviouslyCalled(Node $node, string $variableName): bool
    {
        $variable = new Variable($variableName);
        $isUsedInNextNode = (bool) $this->betterNodeFinder->findFirstNext(
            $node,
            fn (Node $node): bool => $this->exprUsedInNodeAnalyzer->isUsed($node, $variable)
        );

        if (! $isUsedInNextNode) {
            return false;
        }

        return (bool) $this->betterNodeFinder->findFirstPrevious($node, function (Node $subNode): bool {
            if (! $subNode instanceof FuncCall) {
                return false;
            }

            return $this->nodeNameResolver->isName($subNode, 'extract');
        });
    }

    private function shouldSkip(Node $node): bool
    {
        return count($node->getComments()) !== 1;
    }

    private function hasVariableName(Node $node, string $variableName): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($node, function (Node $node) use ($variableName): bool {
            if (! $node instanceof Variable) {
                return false;
            }

            return $this->isName($node, $variableName);
        });
    }

    /**
     * This is a hack,
     * that waits on phpdoc-parser to get merged - https://github.com/phpstan/phpdoc-parser/pull/145
     */
    private function isObjectShapePseudoType(VarTagValueNode $varTagValueNode): bool
    {
        if (! $varTagValueNode->type instanceof IdentifierTypeNode) {
            return false;
        }

        if ($varTagValueNode->type->name !== 'object') {
            return false;
        }

        if (! str_starts_with($varTagValueNode->description, '{')) {
            return false;
        }

        return str_contains($varTagValueNode->description, '}');
    }

    private function isAnnotatableReturn(Node $node): bool
    {
        return $node instanceof Return_
            && $node->expr instanceof CallLike
            && ! $node->expr instanceof New_;
    }
}
