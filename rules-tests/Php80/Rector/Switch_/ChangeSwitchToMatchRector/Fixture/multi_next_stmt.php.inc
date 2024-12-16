<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MultiNextStmt
{
    public function run($value)
    {
        switch ($value) {
        	case 'number':
                case 'percentage':
                case 'integer':
                case 'price':
                case 'decimal':
                    $sort = SORT_NUMERIC;
                    break;
                case 'string':
                case 'list':
                case 'text':
                    $sort = SORT_NATURAL | SORT_FLAG_CASE;
                    break;
                default:
                    $sort = SORT_REGULAR;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MultiNextStmt
{
    public function run($value)
    {
        $sort = match ($value) {
            'number', 'percentage', 'integer', 'price', 'decimal' => SORT_NUMERIC,
            'string', 'list', 'text' => SORT_NATURAL | SORT_FLAG_CASE,
            default => SORT_REGULAR,
        };
    }
}

?>
