<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
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
    private const DOCTRINE_ORM_MAPPING_ANNOTATION = [
        'Doctrine\ORM\Mapping\Entity',
        'Doctrine\ORM\Mapping\Embeddable',
    ];
    
    /**
     * @var string[]
     */
    private const DOCTRINE_ODM_MAPPING_ANNOTATION = [
        'Doctrine\ODM\MongoDB\Mapping\Annotations\Document', 
        'Doctrine\ODM\MongoDB\Mapping\Annotations\EmbeddedDocument',
    ];
    
    public function __construct(
        private ClassAnalyzer $classAnalyzer,
        private FamilyRelationsAnalyzer $familyRelationsAnalyzer
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
        if ($phpDocInfo->hasByAnnotationClasses(self::DOCTRINE_ORM_MAPPING_ANNOTATION)
           || $phpDocInfo->hasByAnnotationClasses(self::DOCTRINE_ODM_MAPPING_ANNOTATION) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $childrenClassReflections = $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection);
        if ($childrenClassReflections !== []) {
            return null;
        }

        if ($this->hasDoctrineAttr($node)) {
            return null;
        }

        $this->visibilityManipulator->makeFinal($node);

        return $node;
    }

    private function hasDoctrineAttr(Class_ $class): bool
    {
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if (! $attribute->name instanceof FullyQualified) {
                    continue;
                }

                $className = $this->nodeNameResolver->getName($attribute->name);
                if (in_array($className, self::DOCTRINE_ORM_MAPPING_ANNOTATION, true)) {
                    return true;
                }
                if (in_array($className, self::DOCTRINE_ODM_MAPPING_ANNOTATION, true)) {
                    return true;
                }
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
