<?php

declare(strict_types=1);

// some comment about the file
// not about the use statement below it.
/* comment
   block */
######

use Exception;
use stdClass;
use SomeOtherClass;

final class KeepFirstCommentAfterDeclare
{
    public ?stdClass $b;
}

?>
-----
<?php

declare(strict_types=1);

// some comment about the file
// not about the use statement below it.
/* comment
   block */
######


use stdClass;

final class KeepFirstCommentAfterDeclare
{
    public ?stdClass $b;
}

?>
