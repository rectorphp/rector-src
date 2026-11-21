<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class AssignmentComparison
{
    public function run($enabled, $cache, $id)
    {
        if (! ($enabled && ($value = $this->value()) !== null)) {
            return null;
        }

        if (! ($enabled && ($registro = $cache[$id] ?? null) !== null)) {
            return null;
        }

        if (! (($value = $this->value()) !== null && $enabled)) {
            return null;
        }
    }

    private function value()
    {
        return rand(0, 1) ? 'value' : null;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class AssignmentComparison
{
    public function run($enabled, $cache, $id)
    {
        if (!$enabled || ($value = $this->value()) === null) {
            return null;
        }

        if (!$enabled || ($registro = $cache[$id] ?? null) === null) {
            return null;
        }

        if (($value = $this->value()) === null || !$enabled) {
            return null;
        }
    }

    private function value()
    {
        return rand(0, 1) ? 'value' : null;
    }
}

?>
