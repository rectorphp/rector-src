<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\PhpParser\AstResolver;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\AddTypeToConstRectorTest
 */
final class AddTypeToConstRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface
{
    /**
     * @api
     * @var string
     */
    final public const ALLOW_HAS_CHILD = 'allow_has_child';

    private bool $allowHasChild = false;

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private readonly AstResolver $astResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add type to constants', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public const TYPE = 'some_type';
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public const string TYPE = 'some_type';
}
CODE_SAMPLE
                ,
                [
                    AddTypeToConstRector::ALLOW_HAS_CHILD => false,
                ]
            ),
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass extends SomeOtherClass
{
    public const TYPE = 'some_type';
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass extends SomeOtherClass
{
    public const string TYPE = 'some_type';
}
CODE_SAMPLE
                ,
                [
                    AddTypeToConstRector::ALLOW_HAS_CHILD => true,
                ]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Class_
    {
        $className = $this->getName($node);
        if (! is_string($className)) {
            return null;
        }

        if ($node->isAbstract()) {
            return null;
        }

        $classConsts = $node->getConstants();
        if ($classConsts === []) {
            return null;
        }

        $parentClassReflections = $this->getParentReflections($className);
        $childClassReflections = $this->getChildClassReflections($className);

        $hasChanged = false;

        foreach ($classConsts as $classConst) {
            $valueTypes = [];
            $valueType = null;

            // If a type is set, skip
            if ($classConst->type !== null) {
                continue;
            }

            foreach ($classConst->consts as $constNode) {
                if ($this->isConstGuardedByParents($constNode, $parentClassReflections)) {
                    continue;
                }

                if (! $this->allowHasChild && $this->canBeInherited($classConst, $node)) {
                    continue;
                }

                if ($this->allowHasChild && $this->isConstGuardedByChildren($constNode, $childClassReflections)) {
                    continue;
                }

                $valueTypes[] = $this->findValueType($constNode->value);
            }

            if ($valueTypes === []) {
                continue;
            }

            if (count($valueTypes) > 1) {
                $valueTypes = array_unique($valueTypes, SORT_REGULAR);
            }

            // once more verify after uniquate
            if (count($valueTypes) > 1) {
                continue;
            }

            $valueType = current($valueTypes);
            if (! $valueType instanceof Identifier) {
                continue;
            }

            $classConst->type = $valueType;
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $ignoreInheritance = $configuration[self::ALLOW_HAS_CHILD] ?? (bool) current($configuration);
        Assert::boolean($ignoreInheritance);
        $this->allowHasChild = $ignoreInheritance;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_CLASS_CONSTANTS;
    }

    /**
     * @param ClassReflection[] $parentClassReflections
     */
    public function isConstGuardedByParents(Const_ $const, array $parentClassReflections): bool
    {
        $constantName = $this->getName($const);

        foreach ($parentClassReflections as $parentClassReflection) {
            if ($parentClassReflection->hasConstant($constantName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ClassReflection[] $childClassReflections
     */
    public function isConstGuardedByChildren(Const_ $const, array $childClassReflections): bool
    {
        foreach ($childClassReflections as $childClassReflection) {
            $classLike = $this->astResolver->resolveClassFromClassReflection($childClassReflection);
            if (! $classLike instanceof Class_) {
                continue;
            }

            foreach ($classLike->getConstants() as $childClassConstants) {
                foreach ($childClassConstants->consts as $childConst) {
                    if ($this->nodeComparator->areNodesEqual($childConst, $const)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function findValueType(Expr $expr): ?Identifier
    {
        if ($expr instanceof UnaryPlus || $expr instanceof UnaryMinus) {
            return $this->findValueType($expr->expr);
        }

        if ($expr instanceof String_) {
            return new Identifier('string');
        }

        if ($expr instanceof LNumber) {
            return new Identifier('int');
        }

        if ($expr instanceof DNumber) {
            return new Identifier('float');
        }

        if ($expr instanceof ConstFetch) {
            if ($expr->name->toLowerString() === 'null') {
                return new Identifier('null');
            }

            $type = $this->nodeTypeResolver->getNativeType($expr);
            $nodeType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::PROPERTY);

            if (! $nodeType instanceof Identifier) {
                return null;
            }

            return $nodeType;
        }

        if ($expr instanceof Array_) {
            return new Identifier('array');
        }

        if ($expr instanceof Concat) {
            return new Identifier('string');
        }

        return null;
    }

    /**
     * @return ClassReflection[]
     */
    private function getParentReflections(string $className): array
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $currentClassReflection = $this->reflectionProvider->getClass($className);

        return array_filter($currentClassReflection->getAncestors(), static fn (ClassReflection $classReflection): bool =>
            // skip base class
            $currentClassReflection !== $classReflection);
    }

    /**
     * @return ClassReflection[]
     */
    private function getChildClassReflections(string $className): array
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $currentClassReflection = $this->reflectionProvider->getClass($className);

        return $this->familyRelationsAnalyzer->getChildrenOfClassReflection($currentClassReflection);
    }

    private function canBeInherited(ClassConst $classConst, Class_ $class): bool
    {
        if (! $this->allowHasChild) {
            return ! $class->isFinal() && ! $classConst->isPrivate() && ! $classConst->isFinal();
        }
    }
}
