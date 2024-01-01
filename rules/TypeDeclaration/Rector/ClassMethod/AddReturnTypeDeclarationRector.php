<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\AddReturnTypeDeclarationRectorTest
 */
final class AddReturnTypeDeclarationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var AddReturnTypeDeclaration[]
     */
    private array $methodReturnTypes = [];

    private bool $hasChanged = false;

    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $arrayType = new ArrayType(new MixedType(), new MixedType());

        return new RuleDefinition('Changes defined return typehint of method and class.', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getData()
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getData(): array
    {
    }
}
CODE_SAMPLE
                ,
                [new AddReturnTypeDeclaration('SomeClass', 'getData', $arrayType)]
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
        $this->hasChanged = false;

        foreach ($this->methodReturnTypes as $methodReturnType) {
            $objectType = $methodReturnType->getObjectType();
            if (! $this->isObjectType($node, $objectType)) {
                continue;
            }

            foreach ($node->getMethods() as $classMethod) {
                if (! $this->isName($classMethod, $methodReturnType->getMethod())) {
                    continue;
                }

                $this->processClassMethodNodeWithTypehints(
                    $classMethod,
                    $node,
                    $methodReturnType->getReturnType(),
                    $objectType
                );
            }
        }

        if (! $this->hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AddReturnTypeDeclaration::class);

        $this->methodReturnTypes = $configuration;
    }

    private function processClassMethodNodeWithTypehints(
        ClassMethod $classMethod,
        Class_ $class,
        Type $newType,
        ObjectType $objectType
    ): void {
        if ($newType instanceof MixedType) {
            $className = (string) $this->nodeNameResolver->getName($class);
            $currentObjectType = new ObjectType($className);

            if (! $objectType->equals($currentObjectType) && $classMethod->returnType !== null) {
                return;
            }
        }

        // remove it
        if ($newType instanceof MixedType && ! $this->phpVersionProvider->isAtLeastPhpVersion(
            PhpVersionFeature::MIXED_TYPE
        )) {
            $classMethod->returnType = null;
            return;
        }

        // already set and sub type or equal → no change
        if ($this->parentClassMethodTypeOverrideGuard->shouldSkipReturnTypeChange($classMethod, $newType)) {
            return;
        }

        $classMethod->returnType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($newType, TypeKind::RETURN);
        $this->hasChanged = true;
    }
}
