<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use Nette\Utils\Strings;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\Exception\ShouldNotHappenException;
use Rector\Naming\RectorNamingInflector;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\StaticTypeMapper\Resolver\ClassNameFromObjectTypeResolver;
use Rector\StaticTypeMapper\ValueObject\Type\SelfObjectType;
use Rector\Util\StringUtils;

/**
 * @see \Rector\Tests\Naming\Naming\PropertyNamingTest
 */
final readonly class PropertyNaming
{
    /**
     * @var string[]
     */
    private const EXCLUDED_CLASSES = ['#Closure#', '#^Spl#', '#FileInfo#', '#^std#', '#Iterator#', '#SimpleXML#'];

    /**
     * @var array<string, string>
     */
    private const CONTEXT_AWARE_NAMES_BY_TYPE = [
        'Twig\Environment' => 'twigEnvironment',
    ];

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
        private RectorNamingInflector $rectorNamingInflector,
    ) {
    }

    public function getExpectedNameFromMethodName(string $methodName): ?ExpectedName
    {
        $matches = Strings::match($methodName, self::GET_PREFIX_REGEX);
        if ($matches === null) {
            return null;
        }

        $originalName = lcfirst((string) $matches['root_name']);

        return new ExpectedName($originalName, $this->rectorNamingInflector->singularize($originalName));
    }

    public function getExpectedNameFromType(Type $type): ?ExpectedName
    {
        // keep collections untouched
        if ($type instanceof ObjectType) {
            if ($type->isInstanceOf('Doctrine\Common\Collections\Collection')->yes()) {
                return null;
            }

            if ($type->isInstanceOf('Illuminate\Support\Collection')->yes()) {
                return null;
            }
        }

        $className = $this->resolveClassNameFromType($type);
        if (! is_string($className)) {
            return null;
        }

        foreach (self::EXCLUDED_CLASSES as $excludedClass) {
            if (StringUtils::isMatch($className, $excludedClass)) {
                return null;
            }
        }

        // special cases to keep context
        foreach (self::CONTEXT_AWARE_NAMES_BY_TYPE as $specialType => $contextAwareName) {
            if ($className === $specialType) {
                return new ExpectedName($contextAwareName, $contextAwareName);
            }
        }

        $shortClassName = $this->resolveShortClassName($className);
        $shortClassName = $this->normalizeShortClassName($shortClassName);

        // prolong too short generic names with one namespace up
        $originalName = $this->prolongIfTooShort($shortClassName, $className);
        return new ExpectedName($originalName, $this->rectorNamingInflector->singularize($originalName));
    }

    public function fqnToVariableName(ThisType | ObjectType | string $objectType): string
    {
        if ($objectType instanceof ThisType) {
            $objectType = $objectType->getStaticObjectType();
        }

        $className = $this->resolveClassName($objectType);
        $shortClassName = str_contains($className, '\\') ? (string) Strings::after($className, '\\', -1) : $className;

        $variableName = $this->removeInterfaceSuffixPrefix($shortClassName, 'interface');
        $variableName = $this->removeInterfaceSuffixPrefix($variableName, 'abstract');

        $variableName = $this->fqnToShortName($variableName);

        $variableName = str_replace('_', '', $variableName);

        // prolong too short generic names with one namespace up
        return $this->prolongIfTooShort($variableName, $className);
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
            return Strings::substring($shortClassName, strlen('Abstract'));
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

        $lastNamePart = Strings::after($fqn, '\\', -1);
        if (! is_string($lastNamePart)) {
            throw new ShouldNotHappenException();
        }

        if (\str_ends_with($lastNamePart, self::INTERFACE)) {
            return Strings::substring($lastNamePart, 0, -strlen(self::INTERFACE));
        }

        return $lastNamePart;
    }

    private function removeInterfaceSuffixPrefix(string $className, string $category): string
    {
        // suffix
        $iSuffixMatch = Strings::match($className, '#' . $category . '$#i');
        if ($iSuffixMatch !== null) {
            return Strings::substring($className, 0, -strlen($category));
        }

        // prefix
        $iPrefixMatch = Strings::match($className, '#^' . $category . '#i');
        if ($iPrefixMatch !== null) {
            return Strings::substring($className, strlen($category));
        }

        // starts with "I\W+"?
        if (StringUtils::isMatch($className, self::I_PREFIX_REGEX)) {
            return Strings::substring($className, 1);
        }

        return $className;
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

    private function normalizeShortClassName(string $shortClassName): string
    {
        $shortClassName = $this->removePrefixesAndSuffixes($shortClassName);

        // if all is upper-cased, it should be lower-cased
        if ($shortClassName === strtoupper($shortClassName)) {
            $shortClassName = strtolower($shortClassName);
        }

        // remove "_"
        $shortClassName = Strings::replace($shortClassName, '#_#');
        return $this->normalizeUpperCase($shortClassName);
    }

    private function resolveClassNameFromType(Type $type): ?string
    {
        $type = TypeCombinator::removeNull($type);
        $className = ClassNameFromObjectTypeResolver::resolve($type);

        if ($className === null) {
            return null;
        }

        if ($type instanceof SelfObjectType) {
            return null;
        }

        if ($type instanceof StaticType) {
            return null;
        }

        // generic types are usually mix of parent type and specific type - various way to handle it
        if ($type instanceof GenericObjectType) {
            return null;
        }

        return $className;
    }
}
