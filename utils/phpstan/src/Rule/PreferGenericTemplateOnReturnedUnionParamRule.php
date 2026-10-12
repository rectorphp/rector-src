<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Flags a method that takes a class-type union param (A|B) but declares a broader single return type
 * (e.g. their shared parent Node), then returns that very param - a hidden generic.
 * A @template generic narrows the return to the passed type instead of the broad parent.
 *
 * @implements Rule<ClassMethod>
 * @see \Rector\Utils\PHPStan\Tests\Rule\PreferGenericTemplateOnReturnedUnionParamRule\PreferGenericTemplateOnReturnedUnionParamRuleTest
 */
final class PreferGenericTemplateOnReturnedUnionParamRule implements Rule
{
    private const string ERROR_MESSAGE = 'Return type "%s" is broader than the returned union param "%s". Add a @template generic to narrow the return type to the passed one.';

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
        $docComment = $node->getDocComment();
        if ($docComment instanceof Doc && str_contains($docComment->getText(), '@template')) {
            return [];
        }

        $returnName = $this->resolveSingleClassReturnName($node->returnType);
        if ($returnName === null) {
            return [];
        }

        foreach ($node->params as $param) {
            $paramNames = $this->resolveClassUnionNames($param->type);
            if ($paramNames === null) {
                continue;
            }

            // return type is one of the union members, not a broad shared parent - a generic does not apply
            if (in_array($returnName, $paramNames, true)) {
                continue;
            }

            if (! $param->var instanceof Variable || ! is_string($param->var->name)) {
                continue;
            }

            if (! $this->methodReturnsVariable($node, $param->var->name)) {
                continue;
            }

            $shortNames = array_map(static function (string $name): string {
                $parts = explode('\\', $name);
                return end($parts);
            }, $paramNames);

            return [
                RuleErrorBuilder::message(sprintf(self::ERROR_MESSAGE, $returnName, implode('|', $shortNames)))
                    ->identifier('rector.genericTemplateOverBroadReturn')
                    ->line($node->getStartLine())
                    ->build(),
            ];
        }

        return [];
    }

    private function methodReturnsVariable(ClassMethod $classMethod, string $variableName): bool
    {
        if ($classMethod->stmts === null) {
            return false;
        }

        $foundReturn = new NodeFinder()->findFirst(
            $classMethod->stmts,
            static fn (Node $subNode): bool => $subNode instanceof Return_
                && $subNode->expr instanceof Variable
                && $subNode->expr->name === $variableName
        );

        return $foundReturn instanceof Return_;
    }

    /**
     * Returns the single class return type name (nullable allowed), or null when the return type is
     * missing, scalar, or a union of 2+ class types - the latter is covered by the same-union rule.
     */
    private function resolveSingleClassReturnName(null|Identifier|Name|ComplexType $type): ?string
    {
        if ($type instanceof NullableType) {
            $type = $type->type;
        }

        if ($type instanceof Name) {
            return $type->toString();
        }

        if (! $type instanceof UnionType) {
            return null;
        }

        $names = [];
        foreach ($type->types as $memberType) {
            if ($memberType instanceof Identifier) {
                if ($memberType->toLowerString() === 'null') {
                    continue;
                }

                return null;
            }

            if (! $memberType instanceof Name) {
                return null;
            }

            $names[] = $memberType->toString();
        }

        if (count($names) !== 1) {
            return null;
        }

        return $names[0];
    }

    /**
     * Returns sorted class-type member names (null stripped), or null when the type is not a
     * union of 2+ class types - scalars, array and intersections disqualify it.
     *
     * @return list<string>|null
     */
    private function resolveClassUnionNames(null|Identifier|Name|ComplexType $type): ?array
    {
        if (! $type instanceof UnionType) {
            return null;
        }

        $names = [];
        foreach ($type->types as $memberType) {
            if ($memberType instanceof Identifier) {
                if ($memberType->toLowerString() === 'null') {
                    continue;
                }

                return null;
            }

            if (! $memberType instanceof Name) {
                return null;
            }

            $names[] = $memberType->toString();
        }

        if (count($names) < 2) {
            return null;
        }

        $names = array_unique($names);
        sort($names);

        return $names;
    }
}
