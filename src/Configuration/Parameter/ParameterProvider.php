<?php

declare(strict_types=1);

namespace Rector\Core\Configuration\Parameter;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @api
 */
final class ParameterProvider
{
    /**
     * @var array<string, mixed>
     */
    private array $parameters = [];

    public function __construct(Container $container)
    {
        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $container->getParameterBag();
        $this->parameters = $parameterBag->all();
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @api
     */
    public function provideParameter(string $name): mixed
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * @api
     */
    public function provideStringParameter(string $name): string
    {
        $this->ensureParameterIsSet($name);

        return (string) $this->parameters[$name];
    }

    /**
     * @api
     * @return mixed[]
     */
    public function provideArrayParameter(string $name): array
    {
        $this->ensureParameterIsSet($name);

        return $this->parameters[$name];
    }

    /**
     * @api
     */
    public function provideBoolParameter(string $parameterName): bool
    {
        return $this->parameters[$parameterName] ?? false;
    }

    public function changeParameter(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @api
     * @return mixed[]
     */
    public function provide(): array
    {
        return $this->parameters;
    }

    /**
     * @api
     */
    public function provideIntParameter(string $name): int
    {
        $this->ensureParameterIsSet($name);

        return (int) $this->parameters[$name];
    }

    /**
     * @api
     */
    public function ensureParameterIsSet(string $name): void
    {
        if (array_key_exists($name, $this->parameters)) {
            return;
        }

        throw new ParameterNotFoundException($name);
    }
}
