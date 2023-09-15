<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ValueObject\MethodName;

/**
 * @implements Rule<InClassNode>
 * This rule helps to find overly complex rules, that usually have little value, but are costrly to run.
 */
final class LongAndDependentComplexRectorRule implements Rule
{
    /**
     * @var int
     */
    private const MAX_CLASS_LINES = 320;

    /**
     * @var int
     */
    private const MAX_DEPENDENCY_COUNT = 7;

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // check only rector rules
        $classReflection = $node->getClassReflection();
        if (! $classReflection->isSubclassOf(RectorInterface::class)) {
            return [];
        }

        $class = $node->getOriginalNode();
        $errorMessages = [];

        $constructorParameterCount = $this->resolveConstructorParameterCount($class);
        if ($constructorParameterCount > self::MAX_DEPENDENCY_COUNT) {
            $errorMessages[] = sprintf(
                'Class "%s" has too many constructor parameters (%d), consider using value objects',
                $classReflection->getName(),
                $constructorParameterCount
            );
        }

        $classLineCount = $class->getEndLine() - $class->getStartLine();
        if ($classLineCount > self::MAX_CLASS_LINES) {
            $errorMessages[] = sprintf(
                'Class "%s" is too long (%d lines), consider splitting it to smaller classes',
                $classReflection->getName(),
                $classLineCount
            );
        }

        return $errorMessages;
    }

    private function resolveConstructorParameterCount(ClassLike $classLike): int
    {
        $constructorClassMethod = $classLike->getMethod(MethodName::CONSTRUCT);
        if (! $constructorClassMethod instanceof ClassMethod) {
            return 0;
        }

        return count($constructorClassMethod->getParams());
    }
}
