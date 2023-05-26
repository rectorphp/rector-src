<?php

declare(strict_types=1);

namespace Rector\Naming\Rector\Assign;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Guard\BreakingVariableRenameGuard;
use Rector\Naming\Matcher\VariableAndCallAssignMatcher;
use Rector\Naming\Naming\ExpectedNameResolver;
use Rector\Naming\NamingConvention\NamingConventionAnalyzer;
use Rector\Naming\PhpDoc\VarTagValueNodeRenamer;
use Rector\Naming\ValueObject\VariableAndCallAssign;
use Rector\Naming\VariableRenamer;
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
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;

            $variableAndCallAssign = $this->variableAndCallAssignMatcher->match($assign);
            if (! $variableAndCallAssign instanceof VariableAndCallAssign) {
                return null;
            }

            $call = $variableAndCallAssign->getCall();

            $expectedName = $this->expectedNameResolver->resolveForCall($call);
            if ($expectedName === null) {
                continue;
            }

            if ($this->isName($assign->var, $expectedName)) {
                continue;
            }

            if ($this->shouldSkip($variableAndCallAssign, $expectedName)) {
                continue;
            }

            $this->renameVariable($variableAndCallAssign, $expectedName);

            return $node;
        }

        return null;
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
