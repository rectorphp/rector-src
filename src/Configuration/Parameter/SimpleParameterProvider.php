<?php

declare(strict_types=1);

namespace Rector\Configuration\Parameter;

use Rector\Configuration\Option;
use Rector\Exception\ShouldNotHappenException;
use Webmozart\Assert\Assert;

/**
 * @api
 */
final class SimpleParameterProvider
{
    /**
     * Parameters that never change the refactored output - runtime tuning and reporting only.
     * They are excluded from the cache invalidation hash, so toggling e.g. parallel or memory
     * limit does not drop the whole cache.
     *
     * @var array<Option::*>
     */
    private const array CACHE_IGNORED_PARAMETER_NAMES = [
        Option::SOURCE,
        Option::PARALLEL,
        Option::PARALLEL_JOB_SIZE,
        Option::PARALLEL_MAX_NUMBER_OF_PROCESSES,
        Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS,
        Option::MEMORY_LIMIT,
        Option::NO_DIFFS,
        Option::CACHE_DIR,
        Option::CONTAINER_CACHE_DIRECTORY,
        Option::EDITOR_URL,
        Option::ABSOLUTE_FILE_PATH,
        Option::REPORT_UNUSED_SKIPS,
        Option::IS_RECTORCONFIG_BUILDER_RECREATED,
        Option::IS_RUN_NARROWED,
        Option::IS_CACHED_RUN,
        Option::SKIPPED_RECTOR_RULES,
        Option::SKIPPED_NON_RECTOR_CLASSES,
        Option::SKIPPED_START_WITH_SHORT_OPEN_TAG_FILES,
        Option::DEPRECATED_PHP_SETS_METHODS,
        Option::DEPRECATED_ATTRIBUTES_SETS_ARGS,
        Option::DEPRECATED_COMPOSER_BASED_ARGS,
        Option::LEVEL_OVERFLOWS,
        Option::CACHE_META_EXTENSIONS,
        Option::COMPOSER_BOUND_RULE_CONFIGURATIONS,
        Option::ROOT_STANDALONE_REGISTERED_RULES,
        Option::SET_REGISTERED_RULES,
    ];

    /**
     * Parameters compared by direction instead of the strict hash: adding a rule/set or removing a
     * skip means more work and must drop the cache, while removing a rule/set or adding a skip is
     * safe and keeps it. Handled in ChangedFilesDetector, so they are excluded from the strict hash.
     *
     * @var array<Option::*>
     */
    private const array CACHE_DIRECTIONAL_PARAMETER_NAMES = [
        Option::REGISTERED_RECTOR_RULES,
        Option::REGISTERED_RECTOR_SETS,
        Option::SKIP,
    ];

    /**
     * @var array<string, mixed>
     */
    private static array $parameters = [];

    /**
     * @param Option::* $name
     */
    public static function addParameter(string $name, mixed $value): void
    {
        if (is_array($value)) {
            $mergedParameters = array_merge(self::$parameters[$name] ?? [], $value);
            self::$parameters[$name] = $mergedParameters;
        } else {
            self::$parameters[$name][] = $value;
        }
    }

    /**
     * @param Option::* $name
     */
    public static function setParameter(string $name, mixed $value): void
    {
        self::$parameters[$name] = $value;
    }

    /**
     * @param Option::* $name
     * @return mixed[]
     */
    public static function provideArrayParameter(string $name): array
    {
        $parameter = self::$parameters[$name] ?? [];
        Assert::isArray($parameter);

        if (array_is_list($parameter)) {
            // remove duplicates
            $uniqueParameters = array_unique($parameter, SORT_REGULAR);
            return array_values($uniqueParameters);
        }

        return $parameter;
    }

    /**
     * @param Option::* $name
     */
    public static function hasParameter(string $name): bool
    {
        return array_key_exists($name, self::$parameters);
    }

    /**
     * @param Option::* $name
     */
    public static function provideStringParameter(string $name, ?string $default = null): string
    {
        if ($default === null) {
            self::ensureParameterIsSet($name);
        }

        return self::$parameters[$name] ?? $default;
    }

    public static function provideIntParameter(string $key): int
    {
        return self::$parameters[$key];
    }

    /**
     * @param Option::* $name
     */
    public static function provideBoolParameter(string $name, ?bool $default = null): bool
    {
        if ($default === null) {
            self::ensureParameterIsSet($name);
        }

        return self::$parameters[$name] ?? $default;
    }

    /**
     * @api
     * Strict hash for cache invalidation. Ignored and directionally compared parameters are left
     * out, so only a real change to an output-affecting parameter drops the cache.
     */
    public static function hashForCacheInvalidation(): string
    {
        $strictParameters = self::$parameters;
        foreach ([...self::CACHE_IGNORED_PARAMETER_NAMES, ...self::CACHE_DIRECTIONAL_PARAMETER_NAMES] as $ignoredName) {
            unset($strictParameters[$ignoredName]);
        }

        ksort($strictParameters);

        return sha1(serialize(self::relativizeProjectPaths($strictParameters, self::projectPathPrefix())));
    }

    /**
     * @api
     * @return array{rules: mixed[], sets: mixed[], skip: mixed[]}
     */
    public static function provideCacheDirectionalParameters(): array
    {
        $projectPathPrefix = self::projectPathPrefix();

        return [
            'rules' => self::relativizeProjectPaths(
                (array) (self::$parameters[Option::REGISTERED_RECTOR_RULES] ?? []),
                $projectPathPrefix
            ),
            'sets' => self::relativizeProjectPaths(
                (array) (self::$parameters[Option::REGISTERED_RECTOR_SETS] ?? []),
                $projectPathPrefix
            ),
            'skip' => self::relativizeProjectPaths(
                (array) (self::$parameters[Option::SKIP] ?? []),
                $projectPathPrefix
            ),
        ];
    }

    /**
     * Paths declared in the configuration - analysed paths, autoload and bootstrap files, set
     * files - are absolute, so they carry the location of the project into the cache identity.
     * Hashed as they are, the cache is bound to one directory: a git worktree, a second
     * checkout or a CI cache restored under a different workspace name looks like a changed
     * configuration and drops every entry on its first run. Anchored to the project instead,
     * they describe the same configuration wherever it is checked out.
     *
     * @param mixed[] $parameters
     * @return mixed[]
     */
    private static function relativizeProjectPaths(array $parameters, string $projectPathPrefix): array
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $parameters[$key] = self::relativizeProjectPaths($value, $projectPathPrefix);
                continue;
            }

            if (is_string($value) && str_starts_with($value, $projectPathPrefix)) {
                $parameters[$key] = substr($value, strlen($projectPathPrefix));
            }
        }

        return $parameters;
    }

    /**
     * Empty when the working directory cannot be resolved, which makes the relativizing above a
     * no-op rather than a wrong answer.
     */
    private static function projectPathPrefix(): string
    {
        $currentDirectory = getcwd();
        if ($currentDirectory === false) {
            return '';
        }

        return rtrim($currentDirectory, '/') . '/';
    }

    /**
     * @param Option::* $name
     */
    private static function ensureParameterIsSet(string $name): void
    {
        if (array_key_exists($name, self::$parameters)) {
            return;
        }

        throw new ShouldNotHappenException(sprintf('Parameter "%s" was not found', $name));
    }
}
