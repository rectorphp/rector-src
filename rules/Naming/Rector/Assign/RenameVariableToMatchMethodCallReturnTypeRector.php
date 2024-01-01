<?php

declare(strict_types=1);

namespace Rector\Naming\Rector\Assign;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Naming\Guard\BreakingVariableRenameGuard;
use Rector\Naming\Matcher\VariableAndCallAssignMatcher;
use Rector\Naming\Naming\ExpectedNameResolver;
use Rector\Naming\NamingConvention\NamingConventionAnalyzer;
use Rector\Naming\PhpDoc\VarTagValueNodeRenamer;
use Rector\Naming\ValueObject\VariableAndCallAssign;
use Rector\Naming\VariableRenamer;
use Rector\Rector\AbstractRector;
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

    /**
     * @var string
     * @see https://regex101.com/r/TV8YXZ/1
     */
    private const VALID_VARIABLE_NAME_REGEX = '#^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$#';

    public function __construct(
        private readonly BreakingVariableRenameGuard $breakingVariableRenameGuard,
        private readonly ExpectedNameResolver $expectedNameResolver,
        private readonly NamingConventionAnalyzer $namingConventionAnalyzer,
        private readonly VarTagValueNodeRenamer $varTagValueNodeRenamer,
        private readonly VariableAndCallAssignMatcher $variableAndCallAssignMatcher,
        private readonly VariableRenamer $variableRenamer,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory
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
        return [ClassMethod::class, Closure::class, Function_::class];
    }

    /**
     * @param ClassMethod|Closure|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;

            $variableAndCallAssign = $this->variableAndCallAssignMatcher->match($assign, $node);
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

            $this->renameVariable($variableAndCallAssign, $expectedName, $stmt);

            return $node;
        }

        return null;
    }

    private function shouldSkip(VariableAndCallAssign $variableAndCallAssign, string $expectedName): bool
    {
        if (Strings::match($expectedName, self::VALID_VARIABLE_NAME_REGEX) === null) {
            return true;
        }

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

    private function renameVariable(
        VariableAndCallAssign $variableAndCallAssign,
        string $expectedName,
        Expression $expression
    ): void {
        $this->variableRenamer->renameVariableInFunctionLike(
            $variableAndCallAssign->getFunctionLike(),
            $variableAndCallAssign->getVariableName(),
            $expectedName,
            $variableAndCallAssign->getAssign()
        );

        $assignPhpDocInfo = $this->phpDocInfoFactory->createFromNode($expression);
        if (! $assignPhpDocInfo instanceof PhpDocInfo) {
            return;
        }

        $this->varTagValueNodeRenamer->renameAssignVarTagVariableName(
            $assignPhpDocInfo,
            $variableAndCallAssign->getVariableName(),
            $expectedName
        );

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($expression);
    }
}
