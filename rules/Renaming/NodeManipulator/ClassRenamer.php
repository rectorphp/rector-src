<?php

declare(strict_types=1);

namespace Rector\Renaming\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\UseUse;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocClassRenamer;
use Rector\BetterPhpDocParser\ValueObject\NodeTypes;
use Rector\CodingStyle\Naming\ClassNaming;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Util\FileHasher;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockClassRenamer;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\Renaming\Helper\RenameClassCallbackHandler;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class ClassRenamer
{
    /**
     * @var string[]
     */
    private array $alreadyProcessedClasses = [];

    /**
     * @var array<string, OldToNewType[]>
     */
    private array $oldToNewTypesByCacheKey = [];

    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly ClassNaming $classNaming,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PhpDocClassRenamer $phpDocClassRenamer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockClassRenamer $docBlockClassRenamer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly RenameClassCallbackHandler $renameClassCallbackHandler,
        private readonly FileHasher $fileHasher
    ) {
    }

    /**
     * @param array<string, string> $oldToNewClasses
     */
    public function renameNode(Node $node, array $oldToNewClasses, ?Scope $scope): ?Node
    {
        $oldToNewTypes = $this->createOldToNewTypes($node, $oldToNewClasses);
        $this->refactorPhpDoc($node, $oldToNewTypes, $oldToNewClasses);

        if ($node instanceof Name) {
            return $this->refactorName($node, $oldToNewClasses);
        }

        if ($node instanceof Namespace_) {
            return $this->refactorNamespace($node, $oldToNewClasses);
        }

        if ($node instanceof ClassLike) {
            return $this->refactorClassLike($node, $oldToNewClasses, $scope);
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if ($phpDocInfo->hasChanged()) {
            return $node;
        }

        return null;
    }

    /**
     * @param OldToNewType[] $oldToNewTypes
     * @param array<string, string> $oldToNewClasses
     */
    private function refactorPhpDoc(Node $node, array $oldToNewTypes, array $oldToNewClasses): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if (! $phpDocInfo->hasByTypes(NodeTypes::TYPE_AWARE_NODES) && ! $phpDocInfo->hasByAnnotationClasses(
            NodeTypes::TYPE_AWARE_DOCTRINE_ANNOTATION_CLASSES
        )) {
            return;
        }

        if ($node instanceof AttributeGroup) {
            return;
        }

        $this->docBlockClassRenamer->renamePhpDocType($phpDocInfo, $oldToNewTypes);

        $this->phpDocClassRenamer->changeTypeInAnnotationTypes($node, $phpDocInfo, $oldToNewClasses);
    }

    private function shouldSkip(string $newName, Name $name): bool
    {
        if ($name->getAttribute(AttributeKey::IS_STATICCALL_CLASS_NAME) === true && $this->reflectionProvider->hasClass(
            $newName
        )) {
            $classReflection = $this->reflectionProvider->getClass($newName);
            return $classReflection->isInterface();
        }

        return false;
    }

    /**
     * @param array<string, string> $oldToNewClasses
     */
    private function refactorName(Name $name, array $oldToNewClasses): ?Name
    {
        if ($name->getAttribute(AttributeKey::IS_NAMESPACE_NAME) === true) {
            return null;
        }

        $stringName = $this->nodeNameResolver->getName($name);

        $newName = $oldToNewClasses[$stringName] ?? null;
        if ($newName === null) {
            return null;
        }

        if (! $this->isClassToInterfaceValidChange($name, $newName)) {
            return null;
        }

        // no need to preslash "use \SomeNamespace" of imported namespace
        if ($name->getAttribute(AttributeKey::IS_USEUSE_NAME) === true) {
            // no need to rename imports, they will be handled by autoimport and coding standard
            // also they might cause some rename
            return null;
        }

        if ($this->shouldSkip($newName, $name)) {
            return null;
        }

        return new FullyQualified($newName);
    }

    /**
     * @param array<string, string> $oldToNewClasses
     */
    private function refactorNamespace(Namespace_ $namespace, array $oldToNewClasses): ?Node
    {
        $name = $this->nodeNameResolver->getName($namespace);
        if ($name === null) {
            return null;
        }

        $classLike = $this->getClassOfNamespaceToRefactor($namespace, $oldToNewClasses);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $currentName = (string) $this->nodeNameResolver->getName($classLike);
        $newClassFullyQualified = $oldToNewClasses[$currentName];

        if ($this->reflectionProvider->hasClass($newClassFullyQualified)) {
            return null;
        }

        $newNamespace = $this->classNaming->getNamespace($newClassFullyQualified);
        // Renaming to class without namespace (example MyNamespace\DateTime -> DateTimeImmutable)
        if (! is_string($newNamespace)) {
            $classLike->name = new Identifier($newClassFullyQualified);

            return $classLike;
        }

        $namespace->name = new Name($newNamespace);

        return $namespace;
    }

    /**
     * @param array<string, string> $oldToNewClasses
     */
    private function refactorClassLike(ClassLike $classLike, array $oldToNewClasses, ?Scope $scope): ?Node
    {
        // rename interfaces
        $this->renameClassImplements($classLike, $oldToNewClasses, $scope);

        $className = (string) $this->nodeNameResolver->getName($classLike);

        $newName = $oldToNewClasses[$className] ?? null;
        if ($newName === null) {
            return null;
        }

        // prevents re-iterating same class in endless loop
        if (in_array($className, $this->alreadyProcessedClasses, true)) {
            return null;
        }

        $this->alreadyProcessedClasses[] = $className;

        $newName = $oldToNewClasses[$className];
        $newClassNamePart = $this->nodeNameResolver->getShortName($newName);
        $newNamespacePart = $this->classNaming->getNamespace($newName);
        if ($this->isClassAboutToBeDuplicated($newName)) {
            return null;
        }

        $classLike->name = new Identifier($newClassNamePart);
        $classNamingGetNamespace = $this->classNaming->getNamespace($className);

        // Old class did not have any namespace, we need to wrap class with Namespace_ node
        if ($newNamespacePart !== null && $classNamingGetNamespace === null) {
            $this->changeNameToFullyQualifiedName($classLike);

            $name = new Name($newNamespacePart);
            return new Namespace_($name, [$classLike]);
        }

        return $classLike;
    }

    /**
     * Checks validity:
     *
     * - extends SomeClass
     * - extends SomeInterface
     *
     * - new SomeClass
     * - new SomeInterface
     *
     * - implements SomeInterface
     * - implements SomeClass
     */
    private function isClassToInterfaceValidChange(Name $name, string $newClassName): bool
    {
        if (! $this->reflectionProvider->hasClass($newClassName)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($newClassName);

        // ensure new is not with interface
        if ($name->getAttribute(AttributeKey::IS_NEW_INSTANCE_NAME) === true && $classReflection->isInterface()) {
            return false;
        }

        $parentNode = $name->getAttribute(AttributeKey::PARENT_NODE);
        if ($parentNode instanceof Class_) {
            return $this->isValidClassNameChange($name, $parentNode, $classReflection);
        }

        // prevent to change to import, that already exists
        if ($parentNode instanceof UseUse) {
            return $this->isValidUseImportChange($newClassName, $parentNode);
        }

        return true;
    }

    /**
     * @param array<string, string> $oldToNewClasses
     */
    private function getClassOfNamespaceToRefactor(Namespace_ $namespace, array $oldToNewClasses): ?ClassLike
    {
        $foundClass = $this->betterNodeFinder->findFirst($namespace, function (Node $node) use (
            $oldToNewClasses
        ): bool {
            if (! $node instanceof ClassLike) {
                return false;
            }

            $classLikeName = $this->nodeNameResolver->getName($node);

            return isset($oldToNewClasses[$classLikeName]);
        });

        return $foundClass instanceof ClassLike ? $foundClass : null;
    }

    /**
     * @param string[] $oldToNewClasses
     */
    private function renameClassImplements(ClassLike $classLike, array $oldToNewClasses, ?Scope $scope): void
    {
        if (! $classLike instanceof Class_) {
            return;
        }

        $classLike->implements = array_unique($classLike->implements);
        foreach ($classLike->implements as $key => $implementName) {
            $virtualNode = (bool) $implementName->getAttribute(AttributeKey::VIRTUAL_NODE);
            if (! $virtualNode) {
                continue;
            }

            $namespaceName = $scope instanceof Scope ? $scope->getNamespace() : null;

            $fullyQualifiedName = $namespaceName . '\\' . $implementName->toString();
            $newName = $oldToNewClasses[$fullyQualifiedName] ?? null;
            if ($newName === null) {
                continue;
            }

            $classLike->implements[$key] = new FullyQualified($newName);
        }
    }

    private function isClassAboutToBeDuplicated(string $newName): bool
    {
        return $this->reflectionProvider->hasClass($newName);
    }

    private function changeNameToFullyQualifiedName(ClassLike $classLike): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($classLike, static function (Node $node) {
            if (! $node instanceof FullyQualified) {
                return null;
            }

            // invoke override
            $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        });
    }

    private function isValidClassNameChange(Name $name, Class_ $class, ClassReflection $classReflection): bool
    {
        if ($class->extends === $name) {
            // is class to interface?
            if ($classReflection->isInterface()) {
                return false;
            }

            if ($classReflection->isFinalByKeyword()) {
                return false;
            }
        }

        // is interface to class?
        return ! (in_array($name, $class->implements, true) && $classReflection->isClass());
    }

    private function isValidUseImportChange(string $newName, UseUse $useUse): bool
    {
        $uses = $this->useImportsResolver->resolveForNode($useUse);
        if ($uses === []) {
            return true;
        }

        foreach ($uses as $use) {
            $prefix = $this->useImportsResolver->resolvePrefix($use);

            foreach ($use->uses as $useUse) {
                if ($prefix . $useUse->name->toString() === $newName) {
                    // name already exists
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param array<string, string> $oldToNewClasses
     * @return OldToNewType[]
     */
    private function createOldToNewTypes(Node $node, array $oldToNewClasses): array
    {
        $oldToNewClasses = $this->resolveOldToNewClassCallbacks($node, $oldToNewClasses);

        $serialized = \serialize($oldToNewClasses);
        $cacheKey = $this->fileHasher->hash($serialized);

        if (isset($this->oldToNewTypesByCacheKey[$cacheKey])) {
            return $this->oldToNewTypesByCacheKey[$cacheKey];
        }

        $oldToNewTypes = [];

        foreach ($oldToNewClasses as $oldClass => $newClass) {
            $oldObjectType = new ObjectType($oldClass);
            $newObjectType = new FullyQualifiedObjectType($newClass);
            $oldToNewTypes[] = new OldToNewType($oldObjectType, $newObjectType);
        }

        $this->oldToNewTypesByCacheKey[$cacheKey] = $oldToNewTypes;

        return $oldToNewTypes;
    }

    /**
     * @param array<string, string> $oldToNewClasses
     * @return array<string, string>
     */
    private function resolveOldToNewClassCallbacks(Node $node, array $oldToNewClasses): array
    {
        return [...$oldToNewClasses, ...$this->renameClassCallbackHandler->getOldToNewClassesFromNode($node)];
    }
}
