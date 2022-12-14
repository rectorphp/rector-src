<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\FuncCall\Utf8DecodeEncodeToMbConvertEncodingRector\Fixture;

$text = "\xE0";
utf8_encode($text);

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\FuncCall\Utf8DecodeEncodeToMbConvertEncodingRector\Fixture;

$text = "\xE0";
mb_convert_encoding($text, 'UTF-8', 'ISO-8859-1');

?>
