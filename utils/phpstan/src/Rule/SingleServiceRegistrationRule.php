<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * A container service must be registered exactly once. Registering the same class under the same
 * tag twice makes every consumer of that tag run it twice, e.g. each phpdoc node visitor
 * traversing every doc node twice.
 *
 * @implements Rule<ClassMethod>
 * @see \Rector\Utils\PHPStan\Tests\Rule\SingleServiceRegistrationRule\SingleServiceRegistrationRuleTest
 */
final class SingleServiceRegistrationRule implements Rule
{
    private const string DUPLICATE_ERROR_MESSAGE = '"%s(%s)" is already called on line %d; register the service exactly once.';

    private const string AUTOTAGGED_ERROR_MESSAGE = 'Service "%s" is registered as singleton and "%s" is autotagged, so this tag() call registers it twice.';

    /**
     * Interfaces RectorConfig tags on its own, see RectorConfig::$autotagInterfaces
     *
     * @var string[]
     */
    private const array AUTOTAG_INTERFACES = [
        'Symfony\Component\Console\Command\Command',
        'Rector\Contract\DependencyInjection\ResettableInterface',
    ];

    /**
     * Registration calls on the container, and the arguments that identify what gets registered.
     * The container itself is never part of the identity, so registerTagged() skips its first argument.
     *
     * @var array<string, int[]>
     */
    private const array REGISTRATION_METHOD_TO_ARG_POSITIONS = [
        'singleton' => [0],
        'tag' => [0, 1],
        'registerTagged' => [1, 2],
    ];

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->stmts === null) {
            return [];
        }

        $ruleErrors = [];
        $firstLineByRegistration = [];
        $singletonClasses = [];

        $nodeFinder = new NodeFinder();

        /** @var MethodCall[] $methodCalls */
        $methodCalls = $nodeFinder->findInstanceOf($node->stmts, MethodCall::class);

        foreach ($methodCalls as $methodCall) {
            $methodName = $this->resolveRegistrationMethodName($methodCall);
            if ($methodName === null) {
                continue;
            }

            $argumentKeys = $this->resolveArgumentKeys(
                $methodCall,
                self::REGISTRATION_METHOD_TO_ARG_POSITIONS[$methodName]
            );

            if ($argumentKeys === null) {
                continue;
            }

            if ($methodName === 'singleton') {
                $singletonClasses[] = $argumentKeys[0];
            }

            $registration = $methodName . '(' . implode(', ', $argumentKeys) . ')';

            if (isset($firstLineByRegistration[$registration])) {
                $ruleErrors[] = RuleErrorBuilder::message(sprintf(
                    self::DUPLICATE_ERROR_MESSAGE,
                    $methodName,
                    implode(', ', $argumentKeys),
                    $firstLineByRegistration[$registration]
                ))
                    ->identifier('rector.singleServiceRegistration')
                    ->line($methodCall->getStartLine())
                    ->build();

                continue;
            }

            $firstLineByRegistration[$registration] = $methodCall->getStartLine();

            if ($methodName !== 'tag') {
                continue;
            }

            if (! in_array($argumentKeys[1], self::AUTOTAG_INTERFACES, true)) {
                continue;
            }

            if (! in_array($argumentKeys[0], $singletonClasses, true)) {
                continue;
            }

            $ruleErrors[] = RuleErrorBuilder::message(
                sprintf(self::AUTOTAGGED_ERROR_MESSAGE, $argumentKeys[0], $argumentKeys[1])
            )
                ->identifier('rector.singleServiceRegistration')
                ->line($methodCall->getStartLine())
                ->build();
        }

        return $ruleErrors;
    }

    /**
     * The scope of a ClassMethod node is the method entry, where body variables are still mixed,
     * so the container is matched on call shape instead of on its resolved type.
     */
    private function resolveRegistrationMethodName(MethodCall $methodCall): ?string
    {
        if (! $methodCall->name instanceof Identifier) {
            return null;
        }

        $methodName = $methodCall->name->toString();
        if (! isset(self::REGISTRATION_METHOD_TO_ARG_POSITIONS[$methodName])) {
            return null;
        }

        // registerTagged() takes the container as its first argument, the rest are called on it
        if ($methodName === 'registerTagged') {
            $firstArg = $methodCall->getArgs()[0] ?? null;
            if (! $firstArg instanceof Arg || ! $firstArg->value instanceof Variable) {
                return null;
            }

            return $methodName;
        }

        if (! $methodCall->var instanceof Variable) {
            return null;
        }

        return $methodName;
    }

    /**
     * @param int[] $argPositions
     * @return string[]|null
     */
    private function resolveArgumentKeys(MethodCall $methodCall, array $argPositions): ?array
    {
        $args = $methodCall->getArgs();

        $argumentKeys = [];
        foreach ($argPositions as $argPosition) {
            $arg = $args[$argPosition] ?? null;
            if (! $arg instanceof Arg) {
                return null;
            }

            $argumentKey = $this->resolveArgumentKey($arg);
            if ($argumentKey === null) {
                return null;
            }

            $argumentKeys[] = $argumentKey;
        }

        return $argumentKeys;
    }

    private function resolveArgumentKey(Arg $arg): ?string
    {
        if ($arg->value instanceof String_) {
            return $arg->value->value;
        }

        if (! $arg->value instanceof ClassConstFetch) {
            return null;
        }

        $classConstFetch = $arg->value;
        if (! $classConstFetch->class instanceof Name || ! $classConstFetch->name instanceof Identifier) {
            return null;
        }

        $constantName = $classConstFetch->name->toString();
        if ($constantName === 'class') {
            return $classConstFetch->class->toString();
        }

        return $classConstFetch->class->toString() . '::' . $constantName;
    }
}
