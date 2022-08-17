<?php

declare(strict_types=1);

namespace Rector\Naming\Rector\VarLikeIdentifier;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\VarLikeIdentifier;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Naming\Rector\VarLikeIdentifier\SnakeToCamelCasePropertiesAndVariablesRector\SnakeToCamelCasePropertiesAndVariablesRectorTest
 */
final class SnakeToCamelCasePropertiesAndVariablesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Converts snake case property, method param and variable names to camel case', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    private static int $static_property
    private string $object_property;
    
    public function setObject_property(string $object_property): void
    {
        $this->object_property = $object_property;
    }
    
    public static setStatic_property(int $static_property): void
    {
        self::$static_property = $static_property;
    }
    
    public function print_properties(): string
    {
        return self::$static_property . ' - ' . $this->object_property;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    class SomeClass
{
    private static int $staticProperty
    private string $objectProperty;
    
    public function setObjectProperty(string $objectProperty): void
    {
        $this->objectProperty = $objectProperty;
    }
    
    public static setStaticProperty(int $staticProperty): void
    {
        self::$staticProperty = $staticProperty;
    }
    
    public function printProperties(): string
    {
        return self::$staticProperty . ' - ' . $this->objectProperty;
    }
}
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [PropertyProperty::class, Variable::class, VarLikeIdentifier::class, PropertyFetch::class];
    }

    public function refactor(Node $node)
    {
        if ($node instanceof VarLikeIdentifier) {
            $node->name = $this->getCamelCasedName($node->name);
            return $node;
        }

        if ($node instanceof PropertyProperty || $node instanceof PropertyFetch) {
            if ($node->name instanceof Expr) {
                return null;
            }

            $node->name->name = $this->getCamelCasedName($node->name->name);
            return $node;
        }

        if ($node instanceof Variable && is_string($node->name)) {
            $node->name = $this->getCamelCasedName($node->name);
        }

        return $node;
    }

    private function getCamelCasedName(string $nodeName): string
    {
        $parts = explode('_', $nodeName);
        $partsCount = count($parts);

        if ($partsCount <= 1) {
            return $nodeName;
        }

        for ($i = 1; $i < $partsCount; ++$i) {
            $parts[$i] = ucfirst($parts[$i]);
        }

        return implode('', $parts);
    }
}
