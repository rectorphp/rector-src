<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\TemplatedTypeOnParamAndReturn\Fixture;

abstract class Model {}
interface EventInterface {}

/** @template TModel of Model */
abstract class SkipTemplatedTypeOnParamAndReturn
{
    /**
     * @param TModel&EventInterface $model
     * @return TModel&EventInterface
     */
    abstract public function run(Model&EventInterface $model): Model&EventInterface;
}
