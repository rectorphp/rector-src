<?php

declare(strict_types=1);

namespace Rector\Core\Util;

use Nette\Utils\Strings;

final class StaticRectorStrings
{
    /**
     * @var string
     * @see https://regex101.com/r/4w2of2/2
     */
    private const CAMEL_CASE_SPLIT_REGEX = '#([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)#';

    /**
     * From: utf-8 → to: UTF_8
     */
    public static function camelCaseToUnderscore(string $input): string
    {
        if ($input === strtolower($input)) {
            return $input;
        }

        $matches = Strings::matchAll($input, self::CAMEL_CASE_SPLIT_REGEX);
        $parts = [];
        foreach ($matches as $match) {
            $matchedPart = (string) $match[0];

            $parts[] = $matchedPart === strtoupper($matchedPart) ? strtolower($matchedPart) : lcfirst($matchedPart);
        }

        return implode('_', $parts);
    }

    /**
     * @param string[] $array
     */
    public static function isInArrayInsensitive(string $checkedItem, array $array): bool
    {
        $checkedItem = strtolower($checkedItem);
        foreach ($array as $singleArray) {
            if (strtolower($singleArray) === $checkedItem) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string[] $prefixesToRemove
     */
    public static function removePrefixes(string $value, array $prefixesToRemove): string
    {
        foreach ($prefixesToRemove as $prefixToRemove) {
            if (\str_starts_with($value, $prefixToRemove)) {
                $value = Strings::substring($value, Strings::length($prefixToRemove));
            }
        }

        return $value;
    }

    /**
     * @param string[] $suffixesToRemove
     */
    public static function removeSuffixes(string $value, array $suffixesToRemove): string
    {
        foreach ($suffixesToRemove as $suffixToRemove) {
            if (\str_ends_with($value, $suffixToRemove)) {
                $value = Strings::substring($value, 0, -Strings::length($suffixToRemove));
            }
        }

        return $value;
    }
}
