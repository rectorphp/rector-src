<?php

declare(strict_types=1);

namespace Rector\Validation;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Exception\ShouldNotHappenException;
use Rector\Skipper\Skipper\Custom\CustomSkipperInterface;
use Rector\Skipper\Skipper\CustomSkipper;
use Rector\Skipper\Skipper\CustomSkipperSerializeWrapper;

final class RectorConfigValidator
{
    /**
     * @param string[] $rectorClasses
     */
    public static function ensureNoDuplicatedClasses(array $rectorClasses): void
    {
        $duplicatedRectorClasses = self::resolveDuplicatedValues($rectorClasses);
        if ($duplicatedRectorClasses === []) {
            return;
        }

        throw new ShouldNotHappenException('Following rules are registered twice: ' . implode(
            ', ',
            $duplicatedRectorClasses
        ));
    }

    /**
     * @param mixed[] $skip
     */
    public static function ensureRectorRulesExist(array &$skip): void
    {
        $nonExistingRules = [];
        $skippedRectorRules = [];

        foreach ($skip as $key => &$value) {
            if (self::isRectorClassValue($key)) {
                if (class_exists($key)) {
                    $skippedRectorRules[] = $key;
                    self::ensureSkipValueIsValid($key, $value);
                } else {
                    $nonExistingRules[] = $key;
                }

                continue;
            }

            if (! self::isRectorClassValue($value)) {
                continue;
            }

            if (class_exists($value)) {
                $skippedRectorRules[] = $value;
                continue;
            }

            $nonExistingRules[] = $value;
        }

        unset($value);

        SimpleParameterProvider::addParameter(Option::SKIPPED_RECTOR_RULES, $skippedRectorRules);

        if ($nonExistingRules === []) {
            return;
        }

        $nonExistingRulesString = '';
        foreach ($nonExistingRules as $nonExistingRule) {
            $nonExistingRulesString .= ' * ' . $nonExistingRule . PHP_EOL;
        }

        throw new ShouldNotHappenException(
            'These rules from "$rectorConfig->skip()" does not exist - remove them or fix their names:' . PHP_EOL . $nonExistingRulesString
        );
    }

    private static function ensureSkipValueIsValid(string $name, mixed &$skipValue): void
    {
        if ($skipValue === null) {
            return;
        }

        if (! is_array($skipValue)) {
            throw new ShouldNotHappenException(
                'Rule value from "$rectorConfig->skip()" is neither null nor array: ' . $name
            );
        }

        foreach ($skipValue as $idx => &$value) {
            if (is_string($value)) {
                continue;
            }

            if (! ($value instanceof CustomSkipperInterface) || ! CustomSkipper::supports($value)) {
                throw new ShouldNotHappenException(
                    'Rule value from "$rectorConfig->skip()" is neither string nor a supported custom skipper implementation: ' . sprintf(
                        '%s[%s]',
                        $name,
                        $idx
                    )
                );
            }

            $value = new CustomSkipperSerializeWrapper($value); // wrap so that SimpleParameterProvider::hash works
        }
    }

    private static function isRectorClassValue(mixed $value): bool
    {
        // only validate string
        if (! is_string($value)) {
            return false;
        }

        // not regex path
        if (str_contains($value, '*')) {
            return false;
        }

        // not if no Rector suffix
        if (! str_ends_with($value, 'Rector')) {
            return false;
        }

        // not directory
        if (is_dir($value)) {
            return false;
        }

        // not file
        return ! is_file($value);
    }

    /**
     * @param string[] $values
     * @return string[]
     */
    private static function resolveDuplicatedValues(array $values): array
    {
        $counted = array_count_values($values);
        $duplicates = [];

        foreach ($counted as $value => $count) {
            if ($count > 1) {
                $duplicates[] = $value;
            }
        }

        return array_unique($duplicates);
    }
}
