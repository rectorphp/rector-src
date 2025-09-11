<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\Type\ArrayType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\AlreadyAssignDetector\ConstructorAssignDetector;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\TrustedClassMethodPropertyTypeInferer;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DocblockVarFromParamDocblockInConstructorRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ConstructorAssignDetector $constructorAssignDetector,
        TrustedClassMethodPropertyTypeInferer $trustedClassMethodPropertyTypeInferer,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $constructorClassMethod = $node->getMethod(MethodName::CONSTRUCT);
        if (! $constructorClassMethod instanceof ClassMethod) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getProperties() as $property) {
            if (! $this->isArrayTypedProperty($property)) {
                continue;
            }

            $propertyPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);

            // @var tag already given
            if ($propertyPhpDocInfo->getVarTagValueNode() instanceof VarTagValueNode) {
                continue;
            }

            $propertyName = $this->getName($property);
            $isAssignedInConstructor = $this->constructorAssignDetector->isPropertyAssigned($node, $propertyName);
            if ($isAssignedInConstructor === false) {
                continue;
            }

            $assignedType = $this->matchAssignedPropertyArrayType($constructorClassMethod, $propertyName);
            if (! $assignedType instanceof ArrayType) {
                continue;
            }

            $arrayDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($assignedType);

            $returnTagValueNode = new VarTagValueNode($arrayDocTypeNode, '', '');
            $propertyPhpDocInfo->addTagValueNode($returnTagValueNode);

            $hasChanged = true;

            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($property);
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add @var array docblock to a property based on @param of constructor assign', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    private array $items;

    /**
     * @param string[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var string[]
     */
    private array $items;

    /**
     * @param string[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }
}
CODE_SAMPLE
            ),

        ]);
    }

    private function isArrayTypedProperty(Property $property): bool
    {
        if (! $property->type instanceof Node) {
            return false;
        }

        return $this->isName($property->type, 'array');
    }

    private function matchAssignedPropertyArrayType(
        ClassMethod $constructorClassMethod,
        string $propertyName
    ): ?ArrayType {
        $assigns = $this->betterNodeFinder->findInstancesOfScoped($constructorClassMethod->stmts, Assign::class);
        foreach ($assigns as $assign) {
            if (! $assign->var instanceof PropertyFetch) {
                continue;
            }

            $propertyFetch = $assign->var;
            if (! $this->isName($propertyFetch->var, 'this')) {
                continue;
            }

            if (! $this->isName($propertyFetch->name, $propertyName)) {
                continue;
            }

            $assignedType = $this->getType($assign->expr);
            if (! $assignedType instanceof ArrayType) {
                continue;
            }

            return $assignedType;
        }

        return null;
    }
}
