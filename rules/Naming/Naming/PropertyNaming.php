<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use Nette\Utils\Strings;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;
use Rector\Exception\ShouldNotHappenException;
use Rector\Util\StringUtils;

/**
 * @see \Rector\Tests\Naming\Naming\PropertyNamingTest
 */
final readonly class PropertyNaming
{
    private const string INTERFACE = 'Interface';

    /**
     * @see https://regex101.com/r/U78rUF/1
     */
    private const string I_PREFIX_REGEX = '#^I[A-Z]#';

    public function fqnToVariableName(ThisType|ObjectType|Name|string $objectType): string
    {
        if ($objectType instanceof Name) {
            $objectType = $objectType->toString();
        }

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

    private function prolongIfTooShort(string $shortClassName, string $className): string
    {
        if (in_array($shortClassName, ['Factory', 'Repository'], true) && ! str_ends_with(
            $className,
            'Repository'
        ) && ! str_ends_with($className, 'Factory')) {
            $namespaceAbove = (string) Strings::after($className, '\\', -2);
            $namespaceAbove = (string) Strings::before($namespaceAbove, '\\');

            return lcfirst($namespaceAbove) . $shortClassName;
        }

        return lcfirst($shortClassName);
    }

    private function resolveClassName(ObjectType|string $objectType): string
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
}
