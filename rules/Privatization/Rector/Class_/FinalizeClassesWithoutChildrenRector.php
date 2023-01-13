<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector\FinalizeClassesWithoutChildrenRectorTest
 */
final class FinalizeClassesWithoutChildrenRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private const DOCTRINE_MAPPING_CLASSES = [
        'Doctrine\ORM\Mapping\Entity',
        'Doctrine\ORM\Mapping\Embeddable',
        'Doctrine\ODM\MongoDB\Mapping\Annotations\Document',
        'Doctrine\ODM\MongoDB\Mapping\Annotations\EmbeddedDocument',
    ];

    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Finalize every class that has no children', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class FirstClass
{
}

class SecondClass
{
}

class ThirdClass extends SecondClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class FirstClass
{
}

class SecondClass
{
}

final class ThirdClass extends SecondClass
{
}
CODE_SAMPLE
            ),
        ]);
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
        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if ($phpDocInfo->hasByAnnotationClasses(self::DOCTRINE_MAPPING_CLASSES)) {
            return null;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $childrenClassReflections = $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection);
        if ($childrenClassReflections !== []) {
            return null;
        }

        if ($this->hasDoctrineAttr($classReflection)) {
            return null;
        }

        $this->visibilityManipulator->makeFinal($node);

        return $node;
    }

    private function hasDoctrineAttr(ClassReflection $classReflection): bool
    {
        /** @var \PHPStan\BetterReflection\Reflection\Adapter\ReflectionClass $nativeReflectionClass */
        $nativeReflectionClass = $classReflection->getNativeReflection();

        // skip early in case of no attributes at all
        if ($nativeReflectionClass->getAttributes() === []) {
            return false;
        }

        foreach (self::DOCTRINE_MAPPING_CLASSES as $doctrineMappingClass) {
            // skip entities
            if ($nativeReflectionClass->getAttributes($doctrineMappingClass) !== []) {
                return true;
            }
        }

        return false;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($class->isFinal()) {
            return true;
        }

        if ($class->isAbstract()) {
            return true;
        }

        return $this->classAnalyzer->isAnonymousClass($class);
    }
}
