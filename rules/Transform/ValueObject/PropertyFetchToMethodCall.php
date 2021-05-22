<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;

final class PropertyFetchToMethodCall
{
    /**
     * @var string
     */
    public const EQUIVALENT = 'equivalent';

    /**
     * @param mixed[] $newGetArguments
     */
    public function __construct(
        private string $oldType,
        private string $oldProperty,
        private string $newGetMethod,
        private ?string $newSetMethod = null,
        private array $newGetArguments = []
    ) {
    }

    public function getOldObjectType(): ObjectType
    {
        return new ObjectType($this->oldType);
    }

    public function getOldProperty(): string
    {
        return $this->oldProperty;
    }

    public function getNewGetMethod(): string
    {
        return $this->newGetMethod;
    }

    public function getNewSetMethod(): ?string
    {
        return $this->newSetMethod;
    }

    /**
     * @return mixed[]
     */
    public function getNewGetArguments(): array
    {
        return $this->newGetArguments;
    }

    public function isEquivalent(): bool
    {
        return $this->oldProperty === self::EQUIVALENT && ($this->newGetMethod === self::EQUIVALENT || $this->newSetMethod === self::EQUIVALENT);
    }
}
