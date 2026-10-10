<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Flags a method whose param and return share the same class-type union, e.g. (A|B $node): A|B|null.
 * Such a method usually returns the passed node, so a @template generic narrows the return to the passed type.
 *
 * @implements Rule<ClassMethod>
 * @see \Rector\Utils\PHPStan\Tests\Rule\PreferGenericTemplateOnSameUnionTypeRule\PreferGenericTemplateOnSameUnionTypeRuleTest
 */
final class PreferGenericTemplateOnSameUnionTypeRule implements Rule
{
    private const string ERROR_MESSAGE = 'Param and return share the same class-type union "%s". Add a @template generic to narrow the return type to the passed one.';

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

        $returnNames = $this->resolveClassUnionNames($node->returnType);
        if ($returnNames === null) {
            return [];
        }

        foreach ($node->params as $param) {
            $paramNames = $this->resolveClassUnionNames($param->type);
            if ($paramNames === null) {
                continue;
            }

            if ($paramNames !== $returnNames) {
                continue;
            }

            $shortNames = array_map(
                static function (string $name): string {
                    $parts = explode('\\', $name);
                    return end($parts);
                },
                $returnNames
            );

            return [
                RuleErrorBuilder::message(sprintf(self::ERROR_MESSAGE, implode('|', $shortNames)))
                    ->identifier('rector.genericTemplateOverSameUnionType')
                    ->line($node->getStartLine())
                    ->build(),
            ];
        }

        return [];
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

                // built-in scalar/array type, not a class
                return null;
            }

            if ($memberType instanceof Name) {
                $names[] = $memberType->toString();
                continue;
            }

            // IntersectionType or anything else
            return null;
        }

        if (count($names) < 2) {
            return null;
        }

        $names = array_unique($names);
        sort($names);

        return $names;
    }
}
