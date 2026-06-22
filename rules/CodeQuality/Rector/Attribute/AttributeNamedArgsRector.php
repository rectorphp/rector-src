<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Attribute;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Identifier;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use Rector\CodeQuality\ValueObject\AttributeNamedArgs;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\AttributeNamedArgsRectorTest
 */
final class AttributeNamedArgsRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface
{
    /**
     * @var AttributeNamedArgs[]
     */
    private array $attributeNamedArgs = [];

    public function __construct(
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert positional arguments on configured attributes into named arguments, using the attribute constructor parameter names',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
#[SomeAttribute(SomeClass::class, null, ['home'])]
class SomeClass
{
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
#[SomeAttribute(value: SomeClass::class, only: null, except: ['home'])]
class SomeClass
{
}
CODE_SAMPLE
                    ,
                    [new AttributeNamedArgs('Some\Attribute\SomeAttribute')]
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Attribute::class];
    }

    /**
     * @param Attribute $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->attributeNamedArgs as $attributeNamedArg) {
            if ($this->isName($node->name, $attributeNamedArg->getAttributeClass())) {
                return $this->nameArguments($node, $attributeNamedArg);
            }
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AttributeNamedArgs::class);

        $this->attributeNamedArgs = $configuration;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NAMED_ARGUMENTS;
    }

    private function nameArguments(Attribute $attribute, AttributeNamedArgs $attributeNamedArgs): ?Attribute
    {
        $methodReflection = $this->reflectionResolver->resolveConstructorReflectionFromAttribute($attribute);
        if (! $methodReflection instanceof MethodReflection) {
            return null;
        }

        $extendedParametersAcceptor = ParametersAcceptorSelector::combineAcceptors($methodReflection->getVariants());
        $parameters = $extendedParametersAcceptor->getParameters();

        $namesToApply = $this->resolveArgNamesToApply(
            $attribute->args,
            $parameters,
            $attributeNamedArgs->getFirstNamedPosition()
        );
        if ($namesToApply === []) {
            return null;
        }

        foreach ($namesToApply as $position => $name) {
            $attribute->args[$position]->name = new Identifier($name);
        }

        return $attribute;
    }

    /**
     * Resolve the positional arguments to name, as a position => parameter-name map, or [] when
     * nothing should change. Naming an argument forces every later positional argument to be named
     * too (PHP forbids a positional argument after a named one). So if any argument in the named
     * range maps to a variadic parameter, or to no parameter at all (overflow past a variadic),
     * the whole attribute is left untouched rather than producing invalid PHP.
     *
     * @param Arg[]                 $args
     * @param ParameterReflection[] $parameters
     * @return array<int, string>
     */
    private function resolveArgNamesToApply(array $args, array $parameters, int $firstNamedPosition): array
    {
        $namesToApply = [];

        $count = count($args);
        for ($position = $firstNamedPosition; $position < $count; ++$position) {
            $arg = $args[$position];

            // already named
            if ($arg->name instanceof Identifier) {
                continue;
            }

            $parameter = $parameters[$position] ?? null;

            // no matching parameter, e.g. overflow past a variadic
            if ($parameter === null) {
                return [];
            }

            // naming a variadic would rebind it or strand later positional arguments
            if ($parameter->isVariadic()) {
                return [];
            }

            $namesToApply[$position] = $parameter->getName();
        }

        return $namesToApply;
    }
}
