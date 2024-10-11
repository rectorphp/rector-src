<?php

namespace Rector\Tests\Php84\Rector\FuncCall\RoundingModeEnumRector;

round(1.5, 0);
round(1.5);
round(1.5, 0, PHP_ROUND_HALF_UP);
round(1.5, 0, PHP_ROUND_HALF_DOWN);
round(1.5, 0, PHP_ROUND_HALF_EVEN);
round(1.5, 0, PHP_ROUND_HALF_ODD);
round(1.5, 0, 'invalid');
round(1.5, 0, PHP_INT_MAX);

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\FuncCall\RoundingModeEnumRector;

round(1.5, 0);
round(1.5);
round(1.5, 0, RoundingMode::HalfAwayFromZero);
round(1.5, 0, RoundingMode::HalfTowardsZero);
round(1.5, 0, RoundingMode::HalfEven);
round(1.5, 0, RoundingMode::HalfOdd);
round(1.5, 0, 'invalid');
round(1.5, 0, PHP_INT_MAX);

?>
