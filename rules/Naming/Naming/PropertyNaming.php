<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use Nette\Utils\Strings;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\RectorNamingInflector;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
use Rector\StaticTypeMapper\ValueObject\Type\SelfObjectType;

/**
 * @deprecated
 * @todo merge with very similar logic in
 * @see VariableNaming
 * @see \Rector\Tests\Naming\Naming\PropertyNamingTest
 */
final class PropertyNaming
{
    /**
     * @var string[]
     */
    private const EXCLUDED_CLASSES = ['#Closure#', '#^Spl#', '#FileInfo#', '#^std#', '#Iterator#', '#SimpleXML#'];

    /**
     * @var string
     */
    private const INTERFACE = 'Interface';

    /**
     * @var string
     * @see https://regex101.com/r/U78rUF/1
     */
    private const I_PREFIX_REGEX = '#^I[A-Z]#';

    /**
     * @see https://regex101.com/r/hnU5pm/2/
     * @var string
     */
    private const GET_PREFIX_REGEX = '#^get(?<root_name>[A-Z].+)#';

    public function __construct(
        private TypeUnwrapper $typeUnwrapper,
        private RectorNamingInflector $rectorNamingInflector,
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getExpectedNameFromMethodName(string $methodName): ?ExpectedName
    {
        $matches = Strings::match($methodName, self::GET_PREFIX_REGEX);
        if ($matches === null) {
            return null;
        }

        $originalName = lcfirst($matches['root_name']);

        return new ExpectedName($originalName, $this->rectorNamingInflector->singularize($originalName));
    }

    public function getExpectedNameFromType(Type $type): ?ExpectedName
    {
        $type = $this->typeUnwrapper->unwrapNullableType($type);
        if (! $type instanceof TypeWithClassName) {
            return null;
        }

        if ($type instanceof SelfObjectType) {
            return null;
        }

        if ($type instanceof StaticType) {
            return null;
        }

        $className = $this->nodeTypeResolver->getFullyQualifiedClassName($type);

        foreach (self::EXCLUDED_CLASSES as $excludedClass) {
            if (Strings::match($className, $excludedClass)) {
                return null;
            }
        }

        $shortClassName = $this->resolveShortClassName($className);
        $shortClassName = $this->removePrefixesAndSuffixes($shortClassName);

        // if all is upper-cased, it should be lower-cased
        if ($shortClassName === strtoupper($shortClassName)) {
            $shortClassName = strtolower($shortClassName);
        }

        // remove "_"
        $shortClassName = Strings::replace($shortClassName, '#_#', '');
        $shortClassName = $this->normalizeUpperCase($shortClassName);

        // prolong too short generic names with one namespace up
        $originalName = $this->prolongIfTooShort($shortClassName, $className);
        return new ExpectedName($originalName, $this->rectorNamingInflector->singularize($originalName));
    }

    public function fqnToVariableName(ObjectType | string $objectType): string
    {
        $className = $this->resolveClassName($objectType);

        $shortName = $this->fqnToShortName($className);
        $shortName = $this->removeInterfaceSuffixPrefix($className, $shortName);

        // prolong too short generic names with one namespace up
        return $this->prolongIfTooShort($shortName, $className);
    }

    /**
     * @changelog https://stackoverflow.com/a/2792045/1348344
     */
    public function underscoreToName(string $underscoreName): string
    {
        $uppercaseWords = ucwords($underscoreName, '_');
        $pascalCaseName = str_replace('_', '', $uppercaseWords);

        return lcfirst($pascalCaseName);
    }

    private function resolveShortClassName(string $className): string
    {
        if (\str_contains($className, '\\')) {
            return (string) Strings::after($className, '\\', -1);
        }

        return $className;
    }

    private function removePrefixesAndSuffixes(string $shortClassName): string
    {
        // is SomeInterface
        if (\str_ends_with($shortClassName, self::INTERFACE)) {
            $shortClassName = Strings::substring($shortClassName, 0, -strlen(self::INTERFACE));
        }

        // is ISomeClass
        if ($this->isPrefixedInterface($shortClassName)) {
            $shortClassName = Strings::substring($shortClassName, 1);
        }

        // is AbstractClass
        if (\str_starts_with($shortClassName, 'Abstract')) {
            $shortClassName = Strings::substring($shortClassName, strlen('Abstract'));
        }

        return $shortClassName;
    }

    private function normalizeUpperCase(string $shortClassName): string
    {
        // turns $SOMEUppercase => $someUppercase
        for ($i = 0; $i <= strlen($shortClassName); ++$i) {
            if (ctype_upper($shortClassName[$i]) && $this->isNumberOrUpper($shortClassName[$i + 1])) {
                $shortClassName[$i] = strtolower($shortClassName[$i]);
            } else {
                break;
            }
        }

        return $shortClassName;
    }

    private function prolongIfTooShort(string $shortClassName, string $className): string
    {
        if (in_array($shortClassName, ['Factory', 'Repository'], true)) {
            $namespaceAbove = (string) Strings::after($className, '\\', -2);
            $namespaceAbove = (string) Strings::before($namespaceAbove, '\\');

            return lcfirst($namespaceAbove) . $shortClassName;
        }

        return lcfirst($shortClassName);
    }

    private function resolveClassName(ObjectType | string $objectType): string
    {
        if ($objectType instanceof ObjectType) {
            return $objectType->getClassName();
        }

        return $objectType;
    }

    private function fqnToShortName(string $fqn): string
    {
        if (! \str_contains($fqn, '\\')) {
            return $fqn;
        }

        /** @var string $lastNamePart */
        $lastNamePart = Strings::after($fqn, '\\', - 1);
        if (\str_ends_with($lastNamePart, self::INTERFACE)) {
            return Strings::substring($lastNamePart, 0, - strlen(self::INTERFACE));
        }

        return $lastNamePart;
    }

    private function removeInterfaceSuffixPrefix(string $className, string $shortName): string
    {
        // remove interface prefix/suffix
        if (! $this->reflectionProvider->hasClass($className)) {
            return $shortName;
        }

        // starts with "I\W+"?
        if (Strings::match($shortName, self::I_PREFIX_REGEX)) {
            return Strings::substring($shortName, 1);
        }

        if (\str_ends_with($shortName, self::INTERFACE)) {
            return Strings::substring($shortName, -strlen(self::INTERFACE));
        }

        return $shortName;
    }

    private function isPrefixedInterface(string $shortClassName): bool
    {
        if (strlen($shortClassName) <= 3) {
            return false;
        }

        if (! \str_starts_with($shortClassName, 'I')) {
            return false;
        }
        if (! ctype_upper($shortClassName[1])) {
            return false;
        }
        return ctype_lower($shortClassName[2]);
    }

    private function isNumberOrUpper(string $char): bool
    {
        if (ctype_upper($char)) {
            return true;
        }

        return ctype_digit($char);
    }
}
