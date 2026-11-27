<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector\Fixture;

defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);
! defined('SHOW_DEBUG_BACKTRACE') && define('SHOW_DEBUG_BACKTRACE', true);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector\Fixture;

if (!defined('SHOW_DEBUG_BACKTRACE')) {
    define('SHOW_DEBUG_BACKTRACE', true);
}
if (! defined('SHOW_DEBUG_BACKTRACE')) {
    define('SHOW_DEBUG_BACKTRACE', true);
}

?>
