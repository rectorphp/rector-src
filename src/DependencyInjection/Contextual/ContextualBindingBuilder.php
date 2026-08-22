<?php

declare(strict_types=1);

namespace Rector\DependencyInjection\Contextual;

/**
 * Backwards-compatible no-op for the former Illuminate contextual-binding fluent API
 * ($rectorConfig->when(X)->needs()->giveTagged(Z)).
 *
 * The entropy container injects tagged collections automatically from the constructor
 * "@param Interface[] $name" docblock, so no per-service wiring is needed anymore. The
 * builder is kept only so existing configs that still call when()->needs()->giveTagged()
 * keep working.
 */
final class ContextualBindingBuilder
{
    public function needs(): self
    {
        return $this;
    }
}
