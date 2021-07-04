<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp72\NodeAnalyzer\ClassLikeWithTraitsClassMethodResolver;
use Rector\DowngradePhp72\NodeAnalyzer\ParamContravariantDetector;
use Rector\DowngradePhp72\NodeAnalyzer\ParentChildClassMethodTypeResolver;
use Rector\DowngradePhp72\PhpDoc\NativeParamToPhpDocDecorator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/migration72.new-features.php#migration72.new-features.param-type-widening
 *
 * @see https://3v4l.org/fOgSE
 *
 * @see \Rector\Tests\DowngradePhp72\Rector\Class_\DowngradeParameterTypeWideningRector\DowngradeParameterTypeWideningRectorTest
 */
final class DowngradeParameterTypeWideningRector extends AbstractRector
{
    //        $classMethods = $this->classLikeWithTraitsClassMethodResolver->resolve($ancestors);
    // @todo move to static reflection

    /**
     * @var mixed[]
     */
    private const CLASS_LIKES = [];

    public function __construct(
        private ParentChildClassMethodTypeResolver $parentChildClassMethodTypeResolver,
        private NativeParamToPhpDocDecorator $nativeParamToPhpDocDecorator,
        private ParamContravariantDetector $paramContravariantDetector,
        private TypeFactory $typeFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change param type to match the lowest type in whole family tree', [
            new CodeSample(
                <<<'CODE_SAMPLE'
interface SomeInterface
{
    public function test(array $input);
}

final class SomeClass implements SomeInterface
{
    public function test($input)
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
interface SomeInterface
{
    /**
     * @param mixed[] $input
     */
    public function test($input);
}

final class SomeClass implements SomeInterface
{
    public function test($input)
    {
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
        // @todo move to class method, with more precise scope - we dont change the class at all
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if ($this->isEmptyClassReflection($classReflection)) {
            return null;
        }

//        $hasChanged = false;

        /** @var ClassReflection[] $ancestors */
        $ancestors = $classReflection->getAncestors(); // $this->nodeRepository->findClassesAndInterfacesByType($classReflection->getName());

        $interfaceClassReflections = $classReflection->getInterfaces();
//        foreach ($classMethods as $classMethod) {
        if ($this->skipClassMethod($node, $classReflection, $ancestors, self::CLASS_LIKES)) {
            return null;
        }

        // refactor here
        return $this->refactorClassMethod($node, $classReflection, $ancestors, $interfaceClassReflections);
    }

    /**
     * The topmost class is the source of truth, so we go only down to avoid up/down collission
     * @param ClassReflection[] $ancestorClassReflections
     * @param ClassReflection[] $interfaceClassReflections
     */
    private function refactorClassMethod(
        ClassMethod $classMethod,
        ClassReflection $classReflection,
        array $ancestorClassReflections,
        array $interfaceClassReflections
    ): ?ClassMethod {
        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);

        $hasChanged = false;
        foreach ($classMethod->params as $position => $param) {
            if (! is_int($position)) {
                throw new ShouldNotHappenException();
            }

            // Resolve the types in:
            // - all ancestors + their descendant classes
            // @todo - all implemented interfaces + their implementing classes
            $parameterTypesByParentClassLikes = $this->parentChildClassMethodTypeResolver->resolve(
                $classReflection,
                $methodName,
                $position,
                $ancestorClassReflections,
                $interfaceClassReflections
            );

            $uniqueTypes = $this->typeFactory->uniquateTypes($parameterTypesByParentClassLikes);
            if (! isset($uniqueTypes[1])) {
                continue;
            }

            $this->removeParamTypeFromMethod($classMethod, $param);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $classMethod;
        }

        return null;
    }

    private function removeParamTypeFromMethod(ClassMethod $classMethod, Param $param): void
    {
        // It already has no type => nothing to do - check original param, as it could have been removed by this rule
        $originalParam = $param->getAttribute(AttributeKey::ORIGINAL_NODE);
        if ($originalParam instanceof Param && $originalParam->type === null) {
            return;
        }
        if ($param->type === null) {
            return;
        }

        // Add the current type in the PHPDoc
        $this->nativeParamToPhpDocDecorator->decorate($classMethod, $param);
        $param->type = null;
    }

    /**
     * @param ClassReflection[] $ancestorClassReflections
     * @param ClassLike[] $classLikes
     */
    private function skipClassMethod(
        ClassMethod $classMethod,
        ClassReflection $classReflection,
        array $ancestorClassReflections,
        array $classLikes
    ): bool {
        if ($classMethod->isMagic()) {
            return true;
        }

        if ($classMethod->params === []) {
            return true;
        }

        /** @var string $classMethodName */
        $classMethodName = $this->nodeNameResolver->getName($classMethod);
        if ($this->paramContravariantDetector->hasChildMethod($classLikes, $classMethodName)) {
            return false;
        }

        return ! $this->paramContravariantDetector->hasParentMethod(
            $classReflection,
            $ancestorClassReflections,
            $classMethodName
        );
    }

    private function isEmptyClassReflection(ClassReflection $classReflection): bool
    {
        if ($classReflection->isInterface()) {
            return false;
        }

        return count($classReflection->getAncestors()) === 1;
    }
}
