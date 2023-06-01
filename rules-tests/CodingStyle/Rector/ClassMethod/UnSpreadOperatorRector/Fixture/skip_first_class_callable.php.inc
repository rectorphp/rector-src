<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector\Fixture;

final class SkipFirstClassCallable
{
    public function run(array $array): array {
    	return array_map(
        	callback: $this->callback(...),
            array: $array,
       );
    }

    private function callback(int $value): string {
        return (string) $value;
    }
}
