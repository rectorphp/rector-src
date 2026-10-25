<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements Rule<MethodCall>
 * @see \Rector\Utils\PHPStan\Tests\Rule\NoGetClassReflectionOnScopeRule\NoGetClassReflectionOnScopeRuleTest
 */
final class NoGetClassReflectionOnScopeRule implements Rule
{
    private const string ERROR_MESSAGE = 'Do not call getClassReflection() on a Scope. Resolve the class name and fetch the reflection via ReflectionProvider->getClass() instead.';

    private const string METHOD_NAME = 'getClassReflection';

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Identifier) {
            return [];
        }

        if ($node->name->toString() !== self::METHOD_NAME) {
            return [];
        }

        $callerType = $scope->getType($node->var);
        if (! new ObjectType(Scope::class)->isSuperTypeOf($callerType)->yes()) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier('rector.getClassReflectionOnScope')
                ->line($node->getStartLine())
                ->build(),
        ];
    }
}
