<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\NodeAnalyzer\ParamAnalyzer;
use Rector\Core\NodeAnalyzer\PropertyAnalyzer;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\Naming\VariableRenamer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\NodeAnalyzer\PromotedPropertyCandidateResolver;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/constructor_promotion https://github.com/php/php-src/pull/5291
 *
 * @see \Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\ClassPropertyAssignToConstructorPromotionRectorTest
 */
final class ClassPropertyAssignToConstructorPromotionRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PromotedPropertyCandidateResolver $promotedPropertyCandidateResolver,
        private readonly VariableRenamer $variableRenamer,
        private readonly VarTagRemover $varTagRemover,
        private readonly ParamAnalyzer $paramAnalyzer,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PropertyAnalyzer $propertyAnalyzer,
        private readonly PropertyFetchFinder $propertyFetchFinder,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change simple property init and assign to constructor promotion',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public float $someVariable;

    public function __construct(
        float $someVariable = 0.0
    ) {
        $this->someVariable = $someVariable;
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(
        private float $someVariable = 0.0
    ) {
    }
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
        $promotionCandidates = $this->promotedPropertyCandidateResolver->resolveFromClass($node);
        if ($promotionCandidates === []) {
            return null;
        }

        /** @var ClassMethod $constructClassMethod */
        $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);

        $classMethodPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($constructClassMethod);

        foreach ($promotionCandidates as $promotionCandidate) {
            // does property have some useful annotations?
            $property = $promotionCandidate->getProperty();
            $param = $promotionCandidate->getParam();

            if ($this->shouldSkipParam($param)) {
                continue;
            }

            if ($this->propertyAnalyzer->hasForbiddenType($property)) {
                continue;
            }

            $props = $property->props[0];
            $propertyName = $this->getName($props);
            $this->removeNode($property);
            $this->removeNode($promotionCandidate->getAssign());

            /** @var string $paramName */
            $paramName = $this->getName($param);

            $this->variableRenamer->renameVariableInFunctionLike($constructClassMethod, $propertyName, $paramName, null);

            $propertyFetches = $this->propertyFetchFinder->findLocalPropertyFetchesByName($node, $propertyName);
            $newName = new Identifier($paramName);
            foreach ($propertyFetches as $propertyFetch) {
                $propertyFetch->name = $newName;
            }

            $paramTagValueNode = $classMethodPhpDocInfo->getParamTagValueNodeByName($paramName);

            if (! $paramTagValueNode instanceof ParamTagValueNode) {
                $this->decorateParamWithPropertyPhpDocInfo($constructClassMethod, $property, $param, $paramName);
            }

            // param name has higher priority
            $props->name->name = $paramName;
            $param->flags = $property->flags;
            // Copy over attributes of the "old" property
            $param->attrGroups = $property->attrGroups;
            $this->processNullableType($property, $param);

            $this->phpDocTypeChanger->copyPropertyDocToParam($property, $param);
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PROPERTY_PROMOTION;
    }

    private function processNullableType(Property $property, Param $param): void
    {
        if ($this->nodeTypeResolver->isNullableType($property)) {
            $objectType = $this->getType($property);
            $param->type = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($objectType, TypeKind::PARAM);
        }
    }

    private function decorateParamWithPropertyPhpDocInfo(
        ClassMethod $classMethod,
        Property $property,
        Param $param,
        string $paramName
    ): void {
        $propertyPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
        $propertyPhpDocInfo->markAsChanged();

        $param->setAttribute(AttributeKey::PHP_DOC_INFO, $propertyPhpDocInfo);

        // make sure the docblock is useful
        if ($param->type === null) {
            $varTagValueNode = $propertyPhpDocInfo->getVarTagValueNode();
            if (! $varTagValueNode instanceof VarTagValueNode) {
                return;
            }

            $paramType = $this->staticTypeMapper->mapPHPStanPhpDocTypeToPHPStanType($varTagValueNode, $property);
            $classMethodPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
            $this->phpDocTypeChanger->changeParamType($classMethodPhpDocInfo, $paramType, $param, $paramName);
        } else {
            $paramType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
        }

        $this->varTagRemover->removeVarPhpTagValueNodeIfNotComment($param, $paramType);
    }

    private function shouldSkipParam(Param $param): bool
    {
        if ($param->variadic) {
            return true;
        }

        if ($this->paramAnalyzer->isNullable($param)) {
            /** @var NullableType $type */
            $type = $param->type;
            $type = $type->type;
        } else {
            $type = $param->type;
        }

        if (! $type instanceof UnionType) {
            return false;
        }

        foreach ($type->types as $type) {
            if (! $type instanceof Identifier) {
                continue;
            }

            if (! $this->isName($type, 'callable')) {
                continue;
            }

            return true;
        }

        return false;
    }
}
