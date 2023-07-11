<?php

declare(strict_types=1);

namespace Rector\Php74\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector\RestoreDefaultNullToNullableTypePropertyRectorTest
 */
final class RestoreDefaultNullToNullableTypePropertyRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ConstructorAssignDetector $constructorAssignDetector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add null default to properties with PHP 7.4 property nullable type',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public ?string $name;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public ?string $name = null;
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isReadonly()) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getProperties() as $property) {
            if ($this->shouldSkip($property, $node)) {
                continue;
            }

            $onlyProperty = $property->props[0];
            $onlyProperty->default = $this->nodeFactory->createNull();

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_PROPERTIES;
    }

    private function shouldSkip(Property $property, Class_ $class): bool
    {
        if ($property->type === null) {
            return true;
        }

        if (count($property->props) > 1) {
            return true;
        }

        $onlyProperty = $property->props[0];
        if ($onlyProperty->default instanceof Expr) {
            return true;
        }

        if ($property->isReadonly()) {
            return true;
        }

        if (! $this->nodeTypeResolver->isNullableType($property)) {
            return true;
        }

        // is variable assigned in constructor
        $propertyName = $this->getName($property);
        return $this->constructorAssignDetector->isPropertyAssigned($class, $propertyName);
    }
}
