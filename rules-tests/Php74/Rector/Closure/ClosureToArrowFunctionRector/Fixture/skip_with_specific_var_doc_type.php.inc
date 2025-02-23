<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

use Illuminate\Support\Collection;

class SkipWithSpecificVarDocType
{
    public function run()
    {
        Collection::macro('set', function(mixed $values) {
            /** @var Collection $this */
            return $this->put(...func_get_args());
        });
    }
}
