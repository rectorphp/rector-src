<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector\Fixture;

class SkipWithElse
{
    public function generateTestFile(): string
    {
        $testNamespace = 'App\Context\Application';
        $namespaceReplaces = ['App' => 'App\Tests\Unit'];

        foreach ($namespaceReplaces as $old => $new) {
            if ($old === $testNamespace) {
                $testNamespace = $new;
            } else {
                $testNamespace = \str_replace($old . '\\', $new . '\\', $testNamespace);
            }
        }

        return $testNamespace;
    }
}
