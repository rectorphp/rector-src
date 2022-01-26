<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\AddParamTypeDeclarationRectorTest
 */
final class AddParamTypeDeclarationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const PARAMETER_TYPEHINTS = 'parameter_typehints';

    /**
     * @var AddParamTypeDeclaration[]
     */
    private array $addParamTypeDeclarations = [];

    public function __construct(
        private readonly TypeComparator $typeComparator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add param types where needed', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function process($name)
    {
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function process(string $name)
    {
    }
}
CODE_SAMPLE
            ,
                [new AddParamTypeDeclaration('SomeClass', 'process', 0, new StringType())]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var ClassLike $classLike */
        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);

        foreach ($this->addParamTypeDeclarations as $addParamTypeDeclaration) {
            if (! $this->isObjectType($classLike, $addParamTypeDeclaration->getObjectType())) {
                continue;
            }

            if (! $this->isName($node, $addParamTypeDeclaration->getMethodName())) {
                continue;
            }

            $this->refactorClassMethodWithTypehintByParameterPosition($node, $addParamTypeDeclaration);
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $parameterTypehints = $configuration[self::PARAMETER_TYPEHINTS] ?? $configuration;
        Assert::isArray($parameterTypehints);
        Assert::allIsAOf($parameterTypehints, AddParamTypeDeclaration::class);

        $this->addParamTypeDeclarations = $parameterTypehints;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        // skip class methods without args
        if ($classMethod->params === []) {
            return true;
        }

        $classLike = $this->betterNodeFinder->findParentType($classMethod, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return true;
        }

        // skip traits
        if ($classLike instanceof Trait_) {
            return true;
        }

        // skip class without parents/interfaces
        if ($classLike instanceof Class_) {
            if ($classLike->implements !== []) {
                return false;
            }

            if ($classLike->extends !== null) {
                return false;
            }

            return true;
        }

        // skip interface without parents
        /** @var Interface_ $classLike */
        return ! (bool) $classLike->extends;
    }

    private function refactorClassMethodWithTypehintByParameterPosition(
        ClassMethod $classMethod,
        AddParamTypeDeclaration $addParamTypeDeclaration
    ): void {
        $parameter = $classMethod->params[$addParamTypeDeclaration->getPosition()] ?? null;
        if (! $parameter instanceof Param) {
            return;
        }

        $this->refactorParameter($parameter, $addParamTypeDeclaration);
    }

    private function refactorParameter(Param $param, AddParamTypeDeclaration $addParamTypeDeclaration): void
    {

        // already set → no change
        if ($param->type !== null) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual($currentParamType, $addParamTypeDeclaration->getParamType())) {
                return;
            }
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            $addParamTypeDeclaration->getParamType(),
            TypeKind::PARAM()
        );

        // remove it
        if ($addParamTypeDeclaration->getParamType() instanceof MixedType) {
            if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::MIXED_TYPE)) {
                $param->type = $paramTypeNode;
                return;
            }

            $param->type = null;
            return;
        }

        $param->type = $paramTypeNode;
    }
}
