<?php

declare(strict_types=1);

namespace Rector\Validation;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Exception\ShouldNotHappenException;

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
    public static function ensureRectorRulesExist(array $skip): void
    {
        $nonExistingRules = [];
        $existingRules = [];

        foreach ($skip as $key => $value) {
            if (self::isRectorClassValue($key) && ! class_exists($key)) {
                $nonExistingRules[] = $key;
                continue;
            }

            if (! self::isRectorClassValue($value)) {
                continue;
            }

            if (class_exists($value)) {
                $existingRules[] = $value;
                continue;
            }

            $nonExistingRules[] = $value;
        }

        SimpleParameterProvider::addParameter(Option::SKIPPED_RECTOR_RULES, $existingRules);

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
