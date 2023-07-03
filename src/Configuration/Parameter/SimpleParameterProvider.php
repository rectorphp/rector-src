<?php

declare(strict_types=1);

namespace Rector\Core\Configuration\Parameter;

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Webmozart\Assert\Assert;

/**
 * @api
 */
final class SimpleParameterProvider
{
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
            $uniqueParameters = array_unique($parameter);
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
    public static function provideBoolParameter(string $name): bool
    {
        return self::$parameters[$name];
    }

    /**
     * @param Option::* $name
     */
    private static function ensureParameterIsSet(string $name): void
    {
        if (array_key_exists($name, self::$parameters)) {
            return;
        }

        throw new ParameterNotFoundException($name);
    }
}
