<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\FuncCall\RemoveFinfoBufferContextArgRector\Fixture;

finfo_buffer($finfo, $fileContents);
finfo_buffer($finfo, $fileContents, FILEINFO_NONE);
finfo_buffer($finfo, $fileContents, FILEINFO_NONE, $context);
finfo_buffer($finfo, $fileContents, FILEINFO_NONE, context: $context);
finfo_buffer($finfo, $fileContents, flags: FILEINFO_NONE, context: $context);
finfo_buffer($finfo, $fileContents, context: $context);
finfo_buffer($finfo, $fileContents, context: $context, flags: FILEINFO_NONE);
finfo_buffer(...[$finfo, $fileContents]);
finfo_buffer(...[$finfo, $fileContents, $context]);
finfo_buffer(...[$finfo, $fileContents, FILEINFO_NONE, $context]);

function foo(\finfo $finfo): void
{
    $finfo->buffer($fileContents, FILEINFO_NONE, $context);
    $finfo->buffer($fileContents, FILEINFO_NONE, context: $context);
    $finfo->buffer($fileContents, flags: FILEINFO_NONE, context: $context);
    $finfo->buffer($fileContents, context: $context);
    $finfo->buffer($fileContents, context: $context, flags: FILEINFO_NONE);
    $finfo->buffer(...[$fileContents]);
    $finfo->buffer(...[$fileContents, $context]);
    $finfo->buffer(...[$fileContents, FILEINFO_NONE, $context]);
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\FuncCall\RemoveFinfoBufferContextArgRector\Fixture;

finfo_buffer($finfo, $fileContents);
finfo_buffer($finfo, $fileContents, FILEINFO_NONE);
finfo_buffer($finfo, $fileContents, FILEINFO_NONE);
finfo_buffer($finfo, $fileContents, FILEINFO_NONE);
finfo_buffer($finfo, $fileContents, flags: FILEINFO_NONE);
finfo_buffer($finfo, $fileContents);
finfo_buffer($finfo, $fileContents, flags: FILEINFO_NONE);
finfo_buffer(...[$finfo, $fileContents]);
finfo_buffer(...[$finfo, $fileContents, $context]);
finfo_buffer(...[$finfo, $fileContents, FILEINFO_NONE, $context]);

function foo(\finfo $finfo): void
{
    $finfo->buffer($fileContents, FILEINFO_NONE);
    $finfo->buffer($fileContents, FILEINFO_NONE);
    $finfo->buffer($fileContents, flags: FILEINFO_NONE);
    $finfo->buffer($fileContents);
    $finfo->buffer($fileContents, flags: FILEINFO_NONE);
    $finfo->buffer(...[$fileContents]);
    $finfo->buffer(...[$fileContents, $context]);
    $finfo->buffer(...[$fileContents, FILEINFO_NONE, $context]);
}

?>
