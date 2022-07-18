<?php

declare(strict_types=1);

namespace Rector\Naming\Rector\Assign;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Guard\BreakingVariableRenameGuard;
use Rector\Naming\Matcher\VariableAndCallAssignMatcher;
use Rector\Naming\Naming\ExpectedNameResolver;
use Rector\Naming\NamingConvention\NamingConventionAnalyzer;
use Rector\Naming\PhpDoc\VarTagValueNodeRenamer;
use Rector\Naming\ValueObject\VariableAndCallAssign;
use Rector\Naming\VariableRenamer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\RenameVariableToMatchMethodCallReturnTypeRectorTest
 */
final class RenameVariableToMatchMethodCallReturnTypeRector extends AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/JG5w9j/1
     */
    private const OR_BETWEEN_WORDS_REGEX = '#[a-z]Or[A-Z]#';

    public function __construct(
        private readonly BreakingVariableRenameGuard $breakingVariableRenameGuard,
        private readonly ExpectedNameResolver $expectedNameResolver,
        private readonly NamingConventionAnalyzer $namingConventionAnalyzer,
        private readonly VarTagValueNodeRenamer $varTagValueNodeRenamer,
        private readonly VariableAndCallAssignMatcher $variableAndCallAssignMatcher,
        private readonly VariableRenamer $variableRenamer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename variable to match method return type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
public function run()
{
    $a = $this->getRunner();
}

public function getRunner(): Runner
{
    return new Runner();
}
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class SomeClass
{
public function run()
{
    $runner = $this->getRunner();
}

public function getRunner(): Runner
{
    return new Runner();
}
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
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        $variableAndCallAssign = $this->variableAndCallAssignMatcher->match($node);
        if (! $variableAndCallAssign instanceof VariableAndCallAssign) {
            return null;
        }

        $call = $variableAndCallAssign->getCall();
        if ($this->isMultipleCall($call)) {
            return null;
        }

        $expectedName = $this->expectedNameResolver->resolveForCall($call);
        if ($expectedName === null) {
            return null;
        }

        if ($this->isName($node->var, $expectedName)) {
            return null;
        }

        if ($this->shouldSkip($variableAndCallAssign, $expectedName)) {
            return null;
        }

        $this->renameVariable($variableAndCallAssign, $expectedName);

        return $node;
    }

    private function isMultipleCall(FuncCall | StaticCall | MethodCall $callNode): bool
    {
        $parentNode = $callNode->getAttribute(AttributeKey::PARENT_NODE);

        $callNodeClass = $callNode::class;

        while ($parentNode instanceof Node) {
            $usedNodes = $this->betterNodeFinder->find($parentNode, function (Node $node) use (
                $callNodeClass,
                $callNode
            ): bool {
                $nodeClass = $node::class;
                if ($callNodeClass !== $nodeClass) {
                    return false;
                }

                $usedNodeOriginalNode = $callNode->getAttribute(AttributeKey::ORIGINAL_NODE);

                if (! $usedNodeOriginalNode instanceof Node) {
                    return false;
                }

                if ($usedNodeOriginalNode::class !== $callNode::class) {
                    return false;
                }

                /** @var FuncCall|StaticCall|MethodCall $node */
                $passedNode = clone $node;

                /** @var FuncCall|StaticCall|MethodCall $usedNodeOriginalNode */
                $usedNode = clone $usedNodeOriginalNode;

                /** @var FuncCall|StaticCall|MethodCall $passedNode */
                $passedNode->args = [];

                /** @var FuncCall|StaticCall|MethodCall $usedNode */
                $usedNode->args = [];

                return $this->nodeComparator->areNodesEqual($passedNode, $usedNode);
            });

            if (count($usedNodes) > 1) {
                return true;
            }

            $parentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
        }

        return false;
    }

    private function shouldSkip(VariableAndCallAssign $variableAndCallAssign, string $expectedName): bool
    {
        if ($this->namingConventionAnalyzer->isCallMatchingVariableName(
            $variableAndCallAssign->getCall(),
            $variableAndCallAssign->getVariableName(),
            $expectedName
        )) {
            return true;
        }

        $isUnionName = Strings::match($variableAndCallAssign->getVariableName(), self::OR_BETWEEN_WORDS_REGEX);
        if ($isUnionName !== null) {
            return true;
        }

        return $this->breakingVariableRenameGuard->shouldSkipVariable(
            $variableAndCallAssign->getVariableName(),
            $expectedName,
            $variableAndCallAssign->getFunctionLike(),
            $variableAndCallAssign->getVariable()
        );
    }

    private function renameVariable(VariableAndCallAssign $variableAndCallAssign, string $expectedName): void
    {
        $assign = $variableAndCallAssign->getAssign();
        $assignPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($assign);

        $this->varTagValueNodeRenamer->renameAssignVarTagVariableName(
            $assignPhpDocInfo,
            $variableAndCallAssign->getVariableName(),
            $expectedName
        );

        $this->variableRenamer->renameVariableInFunctionLike(
            $variableAndCallAssign->getFunctionLike(),
            $variableAndCallAssign->getVariableName(),
            $expectedName,
            $variableAndCallAssign->getAssign()
        );
    }
}
