<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Expression\ReplaceHttpResponseHeaderRector\Fixture;

var_dump( $http_response_header );

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Expression\ReplaceHttpResponseHeaderRector\Fixture;

$http_response_header = http_get_last_response_headers();
var_dump( $http_response_header );

?>
