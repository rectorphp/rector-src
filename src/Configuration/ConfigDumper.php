<?php

declare(strict_types=1);

namespace Rector\Configuration;

use PHPStan\Reflection\ReflectionProvider;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use ReflectionObject;

/**
 * Turns the resolved rector.php configuration into plain data - paths, rules with their configuration
 * and skips - for external runners to consume.
 *
 * @see \Rector\Tests\Configuration\ConfigDumperTest
 */
final readonly class ConfigDumper
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @return array{
     *     php_version: int|null,
     *     paths: string[],
     *     skips: array<array{path?: string, class?: string, paths?: string[]}>,
     *     rules: array<array{class: string, config: mixed}>,
     *     import_names: bool,
     *     import_short_classes: bool,
     *     remove_unused_imports: bool
     * }
     */
    public function dump(): array
    {
        $ruleClasses = array_values(array_unique(
            SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES)
        ));

        return [
            'php_version' => SimpleParameterProvider::hasParameter(Option::PHP_VERSION_FEATURES)
                ? SimpleParameterProvider::provideIntParameter(Option::PHP_VERSION_FEATURES)
                : null,
            'paths' => array_values(SimpleParameterProvider::provideArrayParameter(Option::PATHS)),
            'skips' => $this->dumpSkips($ruleClasses),
            'rules' => $this->dumpRules($ruleClasses),
            'import_names' => SimpleParameterProvider::provideBoolParameter(Option::AUTO_IMPORT_NAMES, false),
            'import_short_classes' => SimpleParameterProvider::provideBoolParameter(
                Option::IMPORT_SHORT_CLASSES,
                true
            ),
            'remove_unused_imports' => SimpleParameterProvider::provideBoolParameter(
                Option::REMOVE_UNUSED_IMPORTS,
                false
            ),
        ];
    }

    /**
     * @param string[] $ruleClasses
     * @return array<array{class: string, config: mixed}>
     */
    private function dumpRules(array $ruleClasses): array
    {
        $ruleConfigurations = SimpleParameterProvider::provideArrayParameter(Option::RULE_CONFIGURATIONS);

        $rules = [];
        foreach ($ruleClasses as $ruleClass) {
            $rules[] = [
                'class' => $ruleClass,
                'config' => isset($ruleConfigurations[$ruleClass])
                    ? $this->normalizeValue($ruleConfigurations[$ruleClass])
                    : null,
            ];
        }

        return $rules;
    }

    /**
     * Same shape as the Skipper reads Option::SKIP: a path, a class skipped everywhere, or a class skipped on paths
     *
     * @param string[] $ruleClasses
     * @return array<array{path?: string, class?: string, paths?: string[]}>
     */
    private function dumpSkips(array $ruleClasses): array
    {
        $skips = [];

        foreach (SimpleParameterProvider::provideArrayParameter(Option::SKIP) as $key => $value) {
            if (is_int($key)) {
                if (! is_string($value)) {
                    continue;
                }

                if ($this->isClassLike($value)) {
                    foreach ($this->resolveCoveredClasses($value, $ruleClasses) as $class) {
                        $skips[] = [
                            'class' => $class,
                        ];
                    }

                    continue;
                }

                $skips[] = [
                    'path' => $value,
                ];
                continue;
            }

            // the Skipper only applies class keys
            if (! $this->isClassLike($key)) {
                continue;
            }

            foreach ($this->resolveCoveredClasses($key, $ruleClasses) as $class) {
                // a non-array value skips the class everywhere, as in the Skipper
                $skips[] = is_array($value) ? [
                    'class' => $class,
                    'paths' => array_values($value),
                ] : [
                    'class' => $class,
                ];
            }
        }

        return $skips;
    }

    /**
     * The Skipper matches with is_a(), so a skipped interface or parent class also names each rule it covers
     *
     * @param string[] $ruleClasses
     * @return string[]
     */
    private function resolveCoveredClasses(string $skippedClass, array $ruleClasses): array
    {
        $classes = [$skippedClass];

        foreach ($ruleClasses as $ruleClass) {
            if ($ruleClass !== $skippedClass && is_a($ruleClass, $skippedClass, true)) {
                $classes[] = $ruleClass;
            }
        }

        return $classes;
    }

    private function isClassLike(string $name): bool
    {
        return $this->reflectionProvider->hasClass($name);
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map($this->normalizeValue(...), $value);
        }

        if (! is_object($value)) {
            return $value;
        }

        $normalized = [
            '@class' => $value::class,
        ];

        $reflectionObject = new ReflectionObject($value);
        foreach ($reflectionObject->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->isStatic()) {
                continue;
            }

            // lazy-initialized property
            if (! $reflectionProperty->isInitialized($value)) {
                continue;
            }

            $normalized[$reflectionProperty->getName()] = $this->normalizeValue(
                $reflectionProperty->getValue($value)
            );
        }

        return $normalized;
    }
}
