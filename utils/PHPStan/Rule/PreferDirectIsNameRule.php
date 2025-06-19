<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

/**
 * @todo outsource to symplify/phpstan-rules later
 */
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Rector\AbstractRector;

final class PreferDirectIsNameRule implements Rule
{
    public const ERROR_MESSAGE = 'Use direct $this->isName() call instead of fetching NodeNameResolver service';

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isFirstClassCallable()) {
            return [];
        }

        if (! $node->name instanceof Identifier) {
            return [];
        }

        if (! in_array($node->name, ['isName', 'isNames', 'getName'])) {
            return [];
        }

        if (! $scope->isInClass()) {
            return [];
        }

        $classReflection = $scope->getClassReflection();

        // skip self
        if ($classReflection->getName() === AbstractRector::class) {
            return [];
        }

        // check rector rules only
        if (! $classReflection->is(AbstractRector::class)) {
            return [];
        }

        // check child Rectors only
        if ($classReflection->isAbstract()) {
            return [];
        }

        if (! $node->var instanceof PropertyFetch) {
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier('rector.preferDirectIsName')
            ->build();

        return [$identifierRuleError];
    }
}
