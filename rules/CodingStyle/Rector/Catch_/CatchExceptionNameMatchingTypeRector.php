<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Catch_;

use PhpParser\Node\Stmt;
use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\TryCatch;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\AliasNameResolver;
use Rector\Naming\Naming\PropertyNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\CatchExceptionNameMatchingTypeRectorTest
 */
final class CatchExceptionNameMatchingTypeRector extends AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/xmfMAX/1
     */
    private const STARTS_WITH_ABBREVIATION_REGEX = '#^([A-Za-z]+?)([A-Z]{1}[a-z]{1})([A-Za-z]*)#';

    public function __construct(
        private readonly PropertyNaming $propertyNaming,
        private readonly AliasNameResolver $aliasNameResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Type and name of catch exception should match',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        try {
            // ...
        } catch (SomeException $typoException) {
            $typoException->getMessage();
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        try {
            // ...
        } catch (SomeException $someException) {
            $someException->getMessage();
        }
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

        $hasChanged = false;
        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof TryCatch) {
                continue;
            }

            if (count($stmt->catches) !== 1) {
                continue;
            }

            if (count($stmt->catches[0]->types) !== 1) {
                continue;
            }

            $catch = $stmt->catches[0];
            if (! $catch->var instanceof Variable) {
                continue;
            }

            /** @var string $oldVariableName */
            $oldVariableName = $this->getName($catch->var);

            $type = $catch->types[0];
            $typeShortName = $this->nodeNameResolver->getShortName($type);

            $aliasName = $this->aliasNameResolver->resolveByName($type);
            if (is_string($aliasName)) {
                $typeShortName = $aliasName;
            }

            $newVariableName = Strings::replace(
                lcfirst($typeShortName),
                self::STARTS_WITH_ABBREVIATION_REGEX,
                static function (array $matches): string {
                    $output = isset($matches[1]) ? strtolower((string) $matches[1]) : '';
                    $output .= $matches[2] ?? '';

                    return $output . ($matches[3] ?? '');
                }
            );

            $objectType = new ObjectType($newVariableName);
            $newVariableName = $this->propertyNaming->fqnToVariableName($objectType);

            if ($oldVariableName === $newVariableName) {
                continue;
            }

            // variable defined first only resolvable by Scope pulled from Stmt
            $scope = $stmt->getAttribute(AttributeKey::SCOPE);
            if (! $scope instanceof  Scope) {
                continue;
            }

            $isFoundInPrevious = $scope->hasVariableType($newVariableName)->yes();
            if ($isFoundInPrevious) {
                return null;
            }

            $catch->var->name = $newVariableName;
            $this->renameVariableInStmts($catch, $stmt, $oldVariableName, $newVariableName, $node->stmts[$key+1] ?? null);

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function renameVariableInStmts(Catch_ $catch, TryCatch $tryCatch, string $oldVariableName, string $newVariableName, ?Stmt $stmt): void
    {
        $this->traverseNodesWithCallable($catch->stmts, function (Node $node) use (
            $oldVariableName,
            $newVariableName
        ) {
            if (! $node instanceof Variable) {
                return null;
            }

            if (! $this->nodeNameResolver->isName($node, $oldVariableName)) {
                return null;
            }

            $node->name = $newVariableName;
            return null;
        });

        $this->replaceNextUsageVariable($tryCatch, $stmt, $oldVariableName, $newVariableName);
    }

    private function replaceNextUsageVariable(
        Node $currentNode,
        ?Node $nextNode,
        string $oldVariableName,
        string $newVariableName
    ): void {
        if (! $nextNode instanceof Node) {
            $parentNode = $currentNode->getAttribute(AttributeKey::PARENT_NODE);
            if (! $parentNode instanceof Node) {
                return;
            }

            if ($parentNode instanceof FunctionLike) {
                return;
            }

            $nextNode = $parentNode->getAttribute(AttributeKey::NEXT_NODE);
            $this->replaceNextUsageVariable($parentNode, $nextNode, $oldVariableName, $newVariableName);

            return;
        }

        /** @var Variable[] $variables */
        $variables = $this->betterNodeFinder->find($nextNode, function (Node $node) use ($oldVariableName): bool {
            if (! $node instanceof Variable) {
                return false;
            }

            return $this->nodeNameResolver->isName($node, $oldVariableName);
        });

        $processRenameVariables = $this->processRenameVariable($variables, $oldVariableName, $newVariableName);
        if (! $processRenameVariables) {
            return;
        }

        $currentNode = $nextNode;
        $nextNode = $nextNode->getAttribute(AttributeKey::NEXT_NODE);
        $this->replaceNextUsageVariable($currentNode, $nextNode, $oldVariableName, $newVariableName);
    }

    /**
     * @param Variable[] $variables
     */
    private function processRenameVariable(array $variables, string $oldVariableName, string $newVariableName): bool
    {
        foreach ($variables as $variable) {
            $parentNode = $variable->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentNode instanceof Assign && $this->nodeComparator->areNodesEqual(
                $parentNode->var,
                $variable
            ) && $this->nodeNameResolver->isName($parentNode->var, $oldVariableName)
                && ! $this->nodeComparator->areNodesEqual($parentNode->expr, $variable)
            ) {
                return false;
            }

            $variable->name = $newVariableName;
        }

        return true;
    }
}
