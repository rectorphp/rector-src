<?php

declare(strict_types=1);

namespace Rector\Core\Configuration\Parameter;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

/**
 * @api
 */
final class ParameterProvider
{
    /**
     * @var array<string, mixed>
     */
    private static array $parameters = [];

    public static function hasParameter(string $name): bool
    {
        return array_key_exists($name, self::$parameters);
    }

    /**
     * @api
     */
    public static function provideParameter(string $name): mixed
    {
        return self::$parameters[$name] ?? null;
    }

    /**
     * @api
     */
    public static function provideStringParameter(string $name, ?string $default = null): string
    {
        if ($default === null) {
            self::ensureParameterIsSet($name);
        }

        return (string) (self::$parameters[$name] ?? $default);
    }

    /**
     * @api
     * @return mixed[]
     */
    public static function provideArrayParameter(string $name): array
    {
        self::ensureParameterIsSet($name);

        return self::$parameters[$name];
    }

    /**
     * @api
     */
    public static function provideBoolParameter(string $parameterName): bool
    {
        return self::$parameters[$parameterName] ?? false;
    }

    /**
     * @api
     */
    public static function provideIntParameter(string $name): int
    {
        self::ensureParameterIsSet($name);
        return (int) self::$parameters[$name];
    }

    public static function addParameter(string $key, mixed $value): void
    {
        if (is_array($value)) {
            $mergedParameters = array_merge(self::$parameters[$key] ?? [], $value);
            self::$parameters[$key] = $mergedParameters;
        } else {
            self::$parameters[$key][] = $value;
        }
    }

    public static function setParameter(string $key, mixed $value): void
    {
        self::$parameters[$key] = $value;
    }

    public static function isParameterSet(string $parameterName): bool
    {
        return isset(self::$parameters[$parameterName]);
    }

    private static function ensureParameterIsSet(string $name): void
    {
        if (array_key_exists($name, self::$parameters)) {
            return;
        }

        throw new ParameterNotFoundException($name);
    }
}
