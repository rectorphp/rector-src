<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipVariadic extends TestCase
{
    /**
     * @dataProvider dataDummy
     */
    public function testDummy(...$args): void {
    	self::assertIsArray($args);
    }

    public function dataDummy(): array {
        return [
        	'case'=> [4, 'arg']           // <-- int + string
        ];
    }
}
