<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\FuncCall\Utf8DecodeToMbConvertEncodingRector\Fixture;

$utf8_string = "\x5A\x6F\xC3\xAB";
utf8_decode($utf8_string);

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\FuncCall\Utf8DecodeToMbConvertEncodingRector\Fixture;

$utf8_string = "\x5A\x6F\xC3\xAB";
mb_convert_encoding($utf8_string, 'ISO-8859-1');

?>
