<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\FuncCall\RemoveFinfoBufferContextArgRector\Fixture;

finfo_file($finfo, $fileContents, FILEINFO_NONE, $context);
finfo_file($finfo, $fileContents, FILEINFO_NONE, context: $context);
finfo_file($finfo, $fileContents, flags: FILEINFO_NONE, context: $context);

function foo(\finfo $finfo): void
{
    $finfo->file($fileContents, FILEINFO_NONE, $context);
    $finfo->file($fileContents, FILEINFO_NONE, context: $context);
    $finfo->file($fileContents, flags: FILEINFO_NONE, context: $context);
}

function bar($otherObject): void
{
    $finfo->buffer($fileContents, FILEINFO_NONE, $context);
    $finfo->buffer($fileContents, FILEINFO_NONE, context: $context);
    $finfo->buffer($fileContents, flags: FILEINFO_NONE, context: $context);
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\FuncCall\RemoveFinfoBufferContextArgRector\Fixture;

finfo_file($finfo, $fileContents, FILEINFO_NONE, $context);
finfo_file($finfo, $fileContents, FILEINFO_NONE, context: $context);
finfo_file($finfo, $fileContents, flags: FILEINFO_NONE, context: $context);

function foo(\finfo $finfo): void
{
    $finfo->file($fileContents, FILEINFO_NONE, $context);
    $finfo->file($fileContents, FILEINFO_NONE, context: $context);
    $finfo->file($fileContents, flags: FILEINFO_NONE, context: $context);
}

function bar($otherObject): void
{
    $finfo->buffer($fileContents, FILEINFO_NONE, $context);
    $finfo->buffer($fileContents, FILEINFO_NONE, context: $context);
    $finfo->buffer($fileContents, flags: FILEINFO_NONE, context: $context);
}

?>
