<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector\Fixture;

final class SkipNonArrayType
{
    public function __construct(
        public int $a,
        public ?int $b
    ) {
    }

    public function run()
    {
        $a = new SkipNonArrayType(3, null);

        $arr = [];
        foreach($a as $k => $v) {
			if($v !== null) {
				$arr[$k] = $v;
            }
        }
        echo json_encode($arr);
    }
}
