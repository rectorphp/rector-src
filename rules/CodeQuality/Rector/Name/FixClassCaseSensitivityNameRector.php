<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Name;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\UnionType;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Mostly mimics source from
 * @changelog https://github.com/phpstan/phpstan-src/blob/master/src/Rules/ClassCaseSensitivityCheck.php
 *
 * @see \Rector\Tests\CodeQuality\Rector\Name\FixClassCaseSensitivityNameRector\FixClassCaseSensitivityNameRectorTest
 */
final class FixClassCaseSensitivityNameRector extends AbstractRector
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change miss-typed case sensitivity name to correct one',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $anotherClass = new anotherclass;
    }
}

final class AnotherClass
{
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $anotherClass = new AnotherClass;
    }
}

final class AnotherClass
{
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
        return [Name::class];
    }

    /**
     * @param Name|Param $node
     */
    public function refactor(Node $node): ?Node
    {
        $fullyQualifiedName = $this->resolveFullyQualifiedName($node);

        if (! $this->reflectionProvider->hasClass($fullyQualifiedName)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($fullyQualifiedName);
        if ($classReflection->isBuiltin()) {
            // skip built-in classes
            return null;
        }

        $realClassName = $classReflection->getName();
        if (strtolower($realClassName) !== strtolower($fullyQualifiedName)) {
            // skip class alias
            return null;
        }

        if ($realClassName === $fullyQualifiedName) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $hasFunction = $this->reflectionProvider->hasFunction(new FullyQualified($fullyQualifiedName), $scope);
        if ($hasFunction) {
            return null;
        }

        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

        // do not FQN use imports
        if ($parent instanceof UseUse) {
            return new Name($realClassName);
        }

        return new FullyQualified($realClassName);
    }

    private function resolveFullyQualifiedName(Name $name): string
    {
        $originalName = $name->getAttribute(AttributeKey::ORIGINAL_NAME);
        if (! $originalName instanceof Name) {
            return $this->getName($name);
        }

        $parent = $name->getAttribute(AttributeKey::PARENT_NODE);

        if (($parent instanceof Param && $parent->type instanceof Name) || $parent instanceof ClassConstFetch) {
            $oldTokens = $this->file->getOldTokens();
            $startTokenPos = $parent->getStartTokenPos();

            if (isset($oldTokens[$startTokenPos][1])) {
                $type = $oldTokens[$startTokenPos][1];
                if (! str_contains($type, '\\')) {
                    $originalNameBeforeLastPart = Strings::after((string) $originalName, '\\', -1);
                    if (strtolower($originalNameBeforeLastPart) !== strtolower($originalNameBeforeLastPart)) {
                        return null;
                    }

                    $name->parts[count($name->parts) - 1] = $type;
                    return (string) $name;
                }
            }
        }

        // replace parts from the old one
        $originalReversedParts = array_reverse($originalName->parts);
        $resolvedReversedParts = array_reverse($name->parts);

        $mergedReversedParts = $originalReversedParts + $resolvedReversedParts;
        $mergedParts = array_reverse($mergedReversedParts);

        return implode('\\', $mergedParts);
    }
}
