<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\ChildDoctrineRepositoryClassTypeRectorTest
 */
final class ChildDoctrineRepositoryClassTypeRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly NodeFinder $nodeFinder,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return type to classes that extend Doctrine\ORM\EntityRepository', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<SomeType>
 */
final class SomeRepository extends EntityRepository
{
    public function getActiveItem()
    {
        return $this->findOneBy([
            'something'
        ]);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<SomeType>
 */
final class SomeRepository extends EntityRepository
{
    public function getActiveItem(): ?SomeType
    {
        return $this->findOneBy([
            'something'
        ]);
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
        if (! $this->isObjectType($node, new ObjectType('Doctrine\ORM\EntityRepository'))) {
            return null;
        }

        $entityClassName = $this->resolveEntityClassnameFromPhpDoc($node);
        if ($entityClassName === null) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if ($this->shouldSkipClassMethod($classMethod)) {
                continue;
            }

            if ($this->containsMethodCallNamed($classMethod, 'findOneBy')) {
                $classMethod->returnType = $this->createNullableType($entityClassName);
            }

            $hasChanged = true;
            // try to figure out the return type
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function resolveEntityClassnameFromPhpDoc(Class_ $class): ?string
    {
        $classPhpDocInfo = $this->phpDocInfoFactory->createFromNode($class);

        // we need a way to resolve entity type... 1st idea is from @extends docblock
        if (! $classPhpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $extendsTagValuePhpDocNodes = $classPhpDocInfo->getTagsByName('extends');

        if ($extendsTagValuePhpDocNodes === []) {
            return null;
        }

        $extendsTagValueNode = $extendsTagValuePhpDocNodes[0]->value;
        if (! $extendsTagValueNode instanceof ExtendsTagValueNode) {
            return null;
        }

        // we look for generic type class
        if (! $extendsTagValueNode->type instanceof GenericTypeNode) {
            return null;
        }

        $genericTypeNode = $extendsTagValueNode->type;
        if ($genericTypeNode->type->name !== 'EntityRepository') {
            return null;
        }

        $entityGenericType = $genericTypeNode->genericTypes[0];

        if (! $entityGenericType instanceof IdentifierTypeNode) {
            return null;
        }

        $entityClassName = $entityGenericType->name;

        return $entityClassName;
    }

    private function containsMethodCallNamed(ClassMethod $classMethod, string $desiredMethodName): bool
    {
        return (bool) $this->nodeFinder->findFirst((array) $classMethod->stmts, function (\PhpParser\Node $node) use (
            $desiredMethodName
        ): bool {
            if (! $node instanceof Node\Expr\MethodCall) {
                return false;
            }

            if (! $node->name instanceof Node\Identifier) {
                return false;
            }

            $currentMethodCallName = $node->name->toString();
            return $currentMethodCallName === $desiredMethodName;
        });
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isPublic()) {
            return true;
        }

        if ($classMethod->isStatic()) {
            return true;
        }

        if ($classMethod->returnType instanceof \PhpParser\Node) {
            return true;
        }

        return false;
    }

    private function createNullableType(string $entityClassName): NullableType
    {
        $name = new Name($entityClassName);
        return new NullableType($name);
    }
}
