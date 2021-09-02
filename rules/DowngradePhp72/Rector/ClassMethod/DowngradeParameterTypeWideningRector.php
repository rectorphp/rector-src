<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp72\NodeAnalyzer\BuiltInMethodAnalyzer;
use Rector\DowngradePhp72\NodeAnalyzer\OverrideFromAnonymousClassMethodAnalyzer;
use Rector\DowngradePhp72\NodeAnalyzer\SealedClassAnalyzer;
use Rector\DowngradePhp72\PhpDoc\NativeParamToPhpDocDecorator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\TypeDeclaration\NodeAnalyzer\AutowiredClassMethodOrPropertyAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;
use PHPStan\Reflection\Php\PhpMethodReflection;

/**
 * @changelog https://www.php.net/manual/en/migration72.new-features.php#migration72.new-features.param-type-widening
 * @see https://3v4l.org/fOgSE
 *
 * @see \Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\DowngradeParameterTypeWideningRectorTest
 */
final class DowngradeParameterTypeWideningRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const SAFE_TYPES = 'safe_types';

    /**
     * @var string
     */
    public const SAFE_TYPES_TO_METHODS = 'safe_types_to_methods';

    /**
     * @var class-string[]
     */
    private array $safeTypes = [];

    /**
     * @var array<class-string, string[]>
     */
    private array $safeTypesToMethods = [];

    public function __construct(
        private NativeParamToPhpDocDecorator $nativeParamToPhpDocDecorator,
        private ReflectionProvider $reflectionProvider,
        private AutowiredClassMethodOrPropertyAnalyzer $autowiredClassMethodOrPropertyAnalyzer,
        private BuiltInMethodAnalyzer $builtInMethodAnalyzer,
        private OverrideFromAnonymousClassMethodAnalyzer $overrideFromAnonymousClassMethodAnalyzer,
        private AstResolver $astResolver,
        private SealedClassAnalyzer $sealedClassAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change param type to match the lowest type in whole family tree', [
            new ConfiguredCodeSample(
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
            ,
                [
                    self::SAFE_TYPES => [],
                    self::SAFE_TYPES_TO_METHODS => [],
                ]
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
        $classLike = $node->getAttribute(AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return null;
        }

        $scope = $classLike->getAttribute(AttributeKey::SCOPE);
        $methodName = $this->nodeNameResolver->getName($node);

        if ($this->overrideFromAnonymousClassMethodAnalyzer->isOverrideParentMethod($classLike, $node)) {
            return $this->processAnonymousOverride($classLike, $node, $methodName, $scope);
        }

        $className = $this->nodeNameResolver->getName($classLike);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        if ($this->shouldSkip($classReflection, $methodName, $node, $scope)) {
            return null;
        }

        if ($this->builtInMethodAnalyzer->isImplementsBuiltInInterface($classReflection, $node)) {
            return null;
        }

        return $this->processRemoveParamTypeFromMethod($node);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $safeTypes = $configuration[self::SAFE_TYPES] ?? [];
        Assert::allString($safeTypes);
        $this->safeTypes = $safeTypes;

        $safeTypesToMethods = $configuration[self::SAFE_TYPES_TO_METHODS] ?? [];
        Assert::isArray($safeTypesToMethods);
        foreach ($safeTypesToMethods as $key => $value) {
            Assert::string($key);
            Assert::allString($value);
        }

        $this->safeTypesToMethods = $safeTypesToMethods;
    }

    private function processAnonymousOverride(
        Class_ $class,
        ClassMethod $node,
        string $methodName,
        ?Scope $scope
    ): ?ClassMethod
    {
        if ($class->implements !== []) {
            $interfaces = $class->implements;
            foreach ($interfaces as $interface) {
                $classReflection = $this->reflectionProvider->getClass($interface->toString());
                $classMethod = $this->resolveClassMethod($classReflection, $methodName);

                if (! $this->shouldSkip($classReflection, $methodName, $classMethod, $scope)) {
                    return $this->processRemoveParamTypeFromMethod($node);
                }
            }
        }

        if (! $class->extends instanceof FullyQualified) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($class->extends->toString());
        $classMethod = $this->resolveClassMethod($classReflection, $methodName);

        if ($this->shouldSkip($classReflection, $methodName, $classMethod, $scope)) {
            return null;
        }

        return $this->processRemoveParamTypeFromMethod($node);
    }

    private function resolveClassMethod(?ClassReflection $classReflection, string $methodName): ?ClassMethod
    {
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        return $this->astResolver->resolveClassMethod($classReflection->getName(), $methodName);
    }

    private function shouldSkip(
        ClassReflection $classReflection,
        string $methodName,
        ?ClassMethod $classMethod,
        ?Scope $scope
    ): bool
    {
        if (! $scope instanceof Scope) {
            return true;
        }

        // from interface
        if (! $classMethod instanceof ClassMethod) {
            $methodReflection = $classReflection->getMethod($methodName, $scope);
            if (! $methodReflection instanceof PhpMethodReflection) {
                return true;
            }

            return $methodReflection->isPrivate();
        }

        if ($this->sealedClassAnalyzer->isSealedClass($classReflection)) {
            return true;
        }

        if ($this->isSafeType($classReflection, $classMethod)) {
            return true;
        }

        if ($classMethod->isPrivate()) {
            return true;
        }

        return $this->shouldSkipClassMethod($classMethod);
    }

    private function processRemoveParamTypeFromMethod(ClassMethod $classMethod): ClassMethod
    {
        // Downgrade every scalar parameter, just to be sure
        foreach (array_keys($classMethod->params) as $paramPosition) {
            $this->removeParamTypeFromMethod($classMethod, $paramPosition);
        }

        return $classMethod;
    }

    private function removeParamTypeFromMethod(ClassMethod $classMethod, int $paramPosition): void
    {
        $param = $classMethod->params[$paramPosition] ?? null;
        if (! $param instanceof Param) {
            return;
        }

        // It already has no type => nothing to do - check original param, as it could have been removed by this rule
        if ($param->type === null) {
            return;
        }

        // Add the current type in the PHPDoc
        $this->nativeParamToPhpDocDecorator->decorate($classMethod, $param);
        $param->type = null;
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        if ($classMethod->isMagic()) {
            return true;
        }

        if ($classMethod->params === []) {
            return true;
        }

        if ($this->autowiredClassMethodOrPropertyAnalyzer->detect($classMethod)) {
            return true;
        }

        foreach ($classMethod->params as $param) {
            if ($param->type !== null) {
                return false;
            }
        }

        return true;
    }

    private function isSafeType(ClassReflection $classReflection, ClassMethod $classMethod): bool
    {
        foreach ($this->safeTypes as $safeType) {
            if ($classReflection->isSubclassOf($safeType)) {
                return true;
            }

            // skip self too
            if ($classReflection->getName() === $safeType) {
                return true;
            }
        }

        foreach ($this->safeTypesToMethods as $safeType => $safeMethods) {
            if (! $this->isNames($classMethod, $safeMethods)) {
                continue;
            }

            if ($classReflection->isSubclassOf($safeType)) {
                return true;
            }

            // skip self too
            if ($classReflection->getName() === $safeType) {
                return true;
            }
        }

        return false;
    }
}
