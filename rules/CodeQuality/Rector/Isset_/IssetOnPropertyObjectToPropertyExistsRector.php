<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Isset_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
<<<<<<< HEAD
use PHPStan\Reflection\Php\PhpPropertyReflection;
=======
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
>>>>>>> 81588f40e (cleanup NodeeEpository)
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\MixedType;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\Rector\AbstractRector;
<<<<<<< HEAD
use Rector\Core\Reflection\ReflectionResolver;
=======
use Rector\NodeTypeResolver\Node\AttributeKey;
>>>>>>> 81588f40e (cleanup NodeeEpository)
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\IssetOnPropertyObjectToPropertyExistsRectorTest
 *
 * @see https://3v4l.org/TI8XL Change isset on property object to property_exists() with not null check
 */
final class IssetOnPropertyObjectToPropertyExistsRector extends AbstractRector
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change isset on property object to property_exists() and not null check', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    private $x;

    public function run(): void
    {
        isset($this->x);
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class SomeClass
{
    private $x;

    public function run(): void
    {
        property_exists($this, 'x') && $this->x !== null;
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
        return [Isset_::class];
    }

    /**
     * @param Isset_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $newNodes = [];

        foreach ($node->vars as $issetVar) {
            if (! $issetVar instanceof PropertyFetch) {
                continue;
            }

<<<<<<< HEAD
            // has property PHP 7.4 type?
            if ($this->hasPropertyTypeDeclaration($issetVar)) {
                continue;
            }

=======
>>>>>>> 81588f40e (cleanup NodeeEpository)
            $propertyFetchName = $this->getName($issetVar->name);
            if ($propertyFetchName === null) {
                continue;
            }

            $propertyFetchVarType = $this->getObjectType($issetVar->var);

            if ($propertyFetchVarType instanceof TypeWithClassName) {
                if (! $this->reflectionProvider->hasClass($propertyFetchVarType->getClassName())) {
                    continue;
                }

                $classReflection = $this->reflectionProvider->getClass($propertyFetchVarType->getClassName());

                if (! $classReflection->hasProperty($propertyFetchName)) {
                    $newNodes[] = $this->replaceToPropertyExistsWithNullCheck(
                        $issetVar->var,
                        $propertyFetchName,
                        $issetVar
                    );
                } else {
                    // has property PHP 7.4 type?
                    if ($this->hasPropertyTypeDeclaration($issetVar, $classReflection)) {
                        continue;
                    }

                    $newNodes[] = $this->createNotIdenticalToNull($issetVar);
                }
            } else {
                $newNodes[] = $this->replaceToPropertyExistsWithNullCheck(
                    $issetVar->var,
                    $propertyFetchName,
                    $issetVar
                );
            }
        }

        return $this->nodeFactory->createReturnBooleanAnd($newNodes);
    }

    private function replaceToPropertyExistsWithNullCheck(
        Expr $expr,
        string $property,
        PropertyFetch $propertyFetch
    ): BooleanAnd {
        $args = [new Arg($expr), new Arg(new String_($property))];
        $propertyExistsFuncCall = $this->nodeFactory->createFuncCall('property_exists', $args);

        return new BooleanAnd($propertyExistsFuncCall, $this->createNotIdenticalToNull($propertyFetch));
    }

    private function createNotIdenticalToNull(Expr $expr): NotIdentical
    {
        return new NotIdentical($expr, $this->nodeFactory->createNull());
    }

<<<<<<< HEAD
    private function hasPropertyTypeDeclaration(PropertyFetch $propertyFetch): bool
    {
        $phpPropertyReflection = $this->reflectionResolver->resolvePropertyReflectionFromPropertyFetch($propertyFetch);
        if (! $phpPropertyReflection instanceof PhpPropertyReflection) {
            return false;
        }

        return ! $phpPropertyReflection->getNativeType() instanceof MixedType;
=======
    private function hasPropertyTypeDeclaration(PropertyFetch $propertyFetch, ClassReflection $classReflection): bool
    {
        $scope = $propertyFetch->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $propertyFetchName = $this->nodeNameResolver->getName($propertyFetch->name);
        if ($propertyFetchName === null) {
            return false;
        }

        $propertyReflection = $classReflection->getProperty($propertyFetchName, $scope);
        return ! $propertyReflection->getReadableType() instanceof MixedType;
>>>>>>> 81588f40e (cleanup NodeeEpository)
    }
}
