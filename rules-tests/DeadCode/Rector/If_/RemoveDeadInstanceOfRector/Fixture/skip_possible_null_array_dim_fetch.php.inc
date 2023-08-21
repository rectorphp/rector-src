<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use Iterator;
use stdClass;

class SkipPossibleNullArrayDimFetch
{
    public function go(Iterator $articles)
    {
        $result = [
            'artikel' => $articles,
            'farben' => null,
            'artikel_ids' => [],
        ];

        // collect article ids
        foreach ($result['artikel'] as $article) {
            $result['artikel_ids'][] = 1;
        }

        if ($result['artikel_ids'] !== []) {
            $result['farben'] = new stdClass();
        }

        // $result['farben'] might be also null
        if ($result['farben'] instanceof stdClass) {
            echo '123';
        }
    }
}
