<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Privatization\Guard\ParentPropertyLookupGuard;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\Visibility;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\PrivatizeFinalClassPropertyRectorTest
 * @see \Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\CallbackTest
 */
final class PrivatizeFinalClassPropertyRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const SHOULD_SKIP_CALLBACK = 'should_skip_callback';

    /**
     * @var ?callable(Property|string, ClassReflection): bool
     */
    private $shouldSkipCallback = null;

    public function __construct(
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly ParentPropertyLookupGuard $parentPropertyLookupGuard,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change property to private if possible', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
final class SomeOtherClass
{
    protected $value;
}
final class SomeClass
{
    protected $value;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeOtherClass
{
    protected $value;
}
final class SomeClass
{
    private $value;
}
CODE_SAMPLE
                ,
                [
                    self::SHOULD_SKIP_CALLBACK => static function (
                        Property|string $property,
                        ClassReflection $classReflection,
                    ): bool {
                        return $classReflection->is('SomeOtherClass');
                    },
                ],
            ),
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->shouldSkipCallback = $configuration[self::SHOULD_SKIP_CALLBACK] ?? null;
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
        if (! $node->isFinal()) {
            return null;
        }

        $hasChanged = false;
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);

        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        foreach ($node->getProperties() as $property) {
            if ($this->shouldSkipProperty($property)) {
                continue;
            }

            if (! $this->parentPropertyLookupGuard->isLegal($property, $classReflection)) {
                continue;
            }

            if (
                is_callable($this->shouldSkipCallback)
                && call_user_func($this->shouldSkipCallback, $property, $classReflection)
            ) {
                continue;
            }

            $this->visibilityManipulator->makePrivate($property);
            $hasChanged = true;
        }

        $construct = $node->getMethod(MethodName::CONSTRUCT);
        if ($construct instanceof ClassMethod) {
            foreach ($construct->params as $param) {
                if (! $param->isPromoted()) {
                    continue;
                }

                if (! $this->visibilityManipulator->hasVisibility($param, Visibility::PROTECTED)) {
                    continue;
                }

                $property = (string) $this->getName($param);

                if (! $this->parentPropertyLookupGuard->isLegal($property, $classReflection)) {
                    continue;
                }

                if (
                    is_callable($this->shouldSkipCallback)
                    && call_user_func($this->shouldSkipCallback, $property, $classReflection)
                ) {
                    continue;
                }

                $this->visibilityManipulator->makePrivate($param);
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function shouldSkipProperty(Property $property): bool
    {
        if (count($property->props) !== 1) {
            return true;
        }

        return ! $property->isProtected();
    }
}
