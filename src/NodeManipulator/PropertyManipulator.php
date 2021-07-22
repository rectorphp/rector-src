<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ParametersAcceptorSelector;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\ReadWrite\Guard\VariableToConstantGuard;
use Rector\ReadWrite\NodeAnalyzer\ReadWritePropertyAnalyzer;
use Symplify\PackageBuilder\Php\TypeChecker;

/**
 * For inspiration to improve this service,
 * @see examples of variable modifications in https://wiki.php.net/rfc/readonly_properties_v2#proposal
 */
final class PropertyManipulator
{
    public function __construct(
        private AssignManipulator $assignManipulator,
        private BetterNodeFinder $betterNodeFinder,
        private VariableToConstantGuard $variableToConstantGuard,
        private ReadWritePropertyAnalyzer $readWritePropertyAnalyzer,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private TypeChecker $typeChecker,
        private PropertyFetchFinder $propertyFetchFinder,
        private ReflectionResolver $reflectionResolver,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function isPropertyUsedInReadContext(Property | Param $propertyOrPromotedParam): bool
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($propertyOrPromotedParam);

        // @todo attributes too
        if ($phpDocInfo->hasByAnnotationClasses([
            'Doctrine\ORM\Mapping\Id',
            'Doctrine\ORM\Mapping\Column',
            'Doctrine\ORM\Mapping\OneToMany',
            'Doctrine\ORM\Mapping\ManyToMany',
            'Doctrine\ORM\Mapping\ManyToOne',
            'Doctrine\ORM\Mapping\OneToOne',
            'JMS\Serializer\Annotation\Type',
        ])) {
            return true;
        }

        // $propertyOrPromotedParam->attrGroups

        $privatePropertyFetches = $this->propertyFetchFinder->findPrivatePropertyFetches($propertyOrPromotedParam);
        foreach ($privatePropertyFetches as $privatePropertyFetch) {
            if ($this->readWritePropertyAnalyzer->isRead($privatePropertyFetch)) {
                return true;
            }
        }

        // has classLike $this->$variable call?
        /** @var ClassLike $classLike */
        $classLike = $propertyOrPromotedParam->getAttribute(AttributeKey::CLASS_NODE);

        return (bool) $this->betterNodeFinder->findFirst($classLike->stmts, function (Node $node): bool {
            if (! $node instanceof PropertyFetch) {
                return false;
            }

            if (! $this->readWritePropertyAnalyzer->isRead($node)) {
                return false;
            }

            return $node->name instanceof Expr;
        });
    }

    public function isPropertyChangeableExceptConstructor(Property | Param $propertyOrParam): bool
    {
        $propertyFetches = $this->propertyFetchFinder->findPrivatePropertyFetches($propertyOrParam);

        foreach ($propertyFetches as $propertyFetch) {
            if ($this->isChangeableContext($propertyFetch)) {
                return true;
            }

            // skip for constructor? it is allowed to set value in constructor method
            $classMethod = $propertyFetch->getAttribute(AttributeKey::METHOD_NODE);
            if ($classMethod instanceof ClassMethod && $this->nodeNameResolver->isName(
                $classMethod->name,
                MethodName::CONSTRUCT
            )) {
                continue;
            }

            if ($this->assignManipulator->isLeftPartOfAssign($propertyFetch)) {
                return true;
            }
        }

        return false;
    }

    public function isPropertyChangeable(Property $property): bool
    {
        $propertyFetches = $this->propertyFetchFinder->findPrivatePropertyFetches($property);

        foreach ($propertyFetches as $propertyFetch) {
            if ($this->isChangeableContext($propertyFetch)) {
                return true;
            }

            if ($this->assignManipulator->isLeftPartOfAssign($propertyFetch)) {
                return true;
            }
        }

        return false;
    }

    private function isChangeableContext(PropertyFetch | StaticPropertyFetch $propertyFetch): bool
    {
        $parent = $propertyFetch->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof Node) {
            return false;
        }

        if ($this->typeChecker->isInstanceOf($parent, [PreInc::class, PreDec::class, PostInc::class, PostDec::class])) {
            $parent = $parent->getAttribute(AttributeKey::PARENT_NODE);
        }

        if (! $parent instanceof Node) {
            return false;
        }

        if ($parent instanceof Arg) {
            $readArg = $this->variableToConstantGuard->isReadArg($parent);
            if (! $readArg) {
                return true;
            }

            $caller = $parent->getAttribute(AttributeKey::PARENT_NODE);
            if ($caller instanceof MethodCall || $caller instanceof StaticCall) {
                return $this->isFoundByRefParam($caller);
            }
        }

        return false;
    }

    private function isFoundByRefParam(MethodCall | StaticCall $node): bool
    {
        $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
        if ($functionLikeReflection === null) {
            return false;
        }

        $parametersAcceptor = ParametersAcceptorSelector::selectSingle($functionLikeReflection->getVariants());
        foreach ($parametersAcceptor->getParameters() as $parameterReflection) {
            if ($parameterReflection->passedByReference()->yes()) {
                return true;
            }
        }

        return false;
    }
}
