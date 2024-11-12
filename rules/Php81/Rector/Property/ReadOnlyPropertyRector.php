<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeAnalyzer\ParamAnalyzer;
use Rector\NodeManipulator\PropertyFetchAssignManipulator;
use Rector\NodeManipulator\PropertyManipulator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\ValueObject\Visibility;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\ReadOnlyPropertyRectorTest
 */
final class ReadOnlyPropertyRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PropertyManipulator $propertyManipulator,
        private readonly PropertyFetchAssignManipulator $propertyFetchAssignManipulator,
        private readonly ParamAnalyzer $paramAnalyzer,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Decorate read-only property with `readonly` attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(
        private string $name
    ) {
    }

    public function getName()
    {
        return $this->name;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName()
    {
        return $this->name;
    }
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
        $scope = ScopeFetcher::fetch($node);
        if ($this->shouldSkip($node)) {
            return null;
        }

        $hasChanged = false;

        $classMethod = $node->getMethod(MethodName::CONSTRUCT);
        if ($classMethod instanceof ClassMethod) {
            foreach ($classMethod->params as $param) {
                $justChanged = $this->refactorParam($node, $classMethod, $param, $scope);
                // different variable to ensure $hasRemoved not replaced
                if ($justChanged instanceof Param) {
                    $hasChanged = true;
                }
            }
        }

        foreach ($node->getProperties() as $property) {
            $changedProperty = $this->refactorProperty($node, $property, $scope);
            if ($changedProperty instanceof Property) {
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::READONLY_PROPERTY;
    }

    private function refactorProperty(Class_ $class, Property $property, Scope $scope): ?Property
    {
        // 1. is property read-only?
        if ($property->isReadonly()) {
            return null;
        }

        if ($property->props[0]->default instanceof Expr) {
            return null;
        }

        if ($property->type === null) {
            return null;
        }

        if ($property->isStatic()) {
            return null;
        }

        if (! $this->visibilityManipulator->hasVisibility($property, Visibility::PRIVATE)) {
            return null;
        }

        if ($this->propertyManipulator->isPropertyChangeableExceptConstructor($class, $property, $scope)) {
            return null;
        }

        if ($this->propertyFetchAssignManipulator->isAssignedMultipleTimesInConstructor($class, $property)) {
            return null;
        }

        $this->visibilityManipulator->makeReadonly($property);

        $attributeGroups = $property->attrGroups;
        if ($attributeGroups !== []) {
            $property->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }

        $this->removeReadOnlyDoc($property);

        return $property;
    }

    private function removeReadOnlyDoc(Property|Param $node): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $readonlyDoc = $phpDocInfo->getByName('readonly');
        if (! $readonlyDoc instanceof PhpDocTagNode) {
            return;
        }

        if (! $readonlyDoc->value instanceof GenericTagValueNode) {
            return;
        }

        if ($readonlyDoc->value->value !== '') {
            return;
        }

        $phpDocInfo->removeByName('readonly');
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
    }

    private function refactorParam(Class_ $class, ClassMethod $classMethod, Param $param, Scope $scope): Param | null
    {
        if (! $this->visibilityManipulator->hasVisibility($param, Visibility::PRIVATE)) {
            return null;
        }

        if ($param->type === null) {
            return null;
        }

        // early check not property promotion and already readonly
        if ($param->flags === 0 || $this->visibilityManipulator->isReadonly($param)) {
            return null;
        }

        if ($this->propertyManipulator->isPropertyChangeableExceptConstructor($class, $param, $scope)) {
            return null;
        }

        if ($this->paramAnalyzer->isParamReassign($classMethod, $param)) {
            return null;
        }

        if ($this->isPromotedPropertyAssigned($class, $param)) {
            return null;
        }

        if ($param->attrGroups !== []) {
            $param->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }

        $this->visibilityManipulator->makeReadonly($param);

        $this->removeReadOnlyDoc($param);

        return $param;
    }

    private function isPromotedPropertyAssigned(Class_ $class, Param $param): bool
    {
        $constructClassMethod = $class->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            return false;
        }

        if ($param->flags === 0) {
            return false;
        }

        $propertyFetch = new PropertyFetch(new Variable('this'), $this->getName($param));

        $isAssigned = false;
        $this->traverseNodesWithCallable($class->stmts, function (Node $node) use ($propertyFetch, &$isAssigned): ?int {
            if (! $node instanceof Assign) {
                return null;
            }

            if ($this->nodeComparator->areNodesEqual($propertyFetch, $node->var)) {
                $isAssigned = true;
                return NodeTraverser::STOP_TRAVERSAL;
            }

            return null;
        });

        return $isAssigned;
    }

    private function shouldSkip(Class_ $class): bool
    {
        if ($class->isReadonly()) {
            return true;
        }

        // not safe
        if ($class->getTraitUses() !== []) {
            return true;
        }

        // skip "clone $this" cases, as can create unexpected write to local constructor property
        return $this->hasCloneThis($class);
    }

    private function hasCloneThis(Class_ $class): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($class, function (Node $node): bool {
            if (! $node instanceof Clone_) {
                return false;
            }

            if (! $node->expr instanceof Variable) {
                return false;
            }

            return $this->isName($node->expr, 'this');
        });
    }
}
