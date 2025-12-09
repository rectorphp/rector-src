<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveDoubleAssignRector\Fixture;

final class CompareInterpolatedString
{
    public function run()
    {
        $markdown = 'a long string';

        $markdown = preg_replace_callback(
            function (array $matches): string {
                $match = $matches[1];
                $className = strtolower(Toolkit::toSlug($match));

                return "<span class='tag tag-{$className}'>{$match}</span>";
            },
            (string)$markdown
        );

    }
}
